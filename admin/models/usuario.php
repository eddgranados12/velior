<?php
require_once(__DIR__ . "/../sistema.class.php");

class Usuario extends Sistema
{
    /* =========================================
       OBTENER USUARIO POR ID
    ========================================= */
    public function obtenerPorId($id_usuario)
    {
        $this->conectar();

        $sql = "SELECT 
                    id_usuario,
                    nombre,
                    apellido_paterno,
                    apellido_materno,
                    correo,
                    direccion,
                    telefono,
                    fecha_registro
                FROM usuario
                WHERE id_usuario = :id_usuario
                LIMIT 1";

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================================
       ACTUALIZAR PERFIL CLIENTE
    ========================================= */
    public function actualizarPerfil($id_usuario, $data)
    {
        $this->conectar();

        $sql = "UPDATE usuario SET
                    nombre = :nombre,
                    apellido_paterno = :apellido_paterno,
                    apellido_materno = :apellido_materno,
                    correo = :correo,
                    telefono = :telefono,
                    direccion = :direccion
                WHERE id_usuario = :id_usuario";

        $stmt = $this->db()->prepare($sql);

        $stmt->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_paterno", $data['apellido_paterno'], PDO::PARAM_STR);
        $stmt->bindParam(":apellido_materno", $data['apellido_materno'], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $data['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(":direccion", $data['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    /* =========================================
       VALIDAR SI CORREO YA EXISTE
    ========================================= */
    public function correoExiste($correo, $id_usuario = null)
    {
        $this->conectar();

        $sql = "SELECT id_usuario
                FROM usuario
                WHERE correo = :correo";

        if ($id_usuario !== null) {
            $sql .= " AND id_usuario != :id_usuario";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(":correo", $correo, PDO::PARAM_STR);

        if ($id_usuario !== null) {
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /* =========================================
       REGISTRAR CLIENTE
    ========================================= */
    public function registrarCliente($data)
    {
        $this->conectar();

        try {
            $this->db()->beginTransaction();

            $sql = "INSERT INTO usuario
                    (nombre, apellido_paterno, apellido_materno, correo, contrasena, direccion, telefono)
                    VALUES
                    (:nombre, :apellido_paterno, :apellido_materno, :correo, :contrasena, :direccion, :telefono)";

            $stmt = $this->db()->prepare($sql);

            $contrasenaMD5 = md5($data['contrasena']);

            $stmt->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(":apellido_paterno", $data['apellido_paterno'], PDO::PARAM_STR);
            $stmt->bindParam(":apellido_materno", $data['apellido_materno'], PDO::PARAM_STR);
            $stmt->bindParam(":correo", $data['correo'], PDO::PARAM_STR);
            $stmt->bindParam(":contrasena", $contrasenaMD5, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $data['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $data['telefono'], PDO::PARAM_STR);

            $stmt->execute();

            $id_usuario = $this->db()->lastInsertId();

            // Asignar rol Cliente
            $sqlRol = "SELECT id_rol FROM rol WHERE rol = 'Cliente' LIMIT 1";
            $stmtRol = $this->db()->prepare($sqlRol);
            $stmtRol->execute();
            $rolCliente = $stmtRol->fetch(PDO::FETCH_ASSOC);

            if ($rolCliente) {
                $sqlAsignar = "INSERT INTO usuario_rol (id_usuario, id_rol)
                               VALUES (:id_usuario, :id_rol)";
                $stmtAsignar = $this->db()->prepare($sqlAsignar);
                $stmtAsignar->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmtAsignar->bindParam(":id_rol", $rolCliente['id_rol'], PDO::PARAM_INT);
                $stmtAsignar->execute();
            }

            $this->db()->commit();
            return $id_usuario;

        } catch (Exception $e) {
            $this->db()->rollBack();
            return false;
        }
    }
}
?>