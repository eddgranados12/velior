<?php
require_once(__DIR__ . "/models/producto.php");

$productoModel = new Producto();

$genero = isset($_GET['genero']) ? trim($_GET['genero']) : null;
$subcategoria = isset($_GET['subcategoria']) ? trim($_GET['subcategoria']) : null;

$productos = $productoModel->getProductosCatalogo($genero, $subcategoria);

require_once(__DIR__ . "/views/productos/index.php");
?>