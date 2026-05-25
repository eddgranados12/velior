<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/coleccion.php");

$app = new Coleccion;
$app->checarRol('Administrador');

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

include_once(__DIR__ . '/views/header.php');

/* =========================================================
   FUNCIÓN AUXILIAR PARA SUBIR IMÁGENES DE COLECCIÓN
========================================================= */
function subirImagenColeccion($fileInputName)
{
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] != 0) {
        return null;
    }

    $permitidas = ["jpg", "jpeg", "png", "webp"];
    $mimePermitidos = ["image/jpeg", "image/png", "image/webp"];

    $nombreArchivo = $_FILES[$fileInputName]['name'];
    $tmp = $_FILES[$fileInputName]['tmp_name'];
    $size = $_FILES[$fileInputName]['size'];

    $ext = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    if (!in_array($ext, $permitidas)) {
        return false;
    }

    $mime = mime_content_type($tmp);

    if (!in_array($mime, $mimePermitidos)) {
        return false;
    }

    if ($size > 2 * 1024 * 1024) {
        return false;
    }

    $rutaCarpeta = __DIR__ . "/../uploads/colecciones/";

    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    $nuevoNombre = uniqid("col_") . "." . $ext;
    $rutaFinal = $rutaCarpeta . $nuevoNombre;

    if (move_uploaded_file($tmp, $rutaFinal)) {
        return $nuevoNombre;
    }

    return false;
}

switch ($accion) {

    /* =====================================================
       CREAR COLECCIÓN
    ===================================================== */
    case 'crear':

        if (isset($_POST['coleccion'])) {

            $data = $_POST;

            $data['coleccion'] = trim($data['coleccion'] ?? '');
            $data['fecha_inicio'] = !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null;
            $data['fecha_fin'] = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;
            $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;

            if (empty($data['coleccion'])) {
                $app->alerta("danger", "El nombre de la colección es obligatorio");
                require(__DIR__ . "/views/coleccion/formulario.php");
                break;
            }

            $id_coleccion = $app->crear($data);

            if ($id_coleccion) {

                // Imagen principal
                $img1 = subirImagenColeccion('imagen_url');
                if ($img1 === false) {
                    $app->alerta("warning", "La imagen principal no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img1 !== null) {
                    $app->guardarImagen($id_coleccion, $img1, 1, 1);
                }

                // Imagen secundaria 1
                $img2 = subirImagenColeccion('imagen_url2');
                if ($img2 === false) {
                    $app->alerta("warning", "La imagen secundaria 1 no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img2 !== null) {
                    $app->guardarImagen($id_coleccion, $img2, 0, 2);
                }

                // Imagen secundaria 2
                $img3 = subirImagenColeccion('imagen_url3');
                if ($img3 === false) {
                    $app->alerta("warning", "La imagen secundaria 2 no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img3 !== null) {
                    $app->guardarImagen($id_coleccion, $img3, 0, 3);
                }

                $app->alerta("success", "Colección creada correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al crear la colección");
            }

            $colecciones = $app->leer();
            require(__DIR__ . "/views/coleccion/index.php");

        } else {
            require(__DIR__ . "/views/coleccion/formulario.php");
        }

        break;


    /* =====================================================
       ACTUALIZAR COLECCIÓN
    ===================================================== */
    case 'actualizar':

        if (!$id) {
            $app->alerta("danger", "ID de colección no válido");
            $colecciones = $app->leer();
            require(__DIR__ . "/views/coleccion/index.php");
            break;
        }

        if (isset($_POST['coleccion'])) {

            $data = $_POST;

            $data['coleccion'] = trim($data['coleccion'] ?? '');
            $data['fecha_inicio'] = !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null;
            $data['fecha_fin'] = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;
            $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;

            if (empty($data['coleccion'])) {
                $app->alerta("danger", "El nombre de la colección es obligatorio");
                $data = $app->leerUno($id);
                require(__DIR__ . "/views/coleccion/formulario.php");
                break;
            }

            $cantidad = $app->actualizar($id, $data);

            // Imagen principal nueva
            $img1 = subirImagenColeccion('imagen_url');
            if ($img1 === false) {
                $app->alerta("warning", "La imagen principal no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img1 !== null) {
                $app->guardarImagen($id, $img1, 1, 1);
            }

            // Imagen secundaria 1 nueva
            $img2 = subirImagenColeccion('imagen_url2');
            if ($img2 === false) {
                $app->alerta("warning", "La imagen secundaria 1 no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img2 !== null) {
                $app->guardarImagen($id, $img2, 0, 2);
            }

            // Imagen secundaria 2 nueva
            $img3 = subirImagenColeccion('imagen_url3');
            if ($img3 === false) {
                $app->alerta("warning", "La imagen secundaria 2 no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img3 !== null) {
                $app->guardarImagen($id, $img3, 0, 3);
            }

            if ($cantidad) {
                $app->alerta("success", "Registro actualizado correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios en la colección");
            }

            $colecciones = $app->leer();
            require(__DIR__ . "/views/coleccion/index.php");

        } else {

            $data = $app->leerUno($id);

            if (!$data) {
                $app->alerta("danger", "Colección no encontrada");
                $colecciones = $app->leer();
                require(__DIR__ . "/views/coleccion/index.php");
                break;
            }

            require(__DIR__ . "/views/coleccion/formulario.php");
        }

        break;


    /* =====================================================
       BORRAR COLECCIÓN
    ===================================================== */
    case "borrar":

        if (!$id) {
            $app->alerta("danger", "ID de colección no válido");
            $colecciones = $app->leer();
            require(__DIR__ . "/views/coleccion/index.php");
            break;
        }

        $coleccion = $app->leerUno($id);

        if ($coleccion && !empty($coleccion['imagenes'])) {
            foreach ($coleccion['imagenes'] as $img) {
                $rutaImagen = __DIR__ . "/../uploads/colecciones/" . $img['imagen_url'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
        }

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Registro eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el registro");
        }

        $colecciones = $app->leer();
        require(__DIR__ . "/views/coleccion/index.php");

        break;


    /* =====================================================
       LISTAR COLECCIONES
    ===================================================== */
    case "leer":
    default:

        $colecciones = $app->leer();
        require(__DIR__ . "/views/coleccion/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>