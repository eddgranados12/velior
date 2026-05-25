<div class="admin-container">

<h1>Actualizar Categoría</h1>

<form action="categoria.php?accion=actualizar&id=<?= $id ?>" method="POST">

    <label>Nombre de la Categoría</label>
    <input type="text" name="categoria" value="<?= $data['categoria'] ?? '' ?>" required>

    <label>Descripción</label>
    <input type="text" name="descripcion" value="<?= $data['descripcion'] ?? '' ?>">

    <label>Posición</label>
    <input type="number" name="posicion" value="<?= $data['posicion'] ?? '' ?>">

    <div class="checkbox-group">
        <label>
            <input type="checkbox" name="activo" value="1" 
            <?= isset($data['activo']) && $data['activo'] == 1 ? 'checked' : '' ?>>
            Categoría activa
        </label>
    </div>

    <button class="btn-guardar" type="submit" name="enviar">
        Guardar Cambios
    </button>

</form>

</div>
