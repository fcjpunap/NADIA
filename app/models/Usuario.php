<?php
require_once APP_ROOT . '/core/Database.php';
class Usuario {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if($row && password_verify($password, $row['password_hash'])) return $row;
        return false;
    }
    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function actualizar_full($id, $d) {
        $campos = "nombres=:n, apellidos=:a, dni=:dni, email=:e, id_rol_principal=:r, activo=:act";
        $params = [':n'=>$d['nombres'], ':a'=>$d['apellidos'], ':dni'=>$d['dni']??'', ':e'=>$d['email'], ':r'=>$d['rol'], ':act'=>$d['activo'], ':id'=>$id];
        if(isset($d['id_facultad'])) { $campos .= ", id_facultad=:idf"; $params[':idf'] = $d['id_facultad']; if(isset($d['facultad'])) { $campos .= ", facultad_asignada=:fa"; $params[':fa'] = $d['facultad']; } }
        if(isset($d['programa_id'])) { $campos .= ", id_programa=:idp"; $params[':idp'] = ($d['programa_id'] === '') ? null : $d['programa_id']; }
        if(isset($d['area'])) { $campos .= ", id_area_investigacion=:ida"; $params[':ida'] = ($d['area'] === '') ? null : $d['area']; }
        if(isset($d['linea'])) { $campos .= ", id_linea_investigacion=:idl"; $params[':idl'] = ($d['linea'] === '') ? null : $d['linea']; }
        if (!empty($d['password'])) { $campos .= ", password_hash=:p"; $params[':p'] = password_hash($d['password'], PASSWORD_BCRYPT); }
        if(isset($d['grado'])) { $campos .= ", grado_academico=:g"; $params[':g']=$d['grado']; }
        if(isset($d['telefono'])) { $campos .= ", telefono=:tel"; $params[':tel'] = $d['telefono']; }
        if(isset($d['codigo'])) { $campos .= ", codigo=:cod"; $params[':cod'] = $d['codigo']; }
        $sql = "UPDATE usuarios SET $campos WHERE id=:id";
        try {
            $this->db->prepare($sql)->execute($params);
            if (isset($d['sublineas'])) {
                $this->db->prepare("DELETE FROM docente_sublineas WHERE id_docente = ?")->execute([$id]);
                foreach ($d['sublineas'] as $sl_id) $this->db->prepare("INSERT INTO docente_sublineas (id_docente, id_sublinea) VALUES (?, ?)")->execute([$id, $sl_id]);
            }
            return true;
        } catch (Exception $e) { return false; }
    }
    public function listar($rol_id=null) { $sql="SELECT * FROM usuarios"; if($rol_id)$sql.=" WHERE id_rol_principal=$rol_id"; return $this->db->query($sql)->fetchAll(); }
    public function crear($d) {
        $idf = $d['facultad_input'] ?? null;
        $idp = $d['programa_input'] ?? null;
        $ida = $d['area'] ?? ''; if($ida==='') $ida=null;
        $idl = $d['linea'] ?? ''; if($idl==='') $idl=null;
        $fac_nom = null;
        if ($idf) {
            $s = $this->db->prepare("SELECT nombre FROM facultades WHERE id=?");
            $s->execute([$idf]);
            $fac_nom = $s->fetchColumn();
        }
        $sql = "INSERT INTO usuarios (nombres, apellidos, email, password_hash, id_rol_principal, activo, dni, codigo, id_facultad, facultad_asignada, id_programa, id_area_investigacion, id_linea_investigacion) 
                VALUES (?, ?, ?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->prepare($sql)->execute([$d['nombres'], $d['apellidos'], $d['email'], password_hash($d['password'], PASSWORD_BCRYPT),$d['rol'], $d['dni'] ?? '', $d['codigo'] ?? '', $idf, $fac_nom, $idp, $ida, $idl]);
        return $this->db->lastInsertId();
    }
    public function obtenerExpertise($id) { $s=$this->db->prepare("SELECT id_sublinea FROM docente_sublineas WHERE id_docente=?"); $s->execute([$id]); return $s->fetchAll(PDO::FETCH_COLUMN); }
    public function actualizar($id,$d){ return $this->actualizar_full($id,$d); }
}
