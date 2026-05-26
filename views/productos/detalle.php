<?php require_once(__DIR__ . "/../header.php"); ?>

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