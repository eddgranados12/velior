<div class="admin-container">

    <h1>Nueva Categoría</h1>

    <form action="categoria.php?accion=crear" method="POST">

        <label>Nombre de la categoría</label>
        <input type="text" name="categoria" required>

        <label>Descripción</label>
        <input type="text" name="descripcion">

        <label>Posición</label>
        <input type="number" name="posicion">

        <div class="checkbox-group">
            <label>
                <input type="checkbox" name="activo" value="1">
                Categoría activa
            </label>
        </div>

        <button class="btn-guardar" type="submit" name="enviar">
            Guardar Categoría
        </button>

    </form>

</div>