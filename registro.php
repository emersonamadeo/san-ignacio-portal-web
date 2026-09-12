<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - I.E.P. San Ignacio de Loyola</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); width: 380px; }
        h2 { text-align: center; color: #1a365d; margin-top: 0; }
        .campo { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #2b6cb0; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #2c5282; }
        .error { background: #fed7d7; color: #9b2c2c; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .links { text-align: center; margin-top: 15px; font-size: 13px; }
        .links a { color: #2b6cb0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registro de Usuario</h2>

        <?php if (isset($_SESSION['error_registro'])): ?>
            <div class="error"><?= $_SESSION['error_registro']; unset($_SESSION['error_registro']); ?></div>
        <?php endif; ?>

        <form action="registro_proceso.php" method="POST">
            <div class="campo">
                <label>Nombres:</label>
                <input type="text" name="nombres" required placeholder="Tus nombres">
            </div>
            <div class="campo">
                <label>Apellidos:</label>
                <input type="text" name="apellidos" required placeholder="Tus apellidos">
            </div>
            <div class="campo">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" required placeholder="correo@ejemplo.com">
            </div>
            <div class="campo">
                <label>Teléfono:</label>
                <input type="text" name="telefono" placeholder="Ej. 984123456">
            </div>
            <div class="campo">
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="Crea una contraseña">
            </div>
            <button type="submit">Guardar y Registrar</button>
        </form>

        <div class="links">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>
</html>