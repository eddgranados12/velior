<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// PREFLIGHT
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once(__DIR__ . "/../sistema.class.php");
require_once(__DIR__ . "/../models/cat_subcategoria.php");

$app = new CategoriaSubcategoria();

$method = $_SERVER['REQUEST_METHOD'];

$id_categoria = $_GET['id_categoria'] ?? null;
$id_subcategoria = $_GET['id_subcategoria'] ?? null;

switch ($method) {

    /* =========================
       GET → LISTAR / FILTRAR
    ========================= */
    case 'GET':

        if ($id_categoria && !$id_subcategoria) {
            // Filtrar por categoría
            echo json_encode($app->leerPorCategoria($id_categoria));
        } else {
            // Listar con nombres (más útil)
            echo json_encode($app->leerConNombres());
        }

        break;


    /* =========================
       POST → CREAR RELACIÓN
    ========================= */
    case 'POST':

        $data = $_POST;

        if (!isset($data['id_categoria']) || !isset($data['id_subcategoria'])) {
            echo json_encode(["error" => "Faltan datos"]);
            break;
        }

        $filas = $app->crear($data);

        echo json_encode([
            "status" => "success",
            "mensaje" => "Relación creada",
            "filas_afectadas" => $filas
        ]);

        break;


    /* =========================
       PUT → ACTUALIZAR POSICIÓN
    ========================= */
    case 'PUT':

        if (!$id_categoria || !$id_subcategoria) {
            echo json_encode(["error" => "Se requieren id_categoria y id_subcategoria"]);
            break;
        }

        $data = $_POST;

        $filas = $app->actualizar($id_categoria, $id_subcategoria, $data);

        echo json_encode([
            "status" => "success",
            "mensaje" => "Relación actualizada",
            "filas_afectadas" => $filas
        ]);

        break;


    /* =========================
       DELETE → BORRAR
    ========================= */
    case 'DELETE':

        if ($id_categoria && $id_subcategoria) {
            // borrar relación específica
            $filas = $app->borrar($id_categoria, $id_subcategoria);

        } elseif ($id_categoria) {
            // borrar todas las subcategorías de una categoría
            $filas = $app->borrarPorCategoria($id_categoria);

        } else {
            echo json_encode(["error" => "Debes proporcionar al menos id_categoria"]);
            break;
        }

        echo json_encode([
            "status" => "success",
            "mensaje" => "Eliminado correctamente",
            "filas_afectadas" => $filas
        ]);

        break;


    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}