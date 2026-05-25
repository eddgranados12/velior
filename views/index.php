<?php require_once(__DIR__ . "/header.php"); ?>

<!-- DEBUG: Verificar datos del lookbook -->
<?php if (!empty($productosLookbook)): ?>
    <div style="display:none;">
        <?php foreach ($productosLookbook as $index => $producto): ?>
            Producto <?php echo $index; ?>:
            ID: <?php echo $producto['id_producto']; ?>
            Nombre: <?php echo $producto['producto'] ?? 'Sin nombre'; ?>
            imagen_principal: <?php echo $producto['imagen_principal'] ?? 'no'; ?>
            <br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section class="hero">
    <div class="hero-content">
        <h1>VELIOR</h1>
        <p>
            Descubre la elegancia en cada detalle con VELIOR,
            tu destino definitivo para accesorios de moda.
        </p>
        <a href="/velior/productos.php" class="btn-primary">
            Ver Colección
        </a>
    </div>
</section>

<!-- Sección Colecciones Dinámica -->
<section class="colecciones">
    <h2>Nuestras Colecciones</h2>

    <div class="coleccion-container">
        <?php if (!empty($colecciones)): ?>
            <?php foreach ($colecciones as $index => $coleccion): ?>
                <div class="coleccion-item">
                    <h3><?php echo htmlspecialchars($coleccion['coleccion']); ?></h3>

                    <div id="carouselColeccion<?php echo $coleccion['id_coleccion']; ?>" class="carousel slide carousel-velior">
                        <div class="carousel-inner">

                            <?php if (!empty($coleccion['imagenes']) && is_array($coleccion['imagenes'])): ?>
                                <?php foreach ($coleccion['imagenes'] as $imgIndex => $imagen): ?>
                                    <div class="carousel-item <?php echo $imgIndex === 0 ? 'active' : ''; ?>">
                                        <img src="/velior/uploads/colecciones/<?php echo htmlspecialchars($imagen['imagen_url']); ?>"
                                            class="d-block w-100"
                                            alt="<?php echo htmlspecialchars($coleccion['coleccion'] ?? 'Colección'); ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active">
                                    <img src="/velior/img/placeholder-coleccion.jpg" class="d-block w-100"
                                        alt="Imagen no disponible">
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php if (!empty($coleccion['imagenes']) && count($coleccion['imagenes']) > 1): ?>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselColeccion<?php echo $coleccion['id_coleccion']; ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselColeccion<?php echo $coleccion['id_coleccion']; ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay colecciones disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Sección Lookbook Dinámica -->
<section class="lookbook">
    <div class="lookbook-header">
        <h2>LOOKBOOK</h2>
        <p>Una expresión visual de la elegancia contemporánea</p>
    </div>

    <div class="lookbook-grid">
        <?php if (!empty($productosLookbook)): ?>
            <?php
            $totalProductos = count($productosLookbook);
            $indiceGrande = 4; // El quinto producto será grande si existe
            ?>

            <?php foreach ($productosLookbook as $index => $producto): ?>
                <div class="lookbook-item <?php echo ($index == $indiceGrande && $index < $totalProductos) ? 'large' : ''; ?>">
                    <img src="/velior/uploads/productos/<?php echo htmlspecialchars($producto['imagen_principal'] ?? 'placeholder-producto.jpg'); ?>"
                        alt="<?php echo htmlspecialchars($producto['producto'] ?? 'Velior Producto'); ?>">

                    <div class="look-overlay">
                        <h3><?php echo htmlspecialchars($producto['coleccion_nombre'] ?? 'VELIOR'); ?></h3>
                        <span><?php echo htmlspecialchars($producto['producto'] ?? 'Sin nombre'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Productos de respaldo si no hay en la BD -->
            <div class="lookbook-item">
                <img src="/velior/img/sama-hosseini-BC_pWCCfqBc-unsplash.jpg" alt="Velior Look 1">
                <div class="look-overlay">
                    <h3>Velior Essence</h3>
                    <span>Pure Refinement</span>
                </div>
            </div>

            <div class="lookbook-item">
                <img src="/velior/img/tim-schmidbauer-QIi-5Ozv_zE-unsplash.jpg" alt="Velior Look 2">
                <div class="look-overlay">
                    <h3>Velior Atelier</h3>
                    <span>Crafted Identity</span>
                </div>
            </div>

            <div class="lookbook-item">
                <img src="/velior/img/oscar-ramirez-KIB002BhDjQ-unsplash.jpg" alt="Velior Look 3">
                <div class="look-overlay">
                    <h3>Velior Nova</h3>
                    <span>Modern Luminosity</span>
                </div>
            </div>

            <div class="lookbook-item">
                <img src="/velior/img/bousbia-kadhem-JX5IcprQX5g-unsplash.jpg" alt="Velior Look 4">
                <div class="look-overlay">
                    <h3>Velior Nova</h3>
                    <span>Modern Luminosity</span>
                </div>
            </div>

            <div class="lookbook-item large">
                <img src="/velior/img/oscar-ramirez-oowM0P8DDEo-unsplash.jpg" alt="Velior Editorial">
                <div class="look-overlay">
                    <h3>Editorial 2026</h3>
                    <span>The Art of Detail</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Sección Acerca de Nosotros -->
<section class="acerca-nosotros" id="acerca">
    <h2>Acerca de Nosotros</h2>
    <p>
        En VELIOR, la elegancia se expresa en los detalles. Creamos accesorios que trascienden tendencias,
        diseñados para quienes valoran la sofisticación, la sobriedad y el estilo atemporal. Cada pieza refleja
        equilibrio entre estética, calidad y carácter. <br><br>
        La marca fue fundada por Eduardo Granados, con la visión de convertir los accesorios en un elemento
        distintivo, capaz de comunicar identidad con sutileza y precisión. VELIOR nace de la convicción de que el
        verdadero lujo no es ostentoso, sino refinado, cuidado y auténtico.
        Más que accesorios, creamos piezas que acompañan momentos, que completan una presencia y que hablan sin
        necesidad de palabras.
        VELIOR es elegancia esencial.
    </p>
</section>

<?php require_once(__DIR__ . "/footer.php"); ?>