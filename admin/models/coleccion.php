<?php
require_once(__DIR__ . "/../sistema.class.php");

class Coleccion extends Sistema
{
    /* =========================================================
       OBTENER COLECCIONES ACTIVAS PARA HOME
    ========================================================= */
    public function getColeccionesActivas()
    {
        $this->conectar();

        $sql = "SELECT 
                    c.*,
                    COALESCE(ci.imagen_url, 'img/placeholder-coleccion.jpg') AS imagen_principal
                FROM coleccion c
                LEFT JOIN coleccion_imagen ci 
                    ON c.id_coleccion = ci.id_coleccion
                    AND ci.principal = 1
                    AND ci.activo = 1
                WHERE c.activo = 1 
                AND (c.fecha_inicio IS NULL OR c.fecha_inicio <= CURDATE())
                AND (c.fecha_fin IS NULL OR c.fecha_fin >= CURDATE())
                ORDER BY c.destacado DESC, c.fecha_creacion DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        $colecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($colecciones as &$coleccion) {
            $coleccion['imagenes'] = $this->obtenerImagenes($coleccion['id_coleccion']);
        }

        return $colecciones;
    }

    /* =========================================================
       OBTENER PRODUCTOS DE UNA COLECCIÓN
    ========================================================= */
    public function getProductosByColeccion($id_coleccion)
    {
        $this->conectar();

        $sql = "SELECT 
                    p.*, 
                    cp.posicion,
                    COALESCE(pi.imagen_url, 'img/placeholder-producto.jpg') AS imagen_principal
                FROM producto p
                INNER JOIN coleccion_producto cp 
                    ON p.id_producto = cp.id_producto
                LEFT JOIN producto_imagen pi
                    ON p.id_producto = pi.id_producto
                    AND pi.principal = 1
                    AND pi.activo = 1
                WHERE cp.id_coleccion = :id_coleccion 
                AND p.activo = 1
                ORDER BY cp.posicion ASC, p.fecha_creacion DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================================
       OBTENER COLECCIONES CON PRODUCTOS
    ========================================================= */
    public function getColeccionesConProductos()
    {
        $colecciones = $this->getColeccionesActivas();

        foreach ($colecciones as &$coleccion) {
            $coleccion['productos'] = $this->getProductosByColeccion($coleccion['id_coleccion']);
        }

        return $colecciones;
    }

    /* =========================================================
       LISTAR TODAS LAS COLECCIONES (ADMIN)
    ========================================================= */
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT 
                    c.*,
                    COALESCE(ci.imagen_url, 'img/placeholder-coleccion.jpg') AS imagen_principal
                FROM coleccion c
                LEFT JOIN coleccion_imagen ci 
                    ON c.id_coleccion = ci.id_coleccion
                    AND ci.principal = 1
                    AND ci.activo = 1
                ORDER BY c.fecha_creacion DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================================
       LEER UNA COLECCIÓN
    ========================================================= */
    public function leerUno($id_coleccion)
    {
        $this->conectar();

        $sql = "SELECT * FROM coleccion WHERE id_coleccion = :id_coleccion";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
        $stmt->execute();

        $coleccion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coleccion) {
            $coleccion['imagenes'] = $this->obtenerImagenes($id_coleccion);
        }

        return $coleccion;
    }

    /* =========================================================
       CREAR COLECCIÓN
    ========================================================= */
    public function crear($data)
    {
        $this->conectar();

        $sql = "INSERT INTO coleccion 
                (coleccion, fecha_inicio, fecha_fin, destacado, activo) 
                VALUES 
                (:coleccion, :fecha_inicio, :fecha_fin, :destacado, :activo)";

        $stmt = $this->db()->prepare($sql);

        $fecha_inicio = !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null;
        $fecha_fin = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;

        $stmt->bindParam(":coleccion", $data['coleccion'], PDO::PARAM_STR);
        $stmt->bindValue(":fecha_inicio", $fecha_inicio, $fecha_inicio === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":fecha_fin", $fecha_fin, $fecha_fin === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":destacado", $data['destacado'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);

        $stmt->execute();

        return $this->db()->lastInsertId();
    }

    /* =========================================================
       ACTUALIZAR COLECCIÓN
    ========================================================= */
    public function actualizar($id_coleccion, $data)
    {
        $this->conectar();

        $sql = "UPDATE coleccion SET 
                    coleccion = :coleccion,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    destacado = :destacado,
                    activo = :activo
                WHERE id_coleccion = :id_coleccion";

        $stmt = $this->db()->prepare($sql);

        $fecha_inicio = !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null;
        $fecha_fin = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;

        $stmt->bindParam(":coleccion", $data['coleccion'], PDO::PARAM_STR);
        $stmt->bindValue(":fecha_inicio", $fecha_inicio, $fecha_inicio === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":fecha_fin", $fecha_fin, $fecha_fin === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":destacado", $data['destacado'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================================
       BORRAR COLECCIÓN
    ========================================================= */
    public function borrar($id_coleccion)
    {
        $this->conectar();

        $sql = "DELETE FROM coleccion WHERE id_coleccion = :id_coleccion";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================================
       GUARDAR IMAGEN DE COLECCIÓN
    ========================================================= */
    public function guardarImagen($id_coleccion, $imagen_url, $principal = 0, $posicion = 0)
    {
        $this->conectar();

        // Si esta imagen será principal, quitar principal anterior
        if ($principal == 1) {
            $sqlReset = "UPDATE coleccion_imagen 
                         SET principal = 0 
                         WHERE id_coleccion = :id_coleccion";
            $stmtReset = $this->db()->prepare($sqlReset);
            $stmtReset->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
            $stmtReset->execute();
        }

        $sql = "INSERT INTO coleccion_imagen 
                (id_coleccion, imagen_url, principal, posicion, activo)
                VALUES
                (:id_coleccion, :imagen_url, :principal, :posicion, 1)";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
        $stmt->bindParam(":imagen_url", $imagen_url, PDO::PARAM_STR);
        $stmt->bindParam(":principal", $principal, PDO::PARAM_INT);
        $stmt->bindParam(":posicion", $posicion, PDO::PARAM_INT);

        $stmt->execute();

        return $this->db()->lastInsertId();
    }

    /* =========================================================
       OBTENER IMÁGENES DE UNA COLECCIÓN
    ========================================================= */
    public function obtenerImagenes($id_coleccion)
    {
        $this->conectar();

        $sql = "SELECT * 
                FROM coleccion_imagen
                WHERE id_coleccion = :id_coleccion
                AND activo = 1
                ORDER BY principal DESC, posicion ASC, fecha_creacion ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_coleccion", $id_coleccion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>