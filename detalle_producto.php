<?php
require_once(__DIR__ . "/admin/models/producto.php");

$productoModel = new Producto();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Producto no válido");
}

$producto = $productoModel->leerUno($id);

if (!$producto) {
    die("Producto no encontrado");
}

$imagenes = $productoModel->obtenerImagenes($id);
$atributos = $productoModel->obtenerAtributosProducto($id);

require_once(__DIR__ . "/views/productos/detalle.php");
?>