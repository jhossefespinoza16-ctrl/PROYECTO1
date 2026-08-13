<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

// Verificar que sea admin
if (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 1) {
    header("Location: ../auth/login.php");
    exit();
}

$busesCount = $conexion->query("SELECT COUNT(*) AS total FROM buses")->fetch_assoc()['total'];
$rutasCount = $conexion->query("SELECT COUNT(*) AS total FROM rutas")->fetch_assoc()['total'];
$viajesCount = $conexion->query("SELECT COUNT(*) AS total FROM viajes WHERE fecha_salida >= CURDATE()")->fetch_assoc()['total'];
$pasajesCount = $conexion->query("SELECT COUNT(*) AS total FROM pasajes WHERE estado_pasaje = 'Reservado'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - BusSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar topbar-custom navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚌 Admin <span style="color: #fff;">BusSystem</span></a>
            <div class="d-flex gap-3 align-items-center">
                <span style="color: rgba(255,255,255,0.9); font-size: 14px; font-weight: 600;">
                    👤 <?= htmlspecialchars($_SESSION['nombres'] ?? 'Admin') ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">🚪 Cerrar</a>
            </div>
        </div>
    </nav>

    <main class="admin-container">
        <div class="row gy-4">
            <div class="col-12">
                <div class="hero-panel">
                    <h1 class="page-title">📊 Dashboard Administrativo</h1>
                    <p class="page-subtitle">Supervisa buses, rutas, viajes y reservas desde una sola interfaz moderna y amigable.</p>
                </div>
            </div>

            <!-- Tarjetas de Métricas -->
            <div class="col-md-6 col-xl-3">
                <div class="metric-card">
                    <p class="metric-label">🚌 Buses Registrados</p>
                    <p class="metric-value"><?= number_format($busesCount) ?></p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="metric-card">
                    <p class="metric-label">🗺️ Rutas Disponibles</p>
                    <p class="metric-value"><?= number_format($rutasCount) ?></p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="metric-card">
                    <p class="metric-label">⏰ Viajes Próximos</p>
                    <p class="metric-value"><?= number_format($viajesCount) ?></p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="metric-card">
                    <p class="metric-label">🎫 Reservas Activas</p>
                    <p class="metric-value"><?= number_format($pasajesCount) ?></p>
                </div>
            </div>

            <!-- Tarjetas de Acciones -->
            <div class="col-12">
                <div class="row g-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="action-card">
                            <h5>🚌 Gestionar Buses</h5>
                            <p class="text-muted">Agrega, edita o elimina unidades disponibles en tu flota.</p>
                            <a href="gestionar_buses.php" class="btn btn-primary mt-auto">Abrir</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="action-card">
                            <h5>🗺️ Gestionar Rutas</h5>
                            <p class="text-muted">Crea nuevas rutas, ajusta precios y destinos fácilmente.</p>
                            <a href="gestionar_rutas.php" class="btn btn-primary mt-auto">Abrir</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="action-card">
                            <h5>⏰ Programar Viajes</h5>
                            <p class="text-muted">Programa horarios, asigna buses y gestiona disponibilidad.</p>
                            <a href="programar_viaje.php" class="btn btn-primary mt-auto">Abrir</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="action-card">
                            <h5>📈 Reportes</h5>
                            <p class="text-muted">Consulta ingresos, estadísticas y análisis de ventas.</p>
                            <a href="reportes.php" class="btn btn-primary mt-auto">Abrir</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="action-card">
                            <h5>👥 Gestionar Usuarios</h5>
                            <p class="text-muted">Administra cuentas de usuarios y permisos del sistema.</p>
                            <a href="gestionar_usuarios.php" class="btn btn-primary mt-auto">Abrir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
    </main>

    <footer class="page-footer">
        <div class="container">BusSystem © 2026 · Sistema administrativo moderno</div>
    </footer>
</body>
</html>