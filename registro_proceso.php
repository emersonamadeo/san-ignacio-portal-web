<?php
session_start();
require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitización de entradas
    $nombres   = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $password  = $_POST['password'] ?? '';

    // 2. Validación de campos obligatorios
    if (empty($nombres) || empty($apellidos) || empty($correo) || empty($password)) {
        $_SESSION['error_registro'] = "Todos los campos obligatorios deben ser completados.";
        header("Location: registro.php");
        exit;
    }

    // 3. Validación de formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_registro'] = "El formato del correo electrónico no es válido.";
        header("Location: registro.php");
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        try {
            // 4. Comprobar si el correo ya existe en la base de datos
            $checkStmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo LIMIT 1");
            $checkStmt->execute([':correo' => $correo]);

            if ($checkStmt->fetch()) {
                $_SESSION['error_registro'] = "El correo ya se encuentra registrado. Utilice otro.";
                header("Location: registro.php");
                exit;
            }

            // 5. Cifrado seguro de contraseña mediante algoritmo Bcrypt
            $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

            // 6. Inserción persistente en la tabla usuarios (id_rol = 3: Usuario General, activo = 1)
            $sql = "INSERT INTO usuarios (id_rol, nombres, apellidos, correo, password_hash, telefono, activo, fecha_registro) 
                    VALUES (3, :nombres, :apellidos, :correo, :password_hash, :telefono, 1, NOW())";
            
            $stmt = $db->prepare($sql);
            $ejecutado = $stmt->execute([
                ':nombres'       => $nombres,
                ':apellidos'     => $apellidos,
                ':correo'        => $correo,
                ':password_hash' => $password_hash,
                ':telefono'      => $telefono
            ]);

            if ($ejecutado) {
                $_SESSION['exito_login'] = "¡Cuenta creada exitosamente! Inicie sesión con sus credenciales.";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error_registro'] = "No se pudo registrar al usuario. Intente nuevamente.";
                header("Location: registro.php");
                exit;
            }

        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            $_SESSION['error_registro'] = "Error interno al procesar el registro.";
            header("Location: registro.php");
            exit;
        }
    }
} else {
    header("Location: registro.php");
    exit;
}
?>