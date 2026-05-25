<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/producto_atributo.php");

$app = new ProductoAtributo;
$app->checarRol('Administrador');

$id = isset($_GET['id']) ? $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    case 'asignar':

        if (isset($_POST['id_producto'])) {

            $id_producto = $_POST['id_producto'];
            $atributosSeleccionados = $_POST['atributos'] ?? [];

            $guardado = $app->guardarAtributosProducto($id_producto, $atributosSeleccionados);

            if ($guardado) {
                $app->alerta("success", "Atributos asignados correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al guardar los atributos");
            }

            $productosAtributos = $app->leerAgrupado();
            require(__DIR__ . "/views/producto_atributo/index.php");

        } else {

            $productos = $app->getProductos();
            $atributos = $app->getAtributosConValores();
            $atributosAsignados = [];

            if (!empty($id)) {
                $atributosAsignadosRaw = $app->getAtributosByProducto($id);

                foreach ($atributosAsignadosRaw as $item) {
                    $atributosAsignados[$item['id_atributo']][] = $item['id_atributo_valor'];
                }
            }

            require(__DIR__ . "/views/producto_atributo/formulario.php");
        }

        break;

    case 'leer':
    default:

        $productosAtributos = $app->leerAgrupado();
        require(__DIR__ . "/views/producto_atributo/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>