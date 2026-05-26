<?php
require_once(__DIR__ . "/../../admin/models/Favorito.php");

$idUsuario = $_SESSION['id_usuario'] ?? null;
$favoritoModel = new Favorito();
$favoritos = $favoritoModel->obtenerPorUsuario($idUsuario);
?>

<h2>Favoritos</h2>
<p class="sub">Tus productos guardados aparecerán aquí.</p>

<?php if (empty($favoritos)): ?>
    <div style="padding:30px; background:#f8f8f8; border-radius:18px;">
        <h4 style="margin-bottom:10px;">Sin favoritos aún</h4>
        <p style="color:#666; margin:0;">
            Cuando actives el sistema de favoritos, aquí verás tus piezas guardadas.
        </p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 20px 0;">
        <?php foreach ($favoritos as $producto): ?>
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; text-align: center; transition: all 0.3s;">
                <a href="/velior/detalle_producto.php?id=<?php echo $producto['id_producto']; ?>" style="text-decoration: none; color: inherit;">
                    <?php if ($producto['imagen_url']): ?>
                        <img src="/velior/<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                            Sin imagen
                        </div>
                    <?php endif; ?>
                    <div style="padding: 15px;">
                        <h5 style="margin: 0 0 10px 0; font-size: 14px; color: #333;">
                            <?php echo htmlspecialchars(substr($producto['nombre'], 0, 50)); ?>
                        </h5>
                        <p style="margin: 10px 0; color: #666; font-size: 12px;">
                            Stock: <?php echo $producto['stock']; ?>
                        </p>
                        <p style="margin: 0; font-size: 16px; font-weight: bold; color: #e74c3c;">
                            $<?php echo number_format($producto['precio'], 2); ?>
                        </p>
                    </div>
                </a>
                <button onclick="eliminarDelFavorito(<?php echo $producto['id_producto']; ?>)" 
                        style="width: 100%; padding: 10px; border: none; background: #f8f8f8; color: #666; cursor: pointer; transition: all 0.3s; border-top: 1px solid #ddd; font-size: 12px;">
                    Eliminar de favoritos
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function eliminarDelFavorito(idProducto) {
            fetch('/velior/admin/favorito.php?accion=eliminar', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id_producto=' + idProducto
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    location.reload();
                }
            });
        }
    </script>
<?php endif; ?>