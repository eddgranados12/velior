<div class="admin-container">

    <h1>
        <?php echo ($accion == 'crear') ? 'Crear Valor de Atributo' : 'Actualizar Valor de Atributo'; ?>
    </h1>

    <form
        action="atributo_valor.php?accion=<?php echo $accion; ?><?php echo ($accion == 'actualizar' && isset($data['id_atributo_valor'])) ? '&id=' . $data['id_atributo_valor'] : ''; ?>"
        method="POST">

        <?php if ($accion == 'actualizar' && isset($data['id_atributo_valor'])): ?>
            <input type="hidden" name="id_atributo_valor" value="<?php echo $data['id_atributo_valor']; ?>">
        <?php endif; ?>

        <label>Atributo</label>
        <select name="id_atributo" required>
            <option value="">-- Selecciona un atributo --</option>

            <?php if (!empty($atributos)): ?>
                <?php foreach ($atributos as $atributo): ?>
                    <option value="<?php echo $atributo['id_atributo']; ?>"
                        <?php
                        if (
                            isset($data['id_atributo']) &&
                            $data['id_atributo'] == $atributo['id_atributo']
                        ) echo "selected";
                        ?>>
                        <?php echo htmlspecialchars($atributo['nombre_atributo']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <label>Valor</label>
        <input type="text" name="valor" required
            value="<?php echo isset($data['valor']) ? htmlspecialchars($data['valor']) : ''; ?>"
            placeholder="Ej. Dorado, M, Unitalla, Plata 925">

        <div class="checkbox-group" style="margin-top: 15px;">
            <label>
                <input type="checkbox" name="activo" value="1"
                    <?php if (!isset($data['activo']) || !empty($data['activo'])) echo "checked"; ?>>
                Valor activo
            </label>
        </div>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar">
                <?php echo ($accion == 'crear') ? 'Guardar Valor' : 'Actualizar Valor'; ?>
            </button>

            <a href="atributo_valor.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>