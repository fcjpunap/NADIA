<?php
class PlazoHelper {
    public static function calcular($proyecto, $db) {
        $etapa = $proyecto['id_etapa_actual'] ?? 1;
        $estado = $proyecto['estado'] ?? 'Iniciado';
        
        $sql = "SELECT * FROM configuracion_plazos WHERE etapa_id = ? AND (estado_trigger = ? OR estado_trigger = 'Cualquiera') ORDER BY estado_trigger DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$etapa, $estado]);
        $config = $stmt->fetch(PDO::FETCH_ASSOC);

        $dias_limite = $config['dias_plazo'] ?? 15;
        $fase_nombre = $config['descripcion'] ?? 'Trámite General';

        $fecha_base = !empty($proyecto['updated_at']) ? $proyecto['updated_at'] : ($proyecto['created_at'] ?? date('Y-m-d'));
        
        if ($estado == 'Aprobado') {
             if(!$config) $dias_limite = 360;
             $fase_nombre = 'Ejecución de Tesis';
        }

        $inicio = new DateTime($fecha_base);
        $hoy = new DateTime();
        
        $interval = $inicio->diff($hoy);
        $dias_transcurridos = $interval->days;
        
        if ($inicio > $hoy) $dias_transcurridos = 0;

        $dias_restantes = $dias_limite - $dias_transcurridos;

        $color = 'success';
        if($dias_restantes <= 5) $color = 'warning';
        if($dias_restantes <= 0) $color = 'danger';

        if ($dias_restantes > 0) {
            $texto = "$dias_restantes días rest.";
        } else {
            $vencido_hace = abs($dias_restantes);
            if ($vencido_hace > 30) {
                $meses = floor($vencido_hace / 30);
                $texto = "Vencido hace $vencido_hace días ($meses meses)";
            } else {
                $texto = "Vencido hace $vencido_hace días";
            }
        }

        return [
            'fase' => $fase_nombre,
            'texto' => $texto,
            'color' => $color,
            'dias_restantes' => $dias_restantes,
            'dias_limite' => $dias_limite,
            'intervalo' => $interval // NUEVO: Objeto DateInterval
        ];
    }
}
