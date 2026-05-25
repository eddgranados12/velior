<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once(__DIR__ . "/../sistema.class.php");
require_once(__DIR__ . "/../models/categoria.php");

$app = new Categoria();

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {

    case 'GET':
        echo json_encode($id ? $app->leerUno($id) : $app->leer());
        break;

    case 'POST':
       $data = $_POST;
        $id_nuevo = $app->crear($data);
        echo json_encode(["mensaje" => "Categoría creada", "id" => $id_nuevo]);
        break;

    case 'PUT':
        $data = $_POST;
        $app->actualizar($id, $data);
        echo json_encode(["mensaje" => "Categoría actualizada"]);
        break;

    case 'DELETE':
        $app->borrar($id);
        echo json_encode(["mensaje" => "Categoría eliminada"]);
        break;
}