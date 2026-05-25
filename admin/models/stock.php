<?php
require_once(__DIR__ . "/../sistema.class.php");

class Stock extends Sistema
{
    public function leerStock($busqueda = '', $filtro = '')
    {
        $this->conectar();

        $sql = "SELECT 
                    p.id_producto,
                    p.producto,
                    p.descripcion,
                    p.precio,
                    p.stock,
                    p.sku,
                    p.destacado,
                    p.activo,
                    p.fecha_creacion,
                    p.id_subcategoria_principal,
                    s.nombre_subcategoria AS subcategoria,
                    (
                        SELECT pi.imagen_url
                        FROM producto_imagen pi
                        WHERE pi.id_producto = p.id_producto
                        AND pi.principal = 1
                        AND pi.activo = 1
                        ORDER BY pi.posicion ASC, pi.id_imagen ASC
                        LIMIT 1
                    ) AS imagen_principal
                FROM producto p
                LEFT JOIN subcategoria s
                    ON p.id_subcategoria_principal = s.id_subcategoria
                WHERE 1=1 ";

        $params = [];

        if (!empty($busqueda)) {
            $sql .= " AND (p.producto LIKE :busqueda OR p.sku LIKE :busqueda) ";
            $params[':busqueda'] = "%" . $busqueda . "%";
        }

        if ($filtro === 'disponibles') {
            $sql .= " AND p.stock > 0 ";
        } elseif ($filtro === 'agotados') {
            $sql .= " AND p.stock <= 0 ";
        } elseif ($filtro === 'bajo') {
            $sql .= " AND p.stock > 0 AND p.stock <= 5 ";
        } elseif ($filtro === 'activos') {
            $sql .= " AND p.activo = 1 ";
        } elseif ($filtro === 'inactivos') {
            $sql .= " AND p.activo = 0 ";
        }

        $sql .= " ORDER BY p.stock ASC, p.producto ASC";

        $stmt = $this->db()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resumenStock()
    {
        $this->conectar();

        $resumen = [];

        $stmt = $this->db()->query("SELECT COUNT(*) FROM producto");
        $resumen['total_productos'] = $stmt->fetchColumn();

        $stmt = $this->db()->query("SELECT COUNT(*) FROM producto WHERE stock > 0");
        $resumen['disponibles'] = $stmt->fetchColumn();

        $stmt = $this->db()->query("SELECT COUNT(*) FROM producto WHERE stock <= 0");
        $resumen['sin_stock'] = $stmt->fetchColumn();

        $stmt = $this->db()->query("SELECT COUNT(*) FROM producto WHERE stock > 0 AND stock <= 5");
        $resumen['stock_bajo'] = $stmt->fetchColumn();

        $stmt = $this->db()->query("SELECT COALESCE(SUM(stock), 0) FROM producto");
        $resumen['unidades_totales'] = $stmt->fetchColumn();

        return $resumen;
    }
}
?>