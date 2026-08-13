<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$id_bus = intval($_GET['id'] ?? 0);
if ($id_bus <= 0) {
    header('Location: gestionar_buses.php');
    exit();
}

$stmt = $conexion->prepare('SELECT * FROM buses WHERE id_bus = ?');
$stmt->bind_param('i', $id_bus);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

if (!$bus) {
    header('Location: gestionar_buses.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="app-shell">
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="gestionar_buses.php">Admin MC JHOSS</a>
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted">Usuario: <?= htmlspecialchars($_SESSION['nombres']) ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="hero-panel p-4 mb-4">
            <h1 class="page-title">Editar Bus</h1>
            <p class="page-subtitle">Actualiza los datos del vehículo seleccionado.</p>
            <div class="page-actions">
                <a href="gestionar_buses.php" class="back-link">Regresar a Buses</a>
            </div>
        </div>
        <div class="card p-4">
            <form action="procesar_bus.php" method="POST" class="row g-3">
                <input type="hidden" name="id_bus" value="<?= intval($bus['id_bus']) ?>">
                <div class="col-md-4">
                    <label class="form-label">Placa</label>
                    <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($bus['placa']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Asientos</label>
                    <input type="number" name="capacidad_asientos" class="form-control" value="<?= htmlspecialchars($bus['capacidad_asientos']) ?>" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($bus['modelo']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="Activo" <?= $bus['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Mantenimiento" <?= $bus['estado'] === 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                        <option value="Inactivo" <?= $bus['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <a href="gestionar_buses.php" class="btn btn-secondary me-2">Volver</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
