<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once APP_ROOT . '/helpers/Mailer.php';
class AuthController extends Controller {
    private $db;
    public function __construct() {
         $this->db = (new Database())->connect();
    }
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $pass = $_POST['password'];
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user && ($user['activo'] == 1) && password_verify($pass, $user['password_hash'])) {
                if(session_status() !== PHP_SESSION_ACTIVE) session_start();
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['rol'] = $user['id_rol_principal'];
                $_SESSION['nombres'] = $user['nombres'];
                $_SESSION['facultad'] = $user['facultad_asignada'] ?? '';
                session_write_close();
                $url = URL_BASE;
                switch((int)$user['id_rol_principal']) {
                    case 1: $url .= 'tesista/dashboard'; break;
                    case 2: $url .= 'docente/dashboard'; break;
                    case 3: $url .= 'admin/dashboard'; break;
                    case 4: $url .= 'admin/dashboard'; break;
                    default: $url .= 'auth/login'; break;
                }
                header("Location: $url");
                exit;
            } else {
                $error = ($user && $user['activo'] == 0) ? 'Usuario inactivo.' : 'Credenciales incorrectas.';
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    public function logout() { 
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        session_destroy(); 
        header('Location: '.URL_BASE.'auth/login');
    }
    // --- LÓGICA DE RECUPERACIÓN ---
    public function recuperar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            // Buscar usuario
            $stmt = $this->db->prepare("SELECT id, nombres FROM usuarios WHERE email = ? AND activo=1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Generar código 6 dígitos
                $code = rand(100000, 999999);
                $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                // Guardar en BD
                $ud = $this->db->prepare("UPDATE usuarios SET recovery_code = ?, recovery_expires = ? WHERE id = ?");
                $ud->execute([$code, $expiry, $user['id']]);
                // Enviar Email
                $asunto = "Código de Recuperación de Contraseña";
                $mensaje = "<h3>Hola, {$user['nombres']}</h3>";
                $mensaje .= "<p>Has solicitado restablecer tu contraseña en el Sistema NADIA.</p>";
                $mensaje .= "<p>Tu código de verificación es: <h2 style='color:#0d6efd;'>$code</h2></p>";
                $mensaje .= "<p>Este código expira en 30 minutos.</p>";
                $mensaje .= "<p>Si no solicitaste esto, ignora este mensaje.</p>";
                $mensaje .= "<hr><small>Sistema NADIA</small>";
                if (Mailer::enviar($email, $asunto, $mensaje)) {
                    // Redirigir a verificar con el email (encriptado o en session, aqui simple por GET oculto o session)
                    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
                    $_SESSION['recuperar_email'] = $email;
                    header('Location: ' . URL_BASE . 'auth/verificar');
                    exit;
                } else {
                    $this->view("auth/recuperar", ['error' => 'Error al enviar email. Revise la configuración SMTP.']);
                }
            } else {
                // Por seguridad, no decimos si el email existe o no, pero simulamos éxito o decimos genérico
                $this->view("auth/recuperar", ['error' => 'Si el correo existe, se enviará un código.']);
            }
        } else {
            $this->view("auth/recuperar");
        }
    }
    public function verificar() {
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();
        $email = $_SESSION['recuperar_email'] ?? '';
        if (!$email) { header('Location: ' . URL_BASE . 'auth/recuperar'); exit; }
        
        $this->view("auth/verificar", ['email' => $email]);
    }
    public function procesar_reseteo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $code = $_POST['codigo'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
            if ($pass1 !== $pass2) {
                $this->view("auth/verificar", ['email' => $email, 'error' => 'Las contraseñas no coinciden.']);
                return;
            }
            // Validar Código
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ? AND recovery_code = ? AND recovery_expires > NOW()");
            $stmt->execute([$email, $code]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Cambiar Password y Limpiar Código
                $hash = password_hash($pass1, PASSWORD_DEFAULT);
                $upd = $this->db->prepare("UPDATE usuarios SET password_hash = ?, recovery_code = NULL, recovery_expires = NULL WHERE id = ?");
                $upd->execute([$hash, $user['id']]);
                
                // Mensaje éxito y redirección login
                header('Location: ' . URL_BASE . 'auth/login?msg=Contraseña actualizada correctamente. Inicie sesión.');
                exit;
            } else {
                $this->view("auth/verificar", ['email' => $email, 'error' => 'Código inválido o expirado.']);
            }
        }
    }
}
