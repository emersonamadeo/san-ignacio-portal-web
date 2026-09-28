<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/conexion.php';

$database = new Database();
$db =$database->getConnection();

$totalUsuarios = 0;
$totalSolicitudes = 0;
$totalNoticias = 0;

if ($db) {
    try {
        $totalUsuarios    = (int)$db->query("SELECT COUNT(*) FROM usuarios WHERE activo = 1")->fetchColumn();
        $totalSolicitudes = (int)$db->query("SELECT COUNT(*) FROM solicitudes_admision WHERE estado = 'Pendiente'")->fetchColumn();
        $totalNoticias    = (int)$db->query("SELECT COUNT(*) FROM noticias_eventos")->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error al cargar métricas: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            --primary: #1a365d;
            --primary-dark: #0f233d;
            --primary-light: #2b6cb0;
            --accent: #d69e2e;
            --bg: #f8fafc;
            --surface: #ffffff;
            --text-main: #2d3748;
            --text-muted: #718096;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Barra Lateral (Sidebar) */
        .sidebar {
            width: 250px;
            background-color: var(--primary-dark);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            flex-shrink: 0;
        }

        .sidebar-brand {
            font-size: 1.15rem;
            font-weight: 700;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 1.5rem;
            text-align: center;
            color: #ffffff;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-menu a {
            display: block;
            color: #cbd5e0;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.92rem;
            transition: all 0.2s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: var(--primary-light);
            color: #ffffff;
        }

        /* Contenido Principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .top-navbar {
            background: var(--surface);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .role-tag {
            background-color: #ebf8ff;
            color: #2b6cb0;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn-exit {
            background: #fed7d7;
            color: #9b2c2c;
            text-decoration: none;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .btn-exit:hover {
            background: #feb2b2;
        }

        .dashboard-body {
            padding: 2rem;
        }

        .dashboard-title {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.35rem;
        }

        .dashboard-sub {
            color: var(--text-muted);
            font-size: 0.92rem;
            margin-bottom: 2rem;
        }

        /* Grid de Métricas */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .card-stat {
            background: var(--surface);
            padding: 1.75rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-light);
        }

        .card-stat h4 {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        .card-stat .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
        }

        /* Cuadro de bienvenida informativa */
        .info-panel {
            background: var(--surface);
            padding: 1.75rem;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .info-panel h3 {
            color: var(--primary);
            font-size: 1.15rem;
            margin-bottom: 0.75rem;
        }

        .info-panel p {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.92rem;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">I.E.P. San Ignacio</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">Panel Principal</a></li>
            <li><a href="../index.html">Ver Sitio Web</a></li>
            <li><a href="../admision.html">Módulo Admisión</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="top-navbar">
            <div class="navbar-title"><strong>Módulo de Administración Directiva</strong></div>
            <div class="user-badge">
                <span><?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?></span>
                <span class="role-tag"><?= htmlspecialchars($_SESSION['rol_nombre'] ?? 'Directivo') ?></span>
                <a href="logout.php" class="btn-exit">Cerrar Sesión</a>
            </div>
        </header>

        <main class="dashboard-body">
            <h1 class="dashboard-title">Resumen Institucional en Tiempo Real</h1>
            <p class="dashboard-sub">Estado general de la plataforma de la I.E.P. "San Ignacio de Loyola" - Sicuani</p>

            <section class="cards-grid">
                <article class="card-stat">
                    <h4>Usuarios del Sistema</h4>
                    <div class="stat-number"><?= $totalUsuarios ?></div>
                </article>

                <article class="card-stat">
                    <h4>Vacantes en Espera</h4>
                    <div class="stat-number"><?= $totalSolicitudes ?></div>
                </article>

                <article class="card-stat">
                    <h4>Noticias / Circulares</h4>
                    <div class="stat-number"><?= $totalNoticias ?></div>
                </article>
            </section>

            <section class="info-panel">
                <h3>Control de Integridad y Persistencia</h3>
                <p>
                    Las métricas mostradas provienen directamente de consultas de agregación (`SELECT COUNT(*)`) ejecutadas sobre la base de datos relacional <strong>bd_san_ignacio</strong> mediante conector <strong>PDO</strong>, garantizando sincronización inmediata ante nuevos registros de alumnos, postulaciones o avisos directivos.
                </p>
            </section>
        </main>
    </div>

</body>
</html>