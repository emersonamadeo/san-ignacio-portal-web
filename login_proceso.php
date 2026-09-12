<?php
/**
 * AUTENTICACIÓN Y REDIRECCIÓN AL DASHBOARD
 * Archivo: login_proceso.php
 */
session_start();
require_once 'config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        $_SESSION['error_login'] = "Debe ingresar su correo y contraseña.";
        header("Location: login.php");
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // 1. Buscar usuario y su rol en la base de datos
    $sql = "SELECT u.id_usuario, u.id_rol, u.nombres, u.apellidos, u.password_hash, u.activo, r.nombre_rol 
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.correo = :correo LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':correo' => $correo]);
    $usuario = $stmt->fetch();

    // 2. Verificar contraseña con Bcrypt
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        if ((int)$usuario['activo'] === 1) {
            // Regenerar identificador de sesión por seguridad
            session_regenerate_id(true);

            // Guardar variables de sesión del usuario autenticado
            $_SESSION['usuario_id']  = $usuario['id_usuario'];
            $_SESSION['rol_id']      = $usuario['id_rol'];
            $_SESSION['rol_nombre']  = $usuario['nombre_rol'];
            $_SESSION['nombre']      = $usuario['nombres'] . ' ' . $usuario['apellidos'];

            // 3. Redirigir al Dashboard Administrativo
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $_SESSION['error_login'] = "Su cuenta se encuentra inactiva. Contacte a la secretaría.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>