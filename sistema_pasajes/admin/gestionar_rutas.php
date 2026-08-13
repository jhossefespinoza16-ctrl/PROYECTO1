<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';
$rutas = $conexion->query("SELECT * FROM rutas ORDER BY id_ruta DESC");
$mensaje = $_GET['mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Rutas</title>
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
        <?php if ($mensaje === 'registrado') : ?>
            <div class="alert alert-success alert-float">Ruta creada correctamente.</div>
        <?php elseif ($mensaje === 'actualizado') : ?>
            <div class="alert alert-success alert-float">Ruta actualizada correctamente.</div>
        <?php elseif ($mensaje === 'eliminado') : ?>
            <div class="alert alert-success alert-float">Ruta eliminada correctamente.</div>
        <?php endif; ?>

        <div class="row gy-4">
            <div class="col-12">
                <div class="hero-panel p-4">
                    <h1 class="page-title">Gestión de Rutas</h1>
                    <p class="page-subtitle">Crea nuevas rutas y administra las rutas existentes en el sistema.</p>
                    <div class="page-actions">
                        <a href="dashboard.php" class="back-link">Regresar al Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-4 mb-4">
                    <form action="procesar_ruta.php" method="POST" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Origen</label>
                            <input type="text" name="origen" class="form-control" placeholder="Ciudad de origen" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Destino</label>
                            <input type="text" name="destino" class="form-control" placeholder="Ciudad de destino" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Duración</label>
                            <input type="text" name="duracion" class="form-control" placeholder="Ej. 03:30" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Precio base</label>
                            <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-success w-100">Crear Ruta</button>
                        </div>
                    </form>
                </div>

                <div class="card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                    <th>Duración</th>
                                    <th>Precio</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($r = $rutas->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['origen']) ?></td>
                                        <td><?= htmlspecialchars($r['destino']) ?></td>
                                        <td><?= htmlspecialchars($r['duracion_estimada']) ?></td>
                                        <td>$<?= number_format($r['precio_base'], 2) ?></td>
                                        <td class="text-end">
                                            <a href="editar_ruta.php?id=<?= intval($r['id_ruta']) ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                            <a href="procesar_ruta.php?eliminar=<?= intval($r['id_ruta']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta ruta?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>