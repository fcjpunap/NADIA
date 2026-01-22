<?php
require_once APP_ROOT . '/core/Database.php';

class Jerarquia {
    private $db;
    public function __construct() { $this->db = (new Database())->connect(); }

    public function obtenerArbol() {
        $arbol = [];
        try {
            // 1. Obtener Áreas
            $stmt = $this->db->query("SELECT * FROM areas_investigacion ORDER BY nombre ASC");
            $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!$areas) return [];

            // 2. Construir Jerarquía
            foreach ($areas as $area) {
                $nodoArea = $area;
                $nodoArea['lineas'] = []; // Inicializar vacío por seguridad

                // Buscar Líneas
                $stmtL = $this->db->prepare("SELECT * FROM lineas_investigacion_v2 WHERE id_area = ? ORDER BY nombre ASC");
                $stmtL->execute([$area['id']]);
                $lineas = $stmtL->fetchAll(PDO::FETCH_ASSOC);

                if ($lineas) {
                    foreach ($lineas as $linea) {
                        $nodoLinea = $linea;
                        $nodoLinea['sublineas'] = []; // Inicializar vacío

                        // Buscar Sublíneas
                        $stmtS = $this->db->prepare("SELECT * FROM sublineas_investigacion WHERE id_linea = ? ORDER BY nombre ASC");
                        $stmtS->execute([$linea['id']]);
                        $sublineas = $stmtS->fetchAll(PDO::FETCH_ASSOC);
                        
                        if($sublineas) {
                            $nodoLinea['sublineas'] = $sublineas;
                        }
                        
                        $nodoArea['lineas'][] = $nodoLinea;
                    }
                }
                $arbol[] = $nodoArea;
            }
            return $arbol;

        } catch (Exception $e) {
            return []; // Retorna array vacío en error, evita crash
        }
    }

    // Métodos de Creación
    public function crearArea($n) { $this->db->prepare("INSERT INTO areas_investigacion (nombre) VALUES (?)")->execute([$n]); }
    public function crearLinea($pid, $n) { $this->db->prepare("INSERT INTO lineas_investigacion_v2 (id_area, nombre) VALUES (?, ?)")->execute([$pid, $n]); }
    public function crearSublinea($pid, $n) { $this->db->prepare("INSERT INTO sublineas_investigacion (id_linea, nombre) VALUES (?, ?)")->execute([$pid, $n]); }
    
    public function eliminarEntidad($tipo, $id) {
        $t = ($tipo=='area')?'areas_investigacion':(($tipo=='linea')?'lineas_investigacion_v2':'sublineas_investigacion');
        $this->db->prepare("DELETE FROM $t WHERE id = ?")->execute([$id]);
    }
}
