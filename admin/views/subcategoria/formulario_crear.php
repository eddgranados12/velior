<div class="admin-container">

    <h1>Nueva Subcategoría</h1>

    <form action="subcategoria.php?accion=crear" method="POST">

        <label>Nombre de la subcategoría</label>
        <input type="text" name="nombre_subcategoria" required>

        <label>Descripción</label>
        <textarea name="descripcion"></textarea>

        <label>Posición</label>
        <input type="number" name="posicion">

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="activo" value="1">
                Subcategoría activa
            </label>
        </div>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar" name="enviar">
                Guardar Subcategoría
            </button>

            <a href="subcategoria.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>