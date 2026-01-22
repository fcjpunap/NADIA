<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

class DocenteController extends Controller
{
    private $db;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
            header('Location: ' . URL_BASE . 'auth/login');
            exit;
        }
        $this->db = (new Database())->connect();
    }
    public function dashboard()
    {
        $id = $_SESSION['user_id'];
        $sA = $this->db->prepare("SELECT p.*, u.nombres as tesista_n, u.apellidos as tesista_a FROM proyectos p JOIN usuarios u ON p.id_tesista=u.id WHERE p.id_asesor=?");
        $sA->execute([$id]);
        $sJ1 = $this->db->prepare("SELECT p.id, p.titulo, p.estado, u.nombres as tesista_n, u.apellidos as tesista_a, ja.rol_jurado, (SELECT resultado FROM dictamenes WHERE id_proyecto=p.id AND id_jurado=? AND etapa='Proyecto' ORDER BY id DESC LIMIT 1) as mi_voto FROM jurado_asignaciones ja JOIN proyectos p ON ja.id_proyecto=p.id JOIN usuarios u ON p.id_tesista=u.id WHERE ja.id_jurado=? AND p.id_etapa_actual=1");
        $sJ1->execute([$id, $id]);
        $sJ2 = $this->db->prepare("SELECT p.id, p.titulo, p.estado, u.nombres as tesista_n, u.apellidos as tesista_a, ja.rol_jurado, (SELECT resultado FROM dictamenes WHERE id_proyecto=p.id AND id_jurado=? AND etapa='Borrador' ORDER BY id DESC LIMIT 1) as mi_voto FROM jurado_asignaciones ja JOIN proyectos p ON ja.id_proyecto=p.id JOIN usuarios u ON p.id_tesista=u.id WHERE ja.id_jurado=? AND p.id_etapa_actual=2");
        $sJ2->execute([$id, $id]);
        $sJ3 = $this->db->prepare("SELECT p.*, u.nombres as tesista_n, u.apellidos as tesista_a, ja.rol_jurado, (SELECT resultado FROM dictamenes WHERE id_proyecto=p.id AND id_jurado=? AND etapa='Sustentacion' ORDER BY id DESC LIMIT 1) as mi_voto, (SELECT COUNT(*) FROM dictamenes WHERE id_proyecto=p.id AND etapa='Sustentacion') as conteo_votos FROM jurado_asignaciones ja JOIN proyectos p ON ja.id_proyecto=p.id JOIN usuarios u ON p.id_tesista=u.id WHERE ja.id_jurado=? AND p.id_etapa_actual=3");
        $sJ3->execute([$id, $id]);
        $this->view('docente/dashboard', ['nombre' => $_SESSION['nombres'], 'asesorados' => $sA->fetchAll(), 'jurados_proy' => $sJ1->fetchAll(), 'jurados_borr' => $sJ2->fetchAll(), 'jurados_sust' => $sJ3->fetchAll()]);
    }
    public function revision()
    {
        if (!isset($_GET['id'])) { header('Location: ' . URL_BASE . 'docente/dashboard'); exit; }
        $pid = $_GET['id'];
        $uid = $_SESSION['user_id'];
        $p = $this->db->query("SELECT * FROM proyectos WHERE id=$pid")->fetch(PDO::FETCH_ASSOC);
        
        // MAPEADO ESTRICTO DE ETAPAS (SEPARADOS)
        $etapa = ($p['id_etapa_actual'] == 2) ? 'Borrador' : (($p['id_etapa_actual'] >= 3) ? 'Sustentacion' : 'Proyecto');
        
        $allDocs = $this->db->query("SELECT * FROM documentos WHERE id_proyecto=$pid ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        $doc = null; 
        $search = $etapa;
        if ($etapa == 'Sustentacion') $search = 'Corrección Borrador';
        foreach ($allDocs as $d) { if ($d['mime_type'] == 'application/pdf' && strpos($d['tipo_documento'], $search) !== false) { $doc = $d; break; } }
        if (!$doc && $etapa == 'Sustentacion') { foreach ($allDocs as $d) { if ($d['mime_type'] == 'application/pdf' && strpos($d['tipo_documento'], 'Borrador') !== false) { $doc = $d; break; } } }
        if (!$doc && !empty($allDocs)) $doc = $allDocs[0];
        
        $voto = $this->db->query("SELECT id FROM dictamenes WHERE id_proyecto=$pid AND id_jurado=$uid AND etapa='$etapa'")->fetch();
        $bloqueado = (bool)$voto;
        
        $stmtF = $this->db->prepare("SELECT MAX(fecha_finalizacion) as fecha_fin FROM observaciones_finalizadas WHERE id_proyecto = ? AND etapa = ? AND id_usuario = ?");
        $stmtF->execute([$pid, $etapa, $uid]);
        $fechaFin = $stmtF->fetchColumn();
        $stmtD = $this->db->prepare("SELECT MAX(created_at) as fecha_doc FROM documentos WHERE id_proyecto = ? AND (tipo_documento LIKE CONCAT('%', ?, '%') OR tipo_documento LIKE '%Correccion%') AND mime_type='application/pdf'");
        $stmtD->execute([$pid, $etapa]);
        $fechaDoc = $stmtD->fetchColumn();
        $obs_finalizadas = ($fechaFin && (!$fechaDoc || $fechaFin >= $fechaDoc));

        if (!$bloqueado && $etapa != 'Sustentacion' && $p['estado'] == 'Aprobado') $bloqueado = true;
        
        $hist = $this->db->query("SELECT * FROM historial_movimientos WHERE id_proyecto=$pid ORDER BY fecha DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
        $votos_sust = $this->db->query("SELECT COUNT(*) FROM dictamenes WHERE id_proyecto=$pid AND etapa='Sustentacion'")->fetchColumn();
        $actas = ['p' => ($p['id_etapa_actual'] >= 2 || $p['estado'] == 'Aprobado'), 'b' => ($p['id_etapa_actual'] >= 3 || ($p['id_etapa_actual'] == 2 && $p['estado'] == 'Aprobado')), 's' => ($votos_sust >= 3)];
        $this->view('docente/visor_avanzado', ['proyecto' => $p, 'documento' => $doc, 'all_docs' => $allDocs, 'historial' => $hist, 'bloqueado' => $bloqueado, 'obs_finalizadas' => $obs_finalizadas, 'fase_lbl' => $etapa, 'actas' => $actas]);
    }
    public function guardar_dictamen_final()
    {
        if ($_POST) {
            $pid = $_POST['id_proyecto']; $res = $_POST['resultado']; $et = $_POST['etapa_actual']; $uid = $_SESSION['user_id'];
            $this->db->prepare("INSERT INTO dictamenes (id_proyecto, id_jurado, resultado, etapa) VALUES (?, ?, ?, ?)")->execute([$pid, $uid, $res, $et]);
            $this->db->prepare("INSERT INTO historial_movimientos (id_proyecto, id_usuario, accion, detalle) VALUES (?, ?, 'DICTAMEN', ?)")->execute([$pid, $uid, "Voto $et: $res"]);
            if ($res == 'Observado') $this->db->prepare("UPDATE proyectos SET estado='Observado' WHERE id=?")->execute([$pid]);
            else if ($res == 'Aprobado' && ($et == 'Proyecto' || $et == 'Borrador')) {
                $num_jurados = $this->db->query("SELECT COUNT(*) FROM jurado_asignaciones WHERE id_proyecto=$pid")->fetchColumn();
                $total_revisores = $num_jurados + 1;
                $votos_favorables = $this->db->query("SELECT COUNT(*) FROM dictamenes WHERE id_proyecto=$pid AND etapa='$et' AND resultado='Aprobado'")->fetchColumn();
                if ($votos_favorables >= $total_revisores) {
                    $this->db->prepare("UPDATE proyectos SET estado='Aprobado' WHERE id=?")->execute([$pid]);
                }
            }
            if ($et == 'Sustentacion' && $this->db->query("SELECT COUNT(*) FROM dictamenes WHERE id_proyecto=$pid AND etapa='Sustentacion'")->fetchColumn() >= 3) $this->db->exec("UPDATE proyectos SET estado='Sustentado' WHERE id=$pid");
            header('Location: ' . URL_BASE . 'docente/dashboard?msg=voto_ok');
        }
    }
    public function api_get_comentarios()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        if (!$in || !isset($in['id_proyecto'])) { echo json_encode([]); return; }
        
        $p = $this->db->query("SELECT id_etapa_actual FROM proyectos WHERE id=" . (int)$in['id_proyecto'])->fetch();
        $tipo = ($p['id_etapa_actual'] == 2) ? 'Borrador' : (($p['id_etapa_actual'] >= 3) ? 'Sustentacion' : 'Proyecto');
        
        $stmt = $this->db->prepare("SELECT o.*, u.nombres FROM observaciones o LEFT JOIN usuarios u ON o.id_jurado=u.id WHERE o.id_proyecto=? AND o.pagina=? AND o.tipo_observacion=? ORDER BY o.fecha_observacion DESC");
        $stmt->execute([$in['id_proyecto'], $in['pagina'], $tipo]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function api_save_comentario()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        try {
            $p = $this->db->query("SELECT id_etapa_actual FROM proyectos WHERE id=" . (int)$in['id_proyecto'])->fetch();
            $tipo = ($p['id_etapa_actual'] == 2) ? 'Borrador' : (($p['id_etapa_actual'] >= 3) ? 'Sustentacion' : 'Proyecto');
            
            $this->db->prepare("INSERT INTO observaciones (id_proyecto, id_jurado, rol_autor, pagina, tipo_observacion, observacion_texto) VALUES (?, ?, 'Jurado', ?, ?, ?)")->execute([$in['id_proyecto'], $_SESSION['user_id'], $in['pagina'], $tipo, $in['texto']]);
            echo json_encode(['status' => 'ok']);
        } catch (Throwable $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function api_update_comentario() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        $this->db->prepare("UPDATE observaciones SET observacion_texto=? WHERE id=? AND id_jurado=?")->execute([$in['texto'], $in['id'], $_SESSION['user_id']]);
        echo json_encode(['status' => 'ok']);
    }
    public function api_delete_comentario() {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        $this->db->prepare("DELETE FROM observaciones WHERE id=? AND id_jurado=?")->execute([$in['id'], $_SESSION['user_id']]);
        echo json_encode(['status' => 'ok']);
    }
    public function dictaminar() { $this->revision(); }
    public function api_finalizar_observaciones()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        try {
            $pid = (int)$in['id_proyecto']; $uid = $_SESSION['user_id'];
            $p = $this->db->query("SELECT id_etapa_actual FROM proyectos WHERE id=$pid")->fetch();
            $etapa = ($p['id_etapa_actual'] == 2) ? 'Borrador' : (($p['id_etapa_actual'] >= 3) ? 'Sustentacion' : 'Proyecto');
            $this->db->prepare("DELETE FROM observaciones_finalizadas WHERE id_proyecto = ? AND id_usuario = ? AND etapa = ?")->execute([$pid, $uid, $etapa]);
            $this->db->prepare("INSERT INTO observaciones_finalizadas (id_proyecto, id_usuario, etapa, fecha_finalizacion) VALUES (?, ?, ?, NOW())")->execute([$pid, $uid, $etapa]);
            echo json_encode(['status' => 'ok']);
        } catch (Throwable $e) { echo json_encode(['status' => 'error', 'message' => $e->getMessage()]); }
    }
}
