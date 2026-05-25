<?php
require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/producto.php");

// Obtener ID del producto
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: producto.php");
    exit;
}

require_once(__DIR__ . "/views/header.php");
require_once(__DIR__ . "/views/producto/detalle_api.php");
require_once(__DIR__ . "/views/footer.php");
?>
