<?php require_once(__DIR__ . "/../header.php"); ?>

<section class="catalogo-page">
    <div class="catalogo-header">
        <h1>
            <?php
            if (!empty($genero)) {
                echo ucfirst(htmlspecialchars($genero));
            } else {
                echo "Todos los productos";
            }
            ?>
        </h1>

        <p>
            <?php
            if (!empty($subcategoria) && strtolower($subcategoria) !== 'todo') {
                echo "Mostrando: " . ucfirst(htmlspecialchars($subcategoria));
            } else {
                echo "Explora nuestra colección";
            }
            ?>
        </p>
    </div>

    <div class="catalogo-grid">
        <?php if (!empty($productos)): ?>

            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <div class="producto-img">
                        <img src="/velior/uploads/productos/<?php echo htmlspecialchars($producto['imagen_principal']); ?>"
                             alt="<?php echo htmlspecialchars($producto['producto']); ?>">
                    </div>

                    <div class="producto-info">
                        <span class="producto-categoria">
                            <?php echo htmlspecialchars($producto['categoria'] ?? 'Colección'); ?> / 
                            <?php echo htmlspecialchars($producto['nombre_subcategoria'] ?? 'Producto'); ?>
                        </span>

                        <h3><?php echo htmlspecialchars($producto['producto']); ?></h3>

                        <p class="producto-precio">
                            $<?php echo number_format($producto['precio'], 2); ?>
                        </p>

                        <a href="/velior/detalle_producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn-ver-producto">
                            Ver producto
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <div class="sin-productos">
                <h3>No hay productos disponibles</h3>
                <p>No encontramos productos para esta selección.</p>
            </div>

        <?php endif; ?>
    </div>
</section>

<?php require_once(__DIR__ . "/../footer.php"); ?>