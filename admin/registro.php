<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/usuario.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = new Sistema();
$usuarioModel = new Usuario();

$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

switch ($accion) {

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido_paterno' => trim($_POST['apellido_paterno'] ?? ''),
                'apellido_materno' => trim($_POST['apellido_materno'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'contrasena' => trim($_POST['contrasena'] ?? ''),
                'confirmar_contrasena' => trim($_POST['confirmar_contrasena'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? '')
            ];

            if (
                empty($data['nombre']) ||
                empty($data['apellido_paterno']) ||
                empty($data['correo']) ||
                empty($data['contrasena']) ||
                empty($data['confirmar_contrasena'])
            ) {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'Completa todos los campos obligatorios.');
                require(__DIR__ . "/views/login/registro.php");
                break;
            }

            if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'El correo electrónico no es válido.');
                require(__DIR__ . "/views/login/registro.php");
                break;
            }

            if ($data['contrasena'] !== $data['confirmar_contrasena']) {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'Las contraseñas no coinciden.');
                require(__DIR__ . "/views/login/registro.php");
                break;
            }

            if (strlen($data['contrasena']) < 6) {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'La contraseña debe tener al menos 6 caracteres.');
                require(__DIR__ . "/views/login/registro.php");
                break;
            }

            if ($usuarioModel->correoExiste($data['correo'])) {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'Ese correo ya está registrado.');
                require(__DIR__ . "/views/login/registro.php");
                break;
            }

            $id_usuario = $usuarioModel->registrarCliente($data);

            if ($id_usuario) {
                // Login automático después de registrarse
                if ($app->login($data['correo'], $data['contrasena'])) {
                    header("Location: /velior/mi_cuenta.php");
                    exit();
                } else {
                    header("Location: /velior/admin/login.php?accion=login");
                    exit();
                }
            } else {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'Ocurrió un error al crear la cuenta.');
                require(__DIR__ . "/views/login/registro.php");
            }

        } else {
            require_once(__DIR__ . "/views/login/login_header.php");
            require(__DIR__ . "/views/login/registro.php");
        }
        break;

    default:
        require_once(__DIR__ . "/views/login/login_header.php");
        require(__DIR__ . "/views/login/registro.php");
        break;
}

include_once(__DIR__ . "/views/footer.php");
?>