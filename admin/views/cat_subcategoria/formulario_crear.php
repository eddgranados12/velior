<div class="admin-container">

    <h1>Asignar Subcategoría a Categoría</h1>

    <form action="cat_subcategoria.php?accion=crear" method="POST">

        <label for="id_categoria">Categoría</label>
        <select name="id_categoria" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id_categoria']; ?>">
                    <?php echo $categoria['categoria']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_subcategoria">Subcategoría</label>
        <select name="id_subcategoria" required>
            <option value="">Seleccione una subcategoría</option>
            <?php foreach ($subcategorias as $subcategoria): ?>
                <option value="<?php echo $subcategoria['id_subcategoria']; ?>">
                    <?php echo $subcategoria['nombre_subcategoria']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="posicion">Posición</label>
        <input type="number" name="posicion" value="0">

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar">
                Guardar Relación
            </button>

            <a href="cat_subcategoria.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>