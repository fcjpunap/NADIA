<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once APP_ROOT . '/helpers/PlazoHelper.php';
class TesistaController extends Controller
{
    private $db;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_BASE . 'auth/login');
            exit;
        }
        $this->db = (new Database())->connect();
    }
    public function dashboard()
    {
        $id = $_SESSION["user_id"];
        $stmt = $this->db->prepare("SELECT p.*, l.nombre as linea_nombre, u.nombres as an, u.apellidos as aa FROM proyectos p LEFT JOIN lineas_investigacion_v2 l ON p.id_linea_investigacion=l.id LEFT JOIN usuarios u ON p.id_asesor=u.id WHERE id_tesista=? OR id_tesista_2=?");
        $stmt->execute([$id, $id]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        $docs_proy = [];
        $docs_borr = [];
        $historial = [];
        $jurados = [];
        $acta_proy = false;
        $acta_borr = false;
        $accion_boton = "ninguno";

        if ($p) {
            $p["asesor_nombre"] = ($p["an"]) ? $p["an"] . " " . $p["aa"] : "Pendiente";
            $etapa = ($p["id_etapa_actual"] >= 3) ? "Sustentacion" : (($p["id_etapa_actual"] == 2) ? "Borrador" : "Proyecto");

            $all_docs = $this->db->prepare("SELECT * FROM documentos WHERE id_proyecto=? ORDER BY created_at DESC");
            $all_docs->execute([$p["id"]]);
            foreach ($all_docs->fetchAll(PDO::FETCH_ASSOC) as $d) {
                if (strpos($d["tipo_documento"], "Proyecto") !== false || strpos($d["tipo_documento"], "Grado") !== false)
                    $docs_proy[] = $d;
                else
                    $docs_borr[] = $d;
            }

            $sqlJ = "SELECT ja.rol_jurado, u.nombres, u.apellidos, ja.id_jurado,
                     (SELECT resultado FROM dictamenes d WHERE d.id_proyecto=ja.id_proyecto AND d.id_jurado=ja.id_jurado AND d.etapa=\"$etapa\" ORDER BY d.id DESC LIMIT 1) as ultimo_dictamen 
                     FROM jurado_asignaciones ja JOIN usuarios u ON ja.id_jurado=u.id 
                     WHERE ja.id_proyecto=?";
            $stmtJ = $this->db->prepare($sqlJ);
            $stmtJ->execute([$p["id"]]);
            $jurados = $stmtJ->fetchAll(PDO::FETCH_ASSOC);

            $num_fin = $this->db->query("SELECT COUNT(*) FROM observaciones_finalizadas WHERE id_proyecto={$p["id"]} AND etapa=\"$etapa\"")->fetchColumn();
            $total_revisores = count($jurados) + 1;
            $todos_listos = ($num_fin >= $total_revisores);

            $v_proy = $this->db->query("SELECT COUNT(*) FROM dictamenes WHERE id_proyecto={$p["id"]} AND etapa=\"Proyecto\" AND resultado=\"Aprobado\"")->fetchColumn();
            $v_borr = $this->db->query("SELECT COUNT(*) FROM dictamenes WHERE id_proyecto={$p["id"]} AND etapa=\"Borrador\" AND resultado=\"Aprobado\"")->fetchColumn();
            $acta_proy = ($p["id_etapa_actual"] >= 2 || $v_proy >= 2);
            $acta_borr = ($p["id_etapa_actual"] >= 3 || $v_borr >= 2);

            if ($p["estado"] != "Iniciado") {
                if ($p["id_etapa_actual"] == 1) {
                    if ($p["estado"] == "Aprobado" || $acta_proy)
                        $accion_boton = "grado";
                    elseif ($p["estado"] == "Observado" || $todos_listos)
                        $accion_boton = "proyecto_correccion";
                } elseif ($p["id_etapa_actual"] == 2) {
                    if ($p["estado"] == "Aprobado" || $acta_borr) {
                        $tiene_sust = false;
                        foreach ($docs_borr as $db)
                            if (strpos($db["tipo_documento"], "Sustentacion") !== false || strpos($db["tipo_documento"], "Sustentación") !== false)
                                $tiene_sust = true;
                        if (!$tiene_sust)
                            $accion_boton = "grado";
                        else
                            $accion_boton = "ninguno";
                    } elseif ($p["estado"] == "Observado" || $todos_listos) {
                        $accion_boton = "borrador_correccion";
                    } else {
                        $tiene_borrador = false;
                        foreach ($docs_borr as $db) {
                            if (strpos($db["tipo_documento"], "Borrador Tesis") !== false) {
                                $tiene_borrador = true;
                                break;
                            }
                        }
                        $accion_boton = (!$tiene_borrador && $p["autorizado_borrador"] == 1) ? "borrador" : "ninguno";
                    }
                }
            }

            $h = $this->db->prepare("SELECT h.*, u.nombres FROM historial_movimientos h LEFT JOIN usuarios u ON h.id_usuario=u.id WHERE id_proyecto=? ORDER BY fecha DESC");
            $h->execute([$p["id"]]);
            $historial = $h->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->view("tesista/dashboard", [
            "nombre" => $_SESSION["nombres"],
            "proyecto" => $p,
            "docs_proy" => $docs_proy,
            "docs_borr" => $docs_borr,
            "accion_boton" => $accion_boton,
            "actas" => ["proyecto" => $acta_proy, "borrador" => $acta_borr],
            "jurados" => $jurados,
            "historial" => $historial
        ]);
    }
    public function subir_correccion()
    {
        if ($_POST) {
            $pid = $_POST['id_proyecto'];
            $tipo_base = $_POST['tipo_archivo'];
            if (!empty($_FILES['archivo_pdf']['name'])) {
                $n = time() . '.pdf';
                move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], '../public/uploads/tesis/' . $n);
                $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,?,?,?,'application/pdf')")->execute([$pid, $_SESSION['user_id'], $tipo_base . ' (PDF)', $_FILES['archivo_pdf']['name'], 'uploads/tesis/' . $n]);
            }
            if (!empty($_FILES['archivo_word']['name'])) {
                $n = time() . 'w.docx';
                move_uploaded_file($_FILES['archivo_word']['tmp_name'], '../public/uploads/tesis/' . $n);
                $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,?,?,?,'application/msword')")->execute([$pid, $_SESSION['user_id'], $tipo_base . ' (Word)', $_FILES['archivo_word']['name'], 'uploads/tesis/' . $n]);
            }
            if (!empty($_FILES['archivo_correccion']['name'])) {
                $ext = pathinfo($_FILES['archivo_correccion']['name'], PATHINFO_EXTENSION);
                $n = time() . '_c.' . $ext;
                move_uploaded_file($_FILES['archivo_correccion']['tmp_name'], '../public/uploads/tesis/' . $n);
                $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,?,?,?,'application/pdf')")->execute([$pid, $_SESSION['user_id'], $tipo_base, $_FILES['archivo_correccion']['name'], 'uploads/tesis/' . $n]);
            }

            // Lógica de actualización según tipo de archivo
            if (strpos($tipo_base, 'Corrección') !== false) {
                // Marcar observaciones previas como antiguas al subir corrección
                $etapa_obs = (strpos($tipo_base, 'Borrador') !== false) ? 'Borrador' : 'Proyecto';
                $this->db->prepare("UPDATE observaciones SET es_antiguo = 1 WHERE id_proyecto = ? AND tipo_observacion = ?")->execute([$pid, $etapa_obs]);
                $this->db->prepare("UPDATE proyectos SET estado='En Revisión',requiere_correccion=0 WHERE id=?")->execute([$pid]);
                // Reiniciar el ciclo de revisión de los jurados
                $this->db->prepare("DELETE FROM observaciones_finalizadas WHERE id_proyecto=? AND etapa=?")->execute([$pid, $etapa_obs]);
                $detalle_historial = "Corrección de " . str_replace('Corrección ', '', $tipo_base);
            } elseif (strpos($tipo_base, 'Requisitos de Grado') !== false) {
                // Cuando se suben requisitos, pasar a etapa 2 (Borrador)
                $this->db->prepare("UPDATE proyectos SET id_etapa_actual=2, estado='En Revisión' WHERE id=?")->execute([$pid]);

                if ($p['id_etapa_actual'] >= 2) {
                    $detalle_historial = "Requisitos de Sustentacion";
                    $tipo_base = "Requisitos de Sustentacion";
                } else {
                    $detalle_historial = "Requisitos de Grado";
                    $tipo_base = "Requisitos de Grado";
                }

            } elseif (strpos($tipo_base, 'Borrador') !== false) {
                $this->db->prepare("UPDATE proyectos SET id_etapa_actual=2, estado='En Revisión' WHERE id=?")->execute([$pid]);
                $detalle_historial = "Borrador de Tesis";
            } else {
                $detalle_historial = $tipo_base;
            }

            $this->db->prepare("INSERT INTO historial_movimientos (id_proyecto,id_usuario,accion,detalle) VALUES (?,?,'SUBIDA',?)")->execute([$pid, $_SESSION['user_id'], $detalle_historial]);
        }
        header('Location: ' . URL_BASE . 'tesista/dashboard?msg=ok');
    }

    public function nuevo_proyecto()
    {
        if (file_exists('../app/models/Jerarquia.php')) {
            require_once '../app/models/Jerarquia.php';
            $a = (new Jerarquia())->obtenerArbol();
        } else {
            $a = [];
        }

        $uid = $_SESSION['user_id'];
        $u_data = $this->db->query("SELECT id_linea_investigacion, id_area_investigacion FROM usuarios WHERE id=$uid")->fetch(PDO::FETCH_ASSOC);

        $user_linea = $u_data['id_linea_investigacion'] ?? null;
        $user_linea_nombre = "No definida";
        if ($user_linea) {
            $stmt = $this->db->prepare("SELECT nombre FROM lineas_investigacion_v2 WHERE id=?");
            $stmt->execute([$user_linea]);
            $user_linea_nombre = $stmt->fetchColumn();
            $stmtS = $this->db->prepare("SELECT * FROM sublineas_investigacion WHERE id_linea=? ORDER BY nombre ASC");
            $stmtS->execute([$user_linea]);
            $sublineas = $stmtS->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sublineas = [];
        }
        $d = $this->db->query("SELECT id,nombres,apellidos FROM usuarios WHERE id_rol_principal=2")->fetchAll();
        $this->view('tesista/nuevo_proyecto', [
            'areas' => $a,
            'docentes' => $d,
            'user_linea_id' => $user_linea,
            'user_linea_nombre' => $user_linea_nombre,
            'sublineas_disponibles' => $sublineas
        ]);
    }

    public function guardar_proyecto()
    {
        if ($_POST) {
            $uuid = bin2hex(random_bytes(8));

            $uid = $_SESSION['user_id'];
            $u_data = $this->db->query("SELECT u.id_facultad, u.id_programa, f.nombre as nom_fac, p.nombre as nom_prog, u.facultad_asignada 
                                        FROM usuarios u 
                                        LEFT JOIN facultades f ON u.id_facultad=f.id 
                                        LEFT JOIN programas p ON u.id_programa=p.id 
                                        WHERE u.id=$uid")->fetch(PDO::FETCH_ASSOC);

            $id_f = $u_data['id_facultad'];
            $id_p = $u_data['id_programa'];
            $nom_f = $u_data['nom_fac'] ?? $u_data['facultad_asignada'];
            $nom_p = $u_data['nom_prog'];

            // FIX V92: Tesista 2
            $idt2 = $_POST['id_tesista_2'] ?? null;
            if ($idt2 === '')
                $idt2 = null;
            $sql = "INSERT INTO proyectos (uuid,titulo,resumen,id_linea_investigacion,id_tesista,id_tesista_2,id_asesor,estado, id_facultad, facultad, id_programa, programa) 
                    VALUES (?,?,?,?,?,?,?, 'Iniciado', ?, ?, ?, ?)";

            $this->db->prepare($sql)->execute([
                $uuid,
                $_POST['titulo'],
                $_POST['resumen'],
                $_POST['sublinea'],
                $uid,
                $idt2,
                $_POST['asesor'],
                $id_f,
                $nom_f,
                $id_p,
                $nom_p
            ]);
            $pid = $this->db->lastInsertId();
            if (!empty($_FILES['archivo_pdf']['name'])) {
                $t = time();
                $n = $t . '.pdf';
                move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], '../public/uploads/tesis/' . $n);
                $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,'Proyecto (PDF)',?,?,'application/pdf')")->execute([$pid, $_SESSION['user_id'], $_FILES['archivo_pdf']['name'], 'uploads/tesis/' . time() . '.pdf']);
            }
            if (!empty($_FILES['archivo_word']['name'])) {
                if (!empty($_FILES['archivo_correccion']['name'])) {
                    $n = time() . '_c.pdf';
                    move_uploaded_file($_FILES['archivo_correccion']['name'], '../public/uploads/tesis/' . $n);
                    $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,?,?,?,'application/pdf')")->execute([$pid, $_SESSION['user_id'], $tipo_base . ' (PDF)', $_FILES['archivo_correccion']['name'], 'uploads/tesis/' . $n]);
                }
                move_uploaded_file($_FILES['archivo_word']['tmp_name'], '../public/uploads/tesis/' . time() . 'w.docx');
                $this->db->prepare("INSERT INTO documentos (id_proyecto,id_usuario_sube,tipo_documento,nombre_archivo_original,ruta_archivo,mime_type) VALUES (?,?,'Proyecto (Word)',?,?,'application/msword')")->execute([$pid, $_SESSION['user_id'], $_FILES['archivo_word']['name'], 'uploads/tesis/' . 'w.docx']);
            }
            $this->db->prepare("INSERT INTO historial_movimientos (id_proyecto,id_usuario,accion,detalle) VALUES (?,?,'REGISTRO','Creación de expediente')")->execute([$pid, $_SESSION['user_id']]);
            header('Location: ' . URL_BASE . 'tesista/dashboard');
        }
    }
    public function api_get_comentarios()
    {
        ob_clean();
        header('Content-Type: application/json');
        $in = json_decode(file_get_contents('php://input'), true);
        $t = ($in['fase'] == 'Borrador') ? 'Borrador' : 'Proyecto';
        $s = $this->db->prepare("SELECT o.*,u.nombres,CASE WHEN o.id_jurado=p.id_asesor THEN 'Asesor' ELSE 'Jurado' END as rol_real FROM observaciones o LEFT JOIN usuarios u ON o.id_jurado=u.id LEFT JOIN proyectos p ON o.id_proyecto=p.id WHERE o.id_proyecto=? AND o.pagina=? AND o.tipo_observacion=? ORDER BY o.fecha_observacion DESC");
        $s->execute([$in['id_proyecto'], $in['pagina'], $t]);
        echo json_encode($s->fetchAll(PDO::FETCH_ASSOC));
    }
    public function ver_observaciones()
    {
        $f = $_GET['fase'] ?? 'Proyecto';
        $p = $this->db->query("SELECT * FROM proyectos WHERE id_tesista=" . $_SESSION['user_id'] . " OR id_tesista_2=" . $_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
        $l = ($f == 'Borrador') ? '%Borrador%' : '%Proyecto%';
        $d = $this->db->prepare("SELECT * FROM documentos WHERE id_proyecto=? AND mime_type='application/pdf' AND tipo_documento LIKE ? ORDER BY created_at DESC LIMIT 1");
        $d->execute([$p['id'], $l]);
        $doc = $d->fetch(PDO::FETCH_ASSOC);
        $this->view('tesista/ver_observaciones', ['proyecto' => $p, 'documento' => $doc, 'fase' => $f]);
    }

    public function imprimir_ficha()
    {
        $uid = $_SESSION['user_id'];
        $id_proyecto_admin = $_GET['id_admin'] ?? null;

        $condicion = "(p.id_tesista=$uid OR p.id_tesista_2=$uid)";
        if ($id_proyecto_admin && ($_SESSION['rol'] == 3 || $_SESSION['rol'] == 4)) {
            $condicion = "p.id=$id_proyecto_admin";
        }

        // Obtener nombre de la institución desde configuración
        $inst = $this->db->query("SELECT valor FROM configuraciones WHERE clave='nombre_institucion'")->fetchColumn();
        if (!$inst)
            $inst = "UNIVERSIDAD NACIONAL DEL ALTIPLANO";
        $sql = "SELECT p.*, 
                t1.nombres as t1_nombres, t1.apellidos as t1_apellidos, t1.codigo as t1_codigo, t1.dni as t1_dni, t1.telefono as t1_telefono, t1.email as t1_email,
                t2.nombres as t2_nombres, t2.apellidos as t2_apellidos, t2.codigo as t2_codigo, t2.dni as t2_dni, t2.telefono as t2_telefono, t2.email as t2_email,
                a.nombres as asesor_nombres, a.apellidos as asesor_apellidos, a.grado_academico as asesor_grado,
                l.nombre as linea_nombre, 
                sl.nombre as sublinea_nombre,
                COALESCE(ft1.nombre, f.nombre, p.facultad) as facultad_nombre, 
                COALESCE(pr.nombre, p.programa, t1pr.nombre) as programa_nombre
                FROM proyectos p 
                LEFT JOIN usuarios t1 ON p.id_tesista=t1.id 
                LEFT JOIN usuarios t2 ON p.id_tesista_2=t2.id
                LEFT JOIN facultades ft1 ON t1.id_facultad=ft1.id
                LEFT JOIN sublineas_investigacion sl ON p.id_linea_investigacion=sl.id
                LEFT JOIN lineas_investigacion_v2 l ON sl.id_linea=l.id 
                LEFT JOIN facultades f ON p.id_facultad=f.id
                LEFT JOIN programas pr ON p.id_programa=pr.id
                LEFT JOIN programas t1pr ON t1.id_programa=t1pr.id
                LEFT JOIN usuarios a ON p.id_asesor=a.id
                WHERE $condicion";

        $p = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($p) {
            $sqlJ = "SELECT ja.rol_jurado, CONCAT(u.nombres, ' ', u.apellidos) as nombre_completo 
                     FROM jurado_asignaciones ja 
                     JOIN usuarios u ON ja.id_jurado=u.id 
                     WHERE ja.id_proyecto=" . $p['id'] . "
                     ORDER BY FIELD(ja.rol_jurado, 'Presidente', 'Primer Miembro', 'Segundo Miembro', 'Tercer Miembro', 'Secretario', 'Vocal')";
            $jurados = $this->db->query($sqlJ)->fetchAll(PDO::FETCH_ASSOC);

            $this->view('tesista/ficha_imprimible', [
                'proyecto' => $p,
                'jurados' => $jurados,
                'institucion' => $inst
            ]);
        } else {
            $this->view('tesista/ficha_imprimible', ['proyecto' => null]);
        }
    }
}