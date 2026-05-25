<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/cat_subcategoria.php");
require_once(__DIR__ . "/models/categoria.php");
require_once(__DIR__ . "/models/subcategoria.php");

$app = new CategoriaSubcategoria;
$app->checarRol('Administrador');
$categoriaModel = new Categoria();
$subcategoriaModel = new Subcategoria();

$id_categoria = (isset($_GET['id_categoria'])) ? $_GET['id_categoria'] : null;
$id_subcategoria = (isset($_GET['id_subcategoria'])) ? $_GET['id_subcategoria'] : null;
$accion = (isset($_GET['accion'])) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

switch ($accion) {

    /* =========================
       CREAR RELACIÓN
    ========================== */
    case 'crear':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;

            $cantidad = $app->crear($data);

            if ($cantidad) {
                $app->alerta("success", "Relación creada correctamente");
            } else {
                $app->alerta("danger", "La relación ya existe o ocurrió un error");
            }

            $relaciones = $app->leerConNombres();
            require(__DIR__ . "/views/cat_subcategoria/index.php");

        } else {

            $categorias = $categoriaModel->leer();
            $subcategorias = $subcategoriaModel->leer();

            require(__DIR__ . "/views/cat_subcategoria/formulario_crear.php");
        }

        break;


    /* =========================
       ACTUALIZAR RELACIÓN
    ========================== */
    case 'actualizar':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;

            $cantidad = $app->actualizar($id_categoria, $id_subcategoria, $data);

            if ($cantidad) {
                $app->alerta("success", "Relación actualizada correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios");
            }

            $relaciones = $app->leerConNombres();
            require(__DIR__ . "/views/cat_subcategoria/index.php");

        } else {

            $categorias = $categoriaModel->leer();
            $subcategorias = $subcategoriaModel->leer();

            // Obtener la relación específica
            $relaciones = $app->leerPorCategoria($id_categoria);
            $data = null;

            foreach ($relaciones as $relacion) {
                if ($relacion['id_subcategoria'] == $id_subcategoria) {
                    $data = $relacion;
                    break;
                }
            }

            if (!$data) {
                $app->alerta("danger", "Relación no encontrada");
                $relaciones = $app->leerConNombres();
                require(__DIR__ . "/views/cat_subcategoria/index.php");
            } else {
                require(__DIR__ . "/views/cat_subcategoria/formulario_actualizar.php");
            }
        }

        break;


    /* =========================
       BORRAR RELACIÓN
    ========================== */
    case 'borrar':

        $cantidad = $app->borrar($id_categoria, $id_subcategoria);

        if ($cantidad) {
            $app->alerta("success", "Relación eliminada correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar la relación");
        }

        $relaciones = $app->leerConNombres();
        require(__DIR__ . "/views/cat_subcategoria/index.php");

        break;


    /* =========================
       LISTAR
    ========================== */
    case 'leer':
    default:

        $relaciones = $app->leerConNombres();
        require(__DIR__ . "/views/cat_subcategoria/index.php");
}

include_once(__DIR__ . '/views/footer.php');
?>