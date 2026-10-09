<?php
$servidor   = "localhost";
$usuario_bd = "root";
$clave_bd   = "";
$nombre_bd  = "bd_san_ignacio";

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error crítico de conexión a la base de datos: " . $e->getMessage());
}
?>