<?php
ini_set('display_errors', 1);
// Usamos __DIR__ para obligar a que la ruta sea relativa a ESTE archivo, no a la terminal
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';

echo "<h3>Iniciando reparación de tablas...</h3>\n";
try {
    $db = (new Database())->connect();
    echo "✅ Conexión DB OK.<br>\n";
} catch (Exception $e) {
    die("❌ Error Conexión: " . $e->getMessage());
}

// Función helper para agregar columna si no existe
function addCol($db, $table, $col, $def) {
    try {
        $db->query("SELECT $col FROM $table LIMIT 1");
        echo "🔹 Columna <b>$col</b> ya existe en <b>$table</b>.<br>\n";
    } catch (Exception $e) {
        try {
            $db->exec("ALTER TABLE $table ADD COLUMN $col $def");
            echo "🛠️ Columna <b>$col</b> creada en <b>$table</b>.<br>\n";
        } catch (Exception $ex) {
            echo "❌ Error creando $col: " . $ex->getMessage() . "<br>\n";
        }
    }
}

addCol($db, 'observaciones', 'pagina', 'INT UNSIGNED DEFAULT 1');
addCol($db, 'observaciones', 'rol_autor', "VARCHAR(50) DEFAULT 'Jurado'");
addCol($db, 'documentos', 'mime_type', "VARCHAR(100) DEFAULT 'application/pdf'");

echo "<h3>Reparación finalizada.</h3>\n";
