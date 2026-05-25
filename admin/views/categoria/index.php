<div class="admin-table-container">

    <h1>Categorías</h1>

    <a href="categoria.php?accion=crear" class="btn-nuevo">
        Nueva Categoría
    </a>

    <table class="admin-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Posición</th>
                <th>Activo</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($categorias as $categoria): ?>

                <tr>

                    <td>
                        <?php echo $categoria['id_categoria'] ?>
                    </td>

                    <td>
                        <?php echo $categoria['categoria'] ?>
                    </td>

                    <td>
                        <?= $categoria['descripcion'] ?>
                    </td>

                    <td>
                        <?= $categoria['posicion'] ?>
                    </td>

                    <td>
                        <?= $categoria['activo'] ? 'Sí' : 'No' ?>
                    </td>

                    <td>

                        <a href="categoria.php?accion=actualizar&id=<?php echo $categoria['id_categoria']; ?>"
                            class="btn-editar">
                            Editar
                        </a>

                        <a href="categoria.php?accion=borrar&id=<?php echo $categoria['id_categoria']; ?>"
                            class="btn-eliminar">
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach ?>

        </tbody>

    </table>

</div>