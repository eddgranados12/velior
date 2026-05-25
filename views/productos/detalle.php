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
                <img
                    id="imagenPrincipal"
                    src="/velior/uploads/productos/<?php echo htmlspecialchars($imagenPrincipal); ?>"
                    alt="<?php echo htmlspecialchars($producto['producto']); ?>">
            </div>

            <?php if (!empty($imagenes) && count($imagenes) > 1): ?>
                <div class="detalle-thumbnails">
                    <?php foreach ($imagenes as $img): ?>
                        <img
                            class="thumb-producto"
                            src="/velior/uploads/productos/<?php echo htmlspecialchars($img['imagen_url']); ?>"
                            alt="Miniatura producto"
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
                $<?php echo number_format($producto['precio'], 2); ?>
            </p>

            <?php if (!empty($producto['descripcion'])): ?>
                <p class="detalle-descripcion">
                    <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
                </p>
            <?php endif; ?>

            <!-- ATRIBUTOS -->
            <?php if (!empty($atributos)): ?>
                <?php foreach ($atributos as $nombre => $atributo): ?>

                    <?php if (strtolower($nombre) === 'talla'): ?>
                        <div class="detalle-atributo">
                            <h3><?php echo htmlspecialchars($atributo['nombre_atributo']); ?></h3>
                            <div class="opciones-atributo">
                                <?php foreach ($atributo['valores'] as $valor): ?>
                                    <button
                                        type="button"
                                        class="btn-atributo"
                                        onclick="seleccionarAtributo(this, '<?php echo htmlspecialchars($atributo['nombre_atributo']); ?>', '<?php echo htmlspecialchars($valor['valor']); ?>')">
                                        <?php echo htmlspecialchars($valor['valor']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="detalle-atributo">
                            <h3><?php echo htmlspecialchars($atributo['nombre_atributo']); ?></h3>
                            <div class="opciones-atributo">
                                <?php foreach ($atributo['valores'] as $valor): ?>
                                    <button
                                        type="button"
                                        class="btn-atributo"
                                        onclick="seleccionarAtributo(this, '<?php echo htmlspecialchars($atributo['nombre_atributo']); ?>', '<?php echo htmlspecialchars($valor['valor']); ?>')">
                                        <?php echo htmlspecialchars($valor['valor']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>

            <div class="detalle-acciones">
                <button class="btn-comprar">Agregar al carrito</button>
            </div>

            <div id="seleccionResumen" class="seleccion-resumen"></div>
        </div>
    </div>
</section>

<script>
    function cambiarImagen(img) {
        document.getElementById("imagenPrincipal").src = img.src;
    }

    const seleccion = {};

    function seleccionarAtributo(btn, nombreAtributo, valor) {
        const contenedor = btn.parentElement;
        const botones = contenedor.querySelectorAll(".btn-atributo");

        botones.forEach(b => b.classList.remove("activo"));
        btn.classList.add("activo");

        seleccion[nombreAtributo] = valor;
        actualizarResumen();
    }

    function actualizarResumen() {
        const resumen = document.getElementById("seleccionResumen");
        let html = "<strong>Selección:</strong><br>";

        for (const atributo in seleccion) {
            html += `${atributo}: ${seleccion[atributo]}<br>`;
        }

        resumen.innerHTML = html;
    }
</script>

<?php require_once(__DIR__ . "/../footer.php"); ?>