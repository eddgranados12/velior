<?php
require_once(__DIR__ . "/../sistema.class.php");

class Categoria extends Sistema
{

    public function leer()
    {
        $this->conectar();
        $sql = "select * from categoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categorias;
    }

    function leerUno($id_categoria)
    {
        $this->conectar();
        $sql = "SELECT * FROM categoria WHERE id_categoria = :id_categoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        return $categoria;
    }


    public function crear($data)
    {
        $this->conectar();
        $sql = "INSERT INTO categoria (categoria, descripcion, posicion, activo) values (:categoria, :descripcion, :posicion, :activo)"; 
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":categoria", $data['categoria'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $resultado = $stmt->execute();
        $cantidad = $stmt->rowCount();
        return $cantidad;
    }

    function actualizar($id_categoria, $data)
    {
        $this->conectar();
        $sql = 'UPDATE categoria set categoria = :categoria, descripcion = :descripcion,  posicion = :posicion, activo = :activo where id_categoria = :id_categoria';
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":categoria", $data['categoria'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function borrar($id_categoria)
    {
        $this->conectar();
        $sql = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
};
?>