<?php

// CONTROLADORRRR
require_once(__DIR__ . "/sistema.class.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = new Sistema();

$accion = (isset($_GET['accion'])) ? htmlspecialchars($_GET['accion']) : null;

switch ($accion) {

    case 'login':
        if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];

            if ($app->login($correo, $contrasena)) {
                $app->redirigirSegunRol();
            } else {
                require_once(__DIR__ . "/views/login/login_header.php");
                $app->alerta('error', 'Correo o contraseña incorrectos. Por favor, inténtalo de nuevo.');
                require(__DIR__ . "/views/login/index.php");
            }

        } else {
            require_once(__DIR__ . "/views/login/login_header.php");
            $app->alerta('error', 'Por favor, completa todos los campos.');
            require(__DIR__ . "/views/login/index.php");
        }
        break;

    case 'recuperar':
        require_once(__DIR__ . "/views/login/login_header.php");
        require(__DIR__ . "/views/login/index.php");
        break;

    case 'logout':
        $app->logout();
        header("Location: /velior/index.php");
        exit();
        break;

        require_once(__DIR__ . "/views/login/recuperar.php");
        break;

    case 'token':
        if (isset($_POST['correo']) && isset($_POST['recuperar'])) {
            $correo = $_POST['correo'];
            $app->token($correo);
            $app->alerta('success', 'Si el correo existe en nuestro sistema, recibirás un mensaje con instrucciones para recuperar tu contraseña.');
        } else {
            $app->alerta('error', 'Por favor, completa el campo de correo.');
        }
        require_once(__DIR__ . "/views/login/login_header.php");
        require_once(__DIR__ . "/views/login/recuperar.php");
        break;

    case 'restablecer':
        $correo = isset($_GET['correo']) ? $_GET['correo'] : null;
        $token = isset($_GET['token']) ? $_GET['token'] : null;

        if ($correo && strlen($token) == 64) {
            require_once(__DIR__ . "/views/login/login_header.php");
            require_once(__DIR__ . "/views/login/restablecer.php");
        } else {
            $app->alerta('error', 'Enlace de restablecimiento no válido.');
            require_once(__DIR__ . "/views/login/login_header.php");
            require_once(__DIR__ . "/views/login/index.php");
        }

        break;

    case 'cambiar':
        $correo = isset($_REQUEST['correo']) ? $_REQUEST['correo'] : null;
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;
        $contrasena_nueva = isset($_REQUEST['nueva']) ? $_REQUEST['nueva'] : null;
        $pass = isset($_REQUEST['pass']) ? $_REQUEST['pass'] : null;
        if ($correo && strlen($token) == 64 && $contrasena_nueva) {
            if ($app->cambiarContrasena($correo, $token, $contrasena_nueva)) {
                $app->alerta('success', 'Contraseña cambiada exitosamente. Ahora puedes iniciar sesión con tu nueva contraseña.');
                require_once(__DIR__ . "/views/login/login_header.php");
                require_once(__DIR__ . "/views/login/index.php");
            } else {
                $app->alerta('error', 'Error al cambiar la contraseña. Asegúrate de que el enlace sea válido y que las contraseñas coincidan.');
                require_once(__DIR__ . "/views/login/login_header.php");
                require_once(__DIR__ . "/views/login/restablecer.php");
            }
        } else {
            $app->alerta('error', 'Por favor, completa todos los campos correctamente.');
            require_once(__DIR__ . "/views/login/login_header.php");
            require_once(__DIR__ . "/views/login/restablecer.php");
        }
        break;

    default:
        require_once(__DIR__ . "/views/login/login_header.php");
        require_once(__DIR__ . "/views/login/index.php");
        break;
}

include_once(__DIR__ . "/views/footer.php");
?>