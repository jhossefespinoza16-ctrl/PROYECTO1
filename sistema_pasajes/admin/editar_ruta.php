<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$id_ruta = intval($_GET['id'] ?? 0);
if ($id_ruta <= 0) {
    header('Location: gestionar_rutas.php');
    exit();
}

$stmt = $conexion->prepare('SELECT * FROM rutas WHERE id_ruta = ?');
$stmt->bind_param('i', $id_ruta);
$stmt->execute();
$result = $stmt->get_result();
$ruta = $result->fetch_assoc();

if (!$ruta) {
    header('Location: gestionar_rutas.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ruta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="app-shell">
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="gestionar_rutas.php">Admin MC JHOSS</a>
            <div class="d-flex gap-2 align-items-center">
                <span class="text-muted">Usuario: <?= htmlspecialchars($_SESSION['nombres']) ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="hero-panel p-4 mb-4">
            <h1 class="page-title">Editar Ruta</h1>
            <p class="page-subtitle">Actualiza la información de esta ruta.</p>
            <div class="page-actions">
                <a href="gestionar_rutas.php" class="back-link">Regresar a Rutas</a>
            </div>
        </div>
        <div class="card p-4">
            <form action="procesar_ruta.php" method="POST" class="row g-3">
                <input type="hidden" name="id_ruta" value="<?= intval($ruta['id_ruta']) ?>">
                <div class="col-md-4">
                    <label class="form-label">Origen</label>
                    <input type="text" name="origen" class="form-control" value="<?= htmlspecialchars($ruta['origen']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Destino</label>
                    <input type="text" name="destino" class="form-control" value="<?= htmlspecialchars($ruta['destino']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duración estimada</label>
                    <input type="text" name="duracion" class="form-control" value="<?= htmlspecialchars($ruta['duracion_estimada']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Precio base</label>
                    <input type="number" step="0.01" name="precio" class="form-control" value="<?= htmlspecialchars($ruta['precio_base']) ?>" required>
                </div>
                <div class="col-12 text-end">
                    <a href="gestionar_rutas.php" class="btn btn-secondary me-2">Volver</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
