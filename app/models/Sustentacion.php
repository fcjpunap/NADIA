<?php
require_once APP_ROOT . '/core/Database.php';

class Sustentacion {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }

    public function listar() {
        $sql = "SELECT s.*, p.titulo, u.nombres as tesista 
                FROM sustentaciones s
                JOIN proyectos p ON s.id_proyecto = p.id
                JOIN usuarios u ON p.id_tesista = u.id
                ORDER BY s.fecha_hora DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function programar($datos) {
        try {
            $sql = "INSERT INTO sustentaciones (id_proyecto, fecha_hora, lugar_enlace, estado) 
                    VALUES (:p, :f, :l, 'Programada')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':p' => $datos['id_proyecto'],
                ':f' => $datos['fecha_hora'],
                ':l' => $datos['lugar']
            ]);
            
            // Actualizar estado del proyecto
            $this->db->query("UPDATE proyectos SET estado = 'Sustentado' WHERE id = " . $datos['id_proyecto']);
            return true;
        } catch (Exception $e) { return false; }
    }
    
    // Obtener proyectos aptos para sustentar (Simulación: cualquiera aprobado o en revisión avanzada)
    public function obtenerAptos() {
        return $this->db->query("SELECT id, titulo FROM proyectos WHERE estado != 'Sustentado'")->fetchAll();
    }
}
