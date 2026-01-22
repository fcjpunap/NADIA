<?php
require_once APP_ROOT . '/core/Database.php';

class Configuracion {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }

    public function obtenerTodo() {
        return $this->db->query("SELECT * FROM configuraciones")->fetchAll();
    }

    public function actualizar($clave, $valor) {
        $stmt = $this->db->prepare("UPDATE configuraciones SET valor = :v WHERE clave = :c");
        return $stmt->execute([':v' => $valor, ':c' => $clave]);
    }
    
    public function obtenerLineas() {
        return $this->db->query("SELECT * FROM lineas_investigacion")->fetchAll();
    }
}
