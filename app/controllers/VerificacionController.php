<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

class VerificacionController extends Controller {
    private $db;
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    // Verificación de Constancias de Docentes
    public function constancia() {
        $id = (int)($_GET['id'] ?? 0);
        $cvd = $_GET['cvd'] ?? '';
        
        if (!$id || !$cvd) {
            die("<div style='text-align:center;margin-top:50px;font-family:sans-serif;'><h3>Enlace incompleto</h3><p>Faltan parámetros de verificación.</p></div>");
        }

        // Validación CVD
        $stmt = $this->db->prepare("SELECT id FROM constancias_emitidas WHERE id_usuario = ? AND cvd = ?");
        $stmt->execute([$id, $cvd]);
        if (!$stmt->fetch()) {
             die("<div style='text-align:center;margin-top:50px;font-family:sans-serif;color:#d00;'><h1>Documento No Verificado</h1><p>El código CVD <b>$cvd</b> no es válido.</p></div>");
        }

        $docente = $this->db->query("SELECT u.*, f.nombre as nombre_facultad FROM usuarios u LEFT JOIN facultades f ON u.id_facultad = f.id WHERE u.id=$id")->fetch(PDO::FETCH_ASSOC);
        if (!$docente) die("Docente no encontrado.");

        // SQL JURADO CORREGIDO (id_tesista_2)
        $sqlJ = "SELECT p.titulo, p.estado, p.created_at as fecha_presentacion, ja.rol_jurado, 
                 u.nombres as tesista_nom, u.apellidos as tesista_ape, u.email as tesista_email, u.telefono as tesista_cel,
                 c.nombres as cotesista_nom, c.apellidos as cotesista_ape, c.email as cotesista_email, c.telefono as cotesista_cel
                 FROM jurado_asignaciones ja 
                 JOIN proyectos p ON ja.id_proyecto=p.id 
                 LEFT JOIN usuarios u ON p.id_tesista=u.id 
                 LEFT JOIN usuarios c ON p.id_tesista_2=c.id
                 WHERE ja.id_jurado=$id ORDER BY p.created_at DESC";
        $jurado = $this->db->query($sqlJ)->fetchAll(PDO::FETCH_ASSOC);

        // SQL ASESOR CORREGIDO (id_tesista_2)
        $sqlA = "SELECT p.titulo, p.estado, p.created_at as fecha_presentacion, 'Asesor' as rol_jurado, 
                 u.nombres as tesista_nom, u.apellidos as tesista_ape, u.email as tesista_email, u.telefono as tesista_cel,
                 c.nombres as cotesista_nom, c.apellidos as cotesista_ape, c.email as cotesista_email, c.telefono as cotesista_cel
                 FROM proyectos p 
                 LEFT JOIN usuarios u ON p.id_tesista=u.id 
                 LEFT JOIN usuarios c ON p.id_tesista_2=c.id
                 WHERE p.id_asesor=$id ORDER BY p.created_at DESC";
                 
        $asesor = $this->db->query($sqlA)->fetchAll(PDO::FETCH_ASSOC);

        $docente['cvd'] = $cvd;
        $protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
        $docente['url_verificacion'] = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/sespecialidad/nadia/gemini/public/verificacion/constancia?id=$id&cvd=$cvd";

        $this->view('admin/docentes/constancia_imprimir', [
            'docente' => $docente, 
            'proyectos' => array_merge($jurado, $asesor),
            'cvd_verificado' => true
        ]);
    }

    public function acta() {
        $id = (int)($_GET['id'] ?? 0);
        $cvd = $_GET['cvd'] ?? '';
        $tipo = $_GET['tipo'] ?? 'Acta Proyecto';
        
        if (!$id || !$cvd) die("Enlace incompleto.");

        $stmt = $this->db->prepare("SELECT id FROM constancias_emitidas WHERE id_usuario = ? AND cvd = ?");
        $stmt->execute([-$id, $cvd]);
        if (!$stmt->fetch()) die("<h1>Error de Verificación</h1><p>Acta no encontrada o CVD incorrecto.</p>");

        require_once '../app/controllers/ReportesController.php';
        $rep = new ReportesController();
        $rep->acta_imprimible();
    }
}