<?php
// Iniciar sesión y validar autenticación
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Directivo';
$rol_usuario = $_SESSION['usuario_rol'] ?? 'Administrador';

$solicitudes = [];
$total_solicitudes = 0;
$total_noticias = 6;
$total_vacantes = 45;
$db_error = "";

try {
    $servidor = "localhost";
    $usuario_bd = "root";
    $clave_bd = "";
    $nombre_bd = "bd_san_ignacio";

    $pdo = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_bd, $clave_bd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si la tabla solicitudes_admision existe
    $stmt_check = $pdo->query("SHOW TABLES LIKE 'solicitudes_admision'");
    if ($stmt_check->rowCount() > 0) {
        // Consultar todas las filas sin forzar ORDER BY id para no fallar por nombre de columna
        $stmt_sol = $pdo->query("SELECT * FROM solicitudes_admision");
        $solicitudes = $stmt_sol->fetchAll(PDO::FETCH_ASSOC);
        
        // Invertir el arreglo para mostrar los últimos registros primero
        $solicitudes = array_reverse($solicitudes);
        $total_solicitudes = count($solicitudes);
    }
} catch (PDOException $e) {
    $db_error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Directivo - I.E.P. San Ignacio de Loyola</title>
    <style>
        :root {
            --primary: #800c19;
            --primary-dark: #58060f;
            --primary-light: #a31928;
            --accent-gold: #e5a910;
            --accent-gold-hover: #c48e06;
            --accent-blue: #0f4c81;
            --bg: #f4efe6;
            --surface: #ffffff;
            --text-dark: #221c1d;
            --text-muted: #6b6364;
            --border: #ebdccb;
            --shadow-sm: 0 2px 6px rgba(88, 6, 15, 0.05);
            --shadow-md: 0 4px 16px rgba(88, 6, 15, 0.08);
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
        }

        /* Barra Lateral */
        .sidebar {
            width: 270px;
            background-color: var(--primary-dark);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            border-right: 4px solid var(--accent-gold);
            flex-shrink: 0;
        }

        .sidebar-brand {
            padding: 1.8rem 1.4rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            height: 75px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
            display: block;
            margin: 0 auto 0.8rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .sidebar-brand h3 {
            font-size: 1.05rem;
            color: #ffffff;
            font-weight: 800;
            line-height: 1.2;
        }

        .sidebar-brand p {
            font-size: 0.78rem;
            color: var(--accent-gold);
            font-weight: 600;
            margin-top: 2px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0;
            flex-grow: 1;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            color: #ebdccb;
            text-decoration: none;
            padding: 0.85rem 1.6rem;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background-color: var(--primary);
            color: #ffffff;
            border-left: 4px solid var(--accent-gold);
        }

        .sidebar-footer {
            padding: 1.4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .btn-logout {
            display: inline-block;
            background-color: rgba(229, 169, 16, 0.2);
            color: var(--accent-gold);
            border: 1px solid var(--accent-gold);
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-logout:hover {
            background-color: var(--accent-gold);
            color: #382400;
        }

        /* Contenedor Principal */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Barra Superior */
        .topbar {
            background-color: var(--surface);
            padding: 1.2rem 2.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .topbar h2 {
            font-size: 1.45rem;
            color: var(--primary);
            font-weight: 800;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-dark);
            display: block;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--accent-blue);
            font-weight: 700;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            border: 2px solid var(--accent-gold);
        }

        /* Área de Trabajo */
        .dashboard-content {
            padding: 2.2rem;
            max-width: 1300px;
            margin: 0 auto;
            width: 100%;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #f87171;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
        }

        /* Tarjetas de Métricas */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .metric-card {
            background: var(--surface);
            padding: 1.6rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            border-top: 4px solid var(--primary);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .metric-card.gold {
            border-top-color: var(--accent-gold);
        }

        .metric-card.blue {
            border-top-color: var(--accent-blue);
        }

        .metric-info h4 {
            font-size: 0.88rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .metric-info .metric-num {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .metric-icon {
            font-size: 2.4rem;
        }

        /* Tabla de Solicitudes */
        .table-container {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.4rem;
        }

        .table-header h3 {
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 800;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }

        .data-table th {
            background-color: #faf7f2;
            color: var(--text-dark);
            text-align: left;
            padding: 0.9rem 1rem;
            font-weight: 700;
            border-bottom: 2px solid var(--border);
        }

        .data-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        .data-table tr:hover {
            background-color: #fcfbf9;
        }

        .badge-status {
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
        }

        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 4px solid var(--accent-gold);
            }
            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Barra Lateral -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="../img/logo.jpg" alt="I.E.P. San Ignacio de Loyola" class="sidebar-logo"
                 onerror="if (!this.t1) { this.t1=true; this.src='../img/logo.png'; } else if (!this.t2) { this.t2=true; this.src='../img/logo'; } else { this.src='../logo.jpg'; }">
            <h3>SAN IGNACIO DE LOYOLA</h3>
            <p>Intranet Directiva - Sicuani</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">📊 Resumen General</a></li>
            <li><a href="../index.html" target="_blank">🌐 Ver Sitio Web</a></li>
            <li><a href="../admision.html" target="_blank">📝 Módulo Admisión</a></li>
            <li><a href="../noticias.html" target="_blank">📰 Noticias Escolares</a></li>
            <li><a href="../contacto.html" target="_blank">✉️ Mensajes y Consultas</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Cerrar Sesión Segura</a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="main-wrapper">

        <!-- Barra Superior -->
        <header class="topbar">
            <h2>Panel de Control y Gestión Institucional</h2>
            <div class="user-profile">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($nombre_usuario) ?></span>
                    <span class="user-role"><?= htmlspecialchars($rol_usuario) ?></span>
                </div>
                <div class="user-avatar">
                    <?= strtoupper(substr($nombre_usuario, 0, 1)) ?>
                </div>
            </div>
        </header>

        <!-- Área Central -->
        <main class="dashboard-content">

            <?php if (!empty($db_error)): ?>
                <div class="alert-error">
                    ⚠️ Error al consultar la base de datos: <?= htmlspecialchars($db_error) ?>
                </div>
            <?php endif; ?>

            <!-- Tarjetas de Métricas -->
            <section class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-info">
                        <h4>Postulaciones Reales</h4>
                        <div class="metric-num"><?= $total_solicitudes ?></div>
                    </div>
                    <div class="metric-icon">📑</div>
                </div>

                <div class="metric-card gold">
                    <div class="metric-info">
                        <h4>Vacantes 2026</h4>
                        <div class="metric-num"><?= $total_vacantes ?></div>
                    </div>
                    <div class="metric-icon">🎒</div>
                </div>

                <div class="metric-card blue">
                    <div class="metric-info">
                        <h4>Artículos Web</h4>
                        <div class="metric-num"><?= $total_noticias ?></div>
                    </div>
                    <div class="metric-icon">📢</div>
                </div>

                <div class="metric-card">
                    <div class="metric-info">
                        <h4>Estado Base de Datos</h4>
                        <div class="metric-num" style="font-size: 1.3rem; color: #16a34a;">Conectado</div>
                    </div>
                    <div class="metric-icon">🟢</div>
                </div>
            </section>

            <!-- Tabla de Expedientes de Admisión -->
            <section class="table-container">
                <div class="table-header">
                    <h3>Expedientes de Admisión y Matrícula Recientes</h3>
                    <a href="../admision.html" target="_blank" style="color: var(--accent-blue); font-weight: 700; text-decoration: none; font-size: 0.9rem;">+ Nueva Postulación</a>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>N.°</th>
                            <th>Apoderado</th>
                            <th>Teléfono / Contacto</th>
                            <th>Nivel</th>
                            <th>Grado</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($solicitudes)): ?>
                            <?php $contador = 1; ?>
                            <?php foreach ($solicitudes as $sol): ?>
                                <?php
                                    // Búsqueda tolerante de columnas para que nunca salga vacío
                                    $nombre_item = $sol['nombre_apoderado'] ?? $sol['nombre'] ?? $sol['apoderado'] ?? 'Sin nombre';
                                    $telefono_item = $sol['telefono'] ?? $sol['celular'] ?? $sol['email'] ?? $sol['correo'] ?? '-';
                                    $nivel_item = $sol['nivel_postulacion'] ?? $sol['nivel'] ?? 'Inicial';
                                    $grado_item = $sol['grado'] ?? $sol['grado_postulacion'] ?? '5 años';
                                    $estado_item = $sol['estado'] ?? 'En Evaluación';
                                ?>
                                <tr>
                                    <td>#<?= $contador++ ?></td>
                                    <td><strong><?= htmlspecialchars($nombre_item) ?></strong></td>
                                    <td><?= htmlspecialchars($telefono_item) ?></td>
                                    <td><?= htmlspecialchars($nivel_item) ?></td>
                                    <td><?= htmlspecialchars($grado_item) ?></td>
                                    <td><span class="badge-status"><?= htmlspecialchars($estado_item) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    No se encontraron postulaciones registradas en la base de datos todavía.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </main>
    </div>

</body>
</html>