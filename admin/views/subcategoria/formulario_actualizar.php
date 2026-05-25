<div class="admin-container">

    <h1>Actualizar Subcategoría</h1>

    <form action="subcategoria.php?accion=actualizar&id=<?= $id ?>" method="POST">

        <label>Nombre de la Subcategoría</label>
        <input type="text" name="nombre_subcategoria" value="<?= $data['nombre_subcategoria'] ?? '' ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion"><?= $data['descripcion'] ?? '' ?></textarea>

        <label>Posición</label>
        <input type="number" name="posicion" value="<?= $data['posicion'] ?? '' ?>">

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="activo" value="1" <?= isset($data['activo']) && $data['activo'] == 1 ? 'checked' : '' ?>>
                Subcategoría activa
            </label>
        </div>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar" name="enviar">
                Guardar Cambios
            </button>

            <a href="subcategoria.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>