<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/atributo_valor.php");

$app = new AtributoValor;
$app->checarRol('Administrador');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    case 'crear':

        if (isset($_POST['valor'])) {

            $data = $_POST;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;

            if (empty($data['id_atributo'])) {
                $app->alerta("warning", "Debes seleccionar un atributo");
                $atributos = $app->obtenerAtributos();
                require(__DIR__ . "/views/atributo_valor/formulario.php");
                break;
            }

            if ($app->existeValor($data['id_atributo'], $data['valor'])) {
                $app->alerta("warning", "Ese valor ya existe para el atributo seleccionado");
                $atributos = $app->obtenerAtributos();
                require(__DIR__ . "/views/atributo_valor/formulario.php");
                break;
            }

            $cantidad = $app->crear($data);

            if ($cantidad) {
                $app->alerta("success", "Valor de atributo creado correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al crear el valor");
            }

            $valores = $app->leer();
            require(__DIR__ . "/views/atributo_valor/index.php");

        } else {

            $data = [];
            $atributos = $app->obtenerAtributos();
            require(__DIR__ . "/views/atributo_valor/formulario.php");
        }

        break;

    case 'actualizar':

        if (isset($_POST['valor'])) {

            $data = $_POST;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;

            if (empty($data['id_atributo'])) {
                $app->alerta("warning", "Debes seleccionar un atributo");
                $atributos = $app->obtenerAtributos();
                $data['id_atributo_valor'] = $id;
                require(__DIR__ . "/views/atributo_valor/formulario.php");
                break;
            }

            if ($app->existeValor($data['id_atributo'], $data['valor'], $id)) {
                $app->alerta("warning", "Ese valor ya existe para el atributo seleccionado");
                $atributos = $app->obtenerAtributos();
                $data['id_atributo_valor'] = $id;
                require(__DIR__ . "/views/atributo_valor/formulario.php");
                break;
            }

            $cantidad = $app->actualizar($id, $data);

            if ($cantidad) {
                $app->alerta("success", "Valor actualizado correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios");
            }

            $valores = $app->leer();
            require(__DIR__ . "/views/atributo_valor/index.php");

        } else {

            $data = $app->leerUno($id);
            $atributos = $app->obtenerAtributos();
            require(__DIR__ . "/views/atributo_valor/formulario.php");
        }

        break;

    case 'borrar':

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Valor eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el valor");
        }

        $valores = $app->leer();
        require(__DIR__ . "/views/atributo_valor/index.php");

        break;

    case 'leer':
    default:

        $valores = $app->leer();
        require(__DIR__ . "/views/atributo_valor/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>