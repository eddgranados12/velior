<?php
require_once(__DIR__ . "/../sistema.class.php");

class Atributo extends Sistema
{
    public function leer()
    {
        $this->conectar();

        $sql = "SELECT * FROM atributo ORDER BY nombre_atributo ASC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerUno($id_atributo)
    {
        $this->conectar();

        $sql = "SELECT * FROM atributo WHERE id_atributo = :id_atributo";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($data)
    {
        $this->conectar();

        $sql = "INSERT INTO atributo
                (nombre_atributo, tipo_dato, filtrable)
                VALUES
                (:nombre_atributo, :tipo_dato, :filtrable)";

        $stmt = $this->db()->prepare($sql);

        $stmt->bindParam(":nombre_atributo", $data['nombre_atributo'], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_dato", $data['tipo_dato'], PDO::PARAM_STR);
        $stmt->bindParam(":filtrable", $data['filtrable'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function actualizar($id_atributo, $data)
    {
        $this->conectar();

        $sql = "UPDATE atributo SET
                nombre_atributo = :nombre_atributo,
                tipo_dato = :tipo_dato,
                filtrable = :filtrable
                WHERE id_atributo = :id_atributo";

        $stmt = $this->db()->prepare($sql);

        $stmt->bindParam(":nombre_atributo", $data['nombre_atributo'], PDO::PARAM_STR);
        $stmt->bindParam(":tipo_dato", $data['tipo_dato'], PDO::PARAM_STR);
        $stmt->bindParam(":filtrable", $data['filtrable'], PDO::PARAM_INT);
        $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function borrar($id_atributo)
    {
        $this->conectar();

        $sql = "DELETE FROM atributo WHERE id_atributo = :id_atributo";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function existeNombre($nombre_atributo, $id_atributo = null)
    {
        $this->conectar();

        $sql = "SELECT COUNT(*) FROM atributo WHERE nombre_atributo = :nombre_atributo";

        if ($id_atributo !== null) {
            $sql .= " AND id_atributo != :id_atributo";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":nombre_atributo", $nombre_atributo, PDO::PARAM_STR);

        if ($id_atributo !== null) {
            $stmt->bindParam(":id_atributo", $id_atributo, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
?>