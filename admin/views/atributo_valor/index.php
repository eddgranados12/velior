<div class="admin-table-container">

    <h1>Valores de Atributos</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; flex-wrap:wrap;">

        <a href="atributo_valor.php?accion=crear" class="btn-nuevo">
            Nuevo Valor
        </a>

        <input type="text" id="buscarValor" placeholder="Buscar valor..."
            style="padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:250px;">

    </div>

    <table class="admin-table" id="tablaValores">

        <thead>
            <tr>
                <th>ID</th>
                <th>Atributo</th>
                <th>Valor</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($valores)): ?>

                <?php foreach ($valores as $v): ?>

                    <tr>

                        <td><?php echo $v['id_atributo_valor']; ?></td>

                        <td><?php echo htmlspecialchars($v['nombre_atributo']); ?></td>

                        <td><?php echo htmlspecialchars($v['valor']); ?></td>

                        <td>
                            <?php if ($v['activo']): ?>
                                <span class="badge-si">Sí</span>
                            <?php else: ?>
                                <span class="badge-no">No</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="atributo_valor.php?accion=actualizar&id=<?php echo $v['id_atributo_valor']; ?>" class="btn-editar">
                                Editar
                            </a>

                            <a href="atributo_valor.php?accion=borrar&id=<?php echo $v['id_atributo_valor']; ?>"
                                class="btn-eliminar"
                                onclick="return confirm('¿Seguro que deseas eliminar este valor?');">
                                Eliminar
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No hay valores registrados</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    const buscador = document.getElementById("buscarValor");

    buscador.addEventListener("keyup", function () {
        let filtro = buscador.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaValores tbody tr");

        filas.forEach(function (fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });
    });
</script>