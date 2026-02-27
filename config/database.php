<?php
class Database {
    private $host = 'postgres';        // nombre del servicio en docker-compose
    private $db_name = 'sgea_db';
    private $username = 'sgea_user';
    private $password = 'sgea_password_123';
    private $conn;
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port=5432;dbname={$this->db_name};";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch(PDOException $e) {
            error_log("Error de conexión PostgreSQL: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }

        return $this->conn;
    }

    public function testConnection() {
        try {
            $conn = $this->getConnection();
            $stmt = $conn->query("SELECT '✅ Conexión exitosa a PostgreSQL' as mensaje");
            return $stmt->fetch()['mensaje'];
        } catch(Exception $e) {
            return "❌ Error: " . $e->getMessage();
        }
    }
}