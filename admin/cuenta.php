<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/usuario.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = new Sistema();
$usuarioModel = new Usuario();

if (!$app->estaLogueado()) {
    header("Location: /velior/admin/login.php?accion=login");
    exit();
}

$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_usuario) {
    header("Location: /velior/admin/login.php?accion=login");
    exit();
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'ver';
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'datos';

switch ($accion) {

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido_paterno' => trim($_POST['apellido_paterno'] ?? ''),
                'apellido_materno' => trim($_POST['apellido_materno'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? '')
            ];

            if (empty($data['nombre']) || empty($data['apellido_paterno']) || empty($data['correo'])) {
                $_SESSION['cuenta_alerta'] = [
                    'tipo' => 'danger',
                    'mensaje' => 'Nombre, apellido paterno y correo son obligatorios.'
                ];
                header("Location: /velior/mi_cuenta.php?seccion=datos");
                exit();
            }

            if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['cuenta_alerta'] = [
                    'tipo' => 'danger',
                    'mensaje' => 'El correo electrónico no es válido.'
                ];
                header("Location: /velior/mi_cuenta.php?seccion=datos");
                exit();
            }

            if ($usuarioModel->correoExiste($data['correo'], $id_usuario)) {
                $_SESSION['cuenta_alerta'] = [
                    'tipo' => 'danger',
                    'mensaje' => 'Ese correo ya está registrado con otra cuenta.'
                ];
                header("Location: /velior/mi_cuenta.php?seccion=datos");
                exit();
            }

            $usuarioModel->actualizarPerfil($id_usuario, $data);

            $_SESSION['cuenta_alerta'] = [
                'tipo' => 'success',
                'mensaje' => 'Tus datos fueron actualizados correctamente.'
            ];

            header("Location: /velior/mi_cuenta.php?seccion=datos");
            exit();
        }
        break;

    case 'ver':
    default:
        break;
}

$usuario = $usuarioModel->obtenerPorId($id_usuario);
if (!$usuario) {
    header("Location: /velior/admin/login.php?accion=login");
    exit();
}

$secciones_validas = ['datos', 'pedidos', 'direcciones', 'favoritos'];
if (!in_array($seccion, $secciones_validas, true)) {
    $seccion = 'datos';
}

require_once(__DIR__ . "/../views/cuenta/index.php");
exit();
?>