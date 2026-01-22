<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

class PerfilController extends Controller
{
    private $db;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_BASE . 'auth/login');
            exit;
        }
        $this->db = (new Database())->connect();
    }

    public function cambiar_password()
    {
        $msg = '';
        if ($_POST) {
            $id = $_SESSION['user_id'];
            $actual = $_POST['actual'];
            $nueva = $_POST['nueva'];
            $confirmar = $_POST['confirmar'];

            // FIX V66: Usar columna correcta 'password_hash'
            $stmt = $this->db->prepare("SELECT password_hash FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $hash = $stmt->fetchColumn();

            if (password_verify($actual, $hash)) {
                if ($nueva === $confirmar) {
                    if (strlen($nueva) >= 6) {
                        $newHash = password_hash($nueva, PASSWORD_DEFAULT);
                        // FIX V66: Actualizar columna correcta 'password_hash'
                        $this->db->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?")->execute([$newHash, $id]);
                        $msg = 'success|Contraseña actualizada correctamente.';
                    } else {
                        $msg = 'error|La nueva contraseña debe tener al menos 6 caracteres.';
                    }
                } else {
                    $msg = 'error|Las nuevas contraseñas no coinciden.';
                }
            } else {
                $msg = 'error|La contraseña actual es incorrecta.';
            }
        }
        
        $rol = $_SESSION['rol'];
        $layout = ['color' => 'primary', 'home' => ''];
        if ($rol == 1) { $layout['color'] = 'primary'; $layout['home'] = 'tesista/dashboard'; }
        elseif ($rol == 2) { $layout['color'] = 'success'; $layout['home'] = 'docente/dashboard'; }
        elseif ($rol == 3 || $rol == 4) { $layout['color'] = ($rol==4)?'primary':'danger'; $layout['home'] = 'admin/dashboard'; }

        $this->view('perfil/cambiar_password', ['msg' => $msg, 'layout' => $layout]);
    }
}
