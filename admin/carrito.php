<?php
// ============================================================
//  CONTROLADOR: Carrito
//  Ubicación: admin/carrito.php
//  Acciones vía GET: ver, agregar, actualizar, eliminar, checkout, confirmar
// ============================================================

require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/Carrito.php");
require_once(__DIR__ . "/models/Pedido.php");

if (session_status() === PHP_SESSION_NONE)
    session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['validado']) || $_SESSION['validado'] !== true) {
    header('Location: /velior/admin/login.php?accion=login');
    exit;
}

$app = new Sistema();
$db = $app->db();
$carrito = new Carrito($db);
$idUsuario = (int) $_SESSION['id_usuario'];
$accion = $_GET['accion'] ?? 'ver';

// ── AGREGAR PRODUCTO (llamada AJAX o redirect) ──────────────────
if ($accion === 'agregar') {
    $idProducto = (int) ($_POST['id_producto'] ?? 0);
    $cantidad = (int) ($_POST['cantidad'] ?? 1);
    $resultado = $carrito->agregarProducto($idUsuario, $idProducto, $cantidad);

    if ($this->esAjax()) {
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }
    header('Location: /velior/admin/carrito.php');
    exit;
}

// ── ACTUALIZAR CANTIDAD (AJAX) ──────────────────────────────────
if ($accion === 'actualizar') {
    header('Content-Type: application/json');
    $idProducto = (int) ($_POST['id_producto'] ?? 0);
    $cantidad = (int) ($_POST['cantidad'] ?? 1);
    $resultado = $carrito->actualizarCantidad($idUsuario, $idProducto, $cantidad);
    $resultado['total'] = number_format($carrito->obtenerTotal($idUsuario), 2);
    $resultado['num_items'] = $carrito->contarItems($idUsuario);
    echo json_encode($resultado);
    exit;
}

// ── ELIMINAR PRODUCTO (AJAX) ────────────────────────────────────
if ($accion === 'eliminar') {
    header('Content-Type: application/json');
    $idProducto = (int) ($_POST['id_producto'] ?? $_GET['id_producto'] ?? 0);
    $resultado = $carrito->eliminarProducto($idUsuario, $idProducto);
    $resultado['total'] = number_format($carrito->obtenerTotal($idUsuario), 2);
    $resultado['num_items'] = $carrito->contarItems($idUsuario);
    echo json_encode($resultado);
    exit;
}

// ── CHECKOUT: formulario de datos de envío ──────────────────────
if ($accion === 'checkout') {
    $items = $carrito->obtenerItems($idUsuario);
    if (empty($items)) {
        header('Location: /velior/admin/carrito.php');
        exit;
    }
    $total = $carrito->obtenerTotal($idUsuario);
    require_once(__DIR__ . "/views/checkout/index.php");
    exit;
}

// ── CONFIRMAR: crear pedido y redirigir a MP ────────────────────
if ($accion === 'confirmar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion_envio'] ?? '');
    $telefono = trim($_POST['telefono_contacto'] ?? '');
    $notas = trim($_POST['notas'] ?? '');

    if (empty($direccion)) {
        $_SESSION['error_checkout'] = 'La dirección de envío es obligatoria.';
        header('Location: /velior/admin/carrito.php?accion=checkout');
        exit;
    }

    try {
        $pedidoModel = new Pedido($db);
        $idPedido = $pedidoModel->crearDesdCarrito($idUsuario, $direccion, $telefono, $notas);
        // Redirigir a Mercado Pago
        header('Location: /velior/pagos/crear_preferencia.php?id_pedido=' . $idPedido);
        exit;
    } catch (Exception $e) {
        $_SESSION['error_checkout'] = $e->getMessage();
        header('Location: /velior/admin/carrito.php?accion=checkout');
        exit;
    }
}

// ── VER CARRITO (default) ───────────────────────────────────────
$items = $carrito->obtenerItems($idUsuario);
$total = $carrito->obtenerTotal($idUsuario);
$numItems = $carrito->contarItems($idUsuario);

require_once(__DIR__ . "/views/carrito/index.php");

// Helper AJAX
function esAjax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}