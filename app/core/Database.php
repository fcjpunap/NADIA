<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Mostrar error amigable en lugar de pantalla blanca
            die('<div style="color:red; font-family:sans-serif; padding:20px; border:1px solid red; background:#ffeeee;">
                <h3>Error Crítico de Conexión a Base de Datos</h3>
                <p>' . $e->getMessage() . '</p>
                <p>Verifique que el usuario <b>' . $this->user . '</b> exista y tenga permisos.</p>
            </div>');
        }
        return $this->conn;
    }
}
