<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/subcategoria.php");

$app = new Subcategoria;
$app->checarRol('Administrador');
$id = (isset($_GET['id'])) ? $_GET['id'] : null;
$accion = (isset($_GET['accion'])) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');
switch ($accion) {
    case 'crear':
        if (isset($_POST['nombre_subcategoria'])) {
            $data = $_POST;
            $cantidad = $app->crear($data);
            if ($cantidad) {
                $app->alerta("success", "Registro creado correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al crear el registro");
            }
            $subcategorias = $app->leer();
            require(__DIR__ . "/views/subcategoria/index.php");
        } else {
            require(__DIR__ . "/views/subcategoria/formulario_crear.php");
        }
        break;

    case 'actualizar':
    if (isset($_POST['nombre_subcategoria'])) {
        $data = $_POST;
        $cantidad = $app->actualizar($id, $data);

        if ($cantidad) {
            $app->alerta("success", "Registro actualizado correctamente");
        } else {
            $app->alerta("warning", "No se realizaron cambios en el registro");
        }

        $subcategorias = $app->leer();
        require(__DIR__ . "/views/subcategoria/index.php");
    } else {
        $data = $app->leerUno($id);
        require(__DIR__ . "/views/subcategoria/formulario_actualizar.php");
    }
    break;
        
    case "borrar":
        $cantidad = $app->borrar($id);
        if ($cantidad) {
            $app->alerta("success", "Registro eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el registro");
        }
        $subcategorias = $app->leer();
        require(__DIR__ . "/views/subcategoria/index.php");
        break;
    
    case "leer":
    default:
        $subcategorias = $app->leer();
        require(__DIR__ . "/views/subcategoria/index.php");
}

include_once(__DIR__ . '/views/footer.php');


?>