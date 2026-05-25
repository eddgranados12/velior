<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/atributo.php");

$app = new Atributo;
$app->checarRol('Administrador');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    case 'crear':

        if (isset($_POST['nombre_atributo'])) {

            $data = $_POST;
            $data['filtrable'] = isset($_POST['filtrable']) ? 1 : 0;

            if ($app->existeNombre($data['nombre_atributo'])) {
                $app->alerta("warning", "Ya existe un atributo con ese nombre");
                require(__DIR__ . "/views/atributo/formulario.php");
                break;
            }

            $cantidad = $app->crear($data);

            if ($cantidad) {
                $app->alerta("success", "Atributo creado correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al crear el atributo");
            }

            $atributos = $app->leer();
            require(__DIR__ . "/views/atributo/index.php");

        } else {

            $data = [];
            require(__DIR__ . "/views/atributo/formulario.php");
        }

        break;

    case 'actualizar':

        if (isset($_POST['nombre_atributo'])) {

            $data = $_POST;
            $data['filtrable'] = isset($_POST['filtrable']) ? 1 : 0;

            if ($app->existeNombre($data['nombre_atributo'], $id)) {
                $app->alerta("warning", "Ya existe otro atributo con ese nombre");
                $data['id_atributo'] = $id;
                require(__DIR__ . "/views/atributo/formulario.php");
                break;
            }

            $cantidad = $app->actualizar($id, $data);

            if ($cantidad) {
                $app->alerta("success", "Atributo actualizado correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios");
            }

            $atributos = $app->leer();
            require(__DIR__ . "/views/atributo/index.php");

        } else {

            $data = $app->leerUno($id);
            require(__DIR__ . "/views/atributo/formulario.php");
        }

        break;

    case 'borrar':

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Atributo eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el atributo");
        }

        $atributos = $app->leer();
        require(__DIR__ . "/views/atributo/index.php");

        break;

    case 'leer':
    default:

        $atributos = $app->leer();
        require(__DIR__ . "/views/atributo/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>