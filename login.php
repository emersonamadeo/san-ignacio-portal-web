<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - I.E.P. San Ignacio de Loyola</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.15); width: 350px; }
        h2 { text-align: center; color: #1a365d; margin-top: 0; }
        .campo { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #1a365d; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #2a4365; }
        .exito { background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .error { background: #fed7d7; color: #9b2c2c; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .links { text-align: center; margin-top: 15px; font-size: 13px; }
        .links a { color: #2b6cb0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Iniciar Sesión</h2>

        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="exito"><?= $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_login'])): ?>
            <div class="error"><?= $_SESSION['error_login']; unset($_SESSION['error_login']); ?></div>
        <?php endif; ?>

        <form action="login_proceso.php" method="POST">
            <div class="campo">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" required placeholder="correo@ejemplo.com">
            </div>
            <div class="campo">
                <label>Contraseña:</label>
                <input type="password" name="password" required placeholder="Tu contraseña">
            </div>
            <button type="submit">Ingresar al Sistema</button>
        </form>

        <div class="links">
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>