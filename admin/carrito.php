<?php
// ============================================================
//  CONTROLADOR: Carrito
//  Ubicación: admin/carrito.php
//  Acciones vía GET: ver, agregar, actualizar, eliminar, checkout, confirmar
// ============================================================

require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/Carrito.php");

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

switch ($accion) {

    // ─────────────────────────────────────────────────────────────
    // AGREGAR producto al carrito (POST, soporta AJAX)
    // ─────────────────────────────────────────────────────────────
    case 'agregar':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /velior/admin/carrito.php');
            exit;
        }

        $idProducto = (int) ($_POST['id_producto'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 1);
        $resultado = $carrito->agregarProducto($idUsuario, $idProducto, $cantidad);

        if (esAjax()) {
            header('Content-Type: application/json');
            $resultado['num_items'] = $carrito->contarItems($idUsuario);
            echo json_encode($resultado);
            exit;
        }

        header('Location: /velior/admin/carrito.php');
        exit;

    // ─────────────────────────────────────────────────────────────
    // ACTUALIZAR cantidad (POST AJAX)
    // ─────────────────────────────────────────────────────────────
    case 'actualizar':

        header('Content-Type: application/json');
        $idProducto = (int) ($_POST['id_producto'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 1);
        $resultado = $carrito->actualizarCantidad($idUsuario, $idProducto, $cantidad);
        $resultado['total'] = number_format($carrito->obtenerTotal($idUsuario), 2);
        $resultado['num_items'] = $carrito->contarItems($idUsuario);
        echo json_encode($resultado);
        exit;

    // ─────────────────────────────────────────────────────────────
    // ELIMINAR producto (POST AJAX)
    // ─────────────────────────────────────────────────────────────
    case 'eliminar':

        header('Content-Type: application/json');
        $idProducto = (int) ($_POST['id_producto'] ?? $_GET['id_producto'] ?? 0);
        $resultado = $carrito->eliminarProducto($idUsuario, $idProducto);
        $resultado['total'] = number_format($carrito->obtenerTotal($idUsuario), 2);
        $resultado['num_items'] = $carrito->contarItems($idUsuario);
        echo json_encode($resultado);
        exit;

    // ─────────────────────────────────────────────────────────────
    // CHECKOUT: formulario de datos de envío
    // ─────────────────────────────────────────────────────────────
    case 'checkout':

        $items = $carrito->obtenerItems($idUsuario);

        if (empty($items)) {
            header('Location: /velior/admin/carrito.php');
            exit;
        }

        $total = $carrito->obtenerTotal($idUsuario);
        require_once(__DIR__ . "/views/checkout/index.php");
        exit;

    // ─────────────────────────────────────────────────────────────
    // VER carrito (default)
    // ─────────────────────────────────────────────────────────────
    case 'ver':
    default:

        $items = $carrito->obtenerItems($idUsuario);
        $total = $carrito->obtenerTotal($idUsuario);
        $numItems = $carrito->contarItems($idUsuario);
        require_once(__DIR__ . "/views/carrito/index.php");
        exit;
}

// ─────────────────────────────────────────────────────────────
// Helper: detectar si la petición es AJAX
// ─────────────────────────────────────────────────────────────
function esAjax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}