<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $nombre_completo = !empty($nombres) ? trim($nombres . ' ' . $apellidos) : trim($_POST['nombre'] ?? '');

    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = trim($_POST['rol'] ?? 'Directivo');

    if (empty($nombre_completo) || empty($email) || empty($password)) {
        echo "<script>alert('Todos los campos obligatorios deben ser completados.'); window.history.back();</script>";
        exit();
    }

    try {
        $servidor = "localhost";
        $usuario_bd = "root";
        $clave_bd = "";
        $nombre_bd = "bd_san_ignacio";

        $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 1. Asegurar que la tabla usuarios exista
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(150) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            telefono VARCHAR(30) NULL,
            password VARCHAR(255) NOT NULL,
            rol VARCHAR(50) DEFAULT 'Directivo',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 2. Leer columnas existentes reales
        $cols_stmt = $pdo->query("SHOW COLUMNS FROM usuarios");
        $cols = $cols_stmt->fetchAll(PDO::FETCH_COLUMN);

        // 3. Auto-reparar columnas que falten en tu base de datos existente
        if (!in_array('password', $cols) && !in_array('clave', $cols)) {
            $pdo->exec("ALTER TABLE usuarios ADD COLUMN password VARCHAR(255) NOT NULL");
            $campo_pass = 'password';
        } else {
            $campo_pass = in_array('password', $cols) ? 'password' : 'clave';
        }

        if (!in_array('email', $cols) && !in_array('correo', $cols)) {
            $pdo->exec("ALTER TABLE usuarios ADD COLUMN email VARCHAR(100) NOT NULL UNIQUE");
            $campo_email = 'email';
        } else {
            $campo_email = in_array('email', $cols) ? 'email' : 'correo';
        }

        if (!in_array('nombre', $cols) && !in_array('nombres', $cols)) {
            $pdo->exec("ALTER TABLE usuarios ADD COLUMN nombre VARCHAR(150) NOT NULL");
            $campo_nombre = 'nombre';
        } else {
            $campo_nombre = in_array('nombre', $cols) ? 'nombre' : 'nombres';
        }

        // 4. Verificar si el correo ya existe
        $check = $pdo->prepare("SELECT * FROM usuarios WHERE $campo_email = :email LIMIT 1");
        $check->execute([':email' => $email]);
        if ($check->rowCount() > 0) {
            echo "<script>alert('El correo electrónico ya se encuentra registrado. Inicie sesión.'); window.location.href='login.php';</script>";
            exit();
        }

        // 5. Cifrado con Bcrypt
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // 6. Construir inserción dinámica
        $campos_insert = [$campo_nombre, $campo_email, $campo_pass];
        $valores_insert = [':nombre', ':email', ':pass'];
        $params = [
            ':nombre' => $nombre_completo,
            ':email'  => $email,
            ':pass'   => $hash_password
        ];

        // Volver a leer columnas tras posibles ALTER TABLE
        $cols_actualizadas = $pdo->query("SHOW COLUMNS FROM usuarios")->fetchAll(PDO::FETCH_COLUMN);

        if (in_array('telefono', $cols_actualizadas)) {
            $campos_insert[] = 'telefono';
            $valores_insert[] = ':tel';
            $params[':tel'] = $telefono;
        }

        if (in_array('rol', $cols_actualizadas)) {
            $campos_insert[] = 'rol';
            $valores_insert[] = ':rol';
            $params[':rol'] = $rol;
        }

        $sql = "INSERT INTO usuarios (" . implode(', ', $campos_insert) . ") VALUES (" . implode(', ', $valores_insert) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo "<script>alert('¡Usuario registrado con éxito! Ahora puede iniciar sesión.'); window.location.href='login.php';</script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Error en base de datos: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: registro.php");
    exit();
}
?>