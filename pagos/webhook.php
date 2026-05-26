<?php
// ============================================================
//  WEBHOOK DE MERCADO PAGO
//  URL pública que MP llama cuando cambia el estado de un pago.
//  Registrar en: https://www.mercadopago.com.mx/developers
// ============================================================
 
require_once(__DIR__ . "/../admin/config.php");
require_once(__DIR__ . "/../admin/sistema.class.php");
 
http_response_code(200); // Responder OK a MP inmediatamente
 
// --- 1. LEER PAYLOAD ---
$payload = file_get_contents('php://input');
$data    = json_decode($payload, true);
 
error_log('[WEBHOOK MP] ' . $payload);
 
// --- 2. VALIDAR TIPO DE NOTIFICACIÓN ---
$tipo = $data['type'] ?? ($_GET['topic'] ?? '');
$mpId = $data['data']['id'] ?? ($_GET['id'] ?? null);
 
if ($tipo !== 'payment' || !$mpId) {
    exit; // No es una notificación de pago, ignorar
}
 
// --- 3. CONSULTAR ESTADO REAL DEL PAGO EN MP ---
require_once __DIR__ . '/../vendor/autoload.php';
 
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
 
MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);
 
try {
    $client  = new PaymentClient();
    $payment = $client->get((int) $mpId);
} catch (Exception $e) {
    error_log('[WEBHOOK MP] Error al consultar pago: ' . $e->getMessage());
    exit;
}
 
if (!$payment) {
    error_log('[WEBHOOK MP] Pago no encontrado: ' . $mpId);
    exit;
}
 
// --- 4. EXTRAER DATOS ---
$paymentId         = (string) $payment->id;
$status            = $payment->status;              // approved, pending, rejected...
$statusDetail      = $payment->status_detail;
$externalReference = $payment->external_reference;  // nuestro id_pedido
$metodoPagoId      = $payment->payment_method_id;   // visa, mastercard, oxxo...
$cuotas            = $payment->installments ?? 1;
$ultimosCuatro     = $payment->card->last_four_digits ?? null;
$fechaAprobado     = $payment->date_approved
                        ? date('Y-m-d H:i:s', strtotime($payment->date_approved))
                        : null;
 
$idPedido = (int) $externalReference;
if ($idPedido <= 0) {
    error_log('[WEBHOOK MP] external_reference inválido: ' . $externalReference);
    exit;
}
 
// --- 5. CONECTAR BD ---
$app = new Sistema();
$db  = $app->db();
 
// --- 6. BUSCAR TRANSACCIÓN ASOCIADA AL PEDIDO ---
$stmtTrans = $db->prepare("
    SELECT id_transaccion, payment_status
    FROM tranasaccion_pago
    WHERE id_pedido = :id_pedido
    ORDER BY fecha_creacion DESC LIMIT 1
");
$stmtTrans->execute([':id_pedido' => $idPedido]);
$trans = $stmtTrans->fetch();
$idTransaccion  = $trans ? (int) $trans['id_transaccion'] : null;
$estadoAnterior = $trans['payment_status'] ?? null;
 
// --- 7. GUARDAR WEBHOOK RAW ---
$db->prepare("
    INSERT INTO webhook_response
        (id_transaccion, tipo_evento, payload_json, procesado)
    VALUES
        (:id_trans, :tipo, :payload, 0)
")->execute([
    ':id_trans' => $idTransaccion,
    ':tipo'     => $tipo,
    ':payload'  => $payload,
]);
 
// --- 8. ACTUALIZAR TRANSACCIÓN ---
if ($idTransaccion) {
    $db->prepare("
        UPDATE tranasaccion_pago SET
            payment_id            = :payment_id,
            payment_status        = :status,
            payment_status_detail = :detail,
            payment_method_id     = :metodo,
            installments          = :cuotas,
            card_last_four        = :ultimos4,
            fecha_aprobado        = :fecha_aprobado
        WHERE id_transaccion = :id_trans
    ")->execute([
        ':payment_id'     => $paymentId,
        ':status'         => $status,
        ':detail'         => $statusDetail,
        ':metodo'         => $metodoPagoId,
        ':cuotas'         => $cuotas,
        ':ultimos4'       => $ultimosCuatro,
        ':fecha_aprobado' => $fechaAprobado,
        ':id_trans'       => $idTransaccion,
    ]);
 
    // Registrar cambio de estado si cambió
    if ($estadoAnterior !== $status) {
        $db->prepare("
            INSERT INTO historial_pago_estado
                (id_transaccion, estado_anterior, estado_nuevo, motivo, webhook_recibido)
            VALUES
                (:id_trans, :anterior, :nuevo, :motivo, 1)
        ")->execute([
            ':id_trans' => $idTransaccion,
            ':anterior' => $estadoAnterior,
            ':nuevo'    => $status,
            ':motivo'   => $statusDetail,
        ]);
    }
}
 
// --- 9. MAPEAR ESTADOS MP → ESTADOS DE TU BD ---
$estadoPago = match($status) {
    'approved'                  => 'aprobado',
    'pending', 'in_process',
    'authorized'                => 'en_proceso',
    'rejected', 'cancelled'     => 'rechazado',
    'refunded', 'charged_back'  => 'reembolzado',
    default                     => 'pendiente',
};
 
$estadoPedido = match($status) {
    'approved'              => 'pagado',
    'rejected', 'cancelled' => 'cancelado',
    default                 => 'pendiente',
};
 
// --- 10. ACTUALIZAR PEDIDO ---
$db->prepare("
    UPDATE pedido SET
        estado_pago = :estado_pago,
        estado      = :estado_pedido
    WHERE id_pedido = :id_pedido
")->execute([
    ':estado_pago'   => $estadoPago,
    ':estado_pedido' => $estadoPedido,
    ':id_pedido'     => $idPedido,
]);
 
// --- 11. MARCAR WEBHOOK COMO PROCESADO ---
$db->prepare("
    UPDATE webhook_response SET procesado = 1
    WHERE id_transaccion = :id_trans
    ORDER BY fecha_recibido DESC LIMIT 1
")->execute([':id_trans' => $idTransaccion]);
 
// --- 12. ACCIONES POST-PAGO APROBADO ---
if ($status === 'approved') {
    // TODO: enviar correo de confirmación usando $app->envioCorreo(...)
    // TODO: actualizar inventario/stock
    error_log("[WEBHOOK MP] Pedido #{$idPedido} APROBADO ✓");
}
 
exit;