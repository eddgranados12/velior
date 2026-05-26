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
        
    default:
        require_once(__DIR__ . "/views/login/login_header.php");
        require_once(__DIR__ . "/views/login/index.php");
        break;
}

include_once(__DIR__ . "/views/footer.php");
?>