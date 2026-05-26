<?php
// ============================================================
//  MODELO: Favorito
//  Ubicación: admin/models/Favorito.php
// ============================================================

require_once(__DIR__ . "/../sistema.class.php");

class Favorito extends Sistema
{
    // ── Agregar a favoritos ──────────────────────────────────
    public function agregar(int $idUsuario, int $idProducto): array
    {
        $this->conectar();

        // Verificar que el producto existe y está activo
        $stmt = $this->db()->prepare("
            SELECT id_producto FROM producto
            WHERE id_producto = :id AND activo = 1 LIMIT 1
        ");
        $stmt->execute([':id' => $idProducto]);
        if (!$stmt->fetch()) {
            return ['ok' => false, 'mensaje' => 'Producto no disponible.'];
        }

        // Insertar ignorando si ya existe (UNIQUE constraint)
        $stmt = $this->db()->prepare("
            INSERT IGNORE INTO favorito (id_usuario, id_producto)
            VALUES (:id_usuario, :id_producto)
        ");
        $stmt->execute([':id_usuario' => $idUsuario, ':id_producto' => $idProducto]);

        return ['ok' => true, 'mensaje' => 'Agregado a favoritos.'];
    }

    // ── Eliminar de favoritos ────────────────────────────────
    public function eliminar(int $idUsuario, int $idProducto): array
    {
        $this->conectar();

        $stmt = $this->db()->prepare("
            DELETE FROM favorito
            WHERE id_usuario = :id_usuario AND id_producto = :id_producto
        ");
        $stmt->execute([':id_usuario' => $idUsuario, ':id_producto' => $idProducto]);

        return ['ok' => true, 'mensaje' => 'Eliminado de favoritos.'];
    }

    // ── Verificar si un producto es favorito ─────────────────
    public function esFavorito(int $idUsuario, int $idProducto): bool
    {
        $this->conectar();

        $stmt = $this->db()->prepare("
            SELECT id_favorito FROM favorito
            WHERE id_usuario = :id_usuario AND id_producto = :id_producto
            LIMIT 1
        ");
        $stmt->execute([':id_usuario' => $idUsuario, ':id_producto' => $idProducto]);
        return (bool) $stmt->fetch();
    }

    // ── Obtener todos los favoritos del usuario ───────────────
    public function obtenerPorUsuario(int $idUsuario): array
    {
        $this->conectar();

        $stmt = $this->db()->prepare("
            SELECT f.id_favorito, f.fecha_agregado,
                   p.id_producto, p.producto AS nombre, p.precio, p.stock,
                   pi.imagen_url
            FROM favorito f
            INNER JOIN producto p ON p.id_producto = f.id_producto AND p.activo = 1
            LEFT JOIN producto_imagen pi
                ON pi.id_producto = p.id_producto AND pi.principal = 1 AND pi.activo = 1
            WHERE f.id_usuario = :id_usuario
            ORDER BY f.fecha_agregado DESC
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Contar favoritos del usuario ─────────────────────────
    public function contar(int $idUsuario): int
    {
        $this->conectar();

        $stmt = $this->db()->prepare("
            SELECT COUNT(*) FROM favorito WHERE id_usuario = :id_usuario
        ");
        $stmt->execute([':id_usuario' => $idUsuario]);
        return (int) $stmt->fetchColumn();
    }
}