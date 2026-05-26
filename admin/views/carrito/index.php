<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito — Velior</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #faf9f7;
            color: #2c2c2c;
            min-height: 100vh;
        }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            padding: 20px 8%;
            font-size: 12px;
            letter-spacing: 1.5px;
            color: #999;
            text-transform: uppercase;
        }

        .breadcrumb a {
            color: #9c6644;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #59452C;
        }

        .breadcrumb span {
            margin: 0 8px;
        }

        /* ── LAYOUT ── */
        .carrito-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 8% 80px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 40px;
            align-items: start;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 400;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-align: left;
        }

        .subtitulo {
            font-size: 12px;
            letter-spacing: 2px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        /* ── TABLA DE ITEMS ── */
        .carrito-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 40px;
            gap: 16px;
            padding: 0 0 12px;
            border-bottom: 1px solid #e0d9d0;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #999;
        }

        .carrito-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 40px;
            gap: 16px;
            align-items: center;
            padding: 24px 0;
            border-bottom: 1px solid #f0ebe4;
            transition: background 0.2s;
        }

        .producto-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .producto-imagen {
            width: 90px;
            height: 110px;
            object-fit: cover;
            border-radius: 4px;
            background: #f0ebe4;
            flex-shrink: 0;
        }

        .producto-imagen-placeholder {
            width: 90px;
            height: 110px;
            background: #f0ebe4;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c0b5a8;
            font-size: 24px;
            flex-shrink: 0;
        }

        .producto-nombre {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .producto-sku {
            font-size: 11px;
            color: #aaa;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .producto-precio,
        .producto-subtotal {
            font-size: 14px;
            font-weight: 500;
            color: #59452C;
        }

        /* ── CONTROL DE CANTIDAD ── */
        .cantidad-control {
            display: flex;
            align-items: center;
            border: 1px solid #e0d9d0;
            border-radius: 2px;
            width: fit-content;
        }

        .cantidad-btn {
            width: 32px;
            height: 32px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #59452C;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cantidad-btn:hover {
            background: #f0ebe4;
        }

        .cantidad-input {
            width: 40px;
            height: 32px;
            border: none;
            border-left: 1px solid #e0d9d0;
            border-right: 1px solid #e0d9d0;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            background: none;
            color: #2c2c2c;
        }

        .cantidad-input:focus {
            outline: none;
        }

        /* ── BOTÓN ELIMINAR ── */
        .btn-eliminar {
            background: none;
            border: none;
            cursor: pointer;
            color: #ccc;
            font-size: 14px;
            transition: color 0.2s;
            padding: 4px;
        }

        .btn-eliminar:hover {
            color: #c0392b;
        }

        /* ── CARRITO VACÍO ── */
        .carrito-vacio {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }

        .carrito-vacio i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 24px;
        }

        .carrito-vacio h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 12px;
            color: #555;
        }

        .carrito-vacio p {
            color: #999;
            font-size: 14px;
            margin-bottom: 32px;
        }

        /* ── RESUMEN ── */
        .resumen {
            background: #fff;
            border: 1px solid #e0d9d0;
            border-radius: 4px;
            padding: 32px;
            position: sticky;
            top: 20px;
        }

        .resumen h2 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0ebe4;
        }

        .resumen-fila {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            font-size: 13px;
            color: #666;
        }

        .resumen-fila.total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0d9d0;
            font-size: 16px;
            font-weight: 600;
            color: #2c2c2c;
        }

        .resumen-fila .monto {
            color: #59452C;
            font-weight: 500;
        }

        .resumen-fila.total .monto {
            font-size: 20px;
        }

        .envio-gratis {
            background: #f0fdf4;
            color: #16a34a;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 12px;
            border-radius: 2px;
            margin: 16px 0;
            text-align: center;
        }

        /* ── BOTONES ── */
        .btn-checkout {
            display: block;
            width: 100%;
            padding: 16px;
            background: #59452C;
            color: #F2E8DC;
            border: none;
            border-radius: 2px;
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.3s;
            margin-top: 24px;
        }

        .btn-checkout:hover {
            background: #3d2e1a;
        }

        .btn-checkout:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-seguir {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #9c6644;
            text-decoration: none;
        }

        .btn-seguir:hover {
            color: #59452C;
        }

        .mp-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            font-size: 11px;
            color: #aaa;
            letter-spacing: 1px;
        }

        .mp-badge i {
            color: #009ee3;
        }

        /* ── TOAST ── */
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
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .toast.error {
            background: #c0392b;
        }

        @media (max-width: 900px) {
            .carrito-container {
                grid-template-columns: 1fr;
            }

            .resumen {
                position: static;
            }
        }

        @media (max-width: 600px) {
            .carrito-header {
                display: none;
            }

            .carrito-item {
                grid-template-columns: 1fr 40px;
                grid-template-rows: auto auto;
            }

            .carrito-container {
                padding: 0 4% 60px;
            }
        }
    </style>
