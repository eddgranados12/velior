<div class="admin-container">

    <h1>
        <?php echo ($accion == 'crear') ? 'Crear Colección' : 'Actualizar Colección'; ?>
    </h1>

    <form
        action="coleccion.php?accion=<?php echo $accion; ?><?php echo ($accion == 'actualizar' && isset($data['id_coleccion'])) ? '&id=' . $data['id_coleccion'] : ''; ?>"
        method="POST" enctype="multipart/form-data">

        <?php if ($accion == 'actualizar' && isset($data['id_coleccion'])): ?>
            <input type="hidden" name="id_coleccion" value="<?php echo (int) $data['id_coleccion']; ?>">
        <?php endif; ?>

        <label>Nombre de la colección</label>
        <input type="text" name="coleccion" required
            value="<?php echo isset($data['coleccion']) ? htmlspecialchars($data['coleccion']) : ''; ?>">

        <label>Fecha Inicio</label>
        <input type="date" name="fecha_inicio"
            value="<?php echo isset($data['fecha_inicio']) ? htmlspecialchars($data['fecha_inicio']) : ''; ?>">

        <label>Fecha Fin</label>
        <input type="date" name="fecha_fin"
            value="<?php echo isset($data['fecha_fin']) ? htmlspecialchars($data['fecha_fin']) : ''; ?>">

        <label>Imagen principal de la colección</label>
        <input type="file" name="imagen_url" accept="image/*">

        <?php if ($accion == 'actualizar' && !empty($data['imagenes'])): ?>
            <?php
            $imagenPrincipal = null;
            foreach ($data['imagenes'] as $img) {
                if (!empty($img['principal'])) {
                    $imagenPrincipal = $img['imagen_url'];
                    break;
                }
            }
            ?>
            <?php if (!empty($imagenPrincipal)): ?>
                <div style="margin: 10px 0 20px 0;">
                    <p style="margin-bottom:8px;">Imagen principal actual:</p>
                    <img src="/velior/uploads/colecciones/<?php echo htmlspecialchars($imagenPrincipal); ?>"
                        alt="Imagen principal actual" width="140"
                        style="border-radius:10px; object-fit:cover; border:1px solid #ddd; padding:4px;">
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <label>Imagen secundaria 1</label>
        <input type="file" name="imagen_url2" accept="image/*">

        <label>Imagen secundaria 2</label>
        <input type="file" name="imagen_url3" accept="image/*">

        <?php if ($accion == 'actualizar' && !empty($data['imagenes'])): ?>
            <div style="margin: 20px 0;">
                <p style="margin-bottom:10px;"><strong>Galería actual:</strong></p>

                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <?php foreach ($data['imagenes'] as $img): ?>
                        <div style="text-align:center;">
                            <img src="/velior/uploads/colecciones/<?php echo htmlspecialchars($img['imagen_url']); ?>"
                                alt="Imagen colección" width="110" height="110"
                                style="object-fit:cover; border-radius:10px; border:1px solid #ddd; padding:4px; display:block; margin-bottom:6px;">

                            <?php if (!empty($img['principal'])): ?>
                                <span style="font-size:12px; background:#198754; color:white; padding:4px 8px; border-radius:20px;">
                                    Principal
                                </span>
                            <?php else: ?>
                                <span style="font-size:12px; background:#6c757d; color:white; padding:4px 8px; border-radius:20px;">
                                    Secundaria
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="checkbox-group">

            <label>
                <input type="checkbox" name="destacado" value="1" <?php if (!empty($data['destacado']))
                    echo "checked"; ?>>
                Colección destacada
            </label>

        </div>

        <div class="checkbox-group">

            <label>
                <input type="checkbox" name="activo" value="1" <?php
                if (!isset($data['activo']) || $data['activo'] == 1)
                    echo "checked";
                ?>>
                Colección activa
            </label>

        </div>

        <div class="admin-buttons">

            <button type="submit" class="btn-guardar">
                <?php echo ($accion == 'crear') ? 'Guardar Colección' : 'Actualizar Colección'; ?>
            </button>

            <a href="coleccion.php" class="btn-cancelar">
                Cancelar
            </a>

        </div>

    </form>

</div>