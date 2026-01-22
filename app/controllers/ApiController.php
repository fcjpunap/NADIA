<?php
require_once "../app/core/Controller.php";
require_once "../app/core/Database.php";
class ApiController extends Controller {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }
    
    public function buscar_usuarios_mensaje() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $q = $_GET["q"] ?? "";
        if (strlen($q) < 2) { echo json_encode([]); return; }
        if (!isset($_SESSION["rol"]) || ($_SESSION["rol"] != 3 && $_SESSION["rol"] != 4)) {
            echo json_encode([]); return;
        }
        $search = "%$q%";
        $sql = "SELECT u.id, u.nombres, u.apellidos, u.dni, u.codigo, 
                (SELECT nombre_rol FROM roles WHERE id = u.id_rol_principal LIMIT 1) as rol
                FROM usuarios u
                WHERE (u.nombres LIKE ? OR u.apellidos LIKE ? OR u.dni LIKE ? OR u.codigo LIKE ? OR CONCAT(u.nombres, ' ', u.apellidos) LIKE ?)
                AND u.id != ? LIMIT 15";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$search, $search, $search, $search, $search, $_SESSION["user_id"]]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) { echo json_encode([]); }
    }
    public function buscar_proyecto_participantes() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION["rol"]) || ($_SESSION["rol"] != 3 && $_SESSION["rol"] != 4)) {
            echo json_encode([]); return;
        }
        $q = $_GET["q"] ?? "";
        if (strlen($q) < 3) { echo json_encode([]); return; }
        $search = "%$q%";
        $sql = "SELECT p.id, p.titulo, 
                t1.id as t1_id, CONCAT(t1.nombres, ' ', t1.apellidos) as tesista1,
                t2.id as t2_id, CONCAT(t2.nombres, ' ', t2.apellidos) as tesista2,
                a.id as asesor_id, CONCAT(a.nombres, ' ', a.apellidos) as asesor
                FROM proyectos p
                LEFT JOIN usuarios t1 ON p.id_tesista = t1.id
                LEFT JOIN usuarios t2 ON p.id_tesista_2 = t2.id
                LEFT JOIN usuarios a ON p.id_asesor = a.id
                WHERE p.titulo LIKE ? OR t1.nombres LIKE ? OR t1.dni LIKE ? LIMIT 8";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$search, $search, $search]);
            $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $results = [];
            foreach ($proyectos as $p) {
                $parts = [];
                if ($p["t1_id"]) $parts[] = ["id" => $p["t1_id"], "nombre" => $p["tesista1"], "rol" => "Tesista 1"];
                if ($p["t2_id"]) $parts[] = ["id" => $p["t2_id"], "nombre" => $p["tesista2"], "rol" => "Tesista 2"];
                if ($p["asesor_id"]) $parts[] = ["id" => $p["asesor_id"], "nombre" => $p["asesor"], "rol" => "Asesor"];
                $sqlJ = "SELECT u.id, CONCAT(u.nombres, ' ', u.apellidos) as nombre, ja.rol_jurado FROM jurado_asignaciones ja JOIN usuarios u ON ja.id_jurado = u.id WHERE ja.id_proyecto = ?";
                $stmtJ = $this->db->prepare($sqlJ); $stmtJ->execute([$p["id"]]);
                foreach($stmtJ->fetchAll(PDO::FETCH_ASSOC) as $j) { $parts[] = ["id" => $j["id"], "nombre" => $j["nombre"], "rol" => $j["rol_jurado"]]; }
                $results[] = ["id" => $p["id"], "titulo" => $p["titulo"], "participantes" => $parts];
            }
            echo json_encode($results);
        } catch (Exception $e) { echo json_encode([]); }
    }

    public function get_lineas_por_area() {
        $id_area = (int)($_POST["id_area"] ?? 0);
        $stmt = $this->db->prepare("SELECT id, nombre FROM lineas_investigacion_v2 WHERE id_area = ? ORDER BY nombre ASC");
        $stmt->execute([$id_area]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get_programas() {
        $id_facultad = (int)($_POST["id_facultad"] ?? 0);
        // CORRECCIÓN: Se añadió el campo 'nivel' para evitar el "undefined" en la vista
        $stmt = $this->db->prepare("SELECT id, nombre, nivel FROM programas WHERE id_facultad = ? ORDER BY nombre ASC");
        $stmt->execute([$id_facultad]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function get_asesores_por_linea() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_sub = (int)($_POST['id_sublinea'] ?? 0);
        $sql = "SELECT u.id, u.nombres, u.apellidos FROM usuarios u JOIN docente_sublineas ds ON u.id = ds.id_docente WHERE ds.id_sublinea = ? AND u.activo = 1 ORDER BY u.apellidos ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_sub]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) { echo json_encode([]); }
    }

    public function buscar_tesista_colegiado() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $termino = $_POST['termino'] ?? '';
        $id_actual = (int)($_POST['id_usuario_actual'] ?? 0);
        if (strlen($termino) < 3) { echo json_encode([]); return; }
        
        $stmt_user = $this->db->prepare("SELECT id_linea_investigacion FROM usuarios WHERE id = ?");
        $stmt_user->execute([$id_actual]);
        $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
        $id_linea = $user_data['id_linea_investigacion'] ?? 0;

        $search = "%$termino%";
        $sql = "SELECT id, nombres, apellidos, email, codigo FROM usuarios WHERE id_rol_principal = 1 AND id != ? AND id_linea_investigacion = ? AND (nombres LIKE ? OR apellidos LIKE ? OR email LIKE ? OR codigo LIKE ?) AND activo = 1 LIMIT 10";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_actual, $id_linea, $search, $search, $search, $search]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) { echo json_encode([]); }
    }
}
