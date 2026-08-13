<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$usuario_id = $_SESSION['id_usuario'];
$nombre = htmlspecialchars($_SESSION['nombres'] ?? 'Usuario');

$viajesCount = $conexion->query("SELECT COUNT(*) AS total FROM viajes WHERE fecha_salida >= CURDATE()")->fetch_assoc()['total'];

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM pasajes WHERE id_usuario = ? AND estado_pasaje = 'Reservado'");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$reservasCount = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$stmt = $conexion->prepare("SELECT COALESCE(SUM(precio_pagado), 0) AS total FROM pasajes WHERE id_usuario = ? AND estado_pasaje = 'Reservado'");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$totalGastado = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$stmt = $conexion->prepare("SELECT v.fecha_salida, v.hora_salida, r.origen, r.destino, p.numero_asiento FROM pasajes p JOIN viajes v ON p.id_viaje = v.id_viaje JOIN rutas r ON v.id_ruta = r.id_ruta WHERE p.id_usuario = ? AND p.estado_pasaje = 'Reservado' AND v.fecha_salida >= CURDATE() ORDER BY v.fecha_salida ASC LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$proximoViaje = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - BusSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚌 BusSystem</a>
            <div class="d-flex gap-3 align-items-center">
                <span style="color: var(--text); font-size: 14px; font-weight: 600;">
                    👤 Hola, <?= $nombre ?>
                </span>
                <a href="../auth/logout.php" class="btn-cerrar" style="display: inline-block;">🚪 Cerrar</a>
            </div>
        </div>
    </nav>

    <main class="admin-container">
        <div class="row gy-4">
            <div class="col-12">
                <div class="hero-panel">
                    <h1 class="page-title">👤 Panel de Usuario</h1>
                    <p class="page-subtitle">Tu panel de control personal para ver viajes, reservas y próximos itinerarios.</p>
                </div>
            </div>

            <!-- Tarjetas de Métricas -->
            <div class="col-md-4">
                <div class="metric-card">
                    <p class="metric-label">✈️ Viajes Disponibles</p>
                    <p class="metric-value"><?= number_format($viajesCount) ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <p class="metric-label">🎫 Reservas Activas</p>
                    <p class="metric-value"><?= number_format($reservasCount) ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card">
                    <p class="metric-label">💰 Gasto Estimado</p>
                    <p class="metric-value">$<?= number_format($totalGastado, 2) ?></p>
                </div>
            </div>

            <!-- Próximo Viaje y Acciones Rápidas -->
            <div class="col-lg-6">
                <div class="card summary-card">
                    <h5 class="mb-3">🚌 Próximo Viaje</h5>
                    <?php if ($proximoViaje): ?>
                        <div class="mb-3">
                            <p class="mb-2"><strong>🗺️ Ruta:</strong> <br>
                                <span style="color: var(--primary); font-weight: 700;">
                                    <?= htmlspecialchars($proximoViaje['origen']) ?> → <?= htmlspecialchars($proximoViaje['destino']) ?>
                                </span>
                            </p>
                            <p class="mb-2"><strong>📅 Fecha:</strong> <?= htmlspecialchars($proximoViaje['fecha_salida']) ?></p>
                            <p class="mb-2"><strong>⏰ Hora:</strong> <?= htmlspecialchars($proximoViaje['hora_salida']) ?></p>
                            <p class="mb-0"><strong>💺 Asiento:</strong> <span style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 800;"><?= htmlspecialchars($proximoViaje['numero_asiento']) ?></span></p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0" style="border-radius: 12px;">
                            ℹ️ No tienes viajes reservados próximos.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card summary-card">
                    <h5 class="mb-3">⚡ Acciones Rápidas</h5>
                    <div class="d-grid gap-3">
                        <a href="index.php" class="btn btn-primary" style="height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 700;">🔍 Ver Viajes Disponibles</a>
                        <a href="mis_viajes.php" class="btn btn-outline-light" style="height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary); border: 2px solid var(--primary);">📋 Mis Viajes</a>
                        <a href="index.php" class="btn btn-success" style="height: 45px; display: flex; align-items: center; justify-content: center; font-weight: 700;">🎫 Reservar Nuevo</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
    </main>

    <footer class="page-footer">
        <div class="container">BusSystem © 2026 · Tu panel de reservas</div>
    </footer>
</body>
</html>
