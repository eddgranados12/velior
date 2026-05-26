<?php

require_once(__DIR__ . "/sistema.class.php");
require_once(__DIR__ . "/models/dashboardModel.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sistema = new Sistema();
$sistema->requiereLogin();

if (!$sistema->esAdmin()) {
    header("Location: /velior/index.php");
    exit();
}

$sistema->checarPermiso('dashboard.view');

$dashboard = new DashboardModel($sistema->db());

/* ESTADISTICAS */

$total_productos = $dashboard->totalProductos();
$total_categorias = $dashboard->totalCategorias();
$total_subcategorias = $dashboard->totalSubcategorias();
$total_colecciones = $dashboard->totalColecciones();

include_once(__DIR__ . "/views/header.php");

?>

<div class="admin-dashboard">

    <h1 class="dashboard-title">Panel de Administración</h1>

    <!-- TARJETAS DE ESTADISTICAS -->

    <div class="dashboard-cards">

        <div class="card-admin">
            <h3>Productos</h3>
            <p><?= $total_productos ?></p>
            <span>Productos registrados</span>
        </div>

        <div class="card-admin">
            <h3>Categorías</h3>
            <p><?= $total_categorias ?></p>
            <span>Categorías del catálogo</span>
        </div>

        <div class="card-admin">
            <h3>Subcategorías</h3>
            <p><?= $total_subcategorias ?></p>
            <span>Organización del catálogo</span>
        </div>

        <div class="card-admin">
            <h3>Colecciones</h3>
            <p><?= $total_colecciones ?></p>
            <span>Colecciones disponibles</span>
        </div>

    </div>


    <!-- ACCIONES RAPIDAS -->

    <div class="quick-actions">

        <h2>Acciones rápidas</h2>

        <div class="quick-grid">

            <?php if ($sistema->validarPermiso('producto.create')): ?>
                <a href="producto.php?accion=crear" class="quick-card">
                    <i class="bi bi-plus-circle-fill"></i>
                    Nuevo Producto
                </a>
            <?php endif; ?>

            <?php if ($sistema->validarPermiso('categoria.create')): ?>
                <a href="categoria.php?accion=crear" class="quick-card">
                    <i class="bi bi-tags-fill"></i>
                    Nueva Categoría
                </a>
            <?php endif; ?>

            <?php if ($sistema->validarPermiso('subcategoria.create')): ?>
                <a href="subcategoria.php?accion=crear" class="quick-card">
                    <i class="bi bi-diagram-3"></i>
                    Nueva Subcategoría
                </a>
            <?php endif; ?>

            <?php if ($sistema->validarPermiso('coleccion.create')): ?>
                <a href="coleccion.php?accion=crear" class="quick-card">
                    <i class="bi bi-gem"></i>
                    Nueva Colección
                </a>
            <?php endif; ?>

        </div>

    </div>


    <!-- ALERTAS -->

    <div class="dashboard-alerts">

        <h2>Alertas del sistema</h2>

        <ul>

            <li>⚠ Revisar productos con bajo stock</li>

            <li>⭐ Verificar productos destacados activos</li>

            <li>📅 Revisar colecciones próximas a finalizar</li>

        </ul>

    </div>

</div>

<?php

include_once(__DIR__ . "/views/footer.php");

?>