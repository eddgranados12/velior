<?php
require_once(__DIR__ . "/../sistema.class.php");

class CategoriaSubcategoria extends Sistema
{

    public function leer()
    {
        $this->conectar();
        $sql = "SELECT * FROM categoria_subcategoria ORDER BY posicion";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();
        $relaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $relaciones;
    }

    public function leerPorCategoria($id_categoria)
    {
        $this->conectar();
        $sql = "SELECT * FROM categoria_subcategoria 
                WHERE id_categoria = :id_categoria
                ORDER BY posicion";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerConNombres()
{
    $this->conectar();
    $sql = "SELECT cs.*, 
                   c.categoria, 
                   s.nombre_subcategoria
            FROM categoria_subcategoria cs
            INNER JOIN categoria c 
                ON cs.id_categoria = c.id_categoria
            INNER JOIN subcategoria s 
                ON cs.id_subcategoria = s.id_subcategoria
            ORDER BY cs.posicion";
    $stmt = $this->db()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function crear($data)
    {
        $this->conectar();
        $sql = "INSERT INTO categoria_subcategoria 
                (id_categoria, id_subcategoria, posicion) 
                VALUES (:id_categoria, :id_subcategoria, :posicion)";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $data['id_categoria'], PDO::PARAM_INT);
        $stmt->bindParam(":id_subcategoria", $data['id_subcategoria'], PDO::PARAM_INT);
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizar($id_categoria, $id_subcategoria, $data)
    {
        $this->conectar();
        $sql = "UPDATE categoria_subcategoria 
                SET posicion = :posicion
                WHERE id_categoria = :id_categoria 
                AND id_subcategoria = :id_subcategoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":posicion", $data['posicion'], PDO::PARAM_INT);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(":id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function borrar($id_categoria, $id_subcategoria)
    {
        $this->conectar();
        $sql = "DELETE FROM categoria_subcategoria 
                WHERE id_categoria = :id_categoria 
                AND id_subcategoria = :id_subcategoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(":id_subcategoria", $id_subcategoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function borrarPorCategoria($id_categoria)
    {
        $this->conectar();
        $sql = "DELETE FROM categoria_subcategoria 
                WHERE id_categoria = :id_categoria";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

}
?>