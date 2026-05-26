<?php
// ============================================================
//  CONTROLADOR: Favorito
//  Ubicación: admin/favorito.php
//  Todas las acciones son AJAX → devuelve JSON
// ============================================================

require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/Favorito.php");

if (session_status() === PHP_SESSION_NONE)
    session_start();

header('Content-Type: application/json');

// Solo usuarios logueados
if (!isset($_SESSION['validado']) || $_SESSION['validado'] !== true) {
    echo json_encode(['ok' => false, 'mensaje' => 'Debes iniciar sesión.']);
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];
$accion = $_GET['accion'] ?? '';
$idProducto = (int) ($_POST['id_producto'] ?? $_GET['id_producto'] ?? 0);
$app = new Favorito();

if ($idProducto <= 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Producto inválido.']);
    exit;
}

switch ($accion) {

    // ── AGREGAR ──────────────────────────────────────────────
    case 'agregar':
        $resultado = $app->agregar($idUsuario, $idProducto);
        $resultado['total'] = $app->contar($idUsuario);
        echo json_encode($resultado);
        break;

    // ── ELIMINAR ─────────────────────────────────────────────
    case 'eliminar':
        $resultado = $app->eliminar($idUsuario, $idProducto);
        $resultado['total'] = $app->contar($idUsuario);
        echo json_encode($resultado);
        break;

    // ── VERIFICAR si es favorito ─────────────────────────────
    case 'verificar':
        echo json_encode([
            'ok' => true,
            'es_favorito' => $app->esFavorito($idUsuario, $idProducto),
        ]);
        break;

    default:
        echo json_encode(['ok' => false, 'mensaje' => 'Acción no válida.']);
        break;
}
exit;