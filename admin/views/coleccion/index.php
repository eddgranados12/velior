<div class="admin-table-container">

    <h1>Administración de Colecciones</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

        <a href="coleccion.php?accion=crear" class="btn-nuevo">
            Nueva Colección
        </a>

        <input type="text" id="buscarColeccion" placeholder="Buscar colección..."
            style="padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:250px;">

    </div>

    <table class="admin-table" id="tablaColecciones">

        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Colección</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Destacado</th>
                <th>Activo</th>
                <th>Fecha creación</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($colecciones)): ?>

                <?php foreach ($colecciones as $coleccion): ?>

                    <tr>

                        <td>
                            <?php echo (int) $coleccion['id_coleccion']; ?>
                        </td>

                        <td>
                            <?php if (!empty($coleccion['imagen_principal'])): ?>

                                <img
                                    src="/velior/uploads/colecciones/<?php echo htmlspecialchars($coleccion['imagen_principal']); ?>"
                                    alt="Imagen de colección"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #ddd;">

                            <?php else: ?>

                                <span style="color:#999;">Sin imagen</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($coleccion['coleccion']); ?>
                        </td>

                        <td>
                            <?php echo !empty($coleccion['fecha_inicio']) ? htmlspecialchars($coleccion['fecha_inicio']) : '—'; ?>
                        </td>

                        <td>
                            <?php echo !empty($coleccion['fecha_fin']) ? htmlspecialchars($coleccion['fecha_fin']) : '—'; ?>
                        </td>

                        <td>
                            <?php if (!empty($coleccion['destacado'])): ?>

                                <span class="badge-si">Sí</span>

                            <?php else: ?>

                                <span class="badge-no">No</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($coleccion['activo'])): ?>

                                <span class="badge-si">Sí</span>

                            <?php else: ?>

                                <span class="badge-no">No</span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo !empty($coleccion['fecha_creacion']) ? htmlspecialchars($coleccion['fecha_creacion']) : '—'; ?>
                        </td>

                        <td>

                            <a href="coleccion.php?accion=actualizar&id=<?php echo (int) $coleccion['id_coleccion']; ?>"
                                class="btn-editar">
                                Editar
                            </a>

                            <a href="coleccion.php?accion=borrar&id=<?php echo (int) $coleccion['id_coleccion']; ?>"
                                class="btn-eliminar btn-eliminar-coleccion">
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9">No hay colecciones registradas</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>

    const buscador = document.getElementById("buscarColeccion");

    buscador.addEventListener("keyup", function () {

        let filtro = buscador.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaColecciones tbody tr");

        filas.forEach(function (fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });

    });

</script>