<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Velior Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/Velior/css/admin_forms.css">
    <link rel="stylesheet" href="/Velior/css/admin_tables.css">
    <link rel="stylesheet" href="/Velior/css/admin_dashboard.css">
    <link rel="stylesheet" href="/Velior/css/admin_navbar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Velior/css/admin_navbar.css" </head>

<body>

    <nav class="navbar velior-navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand-custom" href="/velior/admin/index.php">
                <i class="bi bi-gem-fill"></i> VELIOR
            </a>

            <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Inicio -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/velior/admin/index.php">
                            <i class="bi bi-house-door-fill"></i> Inicio
                        </a>
                    </li>

                    <!-- Menú de Pedidos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="pedidosDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-cart-fill"></i> Pedidos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="pedidosDropdown">
                            <li><a class="dropdown-item dropdown-item-custom" href="#">
                                    <i class="bi bi-plus-circle"></i> Nuevo Pedido
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="#">
                                    <i class="bi bi-list-ul"></i> Lista de Pedidos
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="#">
                                    <i class="bi bi-truck"></i> Seguimiento de Pedidos
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item dropdown-item-custom" href="#">
                                    <i class="bi bi-clock-history"></i> Historial de Pedidos
                                </a></li>
                        </ul>
                    </li>

                    <!-- Menú de Catálogo -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="catalogoDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Catálogo
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="catalogoDropdown">
                            <li><a class="dropdown-item dropdown-item-custom" href="categoria.php">
                                    <i class="bi bi-tags-fill"></i> Categorías
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="subcategoria.php">
                                    <i class="bi bi-diagram-3"></i> Subcategorías
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="cat_subcategoria.php">
                                    <i class="bi bi-link-45deg"></i> Vinculación
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="coleccion.php">
                                    <i class="bi bi-gem"></i> Colecciones
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item dropdown-item-custom" href="producto.php">
                                    <i class="bi bi-box-seam-fill"></i> Productos
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="atributo.php">
                                    <i class="bi bi-tags-fill"></i> Atributos
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="atributo_valor.php">
                                    <i class="bi bi-list-check"></i> Valores de Atributos
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="producto_atributo.php">
                                    <i class="bi bi-list-check"></i> Atributos por Producto
                                </a></li>

                        </ul>
                    </li>

                    <!-- Inventario -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="stock.php">
                            <i class="bi bi-pie-chart-fill"></i> Inventario
                        </a>
                    </li>

                    <!-- Menú de hamburguesa con funciones adicionales -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="masDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i> Más
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end" aria-labelledby="masDropdown">
                            <li><a class="dropdown-item dropdown-item-custom" href="clientes.php">
                                    <i class="bi bi-people-fill"></i> Clientes
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="reportes.php">
                                    <i class="bi bi-file-text-fill"></i> Reportes
                                </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="configuracion.php">
                                    <i class="bi bi-gear-fill"></i> Configuración
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item dropdown-item-custom" href="backup.php">
                                    <i class="bi bi-database-fill-down"></i> Respaldo
                                </a></li>
                        </ul>
                    </li>

                    <!-- Salir -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="login.php?accion=logout">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>