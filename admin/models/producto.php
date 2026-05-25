<?php
require_once(__DIR__ . "/../sistema.class.php");

class Producto extends Sistema
{
    /* =========================================
       OBTENER PRODUCTOS PARA LOOKBOOK
    ========================================= */
    public function getProductosLookbook($limit = 5)
    {
        $this->conectar();

        $sql = "SELECT 
                    p.*,
                    s.nombre_subcategoria AS subcategoria,
                    c.categoria,
                    COALESCE(
                        (
                            SELECT pi.imagen_url
                            FROM producto_imagen pi
                            WHERE pi.id_producto = p.id_producto
                              AND pi.principal = 1
                              AND pi.activo = 1
                            ORDER BY pi.posicion ASC
                            LIMIT 1
                        ),
                        (
                            SELECT pi2.imagen_url
                            FROM producto_imagen pi2
                            WHERE pi2.id_producto = p.id_producto
                              AND pi2.activo = 1
                            ORDER BY pi2.posicion ASC
                            LIMIT 1
                        ),
                        'img/placeholder-producto.jpg'
                    ) AS imagen_principal
                FROM producto p
                LEFT JOIN subcategoria s 
                    ON p.id_subcategoria_principal = s.id_subcategoria
                LEFT JOIN categoria_subcategoria cs
                    ON s.id_subcategoria = cs.id_subcategoria
                LEFT JOIN categoria c
                    ON cs.id_categoria = c.id_categoria
                WHERE p.activo = 1
                ORDER BY p.destacado DESC, p.fecha_creacion DESC
                LIMIT :limit";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       PRODUCTOS ALEATORIOS
    ========================================= */
    public function getProductosAleatorios($limit = 5)
    {
        $this->conectar();

        $sql = "SELECT 
                    p.*,
                    s.nombre_subcategoria AS subcategoria,
                    c.categoria,
                    COALESCE(
                        (
                            SELECT pi.imagen_url
                            FROM producto_imagen pi
                            WHERE pi.id_producto = p.id_producto
                              AND pi.principal = 1
                              AND pi.activo = 1
                            ORDER BY pi.posicion ASC
                            LIMIT 1
                        ),
                        (
                            SELECT pi2.imagen_url
                            FROM producto_imagen pi2
                            WHERE pi2.id_producto = p.id_producto
                              AND pi2.activo = 1
                            ORDER BY pi2.posicion ASC
                            LIMIT 1
                        ),
                        'img/placeholder-producto.jpg'
                    ) AS imagen_principal
                FROM producto p
                LEFT JOIN subcategoria s 
                    ON p.id_subcategoria_principal = s.id_subcategoria
                LEFT JOIN categoria_subcategoria cs
                    ON s.id_subcategoria = cs.id_subcategoria
                LEFT JOIN categoria c
                    ON cs.id_categoria = c.id_categoria
                WHERE p.activo = 1
                ORDER BY RAND()
                LIMIT :limit";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       PRODUCTOS DESTACADOS
    ========================================= */
    public function getProductosDestacados($limit = 10)
    {
        $this->conectar();

        $sql = "SELECT 
                    p.*,
                    s.nombre_subcategoria AS subcategoria,
                    c.categoria,
                    COALESCE(
                        (
                            SELECT pi.imagen_url
                            FROM producto_imagen pi
                            WHERE pi.id_producto = p.id_producto
                              AND pi.principal = 1
                              AND pi.activo = 1
                            ORDER BY pi.posicion ASC
                            LIMIT 1
                        ),
                        (
                            SELECT pi2.imagen_url
                            FROM producto_imagen pi2
                            WHERE pi2.id_producto = p.id_producto
                              AND pi2.activo = 1
                            ORDER BY pi2.posicion ASC
                            LIMIT 1
                        ),
                        'img/placeholder-producto.jpg'
                    ) AS imagen_principal
                FROM producto p
                LEFT JOIN subcategoria s 
                    ON p.id_subcategoria_principal = s.id_subcategoria
                LEFT JOIN categoria_subcategoria cs
                    ON s.id_subcategoria = cs.id_subcategoria
                LEFT JOIN categoria c
                    ON cs.id_categoria = c.id_categoria
                WHERE p.activo = 1 
                  AND p.destacado = 1
                ORDER BY p.fecha_creacion DESC
                LIMIT :limit";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       LEER TODOS LOS PRODUCTOS (ADMIN)
    ========================================= */
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT 
                    p.*,
                    s.nombre_subcategoria AS subcategoria,
                    c.categoria,
                    COALESCE(
                        (
                            SELECT pi.imagen_url
                            FROM producto_imagen pi
                            WHERE pi.id_producto = p.id_producto
                              AND pi.principal = 1
                              AND pi.activo = 1
                            ORDER BY pi.posicion ASC
                            LIMIT 1
                        ),
                        (
                            SELECT pi2.imagen_url
                            FROM producto_imagen pi2
                            WHERE pi2.id_producto = p.id_producto
                              AND pi2.activo = 1
                            ORDER BY pi2.posicion ASC
                            LIMIT 1
                        ),
                        'img/placeholder-producto.jpg'
                    ) AS imagen_principal
                FROM producto p
                LEFT JOIN subcategoria s 
                    ON p.id_subcategoria_principal = s.id_subcategoria
                LEFT JOIN categoria_subcategoria cs
                    ON s.id_subcategoria = cs.id_subcategoria
                LEFT JOIN categoria c
                    ON cs.id_categoria = c.id_categoria
                ORDER BY p.id_producto DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       LEER UN PRODUCTO
    ========================================= */
    public function leerUno($id_producto)
    {
        $this->conectar();

        $sql = "SELECT * 
                FROM producto 
                WHERE id_producto = :id_producto";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $producto['imagenes'] = $this->obtenerImagenes($id_producto);
        }

        return $producto;
    }

    /* =========================================
       CATÁLOGO FRONTEND
       FILTRO POR CATEGORÍA Y SUBCATEGORÍA
       Ejemplo:
       - Dama
       - Caballero
       - Dama + Anillo
       - Caballero + Collar
    ========================================= */
    /* =========================================
   CATÁLOGO FRONTEND
   FILTRO POR CATEGORÍA Y SUBCATEGORÍA
========================================= */
    public function getProductosCatalogo($genero = null, $subcategoria = null)
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
                p.id_categoria_principal,
                p.id_subcategoria_principal,

                c.id_categoria,
                c.categoria,

                s.id_subcategoria,
                s.nombre_subcategoria,

                COALESCE(
                    (
                        SELECT pi.imagen_url
                        FROM producto_imagen pi
                        WHERE pi.id_producto = p.id_producto
                          AND pi.principal = 1
                          AND pi.activo = 1
                        ORDER BY pi.posicion ASC
                        LIMIT 1
                    ),
                    (
                        SELECT pi2.imagen_url
                        FROM producto_imagen pi2
                        WHERE pi2.id_producto = p.id_producto
                          AND pi2.activo = 1
                        ORDER BY pi2.posicion ASC
                        LIMIT 1
                    ),
                    'img/placeholder-producto.jpg'
                ) AS imagen_principal

            FROM producto p
            LEFT JOIN categoria c
                ON p.id_categoria_principal = c.id_categoria
            LEFT JOIN subcategoria s
                ON p.id_subcategoria_principal = s.id_subcategoria
            WHERE p.activo = 1";

        $params = [];

        // FILTRAR POR GÉNERO / CATEGORÍA
        if (!empty($genero) && strtolower($genero) !== 'todos') {
            $sql .= " AND LOWER(c.categoria) = :genero ";
            $params[':genero'] = strtolower(trim($genero));
        }

        // FILTRAR POR SUBCATEGORÍA
        if (!empty($subcategoria) && strtolower($subcategoria) !== 'todo' && strtolower($subcategoria) !== 'todos') {
            $sql .= " AND LOWER(s.nombre_subcategoria) = :subcategoria ";
            $params[':subcategoria'] = strtolower(trim($subcategoria));
        }

        $sql .= " ORDER BY p.destacado DESC, p.fecha_creacion DESC";

        $stmt = $this->db()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
   OBTENER ATRIBUTOS DE UN PRODUCTO
========================================= */
    public function obtenerAtributosProducto($id_producto)
    {
        $this->conectar();

        $sql = "SELECT 
                pa.id_producto_atributo,
                a.id_atributo,
                a.nombre_atributo,
                a.tipo_dato,
                av.id_atributo_valor,
                av.valor
            FROM producto_atributo pa
            INNER JOIN atributo a 
                ON pa.id_atributo = a.id_atributo
            INNER JOIN atributo_valor av 
                ON pa.id_atributo_valor = av.id_atributo_valor
            WHERE pa.id_producto = :id_producto
              AND av.activo = 1
            ORDER BY a.nombre_atributo ASC, av.valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar por atributo
        $atributosAgrupados = [];

        foreach ($resultados as $fila) {
            $nombre = $fila['nombre_atributo'];

            if (!isset($atributosAgrupados[$nombre])) {
                $atributosAgrupados[$nombre] = [
                    'id_atributo' => $fila['id_atributo'],
                    'nombre_atributo' => $fila['nombre_atributo'],
                    'tipo_dato' => $fila['tipo_dato'],
                    'valores' => []
                ];
            }

            $atributosAgrupados[$nombre]['valores'][] = [
                'id_atributo_valor' => $fila['id_atributo_valor'],
                'valor' => $fila['valor']
            ];
        }

        return $atributosAgrupados;
    }



    /* =========================================
       CREAR PRODUCTO
    ========================================= */
    public function crear($data)
    {
        $this->conectar();

        $sql = "INSERT INTO producto
                (producto, descripcion, precio, stock, sku, destacado, activo, id_subcategoria_principal, id_categoria_principal)
                VALUES
                (:producto, :descripcion, :precio, :stock, :sku, :destacado, :activo, :id_subcategoria_principal, :id_categoria_principal)";

        $stmt = $this->db()->prepare($sql);

        $id_subcategoria_principal = !empty($data['id_subcategoria_principal']) ? $data['id_subcategoria_principal'] : null;
        $id_categoria_principal = !empty($data['id_categoria_principal']) ? $data['id_categoria_principal'] : null;

        $stmt->bindParam(":producto", $data['producto'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $data['precio']);
        $stmt->bindParam(":stock", $data['stock'], PDO::PARAM_INT);
        $stmt->bindParam(":sku", $data['sku'], PDO::PARAM_STR);
        $stmt->bindParam(":destacado", $data['destacado'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindValue(":id_subcategoria_principal", $id_subcategoria_principal, $id_subcategoria_principal === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(":id_categoria_principal", $id_categoria_principal, $id_categoria_principal === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $stmt->execute();

        return $this->db()->lastInsertId();
    }

    /* =========================================
       ACTUALIZAR PRODUCTO
    ========================================= */
    public function actualizar($id_producto, $data)
    {
        $this->conectar();

        $sql = "UPDATE producto SET
                    producto = :producto,
                    descripcion = :descripcion,
                    precio = :precio,
                    stock = :stock,
                    sku = :sku,
                    destacado = :destacado,
                    activo = :activo,
                    id_subcategoria_principal = :id_subcategoria_principal,
                    id_categoria_principal = :id_categoria_principal,
                    fecha_actualizacion = NOW()
                WHERE id_producto = :id_producto";

        $stmt = $this->db()->prepare($sql);

        $id_subcategoria_principal = !empty($data['id_subcategoria_principal']) ? $data['id_subcategoria_principal'] : null;
        $id_categoria_principal = !empty($data['id_categoria_principal']) ? $data['id_categoria_principal'] : null;

        $stmt->bindParam(":producto", $data['producto'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $data['precio']);
        $stmt->bindParam(":stock", $data['stock'], PDO::PARAM_INT);
        $stmt->bindParam(":sku", $data['sku'], PDO::PARAM_STR);
        $stmt->bindParam(":destacado", $data['destacado'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindValue(":id_subcategoria_principal", $id_subcategoria_principal, $id_subcategoria_principal === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(":id_categoria_principal", $id_categoria_principal, $id_categoria_principal === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================
       BORRAR PRODUCTO
    ========================================= */
    public function borrar($id_producto)
    {
        $this->conectar();

        $sql = "DELETE FROM producto 
                WHERE id_producto = :id_producto";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================
       OBTENER IMÁGENES DE UN PRODUCTO
    ========================================= */
    public function obtenerImagenes($id_producto)
    {
        $this->conectar();

        $sql = "SELECT *
                FROM producto_imagen
                WHERE id_producto = :id_producto 
                  AND activo = 1
                ORDER BY principal DESC, posicion ASC, id_imagen ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       GUARDAR IMAGEN DE PRODUCTO
    ========================================= */
    public function guardarImagen($id_producto, $imagen_url, $principal = 0, $posicion = 0)
    {
        $this->conectar();

        if ($principal == 1) {
            $this->quitarImagenPrincipal($id_producto);
        }

        $sql = "INSERT INTO producto_imagen
                (id_producto, imagen_url, principal, posicion, activo)
                VALUES
                (:id_producto, :imagen_url, :principal, :posicion, 1)";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(":imagen_url", $imagen_url, PDO::PARAM_STR);
        $stmt->bindParam(":principal", $principal, PDO::PARAM_INT);
        $stmt->bindParam(":posicion", $posicion, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================
       QUITAR IMAGEN PRINCIPAL
    ========================================= */
    public function quitarImagenPrincipal($id_producto)
    {
        $this->conectar();

        $sql = "UPDATE producto_imagen
                SET principal = 0
                WHERE id_producto = :id_producto";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* =========================================
       ELIMINAR IMAGEN
    ========================================= */
    public function eliminarImagen($id_imagen)
    {
        $this->conectar();

        $sql = "DELETE FROM producto_imagen 
                WHERE id_imagen = :id_imagen";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_imagen", $id_imagen, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
?>