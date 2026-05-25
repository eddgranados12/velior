<div class="admin-table-container">

    <?php if (!isset($pedido) || empty($pedido)): ?>

        <div class="stock-filters-card">
            <h2>Pedido no encontrado</h2>
            <p>No fue posible cargar la información del pedido.</p>

            <div class="stock-filter-actions">
                <a href="pedido.php?accion=leer" class="btn-filter-secondary">Volver</a>
            </div>
        </div>

    <?php else: ?>

        <div class="admin-page-header">
            <div>
                <p class="admin-section-label">Pedidos</p>
                <h1>Detalle del Pedido #<?php echo $pedido['id_pedido']; ?></h1>
                <p class="admin-page-description">
                    Consulta la información general, productos y estado del pedido.
                </p>
            </div>
        </div>

        <div class="dashboard-grid stock-summary-grid">

            <div class="dashboard-card">
                <span class="dashboard-card-label">Cliente</span>
                <strong style="font-size:1.1rem;">
                    <?php
                    echo trim(
                        ($pedido['nombre'] ?? '') . ' ' .
                        ($pedido['primer_apellido'] ?? '') . ' ' .
                        ($pedido['segundo_apellido'] ?? '')
                    );
                    ?>
                </strong>
            </div>

            <div class="dashboard-card">
                <span class="dashboard-card-label">Correo</span>
                <strong style="font-size:1rem;">
                    <?php echo $pedido['correo'] ?? 'Sin correo'; ?>
                </strong>
            </div>

            <div class="dashboard-card">
                <span class="dashboard-card-label">Total</span>
                <strong>
                    $<?php echo number_format((float) ($pedido['total'] ?? 0), 2); ?>
                </strong>
            </div>

            <div class="dashboard-card">
                <span class="dashboard-card-label">Fecha</span>
                <strong style="font-size:1rem;">
                    <?php echo $pedido['fecha_pedido'] ?? 'Sin fecha'; ?>
                </strong>
            </div>

        </div>

        <div class="stock-filters-card">
            <h3 style="margin-top:0;">Información de envío</h3>

            <p><strong>Dirección:</strong> <?php echo $pedido['direccion_envio'] ?? 'Sin dirección'; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $pedido['telefono_contacto'] ?? 'Sin teléfono'; ?></p>
            <p><strong>Notas:</strong> <?php echo !empty($pedido['notas']) ? $pedido['notas'] : 'Sin notas'; ?></p>
            <p><strong>Método de pago:</strong> <?php echo $pedido['metodo_pago'] ?? 'No definido'; ?></p>
        </div>

        <div class="admin-table-card" style="margin-bottom: 30px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($detalles)): ?>

                        <?php foreach ($detalles as $d): ?>

                            <tr>
                                <td><?php echo $d['nombre_producto']; ?></td>
                                <td><?php echo $d['cantidad']; ?></td>
                                <td>$<?php echo number_format((float) $d['precio_unitario'], 2); ?></td>
                                <td>$<?php echo number_format((float) $d['cantidad'] * (float) $d['precio_unitario'], 2); ?></td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" class="text-center">Este pedido no tiene productos registrados</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="stock-filters-card">
            <h3 style="margin-top:0;">Actualizar estado</h3>

            <form action="pedido.php?accion=actualizar_estado&id=<?php echo $pedido['id_pedido']; ?>" method="POST"
                class="stock-filters-form">

                <div class="stock-filter-group">
                    <label>Estado del pedido</label>
                    <select name="estado">
                        <option value="pendiente" <?php echo (($pedido['estado'] ?? '') == 'pendiente') ? 'selected' : ''; ?>>
                            Pendiente</option>
                        <option value="pagado" <?php echo (($pedido['estado'] ?? '') == 'pagado') ? 'selected' : ''; ?>>Pagado
                        </option>
                        <option value="enviado" <?php echo (($pedido['estado'] ?? '') == 'enviado') ? 'selected' : ''; ?>>
                            Enviado</option>
                        <option value="entregado" <?php echo (($pedido['estado'] ?? '') == 'entregado') ? 'selected' : ''; ?>>
                            Entregado</option>
                        <option value="cancelado" <?php echo (($pedido['estado'] ?? '') == 'cancelado') ? 'selected' : ''; ?>>
                            Cancelado</option>
                    </select>
                </div>

                <div class="stock-filter-group">
                    <label>Estado del pago</label>
                    <select name="estado_pago">
                        <option value="pendiente" <?php echo (($pedido['estado_pago'] ?? '') == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="aprobado" <?php echo (($pedido['estado_pago'] ?? '') == 'aprobado') ? 'selected' : ''; ?>>Aprobado</option>
                        <option value="rechazado" <?php echo (($pedido['estado_pago'] ?? '') == 'rechazado') ? 'selected' : ''; ?>>Rechazado</option>
                        <option value="reembolzado" <?php echo (($pedido['estado_pago'] ?? '') == 'reembolzado') ? 'selected' : ''; ?>>Reembolsado</option>
                        <option value="en_proceso" <?php echo (($pedido['estado_pago'] ?? '') == 'en_proceso') ? 'selected' : ''; ?>>En proceso</option>
                    </select>
                </div>

                <div class="stock-filter-actions">
                    <button type="submit" class="btn-filter-primary">Guardar cambios</button>
                    <a href="pedido.php?accion=leer" class="btn-filter-secondary">Volver</a>
                </div>

            </form>
        </div>

    <?php endif; ?>

</div>