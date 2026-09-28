<?php
session_start();
require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        $_SESSION['error_login'] = "Por favor ingrese su correo y contraseña.";
        header("Location: login.php");
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        try {
            // Consulta preparada uniendo la tabla de roles
            $sql = "SELECT u.id_usuario, u.id_rol, u.nombres, u.apellidos, u.password_hash, u.activo, r.nombre_rol 
                    FROM usuarios u 
                    INNER JOIN roles r ON u.id_rol = r.id_rol 
                    WHERE u.correo = :correo LIMIT 1";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([':correo' => $correo]);
            $usuario = $stmt->fetch();

            // Verificación matemática con Bcrypt y estado de cuenta activa
            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                if ((int)$usuario['activo'] !== 1) {
                    $_SESSION['error_login'] = "Su cuenta está suspendida. Contacte con secretaría.";
                    header("Location: login.php");
                    exit;
                }

                // Prevención de fijación de sesiones
                session_regenerate_id(true);

                // Asignación de variables de sesión seguras
                $_SESSION['usuario_id']  = $usuario['id_usuario'];
                $_SESSION['nombre']      = $usuario['nombres'] . ' ' . $usuario['apellidos'];
                $_SESSION['rol_nombre']  = $usuario['nombre_rol'];
                $_SESSION['id_rol']      = $usuario['id_rol'];

                header("Location: admin/dashboard.php");
                exit;
            } else {
                $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Error en autenticación: " . $e->getMessage());
            $_SESSION['error_login'] = "Error al autenticar con el servidor.";
            header("Location: login.php");
            exit;
        }
    }
} else {
    header("Location: login.php");
    exit;
}
?>