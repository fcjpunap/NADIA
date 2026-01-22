<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
class ReportesController extends Controller {
    private $db;
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    public function index() {
        // [KPIs Dashboard Principal]
        $totalProyectos = $this->db->query("SELECT COUNT(*) FROM proyectos")->fetchColumn();
        $totalSustentados = $this->db->query("SELECT COUNT(*) FROM proyectos WHERE id_etapa_actual >= 3 AND (estado = 'Sustentado' OR estado = 'Aprobado')")->fetchColumn();
        $totalTesistas = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE id_rol_principal = 1")->fetchColumn();
        $totalDocentes = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE id_rol_principal = 2")->fetchColumn();
        
        $estadosData = $this->db->query("SELECT estado, COUNT(*) as total FROM proyectos GROUP BY estado")->fetchAll(PDO::FETCH_ASSOC);
        
        // Rankings
        $rankAsesores = $this->db->query("SELECT CONCAT(u.nombres, ' ', u.apellidos) as etiqueta, COUNT(p.id) as total FROM proyectos p JOIN usuarios u ON p.id_asesor = u.id GROUP BY u.id ORDER BY total DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        $rankJurados = $this->db->query("SELECT CONCAT(u.nombres, ' ', u.apellidos) as etiqueta, COUNT(ja.id_proyecto) as total FROM jurado_asignaciones ja JOIN usuarios u ON ja.id_jurado = u.id GROUP BY u.id ORDER BY total DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        $rankFacs = $this->db->query("SELECT COALESCE(f.nombre, p.facultad) as etiqueta, COUNT(p.id) as total FROM proyectos p LEFT JOIN facultades f ON p.id_facultad = f.id GROUP BY etiqueta ORDER BY total DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        $recientes = $this->db->query("SELECT p.titulo, h.accion, h.fecha, CONCAT(u.nombres, ' ', u.apellidos) as usuario FROM historial_movimientos h JOIN proyectos p ON h.id_proyecto = p.id JOIN usuarios u ON h.id_usuario = u.id ORDER BY h.fecha DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
        $this->view("admin/reportes/index", [
            'stats' => [
                'total' => $totalProyectos, 'sustentados' => $totalSustentados, 
                'tesistas' => $totalTesistas, 'docentes' => $totalDocentes,
                'estados' => $estadosData, 'recientes' => $recientes,
                'rankings' => ['asesores' => $rankAsesores, 'jurados' => $rankJurados, 'facultades' => $rankFacs]
            ]
        ]);
    }

    public function constructor() {
        // Parámetros
        $anio = $_GET["anio"] ?? "total";
        $semestre = $_GET["semestre"] ?? "";
        $mes = $_GET["mes"] ?? "";
        $etapa = $_GET["etapa"] ?? "";
        $facultad = $_GET["facultad"] ?? "";
        $area = $_GET["area"] ?? "";
        $linea = $_GET["linea"] ?? "";
        $sublinea = $_GET["sublinea"] ?? "";
        
        // Construccion WHERE
        $where = " WHERE 1=1 ";
        if($anio != "total") $where .= " AND YEAR(p.created_at) = " . (int)$anio;
        if($semestre == "1") $where .= " AND MONTH(p.created_at) BETWEEN 1 AND 7 ";
        if($semestre == "2") $where .= " AND MONTH(p.created_at) BETWEEN 8 AND 12 ";
        if($mes != "") $where .= " AND MONTH(p.created_at) = " . (int)$mes;
        if($etapa != "") $where .= " AND p.id_etapa_actual = " . (int)$etapa;
        if($facultad != "") $where .= " AND (p.id_facultad = " . (int)$facultad . " OR p.facultad LIKE '%$facultad%')";
        if($area != "") $where .= " AND ar.id = " . (int)$area;
        if($linea != "") $where .= " AND li.id = " . (int)$linea;
        if($sublinea != "") $where .= " AND sl.id = " . (int)$sublinea;

        $sql = "SELECT p.*, f.nombre as fac_nombre, pr.nombre as prog_nombre,
                       CONCAT(u1.apellidos, ', ', u1.nombres) as tesista,
                       CONCAT(u2.apellidos, ', ', u2.nombres) as cotesista,
                       CONCAT(ua.apellidos, ', ', ua.nombres) as asesor,
                       ar.nombre as area, li.nombre as linea_nombre, sl.nombre as sublinea,
                       (SELECT GROUP_CONCAT(CONCAT(uj.nombres, ' ', uj.apellidos, ' (', ja.rol_jurado, ')') SEPARATOR ' | ') FROM jurado_asignaciones ja JOIN usuarios uj ON ja.id_jurado = uj.id WHERE ja.id_proyecto = p.id) as jurados
                FROM proyectos p
                LEFT JOIN usuarios u1 ON p.id_tesista = u1.id
                LEFT JOIN usuarios u2 ON p.id_tesista_2 = u2.id
                LEFT JOIN usuarios ua ON p.id_asesor = ua.id
                LEFT JOIN facultades f ON p.id_facultad = f.id
                LEFT JOIN programas pr ON p.id_programa = pr.id
                LEFT JOIN sublineas_investigacion sl ON p.id_linea_investigacion = sl.id
                LEFT JOIN lineas_investigacion_v2 li ON sl.id_linea = li.id
                LEFT JOIN areas_investigacion ar ON li.id_area = ar.id
                $where ORDER BY p.created_at DESC";
        
        $proyectos = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $aprobados = count(array_filter($proyectos, function($p){ return strpos(strtoupper($p['estado']), 'APROBADO')!==false; }));
        $total = count($proyectos);
        $anios = $this->db->query("SELECT DISTINCT YEAR(created_at) as anio FROM proyectos ORDER BY anio DESC")->fetchAll(PDO::FETCH_COLUMN);
        $facultades = $this->db->query("SELECT id, nombre FROM facultades ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
        $areas = $this->db->query("SELECT id, nombre FROM areas_investigacion ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
        
        $lineas = ($area != "") ? $this->db->query("SELECT id, nombre FROM lineas_investigacion_v2 WHERE id_area = " . (int)$area . " ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) : [];
        $sublineas = ($linea != "") ? $this->db->query("SELECT id, nombre FROM sublineas_investigacion WHERE id_linea = " . (int)$linea . " ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC) : [];

        $this->view("admin/reportes/constructor", [
            'proyectos' => $proyectos, 'facultades' => $facultades, 'anios' => $anios,
            'areas' => $areas, 'lineas' => $lineas, 'sublineas' => $sublineas,
            'stats_calidad' => ['aprobados' => $aprobados, 'observados' => $total - $aprobados, 'exito' => ($total>0?round(($aprobados/$total)*100,1):0)],
            'filtros' => ['anio' => $anio, 'mes' => $mes, 'semestre' => $semestre, 'etapa' => $etapa, 'facultad' => $facultad, 'area' => $area, 'linea' => $linea, 'sublinea' => $sublinea]
        ]);
    }

    public function acta_imprimible() {
        $id = (int)($_GET["id"] ?? 0); $tipo_busqueda = strtolower($_GET["tipo"] ?? "proyecto"); $cvd_input = $_GET["cvd"] ?? "";
        if (strpos($tipo_busqueda, "sustentacion") !== false) { $tplT = "Acta Sustentacion"; $etapa_busqueda = "Sustentacion"; }
        elseif (strpos($tipo_busqueda, "borrador") !== false) { $tplT = "Acta Borrador"; $etapa_busqueda = "Borrador"; }
        else { $tplT = "Acta Proyecto"; $etapa_busqueda = "Proyecto"; }

        $sql = "SELECT p.*, f.nombre as fac_nombre, pr.nombre as prog_nombre, u1.nombres as t1_n, u1.apellidos as t1_a, u2.nombres as t2_n, u2.apellidos as t2_a, ua.nombres as as_n, ua.apellidos as as_a FROM proyectos p LEFT JOIN facultades f ON p.id_facultad = f.id LEFT JOIN programas pr ON p.id_programa = pr.id LEFT JOIN usuarios u1 ON p.id_tesista = u1.id LEFT JOIN usuarios u2 ON p.id_tesista_2 = u2.id LEFT JOIN usuarios ua ON p.id_asesor = ua.id WHERE p.id = $id";
        $proyecto = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$proyecto) die("Error.");

        $cvd_key = "ACTA_" . $id . "_" . $tplT; $cvd_calc = strtoupper(substr(md5($cvd_key), 0, 16)); $final_cvd = $cvd_input ?: $cvd_calc;
        $this->db->prepare("INSERT IGNORE INTO constancias_emitidas (id_usuario, cvd) VALUES (?, ?)")->execute([-$id, $final_cvd]);

        $sqlRes = "SELECT resultado, fecha_emision FROM dictamenes WHERE id_proyecto = ? AND etapa LIKE ? ORDER BY fecha_emision DESC LIMIT 1";
        $stmtRes = $this->db->prepare($sqlRes); $stmtRes->execute([$id, "%$etapa_busqueda%"]); $rowDictamen = $stmtRes->fetch(PDO::FETCH_ASSOC);
        $fecha_para_acta = $rowDictamen ? $rowDictamen["fecha_emision"] : date("Y-m-d H:i:s");

        $stmtP = $this->db->prepare("SELECT contenido FROM plantillas WHERE tipo = ? OR nombre LIKE ? ORDER BY id DESC LIMIT 1"); $stmtP->execute([$tplT, "%$tplT%"]); $plantilla = $stmtP->fetchColumn() ?: "<h2>$tplT</h2>";
        $inst = $this->db->query("SELECT valor FROM configuraciones WHERE clave='nombre_institucion' LIMIT 1")->fetchColumn() ?: "UNIVERSIDAD NACIONAL DEL ALTIPLANO";
        $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
        $fU = strtotime($fecha_para_acta); $fechaTxt = date("d", $fU) . " de " . $meses[date("n", $fU)-1] . " de " . date("Y", $fU);

        // Variables de Sustentación
        $fSustRaw = $proyecto["fecha_sustentacion"] ?? null;
        $fSustTexto = "por programar";
        if ($fSustRaw && $fSustRaw != "0000-00-00") {
            $ts = strtotime($fSustRaw);
            $fSustTexto = date("d", $ts) . " de " . $meses[date("n", $ts) - 1] . " de " . date("Y", $ts);
        }
        $hSustTexto = (!empty($proyecto["hora_sustentacion"]) && $proyecto["hora_sustentacion"] != "00:00:00") ? $proyecto["hora_sustentacion"] : "por programar";
        $lSustTexto = (!empty(trim($proyecto["lugar_sustentacion"] ?? ""))) ? $proyecto["lugar_sustentacion"] : "por programar";

        // Buscar Coordinador de la Facultad
        $stmtC = $this->db->prepare("SELECT CONCAT(nombres, ' ', apellidos) FROM usuarios WHERE id_rol_principal = 4 AND id_facultad = ? LIMIT 1");
        $stmtC->execute([$proyecto['id_facultad']]);
        $nomCoordinador = $stmtC->fetchColumn();
        if (!$nomCoordinador) {
            $nomCoordinador = $this->db->query("SELECT CONCAT(nombres, ' ', apellidos) FROM usuarios WHERE id_rol_principal = 4 LIMIT 1")->fetchColumn() ?: "POR ASIGNAR";
        }

        $vars = [
            "[institucion]" => strtoupper($inst), "[facultad]" => $proyecto["fac_nombre"], "[programa]" => $proyecto["prog_nombre"], "[titulo]" => $proyecto["titulo"], "[tesista]" => $proyecto["t1_a"] . ", " . $proyecto["t1_n"], "[cotesista]" => ($proyecto["t2_a"]) ? $proyecto["t2_a"] . ", " . $proyecto["t2_n"] : "", "[asesor]" => $proyecto["as_a"] . ", " . $proyecto["as_n"], "[resultado]" => ($rowDictamen?strtoupper($rowDictamen["resultado"]):"APROBADO"), "[fecha]" => $fechaTxt,
            "[fecha_sustentacion]" => $fSustTexto, "[hora_sustentacion]" => $hSustTexto, "[lugar_sustentacion]" => $lSustTexto,
            "[coordinador]" => strtoupper($nomCoordinador)
        ];

        $jurados = $this->db->query("SELECT u.apellidos, u.nombres, ja.rol_jurado FROM jurado_asignaciones ja JOIN usuarios u ON ja.id_jurado = u.id WHERE ja.id_proyecto = $id")->fetchAll(PDO::FETCH_ASSOC);
        foreach($jurados as $j) { $nom = $j["apellidos"] . ", " . $j["nombres"]; if (stripos($j["rol_jurado"], "Presidente") !== false) $vars["[presidente]"] = $nom; if (stripos($j["rol_jurado"], "Primer") !== false) $vars["[primer_miembro]"] = $nom; if (stripos($j["rol_jurado"], "Segundo") !== false) $vars["[segundo_miembro]"] = $nom; }
        
        $html = str_replace(array_keys($vars), array_values($vars), $plantilla);
        $data = ["proyecto" => $proyecto, "contenido" => $html, "cvd" => $final_cvd];
        include "../app/views/admin/actas/imprimir.php";
    }

    public function expediente_imprimible() {
        $id = (int)$_GET["id"]; $fase = $_GET["fase"] ?? "Proyecto";
        $py = $this->db->query("SELECT p.*, IFNULL(t.nombres,'') as t_nom, IFNULL(t.apellidos,'') as t_ape FROM proyectos p LEFT JOIN usuarios t ON p.id_tesista=t.id WHERE p.id=$id")->fetch(PDO::FETCH_ASSOC);
        $like = ($fase == "Borrador") ? "%Borrador%" : "%Proyecto%";
        $pdf = $this->db->query("SELECT * FROM documentos WHERE id_proyecto=$id AND mime_type=\"application/pdf\" AND tipo_documento LIKE '$like' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        if (!$pdf) $pdf = $this->db->query("SELECT * FROM documentos WHERE id_proyecto=$id AND mime_type=\"application/pdf\" ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $observaciones = $this->db->query("SELECT o.*, u.nombres, u.apellidos, o.rol_autor FROM observaciones o LEFT JOIN usuarios u ON o.id_jurado=u.id WHERE id_proyecto=$id AND tipo_observacion='$fase' ORDER BY pagina ASC, fecha_observacion ASC")->fetchAll(PDO::FETCH_ASSOC);
        $porPagina = []; foreach ($observaciones as $o) $porPagina[$o["pagina"]][] = $o;
        $this->view("common/expediente_print", ["proyecto" => $py, "paginas" => $porPagina, "documento" => $pdf, "fase" => $fase]);
    }
}
