<div class="admin-table-container">

    <h1>Asignación de Atributos por Producto</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; flex-wrap:wrap;">
        <a href="producto_atributo.php?accion=asignar" class="btn-nuevo">
            Asignar atributos a producto
        </a>

        <input type="text" id="buscarProductoAtributo" placeholder="Buscar producto..."
            style="padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:250px;">
    </div>

    <table class="admin-table" id="tablaProductoAtributo">

        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Producto</th>
                <th>Atributos asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($productosAtributos)): ?>

                <?php foreach ($productosAtributos as $item): ?>

                    <tr>
                        <td><?php echo htmlspecialchars($item['id_producto']); ?></td>

                        <td><?php echo htmlspecialchars($item['producto']); ?></td>

                        <td>
                            <?php if (!empty($item['atributos'])): ?>
                                <?php foreach ($item['atributos'] as $nombreAtributo => $valores): ?>
                                    <div style="margin-bottom:8px;">
                                        <strong><?php echo htmlspecialchars($nombreAtributo); ?>:</strong>
                                        <?php echo htmlspecialchars(implode(', ', $valores)); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span style="color:#999;">Sin atributos</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="producto_atributo.php?accion=asignar&id=<?php echo $item['id_producto']; ?>"
                                class="btn-editar">
                                Editar
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4">No hay atributos asignados a productos</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    const buscadorPA = document.getElementById("buscarProductoAtributo");

    buscadorPA.addEventListener("keyup", function () {
        let filtro = buscadorPA.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaProductoAtributo tbody tr");

        filas.forEach(function (fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });
    });
</script>