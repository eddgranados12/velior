<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/Pedido.php");
require_once(__DIR__ . "/models/Carrito.php");

if (session_status() === PHP_SESSION_NONE)
    session_start();

$app = new Pedido();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$accion = isset($_GET['accion']) ? htmlspecialchars($_GET['accion']) : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    // ─────────────────────────────────────────────────────────────
    // CONFIRMAR: recibe el form de checkout, crea pedido y va a MP
    // ─────────────────────────────────────────────────────────────
    case 'confirmar':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: pedido.php?accion=leer');
            exit;
        }

        if (!isset($_SESSION['validado']) || !$_SESSION['validado']) {
            $app->alerta("danger", "Debes iniciar sesión para realizar una compra.");
            break;
        }

        $idUsuario = (int) $_SESSION['id_usuario'];

        // Leer carrito desde BD
        $carritoModel = new Carrito($app->db());
        $itemsCarrito = $carritoModel->obtenerItems($idUsuario);

        if (empty($itemsCarrito)) {
            $app->alerta("warning", "Tu carrito está vacío.");
            break;
        }

        $direccion = trim($_POST['direccion_envio'] ?? '');
        $telefono = trim($_POST['telefono_contacto'] ?? '');
        $notas = trim($_POST['notas'] ?? '');

        if (empty($direccion)) {
            $_SESSION['error_checkout'] = 'La dirección de envío es obligatoria.';
            header('Location: carrito.php?accion=checkout');
            exit;
        }

        // Convertir items del carrito al formato que espera crearDesdeCarrito()
        $carritoData = array_map(fn($item) => [
            'id_producto' => $item['id_producto'],
            'cantidad' => $item['cantidad'],
        ], $itemsCarrito);

        $data = [
            'id_usuario' => $idUsuario,
            'id_metodo_pago' => null, // MP lo asignará después via webhook
            'direccion_envio' => $direccion,
            'telefono_contacto' => $telefono,
            'notas' => $notas,
            'carrito' => $carritoData,
        ];

        $resultado = $app->crearDesdeCarrito($data);

        if ($resultado['success']) {
            // Marcar carrito como convertido en BD
            $carritoModel->marcarComoConvertido($idUsuario);
            // Redirigir a Mercado Pago
            header('Location: /velior/pagos/crear_preferencia.php?id_pedido=' . $resultado['id_pedido']);
            exit;
        } else {
            $_SESSION['error_checkout'] = $resultado['message'];
            header('Location: carrito.php?accion=checkout');
            exit;
        }

    // ─────────────────────────────────────────────────────────────
    // ACTUALIZAR ESTADO (admin)
    // ─────────────────────────────────────────────────────────────
    case 'actualizar_estado':

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {

            $data = [
                'estado' => $_POST['estado'] ?? 'pendiente',
                'estado_pago' => $_POST['estado_pago'] ?? 'pendiente',
                'fecha_envio' => null,
                'fecha_entrega' => null,
            ];

            if ($data['estado'] === 'enviado') {
                $data['fecha_envio'] = date('Y-m-d H:i:s');
            }
            if ($data['estado'] === 'entregado') {
                $data['fecha_entrega'] = date('Y-m-d H:i:s');
            }

            $cantidad = $app->actualizarEstado($id, $data);

            if ($cantidad) {
                $app->alerta("success", "Estado del pedido actualizado correctamente.");
            } else {
                $app->alerta("warning", "No se realizaron cambios.");
            }
        }

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;

    // ─────────────────────────────────────────────────────────────
    // VER detalle de un pedido (admin)
    // ─────────────────────────────────────────────────────────────
    case 'ver':

        if (empty($id)) {
            $app->alerta("danger", "No se especificó el pedido.");
            $pedidos = $app->leer();
            require(__DIR__ . "/views/pedido/index.php");
            break;
        }

        $pedido = $app->leerUno($id);

        if (!$pedido) {
            $app->alerta("danger", "El pedido no existe.");
            $pedidos = $app->leer();
            require(__DIR__ . "/views/pedido/index.php");
            break;
        }

        $detalles = $app->leerDetalle($id);
        require(__DIR__ . "/views/pedido/ver.php");
        break;

    // ─────────────────────────────────────────────────────────────
    // BORRAR (admin)
    // ─────────────────────────────────────────────────────────────
    case 'borrar':

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Pedido eliminado correctamente.");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el pedido.");
        }

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;

    // ─────────────────────────────────────────────────────────────
    // LEER (admin) — default
    // ─────────────────────────────────────────────────────────────
    case 'leer':
    default:

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');