<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';
$buses = $conexion->query("SELECT * FROM buses ORDER BY id_bus DESC");
$mensaje = $_GET['mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Buses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    
    <nav class="navbar topbar-custom navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php" style="font-weight: 800; color: #ffffff; font-size: 24px; letter-spacing: -0.5px;">Admin <span style="color: #3b82f6;">MC JHOSS</span></a>
            <div class="d-flex gap-3 align-items-center">
                <span style="color: #94a3b8; font-size: 15px; font-weight: 700;">⚡ Conectado: <span style="color: #ffffff; background: #1e293b; padding: 6px 12px; border-radius: 8px; border: 1px solid #334155;"><?= htmlspecialchars($_SESSION['nombres']) ?></span></span>
                <a href="../auth/logout.php" class="btn btn-sm btn-danger" style="border-radius: 8px; font-weight: 800; padding: 8px 18px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <main class="admin-container">
        
        <?php if ($mensaje === 'registrado') : ?>
            <div class="alert alert-success" style="border-radius: 12px; background-color: #bbf7d0; color: #047857; border-left: 8px solid #059669; font-weight: 700; margin-bottom: 30px; padding: 20px; font-size: 16px;">🎉 ¡Excelente! Bus registrado correctamente en el sistema.</div>
        <?php elseif ($mensaje === 'actualizado') : ?>
            <div class="alert alert-success" style="border-radius: 12px; background-color: #bbf7d0; color: #047857; border-left: 8px solid #059669; font-weight: 700; margin-bottom: 30px; padding: 20px; font-size: 16px;">🔄 ¡Actualizado! Modificaciones guardadas correctamente.</div>
        <?php elseif ($mensaje === 'eliminado') : ?>
            <div class="alert alert-danger" style="border-radius: 12px; background-color: #fca5a5; color: #b91c1c; border-left: 8px solid #dc2626; font-weight: 700; margin-bottom: 30px; padding: 20px; font-size: 16px;">🗑️ Vehículo removido permanentemente de la flota.</div>
        <?php endif; ?>

        <header class="page-header">
            <div class="page-title">
                <h1>Gestión de Buses</h1>
                <p>Panel centralizado para el control operativo de la flota, asignación de unidades y estados técnicos.</p>
            </div>
            <a href="dashboard.php" class="btn-back">
                ← Volver al Menú
            </a>
        </header>

        <div class="card-premium">
            <h3 style="font-size: 20px; font-weight: 900; color: #0f172a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 0.5px;">⚡ REGISTRAR NUEVA UNIDAD</h3>
            <form action="procesar_bus.php" method="POST" class="row g-3 align-items-end">
                <div class="col-md-3 form-group">
                    <label class="form-label">Placa del Bus</label>
                    <input type="text" name="placa" class="form-control-plus" placeholder="Ej. ABC-123" required>
                </div>
                <div class="col-md-2 form-group">
                    <label class="form-label">N° de Asientos</label>
                    <input type="number" name="capacidad_asientos" class="form-control-plus" placeholder="Cant. Asientos" required>
                </div>
                <div class="col-md-3 form-group">
                    <label class="form-label">Modelo / Marca</label>
                    <input type="text" name="modelo" class="form-control-plus" placeholder="Ej. Mercedes-Benz O500" required>
                </div>
                <div class="col-md-2 form-group">
                    <label class="form-label">Estado Operativo</label>
                    <select name="estado" class="form-control-plus" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231e40af%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 14px top 50%; background-size: 12px auto; -webkit-appearance: none; appearance: none; padding-right: 32px;" required>
                        <option value="Activo">🟢 ACTIVO</option>
                        <option value="Mantenimiento">🟡 MANTENIMIENTO</option>
                        <option value="Inactivo">🔴 INACTIVO</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-primary-plus w-100" style="height: 56px;">Guardar Unidad</button>
                </div>
            </form>
        </div>

        <div class="table-premium-container">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Placa del Vehículo</th>
                        <th>Capacidad Máxima</th>
                        <th>Modelo / Línea</th>
                        <th>Estado de Disponibilidad</th>
                        <th class="text-end">Acciones de Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($b = $buses->fetch_assoc()) : ?>
                        <tr>
                            <td><strong style="color: #1e3a8a; font-size: 17px; font-family: 'Courier New', Courier, monospace; background: #dbeafe; padding: 6px 12px; border-radius: 8px; border: 2px solid #bfdbfe; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);"><?= htmlspecialchars($b['placa']) ?></strong></td>
                            <td><span class="badge-seats">💺 <?= htmlspecialchars($b['capacidad_asientos']) ?> Asientos</span></td>
                            <td style="font-weight: 700; color: #1e293b; font-size: 16px;"><?= htmlspecialchars($b['modelo']) ?></td>
                            <td>
                                <?php if ($b['estado'] === 'Activo') : ?>
                                    <span class="badge-status status-activo">● <?= htmlspecialchars($b['estado']) ?></span>
                                <?php elseif ($b['estado'] === 'Mantenimiento') : ?>
                                    <span class="badge-status status-warning">● <?= htmlspecialchars($b['estado']) ?></span>
                                <?php else : ?>
                                    <span class="badge-status status-inactivo">● <?= htmlspecialchars($b['estado']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="editar_bus.php?id=<?= intval($b['id_bus']) ?>" class="btn btn-sm" style="background-color: #f8fafc; color: #1e293b; border: 2px solid #94a3b8; font-weight: 800; border-radius: 8px; padding: 8px 16px; font-size: 14px; transition: all 0.2s; margin-right: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.borderColor='#475569';" onmouseout="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#94a3b8';">Editar</a>
                                <a href="procesar_bus.php?eliminar=<?= intval($b['id_bus']) ?>" class="btn btn-sm" style="background-color: #fff5f5; color: #c53030; border: 2px solid #feb2b2; font-weight: 800; border-radius: 8px; padding: 8px 16px; font-size: 14px; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);" onmouseover="this.style.backgroundColor='#fed7d7'; this.style.borderColor='#9b2c2c';" onmouseout="this.style.backgroundColor='#fff5f5'; this.style.borderColor='#feb2b2';" onclick="return confirm('¿Seguro que deseas eliminar este vehículo permanentemente?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>