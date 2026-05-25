<div class="admin-table-container">

    <h1>Subcategorías</h1>

    <a href="subcategoria.php?accion=crear" class="btn-nuevo">
        Nueva Subcategoría
    </a>

    <br><br>

    <table class="admin-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Subcategoría</th>
                <th>Descripción</th>
                <th>Posición</th>
                <th>Activo</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($subcategorias)): ?>

                <?php foreach ($subcategorias as $subcategoria): ?>

                    <tr>

                        <td>
                            <?php echo $subcategoria['id_subcategoria']; ?>
                        </td>

                        <td>
                            <?php echo $subcategoria['nombre_subcategoria']; ?>
                        </td>

                        <td>
                            <?= $subcategoria['descripcion']; ?>
                        </td>

                        <td>
                            <?= $subcategoria['posicion']; ?>
                        </td>

                        <td>

                            <?php if ($subcategoria['activo']): ?>

                                <span class="badge-si">Sí</span>

                            <?php else: ?>

                                <span class="badge-no">No</span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="subcategoria.php?accion=actualizar&id=<?php echo $subcategoria['id_subcategoria']; ?>"
                                class="btn-editar">
                                Editar
                            </a>

                            <a href="subcategoria.php?accion=borrar&id=<?php echo $subcategoria['id_subcategoria']; ?>"
                                class="btn-eliminar btn-eliminar-subcategoria">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">
                        No hay subcategorías registradas.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>