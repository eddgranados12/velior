<div class="admin-table-container">

    <h1>Administración de Pedidos</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <input type="text" id="buscarPedido" placeholder="Buscar pedido..."
            style="padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:250px;">
    </div>

    <table class="admin-table" id="tablaPedidos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Método de pago</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado pedido</th>
                <th>Estado pago</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($pedidos)): ?>

                <?php foreach ($pedidos as $p): ?>

                    <tr>
                        <td><?php echo $p['id_pedido']; ?></td>

                        <td>
                            <?php
                            echo $p['nombre'] . ' ' .
                                $p['primer_apellido'] . ' ' .
                                $p['segundo_apellido'];
                            ?>
                        </td>

                        <td><?php echo $p['metodo_pago'] ?? 'No especificado'; ?></td>
                        <td><?php echo $p['fecha_pedido']; ?></td>
                        <td>$<?php echo number_format($p['total'], 2); ?></td>

                        <td>
                            <span class="badge-si"><?php echo ucfirst($p['estado']); ?></span>
                        </td>

                        <td>
                            <span class="badge-no"><?php echo ucfirst($p['estado_pago']); ?></span>
                        </td>

                        <td><?php echo $p['telefono_contacto'] ?: 'Sin teléfono'; ?></td>

                        <td>
                            <a href="pedido.php?accion=ver&id=<?php echo $p['id_pedido']; ?>" class="btn-editar">
                                Ver
                            </a>

                            <a href="pedido.php?accion=borrar&id=<?php echo $p['id_pedido']; ?>"
                                class="btn-eliminar">
                                Eliminar
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9">No hay pedidos registrados</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    const buscador = document.getElementById("buscarPedido");

    buscador.addEventListener("keyup", function () {
        let filtro = buscador.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaPedidos tbody tr");

        filas.forEach(function (fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });
    });
</script>