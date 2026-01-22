<?php
require_once APP_ROOT . '/core/Database.php';

class Proyecto {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }

    public function listar() {
        $sql = "SELECT p.*, u.nombres as tesista FROM proyectos p LEFT JOIN usuarios u ON p.id_tesista = u.id ORDER BY p.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function contar($condicion = null) {
        $sql = "SELECT COUNT(*) as total FROM proyectos";
        if($condicion) $sql .= " WHERE $condicion";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'];
    }
}
