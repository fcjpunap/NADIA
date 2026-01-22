<?php
session_start();
// MIDDLEWARE: Control de Acceso Temporal
if (isset($_SESSION['user_id'])) {
    // Solo validar si el usuario NO es Admin (3) ni Coordinador (4)
    if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 4)) {
        
        // Verificar si estamos en ruta de autenticación (permitir logout)
        $url = $_GET['url'] ?? '';
        if (strpos($url, 'auth/') !== 0) {
            
            try {
                require_once '../app/config/config.php';
                $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $db->query("SELECT * FROM sistema_acceso WHERE id=1");
                $acceso = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($acceso && $acceso['activo'] == 1) {
                    $now = date('Y-m-d H:i:s');
                    $bloqueado = false;
                    
                    // Sin fechas = cierre indefinido
                    if (empty($acceso['fecha_inicio']) && empty($acceso['fecha_fin'])) {
                        $bloqueado = true;
                    } else {
                        // Con fechas: verificar rango
                        $inicio = $acceso['fecha_inicio'] ?: '1970-01-01 00:00:00';
                        $fin = $acceso['fecha_fin'] ?: '2099-12-31 23:59:59';
                        
                        if ($now >= $inicio && $now <= $fin) {
                            $bloqueado = true;
                        }
                    }
                    
                    if ($bloqueado) {
                        $mensaje = htmlspecialchars($acceso['mensaje_cierre']);
                        ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Sistema Cerrado Temporalmente</title>
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
                    <p class="mb-0" style="white-space: pre-line;"><?php echo $mensaje; ?></p>
                </div>
                <hr>
                <div class="alert alert-info small">
                    <strong>Periodo de cierre:</strong><br>
                    Desde: <?php echo !empty($acceso['fecha_inicio']) ? date('d/m/Y H:i', strtotime($acceso['fecha_inicio'])) : 'Inmediato'; ?><br>
                    Hasta: <?php echo !empty($acceso['fecha_fin']) ? date('d/m/Y H:i', strtotime($acceso['fecha_fin'])) : 'Indefinido'; ?>
                </div>
                <p class="text-muted small">
                    Si necesita acceso urgente, contacte a la administración del sistema.
                </p>
                <a href="<?php echo URL_BASE; ?>auth/logout" class="btn btn-secondary mt-3">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</body>
</html>
                        <?php
                        exit;
                    }
                }
            } catch(Exception $e) {
                // Silenciar error si tabla no existe
            }
        }
    }
}
// Continuar con el flujo normal
require_once '../app/config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
$app = new App();
