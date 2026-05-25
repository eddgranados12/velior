<div class="admin-container">

<h1>Actualizar Relación Categoría - Subcategoría</h1>

<form action="cat_subcategoria.php?accion=actualizar&id_categoria=<?php echo $data['id_categoria']; ?>&id_subcategoria=<?php echo $data['id_subcategoria']; ?>" method="POST">

    <label>Categoría</label>
    <select disabled>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id_categoria']; ?>"
                <?php echo ($categoria['id_categoria'] == $data['id_categoria']) ? 'selected' : ''; ?>>
                <?php echo $categoria['categoria']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Subcategoría</label>
    <select disabled>
        <?php foreach ($subcategorias as $subcategoria): ?>
            <option value="<?php echo $subcategoria['id_subcategoria']; ?>"
                <?php echo ($subcategoria['id_subcategoria'] == $data['id_subcategoria']) ? 'selected' : ''; ?>>
                <?php echo $subcategoria['nombre_subcategoria']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Posición</label>
    <input type="number" name="posicion" value="<?php echo $data['posicion']; ?>" required>

    <div class="admin-buttons">

        <button type="submit" class="btn-guardar">
            Actualizar
        </button>

        <a href="cat_subcategoria.php" class="btn-cancelar">
            Cancelar
        </a>

    </div>

</form>

</div>