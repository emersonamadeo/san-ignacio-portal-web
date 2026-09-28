<?php
/**
 * Conexión a Base de Datos mediante PDO
 * Proyecto: Portal Web I.E.P. San Ignacio de Loyola
 */
class Database {
    private string $host = "localhost";
    private string $db_name = "bd_san_ignacio";
    private string $username = "root";
    private string $password = "";
    private string $charset = "utf8mb4";
    public ?PDO $conn = null;

    public function getConnection(): ?PDO {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Previene Inyección SQL nativamente
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión a la BD: " . $e->getMessage());
            die("Error temporal en el servidor. Por favor, intente más tarde.");
        }
        return $this->conn;
    }
}
?>