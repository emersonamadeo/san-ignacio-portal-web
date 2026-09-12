<?php
session_start();

// Control de seguridad: Si no hay sesión, expulsa al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Conexión a la base de datos para contar registros reales
require_once '../config/conexion.php';
$db = new Database();
$conn = $db->getConnection();

// 1. Contar usuarios registrados
$qUsers = $conn->query("SELECT COUNT(*) AS total FROM usuarios");
$totalUsuarios = $qUsers->fetch()['total'];

// 2. Contar solicitudes de admisión
$qAdm = $conn->query("SELECT COUNT(*) AS total FROM solicitudes_admision");
$totalSolicitudes = $qAdm->fetch()['total'];

// 3. Contar noticias publicadas
$qNot = $conn->query("SELECT COUNT(*) AS total FROM noticias_eventos");
$totalNoticias = $qNot->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo - I.E.P. San Ignacio</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .admin-layout { display: flex; min-height: 80vh; margin-top: 20px; gap: 20px; }
        .sidebar { width: 260px; background: #1a365d; color: white; padding: 25px; border-radius: 8px; }
        .sidebar h3 { color: #f6ad55; font-size: 16px; margin-bottom: 15px; text-transform: uppercase; }
        .sidebar a { display: block; color: #cbd5e0; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #2d3748; font-size: 14px; }
        .sidebar a:hover { color: white; padding-left: 5px; transition: 0.2s; }
        .content { flex: 1; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 5px solid #2b6cb0; }
        .stat-number { font-size: 36px; font-weight: bold; color: #1a365d; margin-top: 5px; }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <h1>I.E.P. SAN IGNACIO DE LOYOLA</h1>
            <span>Panel de Gestión y Control Institucional</span>
        </div>
        <nav>
            <span style="color: #cbd5e0; margin-right: 15px; font-size: 14px;">
                Sesión: <strong><?= htmlspecialchars($_SESSION['nombre']); ?></strong> (<?= htmlspecialchars($_SESSION['rol_nombre'] ?? 'Usuario'); ?>)
            </span>
            <a href="logout.php" style="background: #c53030; padding: 7px 14px; border-radius: 4px;">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="container admin-layout">
        <aside class="sidebar">
            <h3>Navegación CMS</h3>
            <a href="#">📊 Resumen General</a>
            <a href="#">👥 Usuarios del Sistema</a>
            <a href="#">📝 Solicitudes de Vacantes</a>
            <a href="#">📢 Publicaciones y Avisos</a>
            <a href="../index.html" target="_blank">🌐 Ir al Portal Público</a>
        </aside>

        <main class="content">
            <h2 style="color: #1a365d; margin-bottom: 5px;">Bienvenido(a), <?= htmlspecialchars($_SESSION['nombre']); ?></h2>
            <p style="color: #718096; margin-bottom: 25px;">Monitoreo en tiempo real sincronizado con la base de datos <code>bd_san_ignacio</code>.</p>

            <div class="grid-3">
                <div class="stat-card">
                    <h4>Usuarios Registrados</h4>
                    <div class="stat-number"><?= $totalUsuarios; ?></div>
                    <small style="color: #718096;">Cuentas activas en BD</small>
                </div>
                <div class="stat-card" style="border-left-color: #38a169;">
                    <h4>Solicitudes de Admisión</h4>
                    <div class="stat-number"><?= $totalSolicitudes; ?></div>
                    <small style="color: #718096;">Trámites de vacante</small>
                </div>
                <div class="stat-card" style="border-left-color: #d69e2e;">
                    <h4>Noticias y Circulares</h4>
                    <div class="stat-number"><?= $totalNoticias; ?></div>
                    <small style="color: #718096;">Publicaciones escolares</small>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; 2026 I.E.P. San Ignacio de Loyola - Sicuani.</p>
    </footer>
</body>
</html>