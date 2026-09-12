<?php
// Incluye el archivo de conexión
require_once 'config/conexion.php';

// Creamos el objeto de base de datos
$database = new Database();
$conexion = $database->getConnection();

if ($conexion) {
    echo "<div style='font-family: Arial; padding: 20px; background-color: #d4edda; color: #155724; border: 2px solid #c3e6cb; border-radius: 8px; max-width: 500px; margin: 50px auto; text-align: center;'>";
    echo "<h2>¡Conexión Exitosa!</h2>";
    echo "<p>El sistema se ha conectado correctamente a la base de datos <strong>bd_san_ignacio</strong> mediante PDO.</p>";
    echo "</div>";
}
?>