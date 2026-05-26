<?php
require_once(__DIR__ . "/config.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Sistema
{
    private ?PDO $db = null;

    public function __construct()
    {
        $this->conectar();
    }

    public function getExtensionesImagenes()
    {
        return array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            'image/bmp',
            'image/x-icon',
            'image/tiff',
            'image/avif'
        );
    }

    public function conectar()
    {
        try {
            $this->db = new PDO(
                DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME . ";port=" . DBPORT,
                DBUSER,
                DBPASSWORD
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function db(): PDO
    {
        return $this->db;
    }


    function alerta($tipo, $mensaje)
    {
        if (!is_null($tipo) && !is_null($mensaje)) {
            $alerta = array();
            $alerta['tipo'] = $tipo;
            $alerta['mensaje'] = $mensaje;
            include(__DIR__ . '/views/alerta.php');

        }
    }


    public function getRoles($correo)
    {
        $sql = "SELECT r.rol from rol r inner join usuario_rol ur on r.id_rol = ur.id_rol
                        inner join usuario u on ur.id_usuario = u.id_usuario
                        where u.correo = :correo;";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $aux = [];
        foreach ($roles as $rol) {
            array_push($aux, $rol['rol']);
        }
        return $aux;
    }

    public function getPermisos($correo)
    {
        $sql = "SELECT p.permiso from rol r inner join usuario_rol ur on r.id_rol = ur.id_rol
                        inner join usuario u on ur.id_usuario = u.id_usuario
                        inner join rol_permiso rp on r.id_rol = rp.id_rol
                        inner join permiso p on rp.id_permiso = p.id_permiso
        where u.correo = :correo;";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();
        $permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $aux = [];
        foreach ($permisos as $permiso) {
            array_push($aux, $permiso['permiso']);
        }

        return $aux;
    }


    public function login($correo, $contrasena)
    {
        $contrasena = md5($contrasena);
        $sql = "select * from usuario where correo = :correo and contrasena = :contrasena";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($usuario['correo'])) {
            $_SESSION['validado'] = true;
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['roles'] = $this->getRoles($usuario['correo']);
            $_SESSION['permisos'] = $this->getPermisos($usuario['correo']);
            return true;
        } else {
            session_destroy();
            return false;
        }
    }


    public function logout()
    {
        unset($_SESSION);
        session_destroy();
    }

    public function checarRol($rol)
    {
        if (isset($_SESSION['validado']) && $_SESSION['validado'] === true) {
            $roles = $_SESSION['roles'];
            if (in_array($rol, $roles)) {
                return true;
            }
        }
        require_once(__DIR__ . "/views/login/login_header.php");
        $this->alerta('error', 'No tienes el rol para acceder a esta seccion,<a href="login.php?accion=login"> ');
        die();
        return false;
    }

    public function validarPermiso($permiso)
    {
        if (isset($_SESSION['validado']) && $_SESSION['validado'] === true) {
            $permisos = $_SESSION['permisos'];
            if (in_array($permiso, $permisos)) {
                return true;
            }
        }

        return false;
    }



    // ============================
    // NUEVAS FUNCIONES DE SEGURIDAD
    // ============================


    public function checarPermiso($permiso)
    {
        if ($this->validarPermiso($permiso)) {
            return true;
        }

        require_once(__DIR__ . "/views/login/login_header.php");
        $this->alerta('error', 'No tienes permiso para acceder a esta sección,<a href="login.php?accion=login"> ');
        die();
        return false;
    }

    public function estaLogueado()
    {
        return (isset($_SESSION['validado']) && $_SESSION['validado'] === true);
    }

    public function requiereLogin()
    {
        if (!$this->estaLogueado()) {
            header("Location: /velior/admin/login.php?accion=login");
            exit();
        }
    }

    public function esCliente()
    {
        if ($this->estaLogueado()) {
            return in_array('Cliente', $_SESSION['roles']);
        }
        return false;
    }

    public function esAdmin()
    {
        if ($this->estaLogueado()) {
            return (
                in_array('Administrador', $_SESSION['roles']) ||
                in_array('Superadmin', $_SESSION['roles']) ||
                in_array('Gestor de catálogo', $_SESSION['roles']) ||
                in_array('Gestor de pedidos', $_SESSION['roles'])
            );
        }
        return false;
    }

    public function getRutaInicio()
    {
        if ($this->esAdmin()) {
            return "/velior/admin/index.php";
        }

        if ($this->esCliente()) {
            return "/velior/index.php";
        }

        return "/velior/admin/login.php?accion=login";
    }

    public function redirigirSegunRol()
    {
        header("Location: " . $this->getRutaInicio());
        exit();
    }

    public function getNombreUsuario()
    {
        if (isset($_SESSION['nombre'])) {
            return $_SESSION['nombre'];
        }
        return null;
    }

    public function getCorreoUsuario()
    {
        if (isset($_SESSION['correo'])) {
            return $_SESSION['correo'];
        }
        return null;
    }




function envioCorreo($nombre_destinatario, $destinatario, $asunto, $cuerpo, $adjuntos = null)
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        //Enable SMTP debugging
        //SMTP::DEBUG_OFF = off (for production use)
        //SMTP::DEBUG_CLIENT = client messages
        //SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = '22030782@itcelaya.edu.mx';
        $mail->Password = 'tglglmxbvpqtwsus';
        $mail->setFrom('22030782@itcelaya.edu.mx', 'Eduardo Granados');
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->addAddress($destinatario, $nombre_destinatario);
        $mail->Subject = $asunto;
        $mail->msgHTML($cuerpo);
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
    
    function token($correo)
    {
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->conectar();
            $sql = "SELECT * FROM usuario WHERE correo = :correo";
            $stmt = $this->db()->prepare($sql);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            $cantidad = $stmt->rowCount();
            if ($cantidad > 0) {
                $port1 = md5('Cruz azul campeon');
                $port2 = md5(random_bytes(16));
                $token = $port1 . $port2;
                $sql = "UPDATE usuario SET token = :token WHERE correo = :correo";
                $stmt = $this->db()->prepare($sql);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                $stmt->execute();

                $contenido = "<p> Estimado usuario, haz solicitado restablecer tu contraseña. Para cambiar tu contraseña, haz clic en el siguiente enlace:</p>";
                // Determinar base URL: usar BASE_URL si está definida, si no, intentar construirla desde el host
                if (defined('BASE_URL') && BASE_URL) {
                    $base = rtrim(BASE_URL, '/');
                } else {
                    if (isset($_SERVER['HTTP_HOST'])) {
                        $scheme = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) ? 'https' : 'http';
                        $base = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/velior';
                    } else {
                        $base = 'http://localhost/velior';
                    }
                }
                $link = $base . "/admin/login.php?accion=restablecer&correo=" . urlencode($correo) . "&token=" . $token;
                $contenido .= "<p><a href='" . $link . "'>Restablecer contraseña</a>";
                $this->envioCorreo('Usuario', $correo, 'Recuperar contraseña', $contenido, null);

            }
        }
    }


    function cambiarContrasena($correo, $token, $contrasena_nueva){
        $this->conectar();
        $sql = "SELECT * FROM usuario WHERE correo = :correo AND token = :token";
        $stmt = $this->db()->prepare($sql);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $cantidad = $stmt->rowCount();
        if ($cantidad > 0) {
            $password = md5($contrasena_nueva);
            $sql = "UPDATE usuario SET contrasena = :contrasena, token = NULL WHERE correo = :correo";
            $stmt = $this->db()->prepare($sql);
            $stmt->bindParam(':contrasena', $password, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            return $stmt->execute();
            return true;
        } else{
            return false;
        }
    }

}

;