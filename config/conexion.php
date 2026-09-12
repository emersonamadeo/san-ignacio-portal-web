<?php
/**
 * CLASE DE CONEXIÓN A LA BASE DE DATOS MEDIANTE PDO
 * I.E.P. San Ignacio de Loyola - Sicuani
 */

class Database {
    private $host     = "localhost";
    private $db_name  = "bd_san_ignacio"; // El nombre exacto de la base de datos que creaste
    private $username = "root";             // Usuario por defecto en XAMPP
    private $password = "";                 // En XAMPP viene vacía por defecto
    private $charset  = "utf8mb4";
    public  $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Cadena de conexión (DSN)
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            
            // Opciones de seguridad de PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Avisa cualquier error
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve datos ordenados
                PDO::ATTR_EMULATE_PREPARES   => false                  // Protege contra inyecciones SQL
            ];

            // Se crea la conexión
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $exception) {
            // Si hay error, lo guarda y muestra mensaje sin exponer contraseñas
            error_log("Error de conexión: " . $exception->getMessage());
            die("Error en el servicio de datos: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>