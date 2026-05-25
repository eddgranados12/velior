<div class="admin-container">

    <h1><?php echo ($accion == 'crear') ? 'Crear Producto' : 'Actualizar Producto'; ?></h1>

    <form
        action="producto.php?accion=<?php echo $accion; ?><?php echo ($accion == 'actualizar' && isset($data['id_producto'])) ? '&id=' . $data['id_producto'] : ''; ?>"
        method="POST"
        enctype="multipart/form-data">

        <?php if ($accion == 'actualizar' && isset($data['id_producto'])): ?>
            <input type="hidden" name="id_producto" value="<?php echo $data['id_producto']; ?>">
        <?php endif; ?>

        <!-- NOMBRE -->
        <label>Nombre del producto</label>
        <input
            type="text"
            name="producto"
            required
            value="<?php echo isset($data['producto']) ? htmlspecialchars($data['producto']) : ''; ?>">

        <!-- DESCRIPCIÓN -->
        <label>Descripción</label>
        <textarea
            name="descripcion"
            rows="5"><?php echo isset($data['descripcion']) ? htmlspecialchars($data['descripcion']) : ''; ?></textarea>

        <!-- PRECIO -->
        <label>Precio</label>
        <input
            type="number"
            step="0.01"
            min="0"
            name="precio"
            required
            value="<?php echo isset($data['precio']) ? $data['precio'] : ''; ?>">

        <!-- STOCK -->
        <label>Stock</label>
        <input
            type="number"
            name="stock"
            min="0"
            value="<?php echo isset($data['stock']) ? $data['stock'] : '0'; ?>">

        <!-- SKU -->
        <label>SKU</label>
        <input
            type="text"
            name="sku"
            value="<?php echo isset($data['sku']) ? htmlspecialchars($data['sku']) : ''; ?>">

        <!-- CATEGORÍA -->
        <label>Categoría principal</label>
        <select name="id_categoria_principal" required>
            <option value="">-- Selecciona una categoría --</option>

            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <option
                        value="<?php echo $cat['id_categoria']; ?>"
                        <?php
                        if (
                            isset($data['id_categoria_principal']) &&
                            $data['id_categoria_principal'] == $cat['id_categoria']
                        ) echo "selected";
                        ?>>
                        <?php echo htmlspecialchars($cat['categoria']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <!-- SUBCATEGORÍA -->
        <label>Subcategoría principal</label>
        <select name="id_subcategoria_principal">
            <option value="">-- Selecciona una subcategoría --</option>

            <?php if (!empty($subcategorias)): ?>
                <?php foreach ($subcategorias as $sub): ?>
                    <option
                        value="<?php echo $sub['id_subcategoria']; ?>"
                        <?php
                        if (
                            isset($data['id_subcategoria_principal']) &&
                            $data['id_subcategoria_principal'] == $sub['id_subcategoria']
                        ) echo "selected";
                        ?>>
                        <?php echo htmlspecialchars($sub['nombre_subcategoria']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <hr style="margin: 30px 0;">

        <h3 style="margin-bottom: 15px;">Imágenes del producto</h3>
        <p style="margin-bottom: 20px; color: #666; font-size: 14px;">
            Puedes subir hasta 3 imágenes. La primera será la principal.
        </p>

        <!-- IMAGEN PRINCIPAL -->
        <label>Imagen principal del producto</label>
        <input type="file" name="imagen_url" accept="image/*">

        <!-- IMAGEN SECUNDARIA 1 -->
        <label>Imagen secundaria 1</label>
        <input type="file" name="imagen_url2" accept="image/*">

        <!-- IMAGEN SECUNDARIA 2 -->
        <label>Imagen secundaria 2</label>
        <input type="file" name="imagen_url3" accept="image/*">

        <!-- IMÁGENES ACTUALES -->
        <?php if ($accion == 'actualizar' && !empty($data['imagenes'])): ?>
            <div style="margin: 25px 0;">
                <p style="margin-bottom: 12px;"><strong>Imágenes actuales:</strong></p>

                <div style="display:flex; gap:18px; flex-wrap:wrap;">
                    <?php foreach ($data['imagenes'] as $img): ?>
                        <div style="text-align:center; width:140px;">
                            <img
                                src="/velior/uploads/productos/<?php echo htmlspecialchars($img['imagen_url']); ?>"
                                alt="Imagen producto"
                                width="130"
                                height="130"
                                style="border-radius:12px; object-fit:cover; border:1px solid #ddd; padding:4px; background:#fff;">

                            <div style="margin-top:8px;">
                                <?php if (!empty($img['principal'])): ?>
                                    <span style="display:inline-block; background:#198754; color:white; padding:4px 10px; border-radius:20px; font-size:12px;">
                                        Principal
                                    </span>
                                <?php else: ?>
                                    <span style="display:inline-block; background:#6c757d; color:white; padding:4px 10px; border-radius:20px; font-size:12px;">
                                        Secundaria
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <hr style="margin: 30px 0;">

        <!-- DESTACADO -->
        <div class="checkbox-group">
            <label>
                <input
                    type="checkbox"
                    name="destacado"
                    value="1"
                    <?php if (!empty($data['destacado'])) echo "checked"; ?>>
                Producto destacado
            </label>
        </div>

        <!-- ACTIVO -->
        <div class="checkbox-group">
            <label>
                <input
                    type="checkbox"
                    name="activo"
                    value="1"
                    <?php if (!isset($data['activo']) || $data['activo'] == 1) echo "checked"; ?>>
                Producto activo
            </label>
        </div>

        <div class="admin-buttons">
            <button type="submit" class="btn-guardar">
                <?php echo ($accion == 'crear') ? 'Guardar Producto' : 'Actualizar Producto'; ?>
            </button>

            <a href="producto.php" class="btn-cancelar">
                Cancelar
            </a>
        </div>

    </form>

</div>