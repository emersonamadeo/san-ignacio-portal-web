<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_apoderado = trim($_POST['nombre_apoderado'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $nivel_postulacion = trim($_POST['nivel_postulacion'] ?? '');
    $grado = trim($_POST['grado'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre_apoderado) || empty($correo) || empty($telefono) || empty($nivel_postulacion) || empty($grado)) {
        echo "<script>alert('Por favor complete todos los campos obligatorios.'); window.history.back();</script>";
        exit();
    }

    try {
        $servidor = "localhost";
        $usuario_bd = "root";
        $clave_bd = "";
        $nombre_bd = "bd_san_ignacio";

        $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Asegurar que la tabla exista
        $pdo->exec("CREATE TABLE IF NOT EXISTS solicitudes_admision (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_apoderado VARCHAR(150) NOT NULL,
            email VARCHAR(100) NULL,
            correo VARCHAR(100) NULL,
            telefono VARCHAR(30) NOT NULL,
            nivel_postulacion VARCHAR(50) NOT NULL,
            grado VARCHAR(50) NOT NULL,
            mensaje TEXT,
            estado VARCHAR(30) DEFAULT 'En Evaluación',
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Obtener la lista real de columnas que tiene la tabla actual en MySQL
        $columnas_stmt = $pdo->query("SHOW COLUMNS FROM solicitudes_admision");
        $columnas_existentes = $columnas_stmt->fetchAll(PDO::FETCH_COLUMN);

        // Si no tiene 'email' ni 'correo', agregamos 'email'
        if (!in_array('email', $columnas_existentes) && !in_array('correo', $columnas_existentes)) {
            $pdo->exec("ALTER TABLE solicitudes_admision ADD COLUMN email VARCHAR(100) NULL AFTER nombre_apoderado");
            $columna_email = 'email';
        } else {
            // Usar la columna que realmente exista ('email' o 'correo')
            $columna_email = in_array('email', $columnas_existentes) ? 'email' : 'correo';
        }

        // Determinar si la columna del nombre es 'nombre_apoderado' o 'nombre'
        $columna_nombre = in_array('nombre_apoderado', $columnas_existentes) ? 'nombre_apoderado' : (in_array('nombre', $columnas_existentes) ? 'nombre' : 'nombre_apoderado');

        // Insertar adaptándose a las columnas existentes
        $sql = "INSERT INTO solicitudes_admision ($columna_nombre, $columna_email, telefono, nivel_postulacion, grado, mensaje) 
                VALUES (:nombre, :email, :tel, :nivel, :grado, :msg)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre_apoderado,
            ':email'  => $correo,
            ':tel'    => $telefono,
            ':nivel'  => $nivel_postulacion,
            ':grado'  => $grado,
            ':msg'    => $mensaje
        ]);

        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Solicitud Registrada - San Ignacio de Loyola</title>
            <style>
                body { font-family: 'Segoe UI', sans-serif; background: #fbf8f2; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 1.5rem; }
                .card { background: #ffffff; padding: 2.8rem 2.2rem; border-radius: 12px; border: 1px solid #ebdccb; border-top: 5px solid #800c19; box-shadow: 0 8px 24px rgba(88,6,15,0.08); text-align: center; max-width: 500px; width: 100%; }
                .badge { font-size: 3rem; margin-bottom: 0.8rem; }
                h2 { color: #800c19; font-size: 1.6rem; margin-bottom: 0.8rem; font-weight: 800; }
                p { color: #555; line-height: 1.7; font-size: 0.98rem; margin-bottom: 1.5rem; }
                a { display: inline-block; background: #e5a910; color: #382400; padding: 0.85rem 2rem; text-decoration: none; font-weight: 800; border-radius: 6px; transition: background 0.2s; }
                a:hover { background: #c48e06; }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='badge'>✅</div>
                <h2>¡Solicitud Recibida con Éxito!</h2>
                <p>Estimado(a) <strong>" . htmlspecialchars($nombre_apoderado) . "</strong>, la postulación para <strong>" . htmlspecialchars($grado) . " (" . htmlspecialchars($nivel_postulacion) . ")</strong> ha sido registrada en el sistema escolar. Dirección y Secretaría evaluarán el expediente y se comunicarán al teléfono <strong>" . htmlspecialchars($telefono) . "</strong>.</p>
                <a href='admision.html'>Volver a la Página de Admisión</a>
            </div>
        </body>
        </html>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Error al registrar la solicitud: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: admision.html");
    exit();
}
?>