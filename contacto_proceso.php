<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $asunto   = trim($_POST['asunto'] ?? 'Consulta General');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        echo "<script>alert('Por favor complete todos los campos requeridos.'); window.history.back();</script>";
        exit();
    }

    try {
        $servidor   = "localhost";
        $usuario_bd = "root";
        $clave_bd   = "";
        $nombre_bd  = "bd_san_ignacio";

        $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear la tabla si aún no existe
        $pdo->exec("CREATE TABLE IF NOT EXISTS mensajes_contacto (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(150) NOT NULL,
            email VARCHAR(100) NOT NULL,
            telefono VARCHAR(30) NULL,
            asunto VARCHAR(100) NOT NULL,
            mensaje TEXT NOT NULL,
            estado VARCHAR(30) DEFAULT 'Pendiente',
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $sql = "INSERT INTO mensajes_contacto (nombre, email, telefono, asunto, mensaje) 
                VALUES (:nombre, :email, :tel, :asunto, :msg)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email'  => $correo,
            ':tel'    => $telefono,
            ':asunto' => $asunto,
            ':msg'    => $mensaje
        ]);

        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mensaje Recibido - San Ignacio de Loyola</title>
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
                <div class='badge'>✉️</div>
                <h2>¡Mensaje Enviado con Éxito!</h2>
                <p>Estimado(a) <strong>" . htmlspecialchars($nombre) . "</strong>, su consulta sobre <strong>" . htmlspecialchars($asunto) . "</strong> ha sido registrada en secretaría escolar. Nos pondremos en contacto a la brevedad posible.</p>
                <a href='contacto.html'>Volver a Contacto</a>
            </div>
        </body>
        </html>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Error al guardar el mensaje: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: contacto.html");
    exit();
}
?>