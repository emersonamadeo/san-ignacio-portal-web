<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            --primary: #1a365d;
            --primary-light: #2b6cb0;
            --accent: #d69e2e;
            --bg: #f0f4f8;
            --card-bg: #ffffff;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --success-bg: #e6fffa;
            --success-color: #234e52;
            --success-border: #38b2ac;
            --error-bg: #fff5f5;
            --error-color: #742a2a;
            --error-border: #e53e3e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Barra de navegación superior */
        .header-nav {
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .nav-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0.85rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 1.25rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--primary-light);
        }

        .btn-nav-reg {
            background: var(--primary);
            color: #ffffff !important;
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-nav-reg:hover {
            background: var(--primary-light);
        }

        /* Contenedor central del login */
        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--primary);
        }

        .login-card h2 {
            color: var(--primary);
            font-size: 1.6rem;
            text-align: center;
            margin-bottom: 0.35rem;
        }

        .login-card p.subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
        }

        /* Notificaciones y alertas */
        .alerta-exito {
            background: var(--success-bg);
            border-left: 4px solid var(--success-border);
            color: var(--success-color);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.88rem;
            margin-bottom: 1.2rem;
        }

        .alerta-error {
            background: var(--error-bg);
            border-left: 4px solid var(--error-border);
            color: var(--error-color);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.88rem;
            margin-bottom: 1.2rem;
        }

        /* Formulario */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 0.9rem;
            font-size: 0.95rem;
            border: 1.5px solid #cbd5e0;
            border-radius: 7px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: var(--primary-light);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .card-footer {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .card-footer a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header class="header-nav">
        <div class="nav-container">
            <a href="index.html" class="brand-title">I.E.P. San Ignacio de Loyola</a>
            <ul class="nav-links">
                <li><a href="index.html">Inicio</a></li>
                <li><a href="admision.html">Admisión</a></li>
                <li><a href="registro.php" class="btn-nav-reg">Registrarse</a></li>
            </ul>
        </div>
    </header>

    <div class="login-wrapper">
        <div class="login-card">
            <h2>Iniciar Sesión</h2>
            <p class="subtitle">Acceso al Sistema Integrado Institucional</p>

            <?php if (isset($_SESSION['exito_login'])): ?>
                <div class="alerta-exito"><?= htmlspecialchars($_SESSION['exito_login']); unset($_SESSION['exito_login']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_login'])): ?>
                <div class="alerta-error"><?= htmlspecialchars($_SESSION['error_login']); unset($_SESSION['error_login']); ?></div>
            <?php endif; ?>

            <form action="login_proceso.php" method="POST">
                <div class="form-group">
                    <label for="correo">Correo Electrónico Institucional:</label>
                    <input type="email" id="correo" name="correo" class="form-control" placeholder="nombre@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>

                <button type="submit" class="btn-submit">Ingresar al Sistema</button>
            </form>

            <div class="card-footer">
                ¿No tienes cuenta aún? <a href="registro.php">Regístrate aquí</a>
            </div>
        </div>
    </div>

</body>
</html>