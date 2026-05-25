<div class="admin-container">

    <h1>Asignar Atributos a Producto</h1>

    <form action="producto_atributo.php?accion=asignar" method="POST">

        <label>Selecciona el producto</label>
        <select name="id_producto" required>
            <option value="">-- Selecciona un producto --</option>

            <?php foreach ($productos as $producto): ?>
                <option value="<?php echo $producto['id_producto']; ?>"
                    <?php echo (!empty($id) && $id == $producto['id_producto']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($producto['producto']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <hr style="margin:25px 0;">

        <h3 style="margin-bottom:20px;">Selecciona los atributos disponibles para este producto</h3>

        <?php if (!empty($atributos)): ?>

            <?php foreach ($atributos as $atributo): ?>

                <div style="margin-bottom:30px; padding:20px; border:1px solid #ddd; border-radius:12px; background:#fafafa;">
                    <h4 style="margin-bottom:15px;">
                        <?php echo htmlspecialchars($atributo['nombre_atributo']); ?>
                    </h4>

                    <?php if (!empty($atributo['valores'])): ?>

                        <div style="display:flex; flex-wrap:wrap; gap:15px;">

                            <?php foreach ($atributo['valores'] as $valor): ?>
                                <label style="display:flex; align-items:center; gap:8px; background:white; padding:10px 14px; border-radius:10px; border:1px solid #ddd; cursor:pointer;">
                                    <input type="checkbox"
                                        name="atributos[<?php echo $atributo['id_atributo']; ?>][]"
                                        value="<?php echo $valor['id_atributo_valor']; ?>"
                                        <?php
                                        if (
                                            isset($atributosAsignados[$atributo['id_atributo']]) &&
                                            in_array($valor['id_atributo_valor'], $atributosAsignados[$atributo['id_atributo']])
                                        ) echo "checked";
                                        ?>>
                                    <?php echo htmlspecialchars($valor['valor']); ?>
                                </label>
                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <p style="color:#999;">Este atributo aún no tiene valores registrados.</p>

                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No hay atributos registrados todavía.</p>

        <?php endif; ?>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar">
                Guardar asignación
            </button>

            <a href="producto_atributo.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>