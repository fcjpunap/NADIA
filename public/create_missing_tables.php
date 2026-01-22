<?php
ini_set('display_errors', 1);
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "<h3>Iniciando restauración de estructura...</h3>\n";

try {
    $db = (new Database())->connect();

    // 1. Crear Tabla OBSERVACIONES (Con todas las columnas nuevas incluidas)
    $sqlObs = "CREATE TABLE IF NOT EXISTS observaciones (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_proyecto INT UNSIGNED NOT NULL,
        id_jurado INT UNSIGNED NOT NULL,
        rol_autor VARCHAR(50) DEFAULT 'Jurado',
        pagina INT UNSIGNED DEFAULT 1,
        tipo_observacion VARCHAR(50) DEFAULT 'Borrador',
        observacion_texto TEXT,
        fecha_observacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(id_proyecto),
        INDEX(id_jurado)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sqlObs);
    echo "✅ Tabla <b>observaciones</b> verificada/creada.<br>\n";

    // 2. Crear Tabla DICTAMENES (Por si acaso falte también)
    $sqlDic = "CREATE TABLE IF NOT EXISTS dictamenes (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id_proyecto INT UNSIGNED NOT NULL,
        id_jurado INT UNSIGNED NOT NULL,
        resultado VARCHAR(50) NOT NULL, 
        observaciones TEXT,
        fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(id_proyecto)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sqlDic);
    echo "✅ Tabla <b>dictamenes</b> verificada/creada.<br>\n";

} catch (Exception $e) {
    echo "❌ Error Crítico: " . $e->getMessage() . "<br>\n";
}
echo "<h3>Restauración completada.</h3>\n";
