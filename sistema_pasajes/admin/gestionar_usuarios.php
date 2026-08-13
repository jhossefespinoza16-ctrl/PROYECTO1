<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';
$usuarios = $conexion->query("SELECT id_usuario, nombres, apellidos, correo, id_rol FROM usuarios ORDER BY nombres ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
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
            <h1 class="page-title">Usuarios registrados</h1>
            <p class="page-subtitle">Revisa los usuarios activos y su rol dentro del sistema.</p>
            <div class="page-actions">
                <a href="dashboard.php" class="back-link">Regresar al Dashboard</a>
            </div>
        </div>

        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = $usuarios->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']) ?></td>
                                <td><?= htmlspecialchars($u['correo']) ?></td>
                                <td><?= $u['id_rol'] === '1' || $u['id_rol'] === 1 ? 'Administrador' : 'Cliente' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>