<div class="admin-table-container">

    <h1>Asignación de Subcategorías</h1>

    <a href="cat_subcategoria.php?accion=crear" class="btn-nuevo">
        Nueva Relación
    </a>

    <table class="admin-table">

        <thead>
            <tr>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Posición</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($relaciones)): ?>
                <?php foreach ($relaciones as $relacion): ?>
                    <tr>

                        <td><?php echo $relacion['categoria']; ?></td>

                        <td><?php echo $relacion['nombre_subcategoria']; ?></td>

                        <td><?php echo $relacion['posicion']; ?></td>

                        <td>

                            <a href="cat_subcategoria.php?accion=actualizar&id_categoria=<?php echo $relacion['id_categoria']; ?>&id_subcategoria=<?php echo $relacion['id_subcategoria']; ?>"
                                class="btn-editar">
                                Editar
                            </a>

                            <a href="cat_subcategoria.php?accion=borrar&id_categoria=<?php echo $relacion['id_categoria']; ?>&id_subcategoria=<?php echo $relacion['id_subcategoria']; ?>"
                                class="btn-eliminar">
                                Eliminar
                            </a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>

                <tr>
                    <td colspan="4" style="text-align:center">
                        No hay relaciones registradas.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>