</head>

<body>

    <?php require_once(__DIR__ . "/../header.php"); // Tu header existente ?>

    <div class="breadcrumb">
        <a href="/velior/index.php">Inicio</a>
        <span>›</span>
        Mi carrito
    </div>

    <div style="max-width:1200px;margin:0 auto;padding:0 8% 20px;">
        <h1>Mi Carrito</h1>
        <p class="subtitulo">
            <?= $numItems ?>
            <?= $numItems === 1 ? 'artículo' : 'artículos' ?>
        </p>
    </div>

    <div class="carrito-container">
        <?php if (empty($items)): ?>
            <div class="carrito-vacio">
                <i class="fa-regular fa-bag-shopping"></i>
                <h2>Tu carrito está vacío</h2>
                <p>Descubre nuestra colección y agrega tus piezas favoritas.</p>
                <a href="/velior/productos.php" class="btn-checkout"
                    style="display:inline-block;width:auto;padding:16px 40px;">
                    Explorar colección
                </a>
            </div>

        <?php else: ?>

            <!-- LISTA DE PRODUCTOS -->
            <div class="carrito-lista">
                <div class="carrito-header">
                    <span>Producto</span>
                    <span>Precio</span>
                    <span>Cantidad</span>
                    <span>Subtotal</span>
                    <span></span>
                </div>

                <?php foreach ($items as $item): ?>
                    <div class="carrito-item" id="item-<?= $item['id_producto'] ?>">
                        <!-- PRODUCTO -->
                        <div class="producto-info">
                            <?php if (!empty($item['imagen_url'])): ?>
                               <img src="/velior/uploads/productos/<?= htmlspecialchars($item['imagen_url']) ?>"
                                    alt="<?= htmlspecialchars($item['nombre']) ?>" class="producto-imagen">
                            <?php else: ?>
                                <div class="producto-imagen-placeholder">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="producto-nombre">
                                    <?= htmlspecialchars($item['nombre']) ?>
                                </div>
                                <?php if ($item['sku']): ?>
                                    <div class="producto-sku">SKU:
                                        <?= htmlspecialchars($item['sku']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- PRECIO -->
                        <div class="producto-precio">
                            $
                            <?= number_format($item['precio_unitario'], 2) ?>
                        </div>

                        <!-- CANTIDAD -->
                        <div>
                            <div class="cantidad-control">
                                <button class="cantidad-btn"
                                    onclick="cambiarCantidad(<?= $item['id_producto'] ?>, -1)">−</button>
                                <input type="number" class="cantidad-input" id="qty-<?= $item['id_producto'] ?>"
                                    value="<?= $item['cantidad'] ?>" min="1" max="<?= $item['stock'] ?>"
                                    onchange="setCantidad(<?= $item['id_producto'] ?>, this.value)">
                                <button class="cantidad-btn"
                                    onclick="cambiarCantidad(<?= $item['id_producto'] ?>, 1)">+</button>
                            </div>
                        </div>

                        <!-- SUBTOTAL -->
                        <div class="producto-subtotal" id="sub-<?= $item['id_producto'] ?>">
                            $
                            <?= number_format($item['subtotal'], 2) ?>
                        </div>

                        <!-- ELIMINAR -->
                        <button class="btn-eliminar" onclick="eliminarItem(<?= $item['id_producto'] ?>)" title="Eliminar">
                            <i class="fa-regular fa-xmark"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- RESUMEN -->
            <aside class="resumen">
                <h2>Resumen</h2>

                <div class="resumen-fila">
                    <span>Subtotal</span>
                    <span class="monto" id="resumen-subtotal">$
                        <?= number_format($total, 2) ?>
                    </span>
                </div>
                <div class="resumen-fila">
                    <span>Envío</span>
                    <span class="monto">Por calcular</span>
                </div>

                <div class="envio-gratis">
                    <i class="fa-solid fa-truck-fast"></i> Envío gratis en pedidos +$999
                </div>

                <div class="resumen-fila total">
                    <span>Total</span>
                    <span class="monto" id="resumen-total">$
                        <?= number_format($total, 2) ?>
                    </span>
                </div>

                <a href="/velior/admin/carrito.php?accion=checkout" class="btn-checkout">
                    Proceder al pago
                </a>

                <a href="/velior/productos.php" class="btn-seguir">
                    ← Seguir comprando
                </a>

                <div class="mp-badge">
                    <i class="fa-solid fa-shield-check"></i>
                    Pago seguro con Mercado Pago
                </div>
            </aside>

        <?php endif; ?>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        const BASE = '/velior/admin/carrito.php';

        function mostrarToast(msg, tipo = 'ok') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast show' + (tipo === 'error' ? ' error' : '');
            setTimeout(() => t.className = 'toast', 3000);
        }

        function cambiarCantidad(idProducto, delta) {
            const input = document.getElementById('qty-' + idProducto);
            const nueva = parseInt(input.value) + delta;
            if (nueva < 1) {
                eliminarItem(idProducto);
                return;
            }
            input.value = nueva;
            setCantidad(idProducto, nueva);
        }

        function setCantidad(idProducto, cantidad) {
            const fd = new FormData();
            fd.append('id_producto', idProducto);
            fd.append('cantidad', cantidad);

            fetch(BASE + '?accion=actualizar', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) {
                        mostrarToast(data.mensaje, 'error');
                        return;
                    }
                    // Actualizar subtotal del item
                    const precio = parseFloat(
                        document.querySelector('#item-' + idProducto + ' .producto-precio')
                            .textContent.replace('$', '').replace(',', '')
                    );
                    const qty = parseInt(document.getElementById('qty-' + idProducto).value);
                    document.getElementById('sub-' + idProducto).textContent =
                        '$' + (precio * qty).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                    document.getElementById('resumen-subtotal').textContent = '$' + data.total;
                    document.getElementById('resumen-total').textContent = '$' + data.total;
                    mostrarToast('Carrito actualizado');
                })
                .catch(() => mostrarToast('Error al actualizar', 'error'));
        }

        function eliminarItem(idProducto) {
            const fd = new FormData();
            fd.append('id_producto', idProducto);

            fetch(BASE + '?accion=eliminar', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) { mostrarToast(data.mensaje, 'error'); return; }

                    const el = document.getElementById('item-' + idProducto);
                    el.style.opacity = '0';
                    el.style.transition = 'opacity 0.3s';
                    setTimeout(() => {
                        el.remove();
                        document.getElementById('resumen-subtotal').textContent = '$' + data.total;
                        document.getElementById('resumen-total').textContent = '$' + data.total;
                        if (data.num_items === 0) location.reload();
                    }, 300);
                    mostrarToast('Producto eliminado');
                })
                .catch(() => mostrarToast('Error al eliminar', 'error'));
        }
    </script>

</body>

</html>