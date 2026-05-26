<?php require_once(__DIR__ . "/../header.php"); ?>

<style>
    .catalogo-page {
        padding: 60px 8%;
        background: #faf9f7;
        min-height: 80vh;
    }

    .catalogo-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .catalogo-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 400;
        letter-spacing: 2px;
        color: #2c2c2c;
        margin-bottom: 8px;
    }

    .catalogo-header p {
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #999;
    }

    .catalogo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 32px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* TARJETA */
    .producto-card {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, .08);
    }

    .producto-img {
        position: relative;
        aspect-ratio: 3/4;
        overflow: hidden;
        background: #f0ebe4;
    }

    .producto-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .producto-card:hover .producto-img img {
        transform: scale(1.04);
    }

    /* BOTÓN CARRITO RÁPIDO (aparece al hover) */
    .btn-carrito-rapido {
        position: absolute;
        bottom: 12px;
        left: 12px;
        right: 12px;
        padding: 10px;
        background: rgba(89, 69, 44, 0.92);
        color: #F2E8DC;
        border: none;
        border-radius: 2px;
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        opacity: 0;
        transform: translateY(6px);
        transition: all 0.25s ease;
        backdrop-filter: blur(4px);
    }

    .producto-card:hover .btn-carrito-rapido {
        opacity: 1;
        transform: translateY(0);
    }

    .btn-carrito-rapido:disabled {
        background: rgba(150, 150, 150, 0.9);
        cursor: not-allowed;
    }

    .producto-info {
        padding: 18px 16px 20px;
    }

    .producto-categoria {
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #9c6644;
        display: block;
        margin-bottom: 8px;
    }

    .producto-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: 17px;
        font-weight: 400;
        color: #2c2c2c;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .producto-precio {
        font-size: 15px;
        font-weight: 600;
        color: #59452C;
        margin-bottom: 14px;
        text-align: left;
    }

    .producto-acciones {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-ver-producto {
        flex: 1;
        display: block;
        text-align: center;
        padding: 10px 16px;
        border: 1.5px solid #59452C;
        color: #59452C;
        text-decoration: none;
        border-radius: 2px;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        transition: all 0.2s;
    }

    .btn-ver-producto:hover {
        background: #59452C;
        color: #F2E8DC;
    }

    .sin-productos {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: #999;
    }

    .sin-productos h3 {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        font-weight: 400;
        margin-bottom: 8px;
    }

    /* TOAST */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #59452C;
        color: #F2E8DC;
        padding: 14px 24px;
        border-radius: 4px;
        font-size: 13px;
        letter-spacing: 0.5px;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s;
        z-index: 9999;
        pointer-events: none;
        font-family: 'Montserrat', sans-serif;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    .toast.error {
        background: #c0392b;
    }

    .toast.ok {
        background: #16a34a;
    }

    @media (max-width: 600px) {
        .catalogo-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .catalogo-page {
            padding: 40px 4%;
        }
    }
</style>

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