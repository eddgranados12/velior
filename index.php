<?php

require_once(__DIR__ . "/admin/models/producto.php");
require_once(__DIR__ . "/admin/models/coleccion.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$productoModel = new Producto();
$coleccionModel = new Coleccion();

$productosLookbook = $productoModel->getProductosLookbook();
$colecciones = $coleccionModel->getColeccionesConProductos();

require_once(__DIR__ . "/views/index.php");
