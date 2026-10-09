<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            /* Paleta cromática oficial de la I.E.P. San Ignacio de Loyola */
            --primary: #800c19;          /* Granate institucional */
            --primary-dark: #58060f;     /* Granate profundo */
            --primary-light: #a31928;    /* Granate claro */
            --accent-gold: #e5a910;      /* Dorado del blasón */
            --accent-gold-hover: #c48e06;
            --accent-blue: #0f4c81;      /* Azul institucional */
            --bg: #fbf8f2;               /* Marfil pergamino */
            --surface: #ffffff;          /* Blanco */
            --text-dark: #221c1d;        /* Texto principal */
            --text-muted: #6b6364;       /* Texto descriptivo */
            --border: #ebdccb;           /* Borde pergamino suave */
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

        /* Barra superior de retorno */
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

        /* Envoltorio centrado */
        .registro-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }

        .registro-card {
            background: var(--surface);
            border-radius: 14px;
            border: 1px solid var(--border);
            border-top: 6px solid var(--primary);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 520px;
            padding: 2.8rem 2.4rem;
            text-align: center;
        }

        /* Logotipo oficial */
        .logo-link {
            display: inline-block;
            margin-bottom: 1.2rem;
        }

        .logo-img {
            height: 85px;
            width: auto;
            max-width: 320px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 2px 5px rgba(0,0,0,0.1));
        }

        .registro-card h2 {
            color: var(--primary);
            font-size: 1.65rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .registro-card p.subtitle {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin-bottom: 1.8rem;
        }

        /* Formulario */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.3rem;
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

        .btn-registro {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 0.9rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 0.6rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(128, 12, 25, 0.25);
        }

        .btn-registro:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(128, 12, 25, 0.35);
        }

        .registro-footer {
            margin-top: 1.8rem;
            padding-top: 1.4rem;
            border-top: 1px solid var(--border);
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .registro-footer a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .registro-footer a:hover {
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

        @media (max-width: 540px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .registro-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Franja superior de navegación -->
    <div class="top-return-bar">
        <a href="index.html">
            <span>&larr;</span> Volver al Portal Institucional
        </a>
        <span>I.E.P. San Ignacio de Loyola - Sicuani</span>
    </div>

    <!-- Contenedor del Registro -->
    <div class="registro-wrapper">
        <div class="registro-card">

            <!-- Logo oficial -->
            <a href="index.html" class="logo-link" title="Ir a la portada">
                <img src="img/logo.jpg" alt="I.E.P. San Ignacio de Loyola" class="logo-img"
                     onerror="if (!this.t1) { this.t1=true; this.src='img/logo.png'; } else if (!this.t2) { this.t2=true; this.src='img/logo'; } else { this.src='logo.jpg'; }">
            </a>

            <h2>Crear Cuenta Institucional</h2>
            <p class="subtitle">Registro de Nuevo Usuario en la Plataforma Institucional</p>

            <!-- Formulario con los campos de tu captura -->
            <form action="registro_proceso.php" method="POST">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ej. Isabel" required>
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Ej. Oviedo" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="correo@sanignacio.edu.pe" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono / Celular *</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ej. 984123456" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                </div>

                <button type="submit" class="btn-registro">Guardar y Registrar</button>
            </form>

            <div class="registro-footer">
                ¿Ya tienes una cuenta registrada? <a href="login.php">Inicia sesión aquí</a>
            </div>

        </div>
    </div>

    <!-- Pie de página -->
    <footer class="simple-footer">
        <p>&copy; 2026 I.E.P. San Ignacio de Loyola - Sicuani | R.D. USE-C-N° 214-2002. Todos los derechos reservados.</p>
    </footer>

</body>
</html>