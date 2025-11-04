<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Sistema {

    private $_DSN = "pgsql:host=postgres;port=5432;dbname=database";
    private $_USER = "user";
    private $_PASSWORD = "password"; 
    protected $_DB = null;

    public function __construct() {
        $this->connect(); 
    }

    public function connect() {
        try {
            if ($this->_DB === null) {
                $this->_DB = new PDO($this->_DSN, $this->_USER, $this->_PASSWORD);
                $this->_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            echo "<h1>¡ERROR DE CONEXIÓN!</h1><pre>";
            echo "DSN: " . htmlspecialchars($this->_DSN) . "\n";
            echo "Error: " . $e->getMessage();
            echo "</pre>";
            exit;
        }
    }
    public function tienePermiso($id_usuario, $permiso) {
    $user = $this->fetch("SELECT permisos FROM usuarios WHERE id_usuario = ?", [$id_usuario]);
    if (!$user || !$user['permisos']) return false;
    
    $permisos = json_decode($user['permisos'], true);
    return !empty($permisos[$permiso]);
}

    public function login($correo, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $this->_DB->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify(trim($password), trim($usuario['password']))) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['username']   = $usuario['correo'];
                $_SESSION['rol']        = $usuario['rol'];
                $_SESSION['validado']   = true;
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error login: " . $e->getMessage());
            return false;
        }
    }

    public function isAuth() {
        return isset($_SESSION['validado']) && $_SESSION['validado'];
    }

    public function validarRol($rol_requerido) {
        if (!$this->isAuth() || $_SESSION['rol'] !== $rol_requerido) {
            header("Location: login.php");
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->_DB->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (Exception $e) {
            error_log("Error query: " . $e->getMessage());
            return false;
        }
    }

    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje, $nombre = '') {
        require_once '../vendor/autoload.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '21030846@itcelaya.edu.mx';
            $mail->Password   = 'kpxc bscy njsj bkbk';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('21030846@itcelaya.edu.mx', 'GAM Multimarca');
            $mail->addAddress($destinatario, $nombre);
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error envío correo: " . $mail->ErrorInfo);
            return false;
        }
    }
    
}
?>