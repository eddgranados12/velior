<?php require_once(__DIR__ . "/../header.php"); ?>

<style>
    .detalle-producto-page {
        padding: 60px 8%;
        background: #faf9f7;
        min-height: 80vh;
    }

    .detalle-producto-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    /* GALERÍA */
    .detalle-galeria {
        position: sticky;
        top: 20px;
    }

    .detalle-imagen-principal {
        width: 100%;
        aspect-ratio: 3/4;
        overflow: hidden;
        border-radius: 4px;
        background: #f0ebe4;
        margin-bottom: 12px;
    }

    .detalle-imagen-principal img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .detalle-imagen-principal:hover img {
        transform: scale(1.03);
    }

    .detalle-thumbnails {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .thumb-producto {
        width: 72px;
        height: 88px;
        object-fit: cover;
        border-radius: 3px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s;
        background: #f0ebe4;
    }

    .thumb-producto:hover,
    .thumb-producto.activa {
        border-color: #9c6644;
    }

    /* INFO */
    .detalle-info {
        padding-top: 8px;
    }

    .detalle-categoria {
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #9c6644;
        margin-bottom: 10px;
        display: block;
    }

    .detalle-info h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 400;
        letter-spacing: 1px;
        margin-bottom: 16px;
        text-align: left;
        color: #2c2c2c;
    }

    .detalle-precio {
        font-size: 24px;
        font-weight: 600;
        color: #59452C;
        margin-bottom: 20px;
        text-align: left;
    }

    .detalle-descripcion {
        font-size: 14px;
        line-height: 1.8;
        color: #666;
        margin-bottom: 28px;
        text-align: left;
        border-top: 1px solid #f0ebe4;
        padding-top: 20px;
    }

    /* ATRIBUTOS */
    .detalle-atributo {
        margin-bottom: 24px;
    }

    .detalle-atributo h3 {
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .opciones-atributo {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-atributo {
        padding: 8px 18px;
        border: 1.5px solid #e0d9d0;
        background: #fff;
        border-radius: 2px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        cursor: pointer;
        color: #2c2c2c;
        transition: all 0.2s;
    }

    .btn-atributo:hover {
        border-color: #9c6644;
        color: #9c6644;
    }

    .btn-atributo.activo {
        border-color: #59452C;
        background: #59452C;
        color: #F2E8DC;
    }

    /* STOCK */
    .detalle-stock {
        font-size: 12px;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stock-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #16a34a;
        display: inline-block;
    }

    .stock-dot.bajo {
        background: #d97706;
    }

    .stock-dot.sin {
        background: #dc2626;
    }

    /* CANTIDAD */
    .cantidad-wrapper {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }

    .cantidad-label {
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #999;
    }

    .cantidad-control {
        display: flex;
        align-items: center;
        border: 1.5px solid #e0d9d0;
        border-radius: 2px;
    }

    .cantidad-btn {
        width: 36px;
        height: 36px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
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
        width: 44px;
        height: 36px;
        border: none;
        border-left: 1px solid #e0d9d0;
        border-right: 1px solid #e0d9d0;
        text-align: center;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        background: none;
        color: #2c2c2c;
    }

    .cantidad-input:focus {
        outline: none;
    }

    /* ACCIONES */
    .detalle-acciones {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
    }

    .btn-carrito {
        flex: 1;
        padding: 14px 24px;
        background: #59452C;
        color: #F2E8DC;
        border: none;
        border-radius: 2px;
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-carrito:hover {
        background: #3d2e1a;
    }

    .btn-carrito:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .btn-favorito {
        width: 48px;
        height: 48px;
        border: 1.5px solid #e0d9d0;
        background: #fff;
        border-radius: 2px;
        cursor: pointer;
        font-size: 18px;
        color: #ccc;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .btn-favorito:hover {
        border-color: #9c6644;
        color: #9c6644;
    }

    .btn-favorito.activo {
        border-color: #c0392b;
        color: #c0392b;
    }

    .seleccion-resumen {
        font-size: 12px;
        color: #888;
        letter-spacing: 0.5px;
        margin-top: 8px;
        min-height: 20px;
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

    @media (max-width: 768px) {
        .detalle-producto-container {
            grid-template-columns: 1fr;
            gap: 32px;
        }

        .detalle-galeria {
            position: static;
        }
    }
</style>

<section class="detalle-producto-page">
    <div class="detalle-producto-container">

        <!-- GALERÍA -->
        <div class="detalle-galeria">
            <div class="detalle-imagen-principal">
                <?php
                $imagenPrincipal = !empty($imagenes)
                    ? $imagenes[0]['imagen_url']
                    : 'img/placeholder-producto.jpg';
                ?>
                <img id="imagenPrincipal"
                    src="/velior/uploads/productos/<?php echo htmlspecialchars($imagenPrincipal); ?>"
                    alt="<?php echo htmlspecialchars($producto['producto']); ?>">
            </div>

            <?php if (!empty($imagenes) && count($imagenes) > 1): ?>
                <div class="detalle-thumbnails">
                    <?php foreach ($imagenes as $img): ?>
                        <img class="thumb-producto"
                            src="/velior/uploads/productos/<?php echo htmlspecialchars($img['imagen_url']); ?>" alt="Miniatura"
                            onclick="cambiarImagen(this)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="detalle-info">
            <span class="detalle-categoria">
                <?php echo htmlspecialchars($producto['producto']); ?>
            </span>

            <h1><?php echo htmlspecialchars($producto['producto']); ?></h1>

            <p class="detalle-precio">
                $<?php echo number_format($producto['precio'], 2); ?> MXN
            </p>

            <!-- STOCK -->
            <?php
            $stock = (int) $producto['stock'];
            $stockClass = $stock > 5 ? '' : ($stock > 0 ? 'bajo' : 'sin');
            $stockTexto = $stock > 5
                ? 'En stock'
                : ($stock > 0 ? "Últimas {$stock} piezas" : 'Sin stock');
            ?>
            <div class="detalle-stock">
                <span class="stock-dot <?php echo $stockClass; ?>"></span>
                <span><?php echo $stockTexto; ?></span>
            </div>

            <?php if (!empty($producto['descripcion'])): ?>
                <p class="detalle-descripcion">
                    <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
                </p>
            <?php endif; ?>

            <!-- ATRIBUTOS -->
            <?php if (!empty($atributos)): ?>
                <?php foreach ($atributos as $nombre => $atributo): ?>
                    <div class="detalle-atributo">
                        <h3><?php echo htmlspecialchars($atributo['nombre_atributo']); ?></h3>
                        <div class="opciones-atributo">
                            <?php foreach ($atributo['valores'] as $valor): ?>
                                <button type="button" class="btn-atributo"
                                    onclick="seleccionarAtributo(this, '<?php echo htmlspecialchars($atributo['nombre_atributo']); ?>', '<?php echo htmlspecialchars($valor['valor']); ?>')">
                                    <?php echo htmlspecialchars($valor['valor']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- CANTIDAD -->
            <?php if ($stock > 0): ?>
                <div class="cantidad-wrapper">
                    <span class="cantidad-label">Cantidad</span>
                    <div class="cantidad-control">
                        <button type="button" class="cantidad-btn" onclick="cambiarCantidad(-1)">−</button>
                        <input type="number" id="inputCantidad" class="cantidad-input" value="1" min="1"
                            max="<?php echo $stock; ?>">
                        <button type="button" class="cantidad-btn" onclick="cambiarCantidad(1)">+</button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ACCIONES -->
            <div class="detalle-acciones">
                <?php if ($stock > 0): ?>
                    <button class="btn-carrito" id="btnCarrito"
                        onclick="agregarAlCarrito(<?php echo $producto['id_producto']; ?>)">
                        <i class="fa-regular fa-bag-shopping" style="margin-right:8px"></i>
                        Agregar al carrito
                    </button>
                <?php else: ?>
                    <button class="btn-carrito" disabled>Sin stock disponible</button>
                <?php endif; ?>

                <?php if (isset($_SESSION['validado']) && $_SESSION['validado']): ?>
                    <button class="btn-favorito" id="btnFavorito"
                        onclick="toggleFavorito(<?php echo $producto['id_producto']; ?>)" title="Agregar a favoritos">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                <?php endif; ?>
            </div>

            <div id="seleccionResumen" class="seleccion-resumen"></div>
        </div>
    </div>
</section>

<div class="toast" id="toast"></div>

<script>
    // ── Galería ──────────────────────────────────────────────────
    function cambiarImagen(img) {
        document.getElementById('imagenPrincipal').src = img.src;
        document.querySelectorAll('.thumb-producto').forEach(t => t.classList.remove('activa'));
        img.classList.add('activa');
    }

    // ── Atributos ────────────────────────────────────────────────
    const seleccion = {};

    function seleccionarAtributo(btn, nombreAtributo, valor) {
        btn.parentElement.querySelectorAll('.btn-atributo')
            .forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        seleccion[nombreAtributo] = valor;
        actualizarResumen();
    }

    function actualizarResumen() {
        const resumen = document.getElementById('seleccionResumen');
        let html = '';
        for (const attr in seleccion) {
            html += `<span style="margin-right:12px">${attr}: <strong>${seleccion[attr]}</strong></span>`;
        }
        resumen.innerHTML = html;
    }

    // ── Cantidad ─────────────────────────────────────────────────
    function cambiarCantidad(delta) {
        const input = document.getElementById('inputCantidad');
        const nueva = Math.max(1, Math.min(parseInt(input.max), parseInt(input.value) + delta));
        input.value = nueva;
    }

    // ── Toast ────────────────────────────────────────────────────
    function mostrarToast(msg, tipo = '') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast show ' + tipo;
        setTimeout(() => t.className = 'toast', 3000);
    }

    // ── Agregar al carrito ───────────────────────────────────────
    function agregarAlCarrito(idProducto) {
        <?php if (!isset($_SESSION['validado']) || !$_SESSION['validado']): ?>
            window.location.href = '/velior/admin/login.php?accion=login';
            return;
        <?php endif; ?>

        const cantidad = document.getElementById('inputCantidad').value;
        const btn = document.getElementById('btnCarrito');

        btn.disabled = true;
        btn.innerHTML = 'Agregando...';

        const fd = new FormData();
        fd.append('id_producto', idProducto);
        fd.append('cantidad', cantidad);

        fetch('/velior/admin/carrito.php?accion=agregar', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    btn.innerHTML = '✓ Agregado';
                    btn.style.background = '#16a34a';
                    // Actualizar badge del nav
                    const badge = document.querySelector('.badge');
                    if (badge) badge.textContent = data.num_items;
                    mostrarToast('Producto agregado al carrito', 'ok');
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-regular fa-bag-shopping" style="margin-right:8px"></i>Agregar al carrito';
                        btn.style.background = '#59452C';
                    }, 2000);
                } else {
                    mostrarToast(data.mensaje, 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-regular fa-bag-shopping" style="margin-right:8px"></i>Agregar al carrito';
                }
            })
            .catch(() => {
                mostrarToast('Error de conexión', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Agregar al carrito';
            });
    }

    // ── Favoritos ────────────────────────────────────────────────
    function toggleFavorito(idProducto) {
        const btn = document.getElementById('btnFavorito');
        const esFavorito = btn.classList.contains('activo');

        fetch('/velior/admin/favorito.php?accion=' + (esFavorito ? 'eliminar' : 'agregar'), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id_producto=' + idProducto
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    btn.classList.toggle('activo');
                    btn.querySelector('i').className = btn.classList.contains('activo')
                        ? 'fa-solid fa-heart'
                        : 'fa-regular fa-heart';
                    mostrarToast(btn.classList.contains('activo')
                        ? 'Agregado a favoritos'
                        : 'Eliminado de favoritos', 'ok');
                } else {
                    mostrarToast(data.mensaje, 'error');
                }
            })
            .catch(() => mostrarToast('Error de conexión', 'error'));
    }
</script>

<?php require_once(__DIR__ . "/../footer.php"); ?>