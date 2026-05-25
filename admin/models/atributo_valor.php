<?php
require_once(__DIR__ . "/../sistema.class.php");

class AtributoValor extends Sistema
{
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT av.*, a.nombre_atributo
                FROM atributo_valor av
                INNER JOIN atributo a ON av.id_atributo = a.id_atributo
                ORDER BY a.nombre_atributo ASC, av.valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerUno($id_atributo_valor)
    {
        $this->conectar();

        $sql = "SELECT * FROM atributo_valor WHERE id_atributo_valor = :id_atributo_valor";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo_valor", $id_atributo_valor, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($data)
    {
        $this->conectar();

        $sql = "INSERT INTO atributo_valor
                (id_atributo, valor, activo)
                VALUES
                (:id_atributo, :valor, :activo)";

        $stmt = $this->db()->prepare($sql);

        $stmt->bindParam(":id_atributo", $data['id_atributo'], PDO::PARAM_INT);
        $stmt->bindParam(":valor", $data['valor'], PDO::PARAM_STR);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizar($id_atributo_valor, $data)
    {
        $this->conectar();

        $sql = "UPDATE atributo_valor SET
                id_atributo = :id_atributo,
                valor = :valor,
                activo = :activo
                WHERE id_atributo_valor = :id_atributo_valor";

        $stmt = $this->db()->prepare($sql);

        $stmt->bindParam(":id_atributo", $data['id_atributo'], PDO::PARAM_INT);
        $stmt->bindParam(":valor", $data['valor'], PDO::PARAM_STR);
        $stmt->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
        $stmt->bindParam(":id_atributo_valor", $id_atributo_valor, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function borrar($id_atributo_valor)
    {
        $this->conectar();

        $sql = "DELETE FROM atributo_valor WHERE id_atributo_valor = :id_atributo_valor";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo_valor", $id_atributo_valor, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function existeValor($id_atributo, $valor, $id_atributo_valor = null)
    {
        $this->conectar();

        $sql = "SELECT COUNT(*) 
                FROM atributo_valor 
                WHERE id_atributo = :id_atributo 
                AND valor = :valor";

        if ($id_atributo_valor !== null) {
            $sql .= " AND id_atributo_valor != :id_atributo_valor";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);

        if ($id_atributo_valor !== null) {
            $stmt->bindParam(":id_atributo_valor", $id_atributo_valor, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function obtenerAtributos()
    {
        $this->conectar();

        $sql = "SELECT id_atributo, nombre_atributo
                FROM atributo
                ORDER BY nombre_atributo ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerPorAtributo($id_atributo)
    {
        $this->conectar();

        $sql = "SELECT *
                FROM atributo_valor
                WHERE id_atributo = :id_atributo
                ORDER BY valor ASC";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>