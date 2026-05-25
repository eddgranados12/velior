<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/producto.php");
require_once(__DIR__ . "/models/subcategoria.php");
require_once(__DIR__ . "/models/categoria.php");

$app = new Producto;
$subcategoriaModel = new Subcategoria();
$categoriaModel = new Categoria();

$app->checarRol('Administrador');

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

// Iniciar buffering para evitar problemas con PDF
ob_start();
include_once(__DIR__ . '/views/header.php');

/* =========================================================
   FUNCIÓN AUXILIAR PARA SUBIR IMÁGENES DE PRODUCTO
========================================================= */
function subirImagenProducto($fileInputName)
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

    $rutaCarpeta = __DIR__ . "/../uploads/productos/";

    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    $nuevoNombre = uniqid("prod_") . "." . $ext;
    $rutaFinal = $rutaCarpeta . $nuevoNombre;

    if (move_uploaded_file($tmp, $rutaFinal)) {
        return $nuevoNombre;
    }

    return false;
}

/* =========================================================
   OBTENER DATOS PARA EL FORMULARIO
========================================================= */
$subcategorias = $subcategoriaModel->leer();
$categorias = $categoriaModel->leer();

switch ($accion) {

    /* =====================================================
       CREAR PRODUCTO
    ===================================================== */
    case 'crear':

        if (isset($_POST['producto'])) {

            $data = $_POST;

            $data['producto'] = trim($data['producto'] ?? '');
            $data['descripcion'] = trim($data['descripcion'] ?? '');
            $data['precio'] = isset($data['precio']) ? (float)$data['precio'] : 0;
            $data['stock'] = isset($data['stock']) ? (int)$data['stock'] : 0;
            $data['sku'] = trim($data['sku'] ?? '');
            $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;
            $data['id_subcategoria_principal'] = !empty($_POST['id_subcategoria_principal'])
                ? (int)$_POST['id_subcategoria_principal']
                : null;
            $data['id_categoria_principal'] = !empty($_POST['id_categoria_principal'])
                ? (int)$_POST['id_categoria_principal']
                : null;

            /* =============================
               VALIDACIONES
            ============================== */
            if (empty($data['producto'])) {
                $app->alerta("danger", "El nombre del producto es obligatorio");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            if ($data['precio'] < 0) {
                $app->alerta("danger", "El precio no puede ser negativo");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            if ($data['stock'] < 0) {
                $app->alerta("danger", "El stock no puede ser negativo");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            /* =============================
               CREAR PRODUCTO
            ============================== */
            $id_producto = $app->crear($data);

            if ($id_producto) {

                // Imagen principal
                $img1 = subirImagenProducto('imagen_url');
                if ($img1 === false) {
                    $app->alerta("warning", "La imagen principal no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img1 !== null) {
                    $app->guardarImagen($id_producto, $img1, 1, 1);
                }

                // Imagen secundaria 1
                $img2 = subirImagenProducto('imagen_url2');
                if ($img2 === false) {
                    $app->alerta("warning", "La imagen secundaria 1 no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img2 !== null) {
                    $app->guardarImagen($id_producto, $img2, 0, 2);
                }

                // Imagen secundaria 2
                $img3 = subirImagenProducto('imagen_url3');
                if ($img3 === false) {
                    $app->alerta("warning", "La imagen secundaria 2 no se subió porque no cumple el formato o tamaño permitido");
                } elseif ($img3 !== null) {
                    $app->guardarImagen($id_producto, $img3, 0, 3);
                }

                $app->alerta("success", "Producto creado correctamente");
            } else {
                $app->alerta("danger", "Ocurrió un error al crear el producto");
            }

            $productos = $app->leer();
            require(__DIR__ . "/views/producto/index.php");

        } else {
            $data = [];
            require(__DIR__ . "/views/producto/formulario.php");
        }

        break;


    /* =====================================================
       ACTUALIZAR PRODUCTO
    ===================================================== */
    case 'actualizar':

        if (!$id) {
            $app->alerta("danger", "ID de producto no válido");
            $productos = $app->leer();
            require(__DIR__ . "/views/producto/index.php");
            break;
        }

        if (isset($_POST['producto'])) {

            $data = $_POST;

            $data['producto'] = trim($data['producto'] ?? '');
            $data['descripcion'] = trim($data['descripcion'] ?? '');
            $data['precio'] = isset($data['precio']) ? (float)$data['precio'] : 0;
            $data['stock'] = isset($data['stock']) ? (int)$data['stock'] : 0;
            $data['sku'] = trim($data['sku'] ?? '');
            $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;
            $data['activo'] = isset($_POST['activo']) ? 1 : 0;
            $data['id_subcategoria_principal'] = !empty($_POST['id_subcategoria_principal'])
                ? (int)$_POST['id_subcategoria_principal']
                : null;
            $data['id_categoria_principal'] = !empty($_POST['id_categoria_principal'])
                ? (int)$_POST['id_categoria_principal']
                : null;

            /* =============================
               VALIDACIONES
            ============================== */
            if (empty($data['producto'])) {
                $data = array_merge($app->leerUno($id) ?? [], $data);
                $app->alerta("danger", "El nombre del producto es obligatorio");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            if ($data['precio'] < 0) {
                $data = array_merge($app->leerUno($id) ?? [], $data);
                $app->alerta("danger", "El precio no puede ser negativo");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            if ($data['stock'] < 0) {
                $data = array_merge($app->leerUno($id) ?? [], $data);
                $app->alerta("danger", "El stock no puede ser negativo");
                require(__DIR__ . "/views/producto/formulario.php");
                break;
            }

            $cantidad = $app->actualizar($id, $data);

            /* =============================
               SUBIR NUEVAS IMÁGENES
            ============================== */

            // Imagen principal
            $img1 = subirImagenProducto('imagen_url');
            if ($img1 === false) {
                $app->alerta("warning", "La imagen principal no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img1 !== null) {
                $app->guardarImagen($id, $img1, 1, 1);
            }

            // Imagen secundaria 1
            $img2 = subirImagenProducto('imagen_url2');
            if ($img2 === false) {
                $app->alerta("warning", "La imagen secundaria 1 no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img2 !== null) {
                $app->guardarImagen($id, $img2, 0, 2);
            }

            // Imagen secundaria 2
            $img3 = subirImagenProducto('imagen_url3');
            if ($img3 === false) {
                $app->alerta("warning", "La imagen secundaria 2 no se subió porque no cumple el formato o tamaño permitido");
            } elseif ($img3 !== null) {
                $app->guardarImagen($id, $img3, 0, 3);
            }

            if ($cantidad) {
                $app->alerta("success", "Producto actualizado correctamente");
            } else {
                $app->alerta("warning", "No se realizaron cambios en el producto");
            }

            $productos = $app->leer();
            require(__DIR__ . "/views/producto/index.php");

        } else {
            $data = $app->leerUno($id);

            if (!$data) {
                $app->alerta("danger", "Producto no encontrado");
                $productos = $app->leer();
                require(__DIR__ . "/views/producto/index.php");
                break;
            }

            require(__DIR__ . "/views/producto/formulario.php");
        }

        break;


    /* =====================================================
       BORRAR PRODUCTO
    ===================================================== */
    case "borrar":

        if (!$id) {
            $app->alerta("danger", "ID de producto no válido");
            $productos = $app->leer();
            require(__DIR__ . "/views/producto/index.php");
            break;
        }

        $producto = $app->leerUno($id);

        if ($producto && !empty($producto['imagenes'])) {
            foreach ($producto['imagenes'] as $img) {
                $rutaImagen = __DIR__ . "/../uploads/productos/" . $img['imagen_url'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
        }

        $cantidad = $app->borrar($id);

        if ($cantidad) {
            $app->alerta("success", "Producto eliminado correctamente");
        } else {
            $app->alerta("danger", "Ocurrió un error al eliminar el producto");
        }

        $productos = $app->leer();
        require(__DIR__ . "/views/producto/index.php");

        break;


    /* =====================================================
       REPORTE PDF
    ===================================================== */
    case 'reporte':
        ob_end_clean();
        $productos = $app->leer();
        require(__DIR__ . '/reports/producto.php');
        break;


    /* =====================================================
       LISTAR PRODUCTOS
    ===================================================== */
    case "leer":
    default:

        $productos = $app->leer();
        require(__DIR__ . "/views/producto/index.php");
        break;
}

include_once(__DIR__ . '/views/footer.php');
?>