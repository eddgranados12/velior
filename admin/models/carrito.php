<?php
// ============================================================
//  MODELO: Carrito
// ============================================================

class Carrito
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ------------------------------------------------------------
    // Obtener o crear el carrito activo del usuario
    // ------------------------------------------------------------
    public function obtenerOCrearCarrito(int $idUsuario): int
    {
        $stmt = $this->db->prepare("
            SELECT id_carrito FROM carrito
            WHERE id_usuario = :id_usuario AND estado = 'activo'
            LIMIT 1
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        $carrito = $stmt->fetch();

        if ($carrito) {
            return (int) $carrito['id_carrito'];
        }

        // Crear nuevo carrito
        $stmt = $this->db->prepare("
            INSERT INTO carrito (id_usuario, estado) VALUES (:id_usuario, 'activo')
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        return (int) $this->db->lastInsertId();
    }

    // ------------------------------------------------------------
    // Agregar producto al carrito (o incrementar cantidad)
    // ------------------------------------------------------------
    public function agregarProducto(int $idUsuario, int $idProducto, int $cantidad = 1): array
    {
        // Verificar que el producto existe, está activo y tiene stock
        $stmt = $this->db->prepare("
            SELECT id_producto, producto, precio, stock
            FROM producto
            WHERE id_producto = :id AND activo = 1
            LIMIT 1
        ");
        $stmt->execute([':id' => $idProducto]);
        $producto = $stmt->fetch();

        if (!$producto) {
            return ['ok' => false, 'mensaje' => 'Producto no disponible.'];
        }

        if ($producto['stock'] < $cantidad) {
            return ['ok' => false, 'mensaje' => 'Stock insuficiente.'];
        }

        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);

        // Verificar si ya está en el carrito
        $stmt = $this->db->prepare("
            SELECT id_detalle_carrito, cantidad
            FROM detalle_carrito
            WHERE id_carrito = :id_carrito AND id_producto = :id_producto
            LIMIT 1
        ");
        $stmt->execute([':id_carrito' => $idCarrito, ':id_producto' => $idProducto]);
        $detalle = $stmt->fetch();

        if ($detalle) {
            $nuevaCantidad = $detalle['cantidad'] + $cantidad;
            if ($nuevaCantidad > $producto['stock']) {
                return ['ok' => false, 'mensaje' => 'No hay suficiente stock disponible.'];
            }
            $this->db->prepare("
                UPDATE detalle_carrito SET cantidad = :cantidad
                WHERE id_detalle_carrito = :id
            ")->execute([':cantidad' => $nuevaCantidad, ':id' => $detalle['id_detalle_carrito']]);
        } else {
            $this->db->prepare("
                INSERT INTO detalle_carrito (id_carrito, id_producto, cantidad, precio_unitario)
                VALUES (:id_carrito, :id_producto, :cantidad, :precio)
            ")->execute([
                        ':id_carrito' => $idCarrito,
                        ':id_producto' => $idProducto,
                        ':cantidad' => $cantidad,
                        ':precio' => $producto['precio'],
                    ]);
        }

        // Actualizar fecha del carrito
        $this->db->prepare("
            UPDATE carrito SET fecha_actualizacion = NOW() WHERE id_carrito = :id
        ")->execute([':id' => $idCarrito]);

        return ['ok' => true, 'mensaje' => 'Producto agregado al carrito.'];
    }

    // ------------------------------------------------------------
    // Actualizar cantidad de un producto en el carrito
    // ------------------------------------------------------------
    public function actualizarCantidad(int $idUsuario, int $idProducto, int $cantidad): array
    {
        if ($cantidad <= 0) {
            return $this->eliminarProducto($idUsuario, $idProducto);
        }

        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);

        // Verificar stock
        $stmt = $this->db->prepare("SELECT stock FROM producto WHERE id_producto = :id AND activo = 1");
        $stmt->execute([':id' => $idProducto]);
        $producto = $stmt->fetch();

        if (!$producto || $producto['stock'] < $cantidad) {
            return ['ok' => false, 'mensaje' => 'Stock insuficiente.'];
        }

        $this->db->prepare("
            UPDATE detalle_carrito SET cantidad = :cantidad
            WHERE id_carrito = :id_carrito AND id_producto = :id_producto
        ")->execute([
                    ':cantidad' => $cantidad,
                    ':id_carrito' => $idCarrito,
                    ':id_producto' => $idProducto,
                ]);

        return ['ok' => true, 'mensaje' => 'Cantidad actualizada.'];
    }

    // ------------------------------------------------------------
    // Eliminar un producto del carrito
    // ------------------------------------------------------------
    public function eliminarProducto(int $idUsuario, int $idProducto): array
    {
        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);

        $this->db->prepare("
            DELETE FROM detalle_carrito
            WHERE id_carrito = :id_carrito AND id_producto = :id_producto
        ")->execute([':id_carrito' => $idCarrito, ':id_producto' => $idProducto]);

        return ['ok' => true, 'mensaje' => 'Producto eliminado.'];
    }

    // ------------------------------------------------------------
    // Obtener items del carrito con info del producto
    // ------------------------------------------------------------
    public function obtenerItems(int $idUsuario): array
    {
        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);

        $stmt = $this->db->prepare("
            SELECT
                dc.id_detalle_carrito,
                dc.id_producto,
                dc.cantidad,
                dc.precio_unitario,
                p.producto AS nombre,
                p.stock,
                p.sku,
                (dc.cantidad * dc.precio_unitario) AS subtotal,
                pi.imagen_url
            FROM detalle_carrito dc
            INNER JOIN producto p ON p.id_producto = dc.id_producto
            LEFT JOIN producto_imagen pi
                ON pi.id_producto = dc.id_producto AND pi.principal = 1 AND pi.activo = 1
            WHERE dc.id_carrito = :id_carrito
              AND p.activo = 1
            ORDER BY dc.fecha_agregado ASC
        ");
        $stmt->execute([':id_carrito' => $idCarrito]);
        return $stmt->fetchAll();
    }

    // ------------------------------------------------------------
    // Obtener total del carrito
    // ------------------------------------------------------------
    public function obtenerTotal(int $idUsuario): float
    {
        $items = $this->obtenerItems($idUsuario);
        return array_reduce($items, fn($carry, $item) => $carry + $item['subtotal'], 0.0);
    }

    // ------------------------------------------------------------
    // Contar productos en el carrito (para el badge del nav)
    // ------------------------------------------------------------
    public function contarItems(int $idUsuario): int
    {
        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(cantidad), 0) AS total
            FROM detalle_carrito
            WHERE id_carrito = :id_carrito
        ");
        $stmt->execute([':id_carrito' => $idCarrito]);
        return (int) $stmt->fetchColumn();
    }

    // ------------------------------------------------------------
    // Vaciar el carrito
    // ------------------------------------------------------------
    public function vaciar(int $idUsuario): void
    {
        $idCarrito = $this->obtenerOCrearCarrito($idUsuario);
        $this->db->prepare("
            DELETE FROM detalle_carrito WHERE id_carrito = :id_carrito
        ")->execute([':id_carrito' => $idCarrito]);
    }

    // ------------------------------------------------------------
    // Marcar carrito como convertido (al crear pedido)
    // ------------------------------------------------------------
    public function marcarComoConvertido(int $idUsuario): void
    {
        $this->db->prepare("
            UPDATE carrito SET estado = 'convertido'
            WHERE id_usuario = :id_usuario AND estado = 'activo'
        ")->execute([':id_usuario' => $idUsuario]);
    }
}