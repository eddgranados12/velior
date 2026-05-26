<?php require_once(__DIR__ . "/../header.php"); ?>

<section class="catalogo-page">
    <div class="catalogo-header">
        <h1>
            <?php echo !empty($genero) ? ucfirst(htmlspecialchars($genero)) : 'Todos los productos'; ?>
        </h1>
        <p>
            <?php
            echo (!empty($subcategoria) && strtolower($subcategoria) !== 'todo')
                ? 'Mostrando: ' . ucfirst(htmlspecialchars($subcategoria))
                : 'Explora nuestra colección';
            ?>
        </p>
    </div>

    <div class="catalogo-grid">
        <?php if (!empty($productos)): ?>

            <?php foreach ($productos as $p): ?>
                <div class="producto-card">

                    <div class="producto-img">
                        <img src="/velior/uploads/productos/<?php echo htmlspecialchars($p['imagen_principal']); ?>"
                            alt="<?php echo htmlspecialchars($p['producto']); ?>">

                        <!-- BOTÓN RÁPIDO AL HOVER -->
                        <button class="btn-carrito-rapido" data-id="<?php echo $p['id_producto']; ?>"
                            onclick="agregarRapido(this, <?php echo $p['id_producto']; ?>)">
                            <i class="fa-regular fa-bag-shopping" style="margin-right:6px"></i>
                            Agregar al carrito
                        </button>
                    </div>

                    <div class="producto-info">
                        <span class="producto-categoria">
                            <?php echo htmlspecialchars($p['categoria'] ?? 'Colección'); ?> /
                            <?php echo htmlspecialchars($p['nombre_subcategoria'] ?? 'Producto'); ?>
                        </span>

                        <h3><?php echo htmlspecialchars($p['producto']); ?></h3>

                        <p class="producto-precio">
                            $<?php echo number_format($p['precio'], 2); ?>
                        </p>

                        <div class="producto-acciones">
                            <a href="/velior/detalle_producto.php?id=<?php echo $p['id_producto']; ?>" class="btn-ver-producto">
                                Ver producto
                            </a>
                        </div>
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

<div class="toast" id="toast"></div>

<script>
    function mostrarToast(msg, tipo = '') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast show ' + tipo;
        setTimeout(() => t.className = 'toast', 3000);
    }

    function agregarRapido(btn, idProducto) {
        <?php if (!isset($_SESSION['validado']) || !$_SESSION['validado']): ?>
            window.location.href = '/velior/admin/login.php?accion=login';
            return;
        <?php endif; ?>

        btn.disabled = true;
        btn.innerHTML = 'Agregando...';

        const fd = new FormData();
        fd.append('id_producto', idProducto);
        fd.append('cantidad', 1);

        fetch('/velior/admin/carrito.php?accion=agregar', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    btn.innerHTML = '✓ Agregado';
                    btn.style.background = 'rgba(22,163,74,0.92)';
                    const badge = document.querySelector('.badge');
                    if (badge) badge.textContent = data.num_items;
                    mostrarToast('Producto agregado al carrito', 'ok');
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-regular fa-bag-shopping" style="margin-right:6px"></i>Agregar al carrito';
                        btn.style.background = 'rgba(89,69,44,0.92)';
                    }, 2000);
                } else {
                    mostrarToast(data.mensaje, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-regular fa-bag-shopping" style="margin-right:6px"></i>Agregar al carrito';
                }
            })
            .catch(() => {
                mostrarToast('Error de conexión', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Agregar al carrito';
            });
    }
</script>

<?php require_once(__DIR__ . "/../footer.php"); ?>