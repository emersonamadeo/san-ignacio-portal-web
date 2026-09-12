<?php
session_start();
require_once 'config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres   = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo    = trim($_POST['correo']);
    $telefono  = trim($_POST['telefono']);
    $password  = $_POST['password'];

    // 1. Validar que los campos principales no estén vacíos
    if (empty($nombres) || empty($apellidos) || empty($correo) || empty($password)) {
        $_SESSION['error_registro'] = "Por favor completa todos los campos obligatorios.";
        header("Location: registro.php");
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // 2. Comprobar si el correo ya existe en la base de datos
    $check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = :correo LIMIT 1");
    $check->execute([':correo' => $correo]);

    if ($check->rowCount() > 0) {
        $_SESSION['error_registro'] = "Este correo ya está registrado. Usa otro.";
        header("Location: registro.php");
        exit;
    }

    // 3. Encriptar la contraseña con Bcrypt (máxima seguridad)
    $password_encriptada = password_hash($password, PASSWORD_BCRYPT);

    // 4. Insertar el nuevo usuario en la base de datos (id_rol = 3 para Usuario General)
    $sql = "INSERT INTO usuarios (id_rol, nombres, apellidos, correo, password_hash, telefono, activo) 
            VALUES (3, :nombres, :apellidos, :correo, :password_hash, :telefono, 1)";
    $stmt = $conn->prepare($sql);

    $exito = $stmt->execute([
        ':nombres'       => $nombres,
        ':apellidos'     => $apellidos,
        ':correo'        => $correo,
        ':password_hash' => $password_encriptada,
        ':telefono'      => $telefono
    ]);

    if ($exito) {
        $_SESSION['mensaje_exito'] = "¡Usuario registrado correctamente! Inicia sesión.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error_registro'] = "Error al guardar en la base de datos.";
        header("Location: registro.php");
        exit;
    }
}
?>