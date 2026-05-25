<?php

// HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// PREFLIGHT (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once(__DIR__ . "/../sistema.class.php");
require_once(__DIR__ . "/../models/producto.php");

$app = new Producto();

$accion = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

$data = [];

switch ($accion) {

    /* =========================
       GET → LISTAR / UNO
    ========================= */
    case 'GET':

        if (!is_null($id)) {
            $data = $app->leerUno($id);
        } else {
            $data = $app->leer();
        }

        echo json_encode($data);
        break;


    /* =========================
       POST → CREAR
    ========================= */
    case 'POST':

       $data = $_POST;

        $id_nuevo = $app->crear($data);

        if ($id_nuevo) {
            echo json_encode([
                "status" => "success",
                "mensaje" => "Producto creado",
                "id" => $id_nuevo
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "mensaje" => "No se pudo crear el producto"
            ]);
        }

        break;


    /* =========================
       PUT → ACTUALIZAR
    ========================= */
    case 'PUT':

        if (is_null($id)) {
            echo json_encode(["error" => "ID requerido"]);
            break;
        }

        $data = $_POST;

        $cantidad = $app->actualizar($id, $data);

        echo json_encode([
            "status" => "success",
            "mensaje" => "Producto actualizado",
            "filas_afectadas" => $cantidad
        ]);

        break;


    /* =========================
       DELETE → BORRAR
    ========================= */
    case 'DELETE':

        if (is_null($id)) {
            echo json_encode(["error" => "ID requerido"]);
            break;
        }

        $cantidad = $app->borrar($id);

        echo json_encode([
            "status" => "success",
            "mensaje" => "Producto eliminado",
            "filas_afectadas" => $cantidad
        ]);

        break;


    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}