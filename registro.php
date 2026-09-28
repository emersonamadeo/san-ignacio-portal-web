<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            --primary: #1a365d;
            --primary-light: #2b6cb0;
            --accent: #d69e2e;
            --bg: #f0f4f8;
            --card-bg: #ffffff;
            --text-dark: #2d3748;
            --text-muted: #718096;
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

        .btn-nav-login {
            background: var(--primary);
            color: #ffffff !important;
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-nav-login:hover {
            background: var(--primary-light);
        }

        .reg-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
        }

        .reg-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 480px;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-top: 5px solid var(--primary);
        }

        .reg-card h2 {
            color: var(--primary);
            font-size: 1.6rem;
            text-align: center;
            margin-bottom: 0.35rem;
        }

        .reg-card p.subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 0.85rem;
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
            padding: 0.85rem;
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
            margin-top: 1.35rem;
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

        @media (max-width: 520px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
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
                <li><a href="login.php" class="btn-nav-login">Iniciar Sesión</a></li>
            </ul>
        </div>
    </header>

    <div class="reg-wrapper">
        <div class="reg-card">
            <h2>Crear Cuenta</h2>
            <p class="subtitle">Registro de Nuevo Usuario en la Plataforma Institucional</p>

            <?php if (isset($_SESSION['error_registro'])): ?>
                <div class="alerta-error">
                    <?= htmlspecialchars($_SESSION['error_registro']); unset($_SESSION['error_registro']); ?>
                </div>
            <?php endif; ?>

            <form action="registro_proceso.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres:</label>
                        <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ej. Emerson" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ej. Condori Baca" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono / Celular:</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ej. 984123456" pattern="[0-9]{9}">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>

                <button type="submit" class="btn-submit">Guardar y Registrar</button>
            </form>

            <div class="card-footer">
                ¿Ya tienes una cuenta registrada? <a href="login.php">Inicia sesión aquí</a>
            </div>
        </div>
    </div>

</body>
</html>