<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/pedido.php");

$app = new Pedido();

$id = (isset($_GET['id'])) ? $_GET['id'] : null;
$accion = (isset($_GET['accion'])) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    case 'comprar':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['login']) || !$_SESSION['login']) {
                $app->alerta("danger", "Debes iniciar sesión para realizar una compra");
                break;
            }

            if (!isset($_SESSION['id_usuario'])) {
                $app->alerta("danger", "No se encontró el usuario de la sesión");
                break;
            }

            if (empty($_SESSION['carrito'])) {
                $app->alerta("warning", "Tu carrito está vacío");
                break;
            }

            $data = [];
            $data['id_usuario'] = $_SESSION['id_usuario'];
            $data['id_metodo_pago'] = !empty($_POST['id_metodo_pago']) ? $_POST['id_metodo_pago'] : null;
            $data['direccion_envio'] = trim($_POST['direccion_envio'] ?? '');
            $data['telefono_contacto'] = trim($_POST['telefono_contacto'] ?? '');
            $data['notas'] = trim($_POST['notas'] ?? '');
            $data['carrito'] = $_SESSION['carrito'];

            if (empty($data['direccion_envio'])) {
                $app->alerta("danger", "La dirección de envío es obligatoria");
                break;
            }

            $resultado = $app->crearDesdeCarrito($data);

            if ($resultado['success']) {
                unset($_SESSION['carrito']);
                $app->alerta("success", "Pedido generado correctamente. Folio: #" . $resultado['id_pedido']);
            } else {
                $app->alerta("danger", $resultado['message']);
            }
        }

        break;

    case 'actualizar_estado':

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {

            $data = [];
            $data['estado'] = $_POST['estado'] ?? 'pendiente';
            $data['estado_pago'] = $_POST['estado_pago'] ?? 'pendiente';

            if ($data['estado'] === 'enviado') {
                $data['fecha_envio'] = date('Y-m-d H:i:s');
            } else {
                $data['fecha_envio'] = null;
            }

            if ($data['estado'] === 'entregado') {
                $data['fecha_entrega'] = date('Y-m-d H:i:s');
            } else {
                $data['fecha_entrega'] = null;
            }

            $cantidad = $app->actualizarEstado($id, $data);

            if ($cantidad) {
                $app->alerta("success", "Estado del pedido actualizado correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios");
            }
        }

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;

    case 'ver':

        if (empty($id)) {
            $app->alerta("danger", "No se especificó el pedido");
            $pedidos = $app->leer();
            require(__DIR__ . "/views/pedido/index.php");
            break;
        }

        $pedido = $app->leerUno($id);

        if (!$pedido) {
            $app->alerta("danger", "El pedido no existe o no fue encontrado");
            $pedidos = $app->leer();
            require(__DIR__ . "/views/pedido/index.php");
            break;
        }

        $detalles = $app->leerDetalle($id);

        require(__DIR__ . "/views/pedido/ver.php");
        break;

    case 'borrar':

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Pedido eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el pedido");
        }

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;

    case 'leer':
    default:

        $pedidos = $app->leer();
        require(__DIR__ . "/views/pedido/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>