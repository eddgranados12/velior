<?php
require_once(__DIR__ . '/../admin/sistema.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = new Sistema();
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VELIOR</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Montserrat&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="/velior/css/main.css">
    <link rel="stylesheet" href="/velior/css/catalogo.css">

</head>

<body>
    <nav>

        <ul class="menu">

            <li><a href="/velior/index.php">Inicio</a></li>

            <li class="dropdown">
                Dama
                <ul class="submenu">
                    <li><a href="/velior/productos.php?genero=dama&subcategoria=todo">Todos</a></li>
                    <li><a href="/velior/productos.php?genero=dama&subcategoria=anillos">Anillos</a></li>
                    <li><a href="/velior/productos.php?genero=dama&subcategoria=collares">Collares</a></li>
                    <li><a href="/velior/productos.php?genero=dama&subcategoria=relojes">Relojes</a></li>
                </ul>
            </li>

            <li class="dropdown">
                Caballero
                <ul class="submenu">
                    <li><a href="/velior/productos.php?genero=caballero&subcategoria=todo">Todos</a></li>
                    <li><a href="/velior/productos.php?genero=caballero&subcategoria=anillos">Anillos</a></li>
                    <li><a href="/velior/productos.php?genero=caballero&subcategoria=collares">Collares</a></li>
                    <li><a href="/velior/productos.php?genero=caballero&subcategoria=relojes">Relojes</a></li>
                </ul>
            </li>

            <li><a href="#acerca">Acerca de nosotros</a></li>

        </ul>

        <div class="barra-busqueda">
            <input type="text" placeholder="Buscar...">
            <button><i class="bi bi-search"></i></button>
        </div>

        <div class="nav-icons">

            <a href="/velior/favoritos.php" class="icon-link">
                <i class="bi bi-heart-fill"></i>
                <span class="badge">0</span>
            </a>

            <a href="/velior/carrito.php" class="icon-link">
                <i class="bi bi-cart-fill"></i>
                <span class="badge">0</span>
            </a>

            <?php if ($app->estaLogueado()): ?>

                <a href="/velior/mi_cuenta.php" class="icon-link" title="Mi cuenta">
                    <i class="bi bi-person-fill"></i>
                </a>

                <a href="/velior/admin/login.php?accion=logout" class="icon-link" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </a>

            <?php else: ?>

                <a href="/velior/admin/login.php?accion=login" class="icon-link" title="Iniciar sesión">
                    <i class="bi bi-person-fill"></i>
                </a>

            <?php endif; ?>

        </div>

    </nav>