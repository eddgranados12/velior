<?php
// ============================================================
//  CREAR PREFERENCIA EN MERCADO PAGO
//  Llama a este archivo con: ?id_pedido=123
// ============================================================

require_once(__DIR__ . "/../admin/config.php");
require_once(__DIR__ . "/../admin/sistema.class.php");

// --- 1. VERIFICAR SESIÓN ---
if (!isset($_SESSION['validado']) || $_SESSION['validado'] !== true) {
    header('Location: /velior/admin/login.php?accion=login');
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];
$correo = $_SESSION['correo'];
$nombre = $_SESSION['nombre'];

// --- 2. OBTENER ID DEL PEDIDO ---
$idPedido = isset($_GET['id_pedido']) ? (int) $_GET['id_pedido'] : 0;
if ($idPedido <= 0) {
    die('Pedido no válido.');
}

// --- 3. CONECTAR A LA BD ---
$app = new Sistema();
$db = $app->db();

// --- 4. CARGAR PEDIDO (debe ser del usuario y estar pendiente) ---
$stmtPedido = $db->prepare("
    SELECT p.*
    FROM pedido p
    WHERE p.id_pedido  = :id_pedido
      AND p.id_usuario = :id_usuario
      AND p.estado     = 'pendiente'
      AND p.estado_pago = 'pendiente'
    LIMIT 1
");
$stmtPedido->execute([':id_pedido' => $idPedido, ':id_usuario' => $idUsuario]);
$pedido = $stmtPedido->fetch();

if (!$pedido) {
    die('Pedido no encontrado o ya fue procesado.');
}

// --- 5. CARGAR DETALLES DEL PEDIDO ---
$stmtDetalles = $db->prepare("
    SELECT nombre_producto, cantidad, precio_unitario
    FROM detalle_pedido
    WHERE id_pedido = :id_pedido
");
$stmtDetalles->execute([':id_pedido' => $idPedido]);
$detalles = $stmtDetalles->fetchAll();

if (empty($detalles)) {
    die('El pedido no tiene productos.');
}

// --- 6. SDK DE MERCADO PAGO ---
require_once __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);

// --- 7. CONSTRUIR ITEMS ---
$items = [];
foreach ($detalles as $d) {
    $items[] = [
        'title' => $d['nombre_producto'],
        'quantity' => (int) $d['cantidad'],
        'unit_price' => (float) $d['precio_unitario'],
        'currency_id' => MONEDA,
    ];
}

// --- 8. CREAR PREFERENCIA EN MP ---
$preferenceData = [
    'items' => $items,
    'payer' => [
        'email' => $correo,
        'name' => $nombre,
    ],
    'back_urls' => [
        'success' => URL_SUCCESS,
        'pending' => URL_PENDING,
        'failure' => URL_FAILURE,
    ],
    'auto_return' => 'approved',
    'external_reference' => (string) $idPedido,
    'notification_url' => WEBHOOK_URL,
    'statement_descriptor' => 'Velior',
];

try {
    $client = new PreferenceClient();
    $preference = $client->create($preferenceData);

    $preferenceId = $preference->id;
    $sandboxUrl = $preference->sandbox_init_point; // Sandbox
    $initPoint = $preference->init_point;          // Producción

    // --- 9. OBTENER O INSERTAR MÉTODO DE PAGO "Mercado Pago" ---
    $stmtMetodo = $db->prepare("
        SELECT id_metodo_pago FROM metodo_pago
        WHERE nombre_metodo = 'Mercado Pago' AND activo = 1 LIMIT 1
    ");
    $stmtMetodo->execute();
    $metodo = $stmtMetodo->fetch();

    if (!$metodo) {
        $db->exec("
            INSERT INTO metodo_pago (nombre_metodo, tipo_gateway, descripcion, requiere_externo, activo)
            VALUES ('Mercado Pago', 'mercadopago', 'Checkout Pro de Mercado Pago', 1, 1)
        ");
        $idMetodoPago = (int) $db->lastInsertId();
    } else {
        $idMetodoPago = (int) $metodo['id_metodo_pago'];
    }

    // Actualizar pedido con método de pago
    $db->prepare("
        UPDATE pedido SET id_metodo_pago = :id_metodo WHERE id_pedido = :id_pedido
    ")->execute([':id_metodo' => $idMetodoPago, ':id_pedido' => $idPedido]);

    // --- 10. REGISTRAR TRANSACCIÓN ---
    $stmtTrans = $db->prepare("
        INSERT INTO tranasaccion_pago
            (id_pedido, id_metodo_pago, monto_total, moneda, preference_id, payment_status, ip_cliente, user_agent)
        VALUES
            (:id_pedido, :id_metodo, :monto, :moneda, :pref_id, 'pending', :ip, :ua)
    ");
    $stmtTrans->execute([
        ':id_pedido' => $idPedido,
        ':id_metodo' => $idMetodoPago,
        ':monto' => $pedido['total'],
        ':moneda' => MONEDA,
        ':pref_id' => $preferenceId,
        ':ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ]);
    $idTransaccion = (int) $db->lastInsertId();

    // Historial estado inicial
    $db->prepare("
        INSERT INTO historial_pago_estado
            (id_transaccion, estado_anterior, estado_nuevo, motivo)
        VALUES
            (:id_trans, NULL, 'pending', 'Preferencia creada en Mercado Pago')
    ")->execute([':id_trans' => $idTransaccion]);

    // --- 11. REDIRIGIR A MERCADO PAGO ---
    // Usa $sandboxUrl para pruebas, $initPoint para producción
    header('Location: ' . $sandboxUrl);
    exit;

} catch (MPApiException $e) {
    error_log('[MP ERROR] ' . $e->getMessage());
    die('<h2>Error al conectar con Mercado Pago</h2><p>' . htmlspecialchars($e->getMessage()) . '</p>');
} catch (Exception $e) {
    error_log('[ERROR] ' . $e->getMessage());
    die('<h2>Error inesperado</h2><p>' . htmlspecialchars($e->getMessage()) . '</p>');
}