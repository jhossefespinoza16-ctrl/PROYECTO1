<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$buses = $conexion->query("SELECT id_bus, placa FROM buses ORDER BY placa ASC");
$rutas = $conexion->query("SELECT id_ruta, origen, destino FROM rutas ORDER BY origen ASC");
$mensaje = $_GET['mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programar Viaje</title>
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
        <?php if ($mensaje === 'creado') : ?>
            <div class="alert alert-success alert-float">Viaje programado correctamente.</div>
        <?php endif; ?>

        <div class="row gy-4">
            <div class="col-12">
                <div class="hero-panel p-4">
                    <h1 class="page-title">Programar Nuevo Viaje</h1>
                    <p class="page-subtitle">Define la ruta, el bus y la fecha para un nuevo viaje.</p>
                    <div class="page-actions">
                        <a href="dashboard.php" class="back-link">Regresar al Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-4">
                    <form action="procesar_viaje.php" method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bus</label>
                            <select name="bus_id" class="form-select" required>
                                <option value="">Seleccione un bus</option>
                                <?php while ($b = $buses->fetch_assoc()) : ?>
                                    <option value="<?= intval($b['id_bus']) ?>"><?= htmlspecialchars($b['placa']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ruta</label>
                            <select name="ruta_id" class="form-select" required>
                                <option value="">Seleccione una ruta</option>
                                <?php while ($r = $rutas->fetch_assoc()) : ?>
                                    <option value="<?= intval($r['id_ruta']) ?>"><?= htmlspecialchars($r['origen']) ?> a <?= htmlspecialchars($r['destino']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de salida</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hora de salida</label>
                            <input type="time" name="hora" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Asientos disponibles</label>
                            <input type="number" name="asientos" class="form-control" placeholder="Cantidad" min="1" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Programar Viaje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>