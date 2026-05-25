<div class="admin-container">

    <h1>
        <?php echo ($accion == 'crear') ? 'Crear Atributo' : 'Actualizar Atributo'; ?>
    </h1>

    <form
        action="atributo.php?accion=<?php echo $accion; ?><?php echo ($accion == 'actualizar' && isset($data['id_atributo'])) ? '&id=' . $data['id_atributo'] : ''; ?>"
        method="POST">

        <?php if ($accion == 'actualizar' && isset($data['id_atributo'])): ?>
            <input type="hidden" name="id_atributo" value="<?php echo $data['id_atributo']; ?>">
        <?php endif; ?>

        <label>Nombre del atributo</label>
        <input type="text" name="nombre_atributo" required
            value="<?php echo isset($data['nombre_atributo']) ? htmlspecialchars($data['nombre_atributo']) : ''; ?>"
            placeholder="Ej. Color, Talla, Material">

        <label>Tipo de dato</label>
        <select name="tipo_dato" required>
            <option value="texto" <?php echo (isset($data['tipo_dato']) && $data['tipo_dato'] == 'texto') ? 'selected' : ''; ?>>
                Texto
            </option>
            <option value="numero" <?php echo (isset($data['tipo_dato']) && $data['tipo_dato'] == 'numero') ? 'selected' : ''; ?>>
                Número entero
            </option>
            <option value="decimal" <?php echo (isset($data['tipo_dato']) && $data['tipo_dato'] == 'decimal') ? 'selected' : ''; ?>>
                Decimal
            </option>
            <option value="booleano" <?php echo (isset($data['tipo_dato']) && $data['tipo_dato'] == 'booleano') ? 'selected' : ''; ?>>
                Sí / No
            </option>
        </select>

        <div class="checkbox-group" style="margin-top: 15px;">
            <label>
                <input type="checkbox" name="filtrable" value="1"
                    <?php if (!isset($data['filtrable']) || !empty($data['filtrable'])) echo "checked"; ?>>
                Mostrar este atributo como filtro en la tienda
            </label>
        </div>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar">
                <?php echo ($accion == 'crear') ? 'Guardar Atributo' : 'Actualizar Atributo'; ?>
            </button>

            <a href="atributo.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>