<?php
require_once(__DIR__ . "/../sistema.class.php");

class Pedido extends Sistema
{
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT p.*, u.nombre, u.primer_apellido, u.segundo_apellido, mp.nombre_metodo AS metodo_pago
                FROM pedido p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN metodo_pago mp ON p.id_metodo_pago = mp.id_metodo_pago
                ORDER BY p.fecha_pedido DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerUno($id_pedido)
    {
        $this->conectar();

        $sql = "SELECT p.*, u.nombre, u.primer_apellido, u.segundo_apellido, u.correo, mp.nombre_metodo AS metodo_pago
                FROM pedido p
                INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                LEFT JOIN metodo_pago mp ON p.id_metodo_pago = mp.id_metodo_pago
                WHERE p.id_pedido = :id_pedido";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function leerDetalle($id_pedido)
    {
        $this->conectar();

        $sql = "SELECT dp.*, pi.imagen_url
                FROM detalle_pedido dp
                LEFT JOIN producto_imagen pi
                    ON pi.id_producto = dp.id_producto AND pi.principal = 1 AND pi.activo = 1
                WHERE dp.id_pedido = :id_pedido";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pedidos de un usuario específico (vista cliente "Mis pedidos")
    public function leerPorUsuario($id_usuario)
    {
        $this->conectar();

        $sql = "SELECT p.*, mp.nombre_metodo AS metodo_pago
                FROM pedido p
                LEFT JOIN metodo_pago mp ON p.id_metodo_pago = mp.id_metodo_pago
                WHERE p.id_usuario = :id_usuario
                ORDER BY p.fecha_pedido DESC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id_pedido, $data)
    {
        $this->conectar();

        $sql = "UPDATE pedido SET
                    estado = :estado,
                    estado_pago = :estado_pago,
                    fecha_envio = :fecha_envio,
                    fecha_entrega = :fecha_entrega
                WHERE id_pedido = :id_pedido";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":estado", $data['estado'], PDO::PARAM_STR);
        $stmt->bindParam(":estado_pago", $data['estado_pago'], PDO::PARAM_STR);
        $stmt->bindValue(":fecha_envio", $data['fecha_envio'], $data['fecha_envio'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":fecha_entrega", $data['fecha_entrega'], $data['fecha_entrega'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function borrar($id_pedido)
    {
        $this->conectar();

        $sql = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function crearDesdeCarrito($data)
    {
        $this->conectar();

        try {
            $this->db()->beginTransaction();

            $carrito = $data['carrito'];
            $total = 0;

            foreach ($carrito as $item) {
                $productoBD = $this->obtenerProductoPorId($item['id_producto']);

                if (!$productoBD) {
                    throw new Exception("Uno de los productos ya no existe.");
                }

                if ($productoBD['stock'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para: " . $productoBD['producto']);
                }

                $total += ($productoBD['precio'] * $item['cantidad']);
            }

            $sqlPedido = "INSERT INTO pedido
                (id_usuario, id_metodo_pago, total, estado, estado_pago, direccion_envio, telefono_contacto, notas)
                VALUES
                (:id_usuario, :id_metodo_pago, :total, 'pendiente', 'pendiente', :direccion_envio, :telefono_contacto, :notas)";

            $stmtPedido = $this->db()->prepare($sqlPedido);
            $stmtPedido->bindParam(":id_usuario", $data['id_usuario'], PDO::PARAM_INT);
            $stmtPedido->bindValue(":id_metodo_pago", $data['id_metodo_pago'], $data['id_metodo_pago'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmtPedido->bindParam(":total", $total);
            $stmtPedido->bindParam(":direccion_envio", $data['direccion_envio'], PDO::PARAM_STR);
            $stmtPedido->bindParam(":telefono_contacto", $data['telefono_contacto'], PDO::PARAM_STR);
            $stmtPedido->bindParam(":notas", $data['notas'], PDO::PARAM_STR);
            $stmtPedido->execute();

            $id_pedido = (int) $this->db()->lastInsertId();

            foreach ($carrito as $item) {
                $productoBD = $this->obtenerProductoPorId($item['id_producto']);

                $sqlDetalle = "INSERT INTO detalle_pedido
                    (id_pedido, id_producto, cantidad, precio_unitario, nombre_producto)
                    VALUES
                    (:id_pedido, :id_producto, :cantidad, :precio_unitario, :nombre_producto)";

                $stmtDetalle = $this->db()->prepare($sqlDetalle);
                $stmtDetalle->bindParam(":id_pedido", $id_pedido, PDO::PARAM_INT);
                $stmtDetalle->bindParam(":id_producto", $productoBD['id_producto'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":cantidad", $item['cantidad'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":precio_unitario", $productoBD['precio']);
                $stmtDetalle->bindParam(":nombre_producto", $productoBD['producto'], PDO::PARAM_STR);
                $stmtDetalle->execute();

                $nuevoStock = $productoBD['stock'] - $item['cantidad'];
                $this->actualizarStockProducto($productoBD['id_producto'], $nuevoStock);
            }

            $this->db()->commit();

            return [
                'success' => true,
                'id_pedido' => $id_pedido
            ];

        } catch (Exception $e) {
            $this->db()->rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function obtenerProductoPorId($id_producto)
    {
        $sql = "SELECT * FROM producto WHERE id_producto = :id_producto";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function actualizarStockProducto($id_producto, $nuevoStock)
    {
        $sql = "UPDATE producto SET stock = :stock WHERE id_producto = :id_producto";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":stock", $nuevoStock, PDO::PARAM_INT);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->execute();
    }
}