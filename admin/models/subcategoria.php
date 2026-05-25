<?php
require_once(__DIR__ . "/../sistema.class.php");

class Subcategoria extends Sistema
{

    public function leer()
    {
        $this->conectar();
        $sql = "select * from subcategoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        $subcategorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $subcategorias;
    }

    function leerUno($id_subcategoria)
    {
        $this->conectar();
        $sql = "SELECT * FROM subcategoria WHERE id_subcategoria = :id_subcategoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
        $stmt->execute();
        $subcategoria = $stmt->fetch(PDO::FETCH_ASSOC);
        return $subcategoria;
    }


    public function crear($data)
    {
        $this->conectar();
        $sql = "INSERT INTO subcategoria (nombre_subcategoria, descripcion, posicion, activo) values (:nombre_subcategoria, :descripcion, :posicion, :activo)"; 
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":nombre_subcategoria", $data['nombre_subcategoria'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $resultado = $stmt->execute();
        $cantidad = $stmt->rowCount();
        return $cantidad;
    }

    function actualizar($id_subcategoria, $data)
    {
        $this->conectar();
        $sql = 'UPDATE subcategoria set nombre_subcategoria = :nombre_subcategoria, descripcion = :descripcion,  posicion = :posicion, activo = :activo where id_subcategoria = :id_subcategoria';
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":nombre_subcategoria", $data['nombre_subcategoria'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindParam(":id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    function borrar($id_subcategoria)
    {
        $this->conectar();
        $sql = "DELETE FROM subcategoria WHERE id_subcategoria = :id_subcategoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
};
?>