<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/stock.php");

$app = new Stock();
$app->checarRol('Administrador');

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$filtro = isset($_GET['filtro']) ? trim($_GET['filtro']) : '';

include_once(__DIR__ . '/views/header.php');

$resumen = $app->resumenStock();
$productos = $app->leerStock($busqueda, $filtro);

require(__DIR__ . "/views/stock/index.php");

include_once(__DIR__ . '/views/footer.php');
?>