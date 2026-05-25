<div class="admin-table-container">

    <h1>Catálogo de Atributos</h1>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; flex-wrap:wrap;">

        <a href="atributo.php?accion=crear" class="btn-nuevo">
            Nuevo Atributo
        </a>

        <input type="text" id="buscarAtributo" placeholder="Buscar atributo..."
            style="padding:8px 12px;border-radius:6px;border:1px solid #ccc;width:250px;">

    </div>

    <table class="admin-table" id="tablaAtributos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del atributo</th>
                <th>Tipo de dato</th>
                <th>Filtrable</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($atributos)): ?>

                <?php foreach ($atributos as $a): ?>

                    <tr>

                        <td><?php echo $a['id_atributo']; ?></td>

                        <td><?php echo htmlspecialchars($a['nombre_atributo']); ?></td>

                        <td>
                            <?php
                            $tipos = [
                                'texto' => 'Texto',
                                'numero' => 'Número',
                                'decimal' => 'Decimal',
                                'booleano' => 'Sí / No'
                            ];
                            echo $tipos[$a['tipo_dato']] ?? $a['tipo_dato'];
                            ?>
                        </td>

                        <td>
                            <?php if ($a['filtrable']): ?>
                                <span class="badge-si">Sí</span>
                            <?php else: ?>
                                <span class="badge-no">No</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="atributo.php?accion=actualizar&id=<?php echo $a['id_atributo']; ?>" class="btn-editar">
                                Editar
                            </a>

                            <a href="atributo.php?accion=borrar&id=<?php echo $a['id_atributo']; ?>"
                                class="btn-eliminar"
                                onclick="return confirm('¿Seguro que deseas eliminar este atributo?');">
                                Eliminar
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="5">No hay atributos registrados</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<script>
    const buscador = document.getElementById("buscarAtributo");

    buscador.addEventListener("keyup", function () {
        let filtro = buscador.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaAtributos tbody tr");

        filas.forEach(function (fila) {
            let texto = fila.innerText.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });
    });
</script>