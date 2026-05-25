<?php

require_once(__DIR__ . '../../../vendor/autoload.php');
include_once(__DIR__ . '../../models/producto.php');

$app = new Producto();
$app->checarRol('Administrador');

// Suponiendo que este método devuelve el array que mostraste
$productos = $app->leer();

// print_r($productos);
// die;

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_top' => 18,
    'margin_bottom' => 15,
    'margin_left' => 12,
    'margin_right' => 12
]);

$html = '
<style>
    body {I
        font-family: sans-serif;
        color: #4b3b33;
        background-color: #fdfaf6;
    }

    .header {
        text-align: center;
        margin-bottom: 18px;
        border-bottom: 2px solid #c8b6a6;
        padding-bottom: 12px;
    }

    .titulo {
        font-size: 24px;
        font-weight: bold;
        color: #7a5c4f;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }

    .subtitulo {
        font-size: 11px;
        color: #9b8579;
        font-style: italic;
    }

    .info-box {
        background: #f7efe8;
        border: 1px solid #d8c3b5;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 10px;
        color: #5f4b42;
    }

    .info-box strong {
        color: #7a5c4f;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        background: #fffdfb;
    }

    thead th {
        background-color: #8b6f61;
        color: #fffaf5;
        padding: 8px;
        text-align: center;
        border: 1px solid #d7c2b2;
        font-size: 9px;
    }

    tbody td {
        border: 1px solid #eadfd5;
        padding: 7px;
        vertical-align: middle;
        color: #4a3b34;
    }

    tbody tr:nth-child(even) {
        background-color: #fcf7f2;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .foto {
        width: 45px;
        height: 45px;
        border: 1px solid #d8c3b5;
        border-radius: 8px;
        object-fit: cover;
    }

    .sin-foto {
        width: 45px;
        height: 45px;
        border: 1px dashed #c9b4a5;
        border-radius: 8px;
        text-align: center;
        line-height: 45px;
        font-size: 7px;
        color: #a08b7d;
        margin: auto;
        background: #f8f1eb;
    }

    .badge {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 10px;
        font-size: 8px;
        font-weight: bold;
    }

    .activo {
        background: #d8ead3;
        color: #4b6b3c;
    }

    .inactivo {
        background: #f2d6d6;
        color: #8a4d4d;
    }

    .destacado {
        background: #f3e2b3;
        color: #8a6d1f;
    }

    .normal {
        background: #e9ddd4;
        color: #6b5449;
    }

    .stock-ok {
        color: #4f6b4f;
        font-weight: bold;
    }

    .stock-bajo {
        color: #a36b2b;
        font-weight: bold;
    }

    .stock-agotado {
        color: #a94442;
        font-weight: bold;
    }

    .descripcion {
        font-size: 8px;
        color: #6d5b52;
    }

    .footer {
        margin-top: 18px;
        text-align: right;
        font-size: 9px;
        color: #8c7b70;
        border-top: 1px solid #d8c3b5;
        padding-top: 8px;
    }
</style>

<div class="header">
    <div class="titulo">VELIOR</div>
    <div class="subtitulo">Reporte general de productos registrados</div>
</div>

<div class="info-box">
    <strong>Fecha de generación:</strong> ' . date('d/m/Y H:i:s') . '<br>
    <strong>Total de productos:</strong> ' . count($productos) . '
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Producto</th>
            <th>SKU</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Destacado</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
';

foreach ($productos as $producto) {

    $descripcion = !empty($producto['descripcion'])
        ? htmlspecialchars($producto['descripcion'])
        : '<span style="color:#a08b7d;">Sin descripción</span>';

    $precio = '$' . number_format((float)($producto['precio'] ?? 0), 2);

    $stock = (int)($producto['stock'] ?? 0);
    if ($stock <= 0) {
        $stockClass = 'stock-agotado';
    } elseif ($stock <= 5) {
        $stockClass = 'stock-bajo';
    } else {
        $stockClass = 'stock-ok';
    }

    $estadoHTML = !empty($producto['activo']) && $producto['activo'] == 1
        ? '<span class="badge activo">Activo</span>'
        : '<span class="badge inactivo">Inactivo</span>';

    $destacadoHTML = !empty($producto['destacado']) && $producto['destacado'] == 1
        ? '<span class="badge destacado">Sí</span>'
        : '<span class="badge normal">No</span>';

    $imagenHTML = '<div class="sin-foto">Sin imagen</div>';

    if (!empty($producto['imagen_url'])) {
        $rutaImagen = $_SERVER['DOCUMENT_ROOT'] . '/uploads/productos/' . basename($producto['imagen_url']);

        if (file_exists($rutaImagen)) {
            $tipo = strtolower(pathinfo($rutaImagen, PATHINFO_EXTENSION));
            $data = base64_encode(file_get_contents($rutaImagen));
            $src = 'data:image/' . $tipo . ';base64,' . $data;
            $imagenHTML = '<img src="' . $src . '" class="foto">';
        }
    }

    $html .= '
        <tr>
            <td class="text-center">' . htmlspecialchars($producto['id_producto'] ?? '') . '</td>
            <td class="text-center">' . $imagenHTML . '</td>
            <td><strong>' . htmlspecialchars($producto['producto'] ?? '') . '</strong></td>
            <td class="text-center">' . htmlspecialchars($producto['sku'] ?? '') . '</td>
            <td class="descripcion">' . $descripcion . '</td>
            <td class="text-right"><strong>' . $precio . '</strong></td>
            <td class="text-center"><span class="' . $stockClass . '">' . $stock . '</span></td>
            <td class="text-center">' . $destacadoHTML . '</td>
            <td class="text-center">' . $estadoHTML . '</td>
        </tr>
    ';
}

$html .= '
    </tbody>
</table>

<div class="footer">
    Documento generado automáticamente por Velior
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output('reporte_productos_velior.pdf', 'I');
exit(); // Terminar para no incluir contenido adicional
?>