<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$reporte = $conexion->query("SELECT SUM(p.precio_pagado) as total FROM pasajes p 
                             JOIN viajes v ON p.id_viaje = v.id_viaje 
                             JOIN rutas ru ON v.id_ruta = ru.id_ruta 
                             WHERE p.estado_pasaje = 'Pagado'");
$total = $reporte->fetch_assoc();
$ingresos = floatval($total['total']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="app-shell">
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin MC JHOSS</a>
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted">Usuario: <?= htmlspecialchars($_SESSION['nombres']) ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="hero-panel p-4 mb-4">
            <h1 class="page-title">Reportes de Ventas</h1>
            <p class="page-subtitle">Sigue el desempeño económico de las reservas pagadas.</p>
            <div class="page-actions">
                <a href="dashboard.php" class="back-link">Regresar al Dashboard</a>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-md-6">
                <div class="metric-card">
                    <p class="metric-label">Ingresos totales</p>
                    <p class="metric-value">$<?= number_format($ingresos, 2) ?></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>