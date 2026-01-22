<?php
class Controller {
    
    public function __construct() {
        $this->verificarAcceso();
    }
    
    private function verificarAcceso() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Excluir Admin y Coordinador
        if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 3 || $_SESSION['rol'] == 4)) {
            return;
        }
        
        // Excluir rutas de auth
        $url = $_GET['url'] ?? '';
        if (strpos($url, 'auth/') === 0) {
            return;
        }
        
        // Verificar cierre del sistema
        try {
            $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            $stmt = $db->query("SELECT * FROM sistema_acceso WHERE id=1");
            $acc = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($acc && $acc['activo'] == 1) {
                $now = date('Y-m-d H:i:s');
                $bloqueado = false;
                
                // Sin fechas = cierre indefinido
                if (empty($acc['fecha_inicio']) && empty($acc['fecha_fin'])) {
                    $bloqueado = true;
                } else {
                    $inicio = $acc['fecha_inicio'] ?: '1970-01-01 00:00:00';
                    $fin = $acc['fecha_fin'] ?: '2099-12-31 23:59:59';
                    
                    if ($now >= $inicio && $now <= $fin) {
                        $bloqueado = true;
                    }
                }
                
                if ($bloqueado) {
                    $mensaje = htmlspecialchars($acc['mensaje_cierre']);
                    $baseUrl = URL_BASE;
                    
                    $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Sistema Cerrado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-lg border-0">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-lock-fill text-danger" style="font-size: 4rem;"></i>
                </div>
                <h2 class="text-danger mb-3">Sistema Cerrado Temporalmente</h2>
                <div class="alert alert-warning">
                    <p class="mb-0" style="white-space: pre-line;">{$mensaje}</p>
                </div>
                <hr>
                <p class="text-muted small">
                    Si necesita acceso urgente, contacte a la administración.
                </p>
                <a href="{$baseUrl}auth/logout" class="btn btn-secondary mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
                    die($html);
                }
            }
        } catch(Exception $e) {
            // Silenciar si la tabla no existe aún
        }
    }
    
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    
    public function view($view, $data = []) {
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            echo "Vista no encontrada: $view";
        }
    }
}
