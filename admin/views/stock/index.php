<div class="admin-table-wrapper">

    <div class="admin-page-header">
        <div>
            <p class="admin-section-label">Inventario</p>
            <h1>Control de Stock</h1>
            <p class="admin-page-description">
                Supervisa existencias, detecta productos agotados y administra el inventario disponible.
            </p>
        </div>
    </div>

    <div class="dashboard-grid stock-summary-grid">
        <div class="dashboard-card">
            <span class="dashboard-card-label">Total de productos</span>
            <strong><?= $resumen['total_productos']; ?></strong>
        </div>

        <div class="dashboard-card">
            <span class="dashboard-card-label">Disponibles</span>
            <strong><?= $resumen['disponibles']; ?></strong>
        </div>

        <div class="dashboard-card">
            <span class="dashboard-card-label">Agotados</span>
            <strong><?= $resumen['sin_stock']; ?></strong>
        </div>

        <div class="dashboard-card">
            <span class="dashboard-card-label">Stock bajo</span>
            <strong><?= $resumen['stock_bajo']; ?></strong>
        </div>

        <div class="dashboard-card">
            <span class="dashboard-card-label">Unidades totales</span>
            <strong><?= $resumen['unidades_totales'] ?: 0; ?></strong>
        </div>
    </div>

    <div class="stock-filters-card">
        <form method="GET" action="stock.php" class="stock-filters-form">
            <div class="stock-filter-group">
                <label for="busqueda">Buscar</label>
                <input type="text" name="busqueda" id="busqueda" placeholder="Nombre o SKU"
                    value="<?= htmlspecialchars($busqueda ?? ''); ?>">
            </div>

            <div class="stock-filter-group">
                <label for="filtro">Filtrar</label>
                <select name="filtro" id="filtro">
                    <option value="">Todos</option>
                    <option value="disponibles" <?= ($filtro ?? '') === 'disponibles' ? 'selected' : ''; ?>>Disponibles
                    </option>
                    <option value="agotados" <?= ($filtro ?? '') === 'agotados' ? 'selected' : ''; ?>>Agotados</option>
                    <option value="bajo" <?= ($filtro ?? '') === 'bajo' ? 'selected' : ''; ?>>Stock bajo</option>
                    <option value="activos" <?= ($filtro ?? '') === 'activos' ? 'selected' : ''; ?>>Activos</option>
                    <option value="inactivos" <?= ($filtro ?? '') === 'inactivos' ? 'selected' : ''; ?>>Inactivos</option>
                </select>
            </div>

            <div class="stock-filter-actions">
                <button type="submit" class="btn-filter-primary">Aplicar</button>
                <a href="stock.php" class="btn-filter-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="admin-table-card">
        <table class="admin-table stock-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Subcategoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Activo</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?= $p['id_producto']; ?></td>

                            <td>
                                <?php if (!empty($p['imagen_url'])): ?>
                                    <img src="/velior/uploads/productos/<?= htmlspecialchars($p['imagen_url']); ?>" alt="Producto"
                                        class="table-product-image-small" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">>
                                <?php else: ?>
                                    <span class="table-empty">Sin imagen</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="stock-product-cell">
                                    <strong><?= htmlspecialchars($p['producto']); ?></strong>
                                </div>
                            </td>

                            <td>
                                <?php if (!empty($p['sku'])): ?>
                                    <?= htmlspecialchars($p['sku']); ?>
                                <?php else: ?>
                                    <span class="table-empty">Sin SKU</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!empty($p['subcategoria'])): ?>
                                    <?= htmlspecialchars($p['subcategoria']); ?>
                                <?php else: ?>
                                    <span class="table-empty">Sin subcategoría</span>
                                <?php endif; ?>
                            </td>

                            <td>$<?= number_format($p['precio'], 2); ?></td>

                            <td>
                                <?php if ((int) $p['stock'] <= 0): ?>
                                    <span class="badge-no">Agotado</span>
                                <?php elseif ((int) $p['stock'] <= 5): ?>
                                    <span class="badge-warning"><?= (int) $p['stock']; ?> u.</span>
                                <?php else: ?>
                                    <span class="badge-si"><?= (int) $p['stock']; ?> u.</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($p['activo']): ?>
                                    <span class="badge-si">Sí</span>
                                <?php else: ?>
                                    <span class="badge-no">No</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($p['destacado']): ?>
                                    <span class="badge-gold">Sí</span>
                                <?php else: ?>
                                    <span class="badge-no">No</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a href="producto.php?accion=actualizar&id=<?= $p['id_producto']; ?>"
                                        class="btn-table-edit">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No se encontraron productos con esos filtros.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>