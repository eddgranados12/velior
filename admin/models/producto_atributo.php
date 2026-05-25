<?php
require_once(__DIR__ . "/../sistema.class.php");

class ProductoAtributo extends Sistema
{
    /* =========================
       PRODUCTOS
    ========================== */
    public function getProductos()
    {
        $this->conectar();
        $sql = "SELECT id_producto, producto 
                FROM producto
                WHERE activo = 1
                ORDER BY producto ASC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       ATRIBUTOS CON SUS VALORES
    ========================== */
    public function getAtributosConValores()
    {
        $this->conectar();

        $sql = "SELECT 
                    a.id_atributo,
                    a.nombre_atributo,
                    a.tipo_dato,
                    av.id_atributo_valor,
                    av.valor
                FROM atributo a
                LEFT JOIN atributo_valor av 
                    ON a.id_atributo = av.id_atributo
                ORDER BY a.nombre_atributo ASC, av.valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $atributos = [];

        foreach ($rows as $row) {
            $id = $row['id_atributo'];

            if (!isset($atributos[$id])) {
                $atributos[$id] = [
                    'id_atributo' => $row['id_atributo'],
                    'nombre_atributo' => $row['nombre_atributo'],
                    'tipo_dato' => $row['tipo_dato'],
                    'valores' => []
                ];
            }

            if (!empty($row['id_atributo_valor'])) {
                $atributos[$id]['valores'][] = [
                    'id_atributo_valor' => $row['id_atributo_valor'],
                    'valor' => $row['valor']
                ];
            }
        }

        return $atributos;
    }

    /* =========================
       OBTENER ASIGNACIONES
    ========================== */
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT 
                    pa.id_producto_atributo,
                    p.id_producto,
                    p.producto,
                    a.id_atributo,
                    a.nombre_atributo,
                    av.id_atributo_valor,
                    av.valor
                FROM producto_atributo pa
                INNER JOIN producto p ON pa.id_producto = p.id_producto
                INNER JOIN atributo a ON pa.id_atributo = a.id_atributo
                INNER JOIN atributo_valor av ON pa.id_atributo_valor = av.id_atributo_valor
                ORDER BY p.producto ASC, a.nombre_atributo ASC, av.valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       OBTENER ATRIBUTOS DE UN PRODUCTO
    ========================== */
    public function getAtributosByProducto($id_producto)
    {
        $this->conectar();

        $sql = "SELECT 
                    pa.id_producto_atributo,
                    pa.id_producto,
                    pa.id_atributo,
                    pa.id_atributo_valor,
                    a.nombre_atributo,
                    av.valor
                FROM producto_atributo pa
                INNER JOIN atributo a ON pa.id_atributo = a.id_atributo
                INNER JOIN atributo_valor av ON pa.id_atributo_valor = av.id_atributo_valor
                WHERE pa.id_producto = :id_producto
                ORDER BY a.nombre_atributo ASC, av.valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
       GUARDAR ATRIBUTOS DE UN PRODUCTO
    ========================== */
    public function guardarAtributosProducto($id_producto, $atributosSeleccionados)
    {
        $this->conectar();
        $db = $this->db();

        try {
            $db->beginTransaction();

            // Eliminar asignaciones anteriores
            $sqlDelete = "DELETE FROM producto_atributo WHERE id_producto = :id_producto";
            $stmtDelete = $db->prepare($sqlDelete);
            $stmtDelete->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insertar nuevas asignaciones
            if (!empty($atributosSeleccionados) && is_array($atributosSeleccionados)) {
                $sqlInsert = "INSERT INTO producto_atributo 
                              (id_producto, id_atributo, id_atributo_valor)
                              VALUES
                              (:id_producto, :id_atributo, :id_atributo_valor)";
                $stmtInsert = $db->prepare($sqlInsert);

                foreach ($atributosSeleccionados as $id_atributo => $valores) {
                    if (!is_array($valores)) continue;

                    foreach ($valores as $id_atributo_valor) {
                        $stmtInsert->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                        $stmtInsert->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
                        $stmtInsert->bindParam(":id_atributo_valor", $id_atributo_valor, PDO::PARAM_INT);
                        $stmtInsert->execute();
                    }
                }
            }

            $db->commit();
            return true;

        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    /* =========================
       AGRUPAR ATRIBUTOS POR PRODUCTO
    ========================== */
    public function leerAgrupado()
    {
        $registros = $this->leer();
        $agrupado = [];

        foreach ($registros as $r) {
            $idProducto = $r['id_producto'];

            if (!isset($agrupado[$idProducto])) {
                $agrupado[$idProducto] = [
                    'id_producto' => $r['id_producto'],
                    'producto' => $r['producto'],
                    'atributos' => []
                ];
            }

            $atributo = $r['nombre_atributo'];

            if (!isset($agrupado[$idProducto]['atributos'][$atributo])) {
                $agrupado[$idProducto]['atributos'][$atributo] = [];
            }

            $agrupado[$idProducto]['atributos'][$atributo][] = $r['valor'];
        }

        return $agrupado;
    }
}
?>