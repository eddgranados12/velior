<div class="admin-table-container">

    <h1>Administración de Productos</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="producto.php?accion=crear" class="btn-nuevo">
                Agregar Producto
            </a>

            <a href="producto.php?accion=reporte" target="_blank" class="btn-nuevo">
                Generar reporte
            </a>
        </div>

        <input type="text" id="buscarProducto" placeholder="Buscar producto..."
            style="padding:8px 12px; border-radius:6px; border:1px solid #ccc; width:250px;">

    </div>

    <table class="admin-table" id="tablaProductos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>SKU</th>
                <th>Destacado</th>
                <th>Activo</th>
                <th>Subcategoría</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($productos)): ?>

                <?php foreach ($productos as $p): ?>

                    <tr>

                        <td>
                            <?php echo (int) ($p['id_producto'] ?? 0); ?>
                        </td>

                        <td>
                            <?php if (!empty($p['imagen_principal']) && $p['imagen_principal'] !== 'img/placeholder-producto.jpg'): ?>

                                <img src="../uploads/productos/<?php echo htmlspecialchars($p['imagen_principal']); ?>"
                                    alt="<?php echo htmlspecialchars($p['producto'] ?? 'Producto'); ?>"
                                    style="width:50px; height:50px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">

                            <?php else: ?>

                                <span style="color:#999;">Sin imagen</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($p['producto'] ?? 'Sin nombre'); ?>
                        </td>

                        <td>
                            $<?php echo number_format((float) ($p['precio'] ?? 0), 2); ?>
                        </td>

                        <td>
                            <?php if (($p['stock'] ?? 0) > 10): ?>
                                <span class="badge-si">
                                    <?php echo (int) ($p['stock'] ?? 0); ?>
                                </span>
                            <?php else: ?>
                                <span class="badge-no">
                                    <?php echo (int) ($p['stock'] ?? 0); ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($p['sku'] ?? 'Sin SKU'); ?>
                        </td>

                        <td>
                            <?php if (!empty($p['destacado'])): ?>
                                <span class="badge-si">Sí</span>
                            <?php else: ?>
                                <span class="badge-no">No</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($p['activo'])): ?>
                                <span class="badge-si">Sí</span>
                            <?php else: ?>
                                <span class="badge-no">No</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($p['subcategoria'] ?? 'Sin subcategoría'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($p['categoria'] ?? 'Sin categoría'); ?>
                        </td>

                        <td style="display:flex; gap:8px; flex-wrap:wrap;">
                            <a href="producto_detalle.php?id=<?php echo (int) $p['id_producto']; ?>" class="btn-ver">
                                Ver
                            </a>

                            <a href="producto.php?accion=actualizar&id=<?php echo (int) $p['id_producto']; ?>" class="btn-editar">
                                Editar
                            </a>

                            <a href="producto.php?accion=borrar&id=<?php echo (int) $p['id_producto']; ?>"
                                class="btn-eliminar btn-eliminar-producto"
                                onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                Eliminar
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="10">No hay productos registrados</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    const buscador = document.getElementById("buscarProducto");

    if (buscador) {
        buscador.addEventListener("keyup", function () {
            let filtro = buscador.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaProductos tbody tr");

            filas.forEach(function (fila) {
                let texto = fila.innerText.toLowerCase();
                fila.style.display = texto.includes(filtro) ? "" : "none";
            });
        });
    }
</script>