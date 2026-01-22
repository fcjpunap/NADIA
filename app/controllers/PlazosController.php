<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once APP_ROOT . '/helpers/PlazoHelper.php';
require_once APP_ROOT . '/helpers/Paginator.php';

class PlazosController extends Controller
{
    private $db;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || ($_SESSION['rol'] != 3 && $_SESSION['rol'] != 4)) {
            header('Location: ' . URL_BASE . 'auth/login');
            exit;
        }
        $this->db = (new Database())->connect();
    }

    public function index()
    {
        $plazos = $this->db->query("SELECT * FROM configuracion_plazos ORDER BY etapa_id, id")->fetchAll(PDO::FETCH_ASSOC);
        $acceso = $this->db->query("SELECT * FROM sistema_acceso WHERE id=1")->fetch(PDO::FETCH_ASSOC);
        if (!$acceso) $acceso = ['activo' => 0, 'fecha_inicio' => '', 'fecha_fin' => '', 'mensaje_cierre' => ''];
        $this->view('admin/plazos/index', ['plazos' => $plazos, 'acceso' => $acceso]);
    }

    public function guardar()
    {
        if ($_POST) {
            foreach ($_POST['dias'] as $id => $dias) {
                $this->db->prepare("UPDATE configuracion_plazos SET dias_plazo=? WHERE id=?")->execute([$dias, $id]);
            }
            header('Location: ' . URL_BASE . 'plazos/index?msg=ok');
        }
    }

    public function monitor()
    {
        $q = $_GET['q'] ?? '';
        $p = $_GET['page'] ?? 1;
        
        $sql = "SELECT p.id, p.titulo, p.estado, p.id_etapa_actual, p.created_at, p.updated_at, p.fecha_sustentacion,
                u.nombres, u.apellidos
                FROM proyectos p 
                JOIN usuarios u ON p.id_tesista=u.id 
                WHERE (p.titulo LIKE ? OR u.nombres LIKE ? OR u.apellidos LIKE ?)";
        
        $sql .= " ORDER BY p.updated_at ASC";

        $pg = Paginator::paginate($this->db, $sql, ["%$q%", "%$q%", "%$q%"], $p, 20);
        
        foreach ($pg['data'] as &$row) {
            $calculo = PlazoHelper::calcular($row, $this->db);
            $row['plazo_info'] = $calculo;
            
            $fecha_inicio = new DateTime(!empty($row['updated_at']) ? $row['updated_at'] : $row['created_at']);
            $row['fecha_inicio'] = $fecha_inicio->format('d/m/Y');
            
            $dias_limite = $calculo['dias_limite'];
            $fecha_venc = clone $fecha_inicio;
            $fecha_venc->modify("+$dias_limite days");
            
            if (in_array($row['estado'], ['Aprobado', 'Sustentado', 'Archivado'])) {
                $row['fecha_vencimiento'] = 'Finalizado';
                // Si está finalizado, tal vez no queremos mostrar vencimiento, pero el usuario pidió "Tiempo de ejecución"
                // Para Aprobado (Ejecución), el tiempo sigue corriendo hasta que se sustente.
            } else {
                $row['fecha_vencimiento'] = $fecha_venc->format('d/m/Y');
            }

            // NUEVO: Cálculo de Tiempo de Ejecución
            // Solo si está en etapa de ejecución (Aprobado) o posterior
            if ($row['estado'] == 'Aprobado' || $row['id_etapa_actual'] >= 2) {
                $i = $calculo['intervalo'];
                $parts = [];
                if ($i->y > 0) $parts[] = $i->y . " año" . ($i->y > 1 ? 's' : '');
                if ($i->m > 0) $parts[] = $i->m . " mes" . ($i->m > 1 ? 'es' : '');
                if ($i->d > 0) $parts[] = $i->d . " día" . ($i->d > 1 ? 's' : '');
                
                if (empty($parts)) $row['tiempo_ejecucion'] = "0 días";
                else $row['tiempo_ejecucion'] = implode(", ", $parts);
            } else {
                $row['tiempo_ejecucion'] = '-';
            }
        }

        $this->view('admin/plazos/monitor', ['proyectos' => $pg['data'], 'paginacion' => $pg, 'q' => $q, 'ui' => ['rol_label' => 'Admin', 'theme_color' => 'danger']]);
    }

    public function guardar_acceso() {
        if ($_POST) {
            $activo = isset($_POST['activo']) ? 1 : 0;
            $inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : NULL;
            $fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : NULL;
            $mensaje = $_POST['mensaje'] ?? 'El sistema se encuentra cerrado temporalmente.';
            
            $stmt = $this->db->prepare("UPDATE sistema_acceso SET activo=?, fecha_inicio=?, fecha_fin=?, mensaje_cierre=? WHERE id=1");
            $stmt->execute([$activo, $inicio, $fin, $mensaje]);
            
            header('Location: ' . URL_BASE . 'plazos/index?msg=Configuración guardada correctamente');
            exit;
        }
    }
}