<?php
// Iniciar sesión para autenticación
session_start();

// Si ya tiene una sesión activa, redirigir al panel directivo
if (isset($_SESSION['usuario_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}

$error_mensaje = "";

// Procesar el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error_mensaje = "Por favor, complete todos los campos obligatorios.";
    } else {
        try {
            $servidor = "localhost";
            $usuario_bd = "root";
            $clave_bd = "";
            $nombre_bd = "bd_san_ignacio";

            $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Obtener los nombres reales de las columnas en la tabla usuarios
            $cols_stmt = $pdo->query("SHOW COLUMNS FROM usuarios");
            $cols = $cols_stmt->fetchAll(PDO::FETCH_COLUMN);

            // 2. Detectar cuál es la columna de correo (email o correo)
            $col_email = in_array('email', $cols) ? 'email' : (in_array('correo', $cols) ? 'correo' : 'email');

            // 3. Buscar al usuario usando SELECT * para NUNCA fallar por columnas inexistentes como 'id'
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE $col_email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // 4. Detectar dinámicamente cuál campo tiene el hash de la clave
                $hash_almacenado = $usuario['password'] ?? $usuario['clave'] ?? $usuario['contrasena'] ?? '';

                // 5. Detectar el nombre y rol para la sesión
                $nombre_sesion = $usuario['nombre'] ?? $usuario['nombres'] ?? $usuario['nombre_completo'] ?? 'Directivo';
                $id_sesion = $usuario['id'] ?? $usuario['id_usuario'] ?? $usuario['id_user'] ?? 1;
                $rol_sesion = $usuario['rol'] ?? 'Directivo';

                // 6. Verificar contraseña con Bcrypt (o en texto plano por retrocompatibilidad)
                if (password_verify($password, $hash_almacenado) || $password === $hash_almacenado) {
                    $_SESSION['usuario_id'] = $id_sesion;
                    $_SESSION['usuario_nombre'] = $nombre_sesion;
                    $_SESSION['usuario_email'] = $usuario[$col_email];
                    $_SESSION['usuario_rol'] = $rol_sesion;

                    header("Location: admin/dashboard.php");
                    exit();
                } else {
                    $error_mensaje = "Contraseña incorrecta. Verifique sus datos.";
                }
            } else {
                $error_mensaje = "No existe ninguna cuenta registrada con ese correo.";
            }
        } catch (PDOException $e) {
            $error_mensaje = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Directivo e Intranet - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            /* Paleta cromática oficial de la insignia del colegio */
            --primary: #800c19;          /* Granate institucional */
            --primary-dark: #58060f;     /* Granate profundo */
            --primary-light: #a31928;    /* Granate claro */
            --accent-gold: #e5a910;      /* Dorado del blasón y estrellas */
            --accent-gold-hover: #c48e06;
            --accent-blue: #0f4c81;      /* Azul de contraste */
            --bg: #fbf8f2;               /* Marfil pergamino */
            --surface: #ffffff;          /* Fondo blanco para tarjeta */
            --text-dark: #221c1d;        /* Texto principal */
            --text-muted: #6b6364;       /* Texto descriptivo */
            --border: #ebdccb;           /* Bordes suaves */
            --shadow-lg: 0 12px 32px rgba(88, 6, 15, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            background-image: radial-gradient(circle at 10% 20%, rgba(128, 12, 25, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(229, 169, 16, 0.08) 0%, transparent 40%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Barra de Retorno Superior */
        .top-return-bar {
            background-color: var(--primary-dark);
            padding: 0.6rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--accent-gold);
        }

        .top-return-bar a {
            color: #faebd7;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s;
        }

        .top-return-bar a:hover {
            color: var(--accent-gold);
        }

        .top-return-bar span {
            color: #ebdccb;
            font-size: 0.82rem;
        }

        /* Contenedor Principal Centrado */
        .login-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }

        .login-card {
            background: var(--surface);
            border-radius: 14px;
            border: 1px solid var(--border);
            border-top: 6px solid var(--primary);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 460px;
            padding: 2.8rem 2.2rem;
            text-align: center;
            position: relative;
        }

        /* Logo de la Institución */
        .login-logo-link {
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .login-logo {
            height: 90px;
            width: auto;
            max-width: 320px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 2px 5px rgba(0,0,0,0.1));
        }

        .login-card h2 {
            color: var(--primary);
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0.4rem;
        }

        .login-card p.subtitle {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin-bottom: 1.8rem;
        }

        /* Alerta de Error */
        .alert-error {
            background-color: #fff5f5;
            color: #c53030;
            border: 1px solid #feb2b2;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            word-break: break-word;
        }

        /* Estilos del Formulario */
        .form-group {
            text-align: left;
            margin-bottom: 1.4rem;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: #faf7f2;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(128, 12, 25, 0.15);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
            font-size: 0.85rem;
        }

        .form-options label {
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
        }

        .form-options a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .form-options a:hover {
            text-decoration: underline;
        }

        .btn-login {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 0.9rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(128, 12, 25, 0.25);
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(128, 12, 25, 0.35);
        }

        .login-footer {
            margin-top: 1.8rem;
            padding-top: 1.4rem;
            border-top: 1px solid var(--border);
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Pie de página */
        .simple-footer {
            background-color: var(--primary-dark);
            color: #c9b8a8;
            text-align: center;
            padding: 1.2rem;
            font-size: 0.82rem;
            border-top: 2px solid var(--accent-gold);
        }
    </style>
</head>
<body>

    <!-- Franja Superior de Retorno -->
    <div class="top-return-bar">
        <a href="index.html">
            <span>&larr;</span> Volver al Portal Institucional
        </a>
        <span>I.E.P. San Ignacio de Loyola - Sicuani</span>
    </div>

    <!-- Tarjeta Login -->
    <div class="login-wrapper">
        <div class="login-card">

            <a href="index.html" class="login-logo-link" title="Ir a la portada">
                <img src="img/logo.jpg" alt="I.E.P. San Ignacio de Loyola" class="login-logo"
                     onerror="if (!this.t1) { this.t1=true; this.src='img/logo.png'; } else if (!this.t2) { this.t2=true; this.src='img/logo'; } else { this.src='logo.jpg'; }">
            </a>

            <h2>Intranet Directiva</h2>
            <p class="subtitle">Panel de gestión y administración escolar institucional</p>

            <!-- Mensaje de Error (si ocurre) -->
            <?php if (!empty($error_mensaje)): ?>
                <div class="alert-error">
                    <span>⚠️</span>
                    <span><?= htmlspecialchars($error_mensaje) ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Correo Institucional *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="correo@sanignacio.edu.pe" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña de Seguridad *</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="recordar"> Recordar sesión
                    </label>
                    <a href="contacto.html">¿Olvidó su clave?</a>
                </div>

                <button type="submit" class="btn-login">Ingresar al Sistema</button>
            </form>

            <div class="login-footer">
                ¿No dispone de credenciales? <a href="registro.php">Registrar nuevo usuario</a>
            </div>

        </div>
    </div>

    <!-- Pie de página -->
    <footer class="simple-footer">
        <p>&copy; 2026 I.E.P. San Ignacio de Loyola - Sicuani | R.D. USE-C-N° 214-2002. Todos los derechos reservados.</p>
    </footer>

</body>
</html>