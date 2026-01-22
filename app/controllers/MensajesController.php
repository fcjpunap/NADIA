<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once APP_ROOT . '/helpers/Mailer.php';
class MensajesController extends Controller {
    private $db;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) { 
            header('Location: ' . URL_BASE . 'auth/login'); 
            exit; 
        }
        $this->db = (new Database())->connect();
        // Limpieza automática papelera (30 días)
        $this->db->query("DELETE FROM mensaje_destinatarios WHERE carpeta='Papelera' AND borrado_papelera_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
    }
    public function index() {
        $uid = $_SESSION['user_id'];
        $carpeta = $_GET['c'] ?? 'Entrada';
        
        if ($carpeta == 'Enviados') {
            $sql = "SELECT m.*, 
                    (SELECT COUNT(*) FROM mensaje_adjuntos WHERE id_mensaje=m.id) as adjuntos,
                    (SELECT GROUP_CONCAT(CONCAT(u.nombres, ' ', u.apellidos) SEPARATOR ', ') 
                     FROM mensaje_destinatarios md 
                     JOIN usuarios u ON md.id_destinatario=u.id 
                     WHERE md.id_mensaje=m.id) as lista_destinatarios
                    FROM mensajes m 
                    WHERE m.id_remitente=$uid AND m.estado='Enviado' 
                    ORDER BY m.fecha_envio DESC";
        } else {
            $sql = "SELECT m.*, md.leido, md.id as id_relacion, 
                    u.nombres as rem_n, u.apellidos as rem_a,
                    (SELECT COUNT(*) FROM mensaje_adjuntos WHERE id_mensaje=m.id) as adjuntos
                    FROM mensaje_destinatarios md 
                    JOIN mensajes m ON md.id_mensaje=m.id
                    JOIN usuarios u ON m.id_remitente=u.id
                    WHERE md.id_destinatario=$uid AND md.carpeta='$carpeta' 
                    ORDER BY m.fecha_envio DESC";
        }
        
        $msgs = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $this->view('mensajes/index', ['mensajes' => $msgs, 'carpeta' => $carpeta]);
    }
    public function crear() {
        // V102: Lógica de Respuesta (Reply)
        $reply_to = null;
        if (isset($_GET["reply"])) {
            $rid = (int)$_GET["reply"];
            $orig = $this->db->query("SELECT m.*, u.nombres, u.apellidos FROM mensajes m JOIN usuarios u ON m.id_remitente=u.id WHERE m.id=$rid")->fetch(PDO::FETCH_ASSOC);
            if ($orig) {
                $reply_to = [
                    "id" => $orig["id_remitente"],
                    "nombre" => $orig["nombres"] . " " . $orig["apellidos"],
                    "asunto" => (strpos($orig["asunto"], "Re:") === 0) ? $orig["asunto"] : "Re: " . $orig["asunto"]
                ];
            }
        }
        $uid = $_SESSION['user_id'];
        $rol = $_SESSION['rol'];
        $destinatarios = [];
        
        // Lógica según rol (mantener del backup V6)
        if ($rol == 1) { // Tesista
            $sql = "SELECT p.id as idp, p.titulo, 
                    c.id as id_coord, c.nombres as nc, c.apellidos as ac,
                    a.id as id_asesor, a.nombres as na, a.apellidos as aa,
                    u2.id as id_t2, u2.nombres as n2, u2.apellidos as a2
                    FROM proyectos p
                    LEFT JOIN usuarios c ON c.id_rol_principal=4 AND c.id_facultad=p.id_facultad
                    LEFT JOIN usuarios a ON p.id_asesor=a.id
                    LEFT JOIN usuarios u2 ON p.id_tesista_2=u2.id
                    WHERE p.id_tesista=$uid OR p.id_tesista_2=$uid";
            
            $proyectos = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($proyectos as $p) {
                if($p['id_coord']) $destinatarios[$p['id_coord']] = "Coordinador: {$p['nc']} {$p['ac']}";
                if($p['id_asesor']) $destinatarios[$p['id_asesor']] = "Asesor: {$p['na']} {$p['aa']}";
                if($p['id_t2']) $destinatarios[$p['id_t2']] = "Co-Tesista: {$p['n2']} {$p['a2']}";
                
                $jur = $this->db->query("SELECT u.id, u.nombres, u.apellidos FROM jurado_asignaciones ja JOIN usuarios u ON ja.id_jurado=u.id WHERE ja.id_proyecto=".$p['idp'])->fetchAll();
                foreach($jur as $j) $destinatarios[$j['id']] = "Jurado: {$j['nombres']} {$j['apellidos']}";
            }
        } elseif ($rol == 2) { // Docente
            $sqlA = "SELECT t.id, t.nombres, t.apellidos FROM proyectos p JOIN usuarios t ON p.id_tesista=t.id WHERE p.id_asesor=$uid";
            foreach($this->db->query($sqlA)->fetchAll() as $r) $destinatarios[$r['id']] = "Tesista (Asesorado): {$r['nombres']} {$r['apellidos']}";
            
            $sqlJ = "SELECT t.id, t.nombres, t.apellidos FROM jurado_asignaciones ja JOIN proyectos p ON ja.id_proyecto=p.id JOIN usuarios t ON p.id_tesista=t.id WHERE ja.id_jurado=$uid";
            foreach($this->db->query($sqlJ)->fetchAll() as $r) $destinatarios[$r['id']] = "Tesista (Jurado): {$r['nombres']} {$r['apellidos']}";
            
            $sqlC = "SELECT id, nombres, apellidos FROM usuarios WHERE id_rol_principal=4";
            foreach($this->db->query($sqlC)->fetchAll() as $r) $destinatarios[$r['id']] = "Coordinador: {$r['nombres']} {$r['apellidos']}";
        } else { // Admin
            $all = $this->db->query("SELECT id, nombres, apellidos FROM usuarios LIMIT 200")->fetchAll();
            foreach($all as $r) $destinatarios[$r['id']] = "Usuario: {$r['nombres']} {$r['apellidos']}";
        }
        
        // V101: Obtener límite de subida
        $max_size = ini_get('upload_max_filesize');
        
        $this->view('mensajes/crear', [
            'destinatarios' => $destinatarios,
            'max_size' => $max_size, 'reply_to' => $reply_to
        ]);
    }
    public function guardar() {
        if($_POST) {
            $uid = $_SESSION['user_id'];
            $asunto = $_POST['asunto'];
            $cuerpo = $_POST['cuerpo'];
            $dests = $_POST['destinatarios'] ?? [];
            $notificar = isset($_POST['notificar_email']);
            $this->db->prepare("INSERT INTO mensajes (id_remitente, asunto, cuerpo, estado) VALUES (?, ?, ?, 'Enviado')")
                 ->execute([$uid, $asunto, $cuerpo]);
            $msgId = $this->db->lastInsertId();
            // Subida de archivos (segura)
            if (!empty($_FILES['adjuntos']['name'][0])) {
                $uploadDir = '../public/uploads/mensajes_secure/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                    file_put_contents($uploadDir . 'index.html', '');
                }
                $total = count($_FILES['adjuntos']['name']);
                for($i=0; $i < $total; $i++) {
                    $tmpFilePath = $_FILES['adjuntos']['tmp_name'][$i];
                    if ($tmpFilePath != ""){
                        $origName = $_FILES['adjuntos']['name'][$i];
                        $mime = $_FILES['adjuntos']['type'][$i];
                        $safeName = hash('sha256', $origName . time() . uniqid()); 
                        
                        if(move_uploaded_file($tmpFilePath, $uploadDir . $safeName)) {
                            $this->db->prepare("INSERT INTO mensaje_adjuntos (id_mensaje, nombre_original, ruta_archivo, mime_type) VALUES (?, ?, ?, ?)")
                                 ->execute([$msgId, $origName, 'uploads/mensajes_secure/' . $safeName, $mime]);
                        }
                    }
                }
            }
            // Notificaciones por email (URL completa)
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
            $fullUrl = $protocol . $_SERVER['HTTP_HOST'] . URL_BASE . 'mensajes/detalle?id=' . $msgId;
            foreach($dests as $did) {
                $this->db->prepare("INSERT INTO mensaje_destinatarios (id_mensaje, id_destinatario) VALUES (?, ?)")
                     ->execute([$msgId, $did]);
                
                if($notificar) {
                    $u = $this->db->query("SELECT email, nombres FROM usuarios WHERE id=$did")->fetch();
                    if($u && $u['email']) {
                        Mailer::enviar(
                            $u['email'], 
                            "Nuevo Mensaje: $asunto", 
                            "Hola {$u['nombres']},<br>Tienes un nuevo mensaje en NADIA.<br><br><strong>Asunto:</strong> $asunto<br><br>Para ver el mensaje y descargar adjuntos ingresa aquí: <a href='$fullUrl'>$fullUrl</a>"
                        );
                    }
                }
            }
            
            header('Location: ' . URL_BASE . 'mensajes/index?msg=Mensaje enviado correctamente');
        }
    }
    
    public function detalle() {
        $id = $_GET['id'];
        $uid = $_SESSION['user_id'];
        
        $msg = $this->db->query("SELECT m.*, u.nombres, u.apellidos, u.email FROM mensajes m JOIN usuarios u ON m.id_remitente=u.id WHERE m.id=$id")->fetch(PDO::FETCH_ASSOC);
        if (!$msg) { echo "Mensaje no encontrado"; return; }
        $dest_sql = "SELECT u.nombres, u.apellidos, md.leido FROM mensaje_destinatarios md JOIN usuarios u ON md.id_destinatario=u.id WHERE md.id_mensaje=$id";
        $destinatarios = $this->db->query($dest_sql)->fetchAll(PDO::FETCH_ASSOC);
        
        $soy_remitente = ($msg['id_remitente'] == $uid);
        
        $check = $this->db->prepare("SELECT * FROM mensaje_destinatarios WHERE id_mensaje=? AND id_destinatario=?");
        $check->execute([$id, $uid]);
        $rel = $check->fetch();
        
        if (!$soy_remitente && !$rel) die("Acceso denegado.");
        
        if ($rel && !$rel['leido']) {
            $this->db->prepare("UPDATE mensaje_destinatarios SET leido=1, fecha_lectura=NOW() WHERE id_mensaje=? AND id_destinatario=?")
                 ->execute([$id, $uid]);
        }
        
        $adjs = $this->db->query("SELECT * FROM mensaje_adjuntos WHERE id_mensaje=$id")->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('mensajes/detalle', [
            'm' => $msg, 
            'adjuntos' => $adjs, 
            'destinatarios' => $destinatarios,
            'soy_remitente' => $soy_remitente
        ]);
    }
    
    public function descargar() {
        $id_adj = $_GET['id'];
        $uid = $_SESSION['user_id'];
        
        $adj = $this->db->query("SELECT * FROM mensaje_adjuntos WHERE id=$id_adj")->fetch(PDO::FETCH_ASSOC);
        if(!$adj) die("Archivo no encontrado");
        
        $msgId = $adj['id_mensaje'];
        $permiso = $this->db->prepare("SELECT 1 FROM mensajes m LEFT JOIN mensaje_destinatarios md ON m.id=md.id_mensaje WHERE m.id=? AND (m.id_remitente=? OR md.id_destinatario=?) LIMIT 1");
        $permiso->execute([$msgId, $uid, $uid]);
        
        if(!$permiso->fetchColumn()) die("Acceso denegado.");
        
        $ruta_fisica = '../public/' . $adj['ruta_archivo']; 
        if(!file_exists($ruta_fisica)) die("El archivo no existe.");
        
        header("Content-Description: File Transfer");
        header("Content-Type: " . ($adj['mime_type'] ?: 'application/octet-stream'));
        header("Content-Disposition: attachment; filename=\"" . $adj['nombre_original'] . "\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($ruta_fisica));
        readfile($ruta_fisica);
        exit;
    }
    // V101: NUEVO - Restaurar desde Papelera
    public function restaurar() {
        $id = $_GET['id'];
        $uid = $_SESSION['user_id'];
        
        $this->db->prepare("UPDATE mensaje_destinatarios SET carpeta='Entrada', borrado_papelera_at=NULL WHERE id_mensaje=? AND id_destinatario=?")
             ->execute([$id, $uid]);
        
        header('Location: ' . URL_BASE . 'mensajes/index?c=Entrada&msg=Mensaje restaurado correctamente');
    }
    public function eliminar() {
        $id = $_GET['id'];
        $uid = $_SESSION['user_id'];
        
        $this->db->prepare("UPDATE mensaje_destinatarios SET carpeta='Papelera', borrado_papelera_at=NOW() WHERE id_mensaje=? AND id_destinatario=?")
             ->execute([$id, $uid]);
        
        header('Location: ' . URL_BASE . 'mensajes/index');
    }
}
