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
require_once(__DIR__ . "/../models/coleccion.php");

$app = new Coleccion();

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {

    case 'GET':
        if ($id) {
            echo json_encode($app->leerUno($id));
        } else {
            echo json_encode($app->leer());
        }
        break;

    case 'POST':
       $data = $_POST;
        $id_nuevo = $app->crear($data);
        echo json_encode(["mensaje" => "Colección creada", "id" => $id_nuevo]);
        break;

    case 'PUT':
        $data = $_POST;
        $app->actualizar($id, $data);
        echo json_encode(["mensaje" => "Colección actualizada"]);
        break;

    case 'DELETE':
        $app->borrar($id);
        echo json_encode(["mensaje" => "Colección eliminada"]);
        break;
}