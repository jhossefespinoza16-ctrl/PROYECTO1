<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$message = '';
$error = '';
$selectedSeat = null;
$paymentMethod = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $viaje_id = isset($_POST['id_viaje']) && ctype_digit($_POST['id_viaje']) ? (int)$_POST['id_viaje'] : null;
    $selectedSeat = isset($_POST['asiento']) && ctype_digit($_POST['asiento']) ? (int)$_POST['asiento'] : null;
    $paymentMethod = isset($_POST['metodo_pago']) ? trim($_POST['metodo_pago']) : '';
    $usuario_id = $_SESSION['id_usuario'];

    if (!$viaje_id || !$selectedSeat || !$paymentMethod) {
        $error = 'Selecciona un asiento válido y un método de pago antes de reservar.';
    } else {
        $check = $conexion->prepare("SELECT COUNT(*) as total FROM pasajes WHERE id_viaje = ? AND numero_asiento = ? AND estado_pasaje != 'Cancelado'");
        $check->bind_param("ii", $viaje_id, $selectedSeat);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            $error = 'El asiento seleccionado ya está ocupado. Elige otro asiento libre.';
        } else {
            $query = $conexion->prepare("SELECT v.id_viaje, v.asientos_disponibles, b.capacidad_asientos, b.placa, r.origen, r.destino, r.precio_base, v.fecha_salida, v.hora_salida FROM viajes v JOIN buses b ON v.id_bus = b.id_bus JOIN rutas r ON v.id_ruta = r.id_ruta WHERE v.id_viaje = ?");
            $query->bind_param("i", $viaje_id);
            $query->execute();
            $viaje = $query->get_result()->fetch_assoc();

            if (!$viaje) {
                $error = 'Viaje no encontrado.';
            } elseif ($selectedSeat < 1 || $selectedSeat > $viaje['capacidad_asientos']) {
                $error = 'El asiento seleccionado no existe en este bus.';
            } else {
                $precio = $viaje['precio_base'];
                $insert = $conexion->prepare("INSERT INTO pasajes (id_usuario, id_viaje, numero_asiento, fecha_reserva, precio_pagado, estado_pasaje) VALUES (?, ?, ?, NOW(), ?, 'Reservado')");
                $insert->bind_param("iiid", $usuario_id, $viaje_id, $selectedSeat, $precio);

                if ($insert->execute()) {
                    $message = '¡Reserva completada con éxito! Tu asiento seleccionado es el ' . $selectedSeat . '. Método de pago: ' . htmlspecialchars($paymentMethod) . '.';
                    $update = $conexion->prepare("UPDATE viajes SET asientos_disponibles = GREATEST(asientos_disponibles - 1, 0) WHERE id_viaje = ?");
                    $update->bind_param("i", $viaje_id);
                    $update->execute();
                } else {
                    $error = 'Error al guardar la reserva: ' . $conexion->error;
                }
            }
        }
    }
}

$viaje_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : ($viaje_id ?? null);
if (!$viaje_id) {
    echo '<div class="container mt-5"><div class="alert alert-danger">ID de viaje inválido.</div></div>';
    exit;
}

$query = $conexion->prepare("SELECT v.id_viaje, v.asientos_disponibles, b.capacidad_asientos, b.placa, r.origen, r.destino, r.precio_base, v.fecha_salida, v.hora_salida FROM viajes v JOIN buses b ON v.id_bus = b.id_bus JOIN rutas r ON v.id_ruta = r.id_ruta WHERE v.id_viaje = ?");
$query->bind_param("i", $viaje_id);
$query->execute();
$viaje = $query->get_result()->fetch_assoc();

if (!$viaje) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Viaje no encontrado.</div></div>';
    exit;
}

$reservas = $conexion->prepare("SELECT numero_asiento, id_usuario, estado_pasaje FROM pasajes WHERE id_viaje = ? AND estado_pasaje != 'Cancelado'");
$reservas->bind_param("i", $viaje_id);
$reservas->execute();
$result = $reservas->get_result();

$reservedSeats = [];
while ($row = $result->fetch_assoc()) {
    $reservedSeats[$row['numero_asiento']] = $row;
}

$capacity = (int)$viaje['capacidad_asientos'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar - MC JHOSS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚌 MC JHOSS</a>
            <div class="d-flex gap-3 align-items-center">
                <span style="color: var(--text); font-size: 14px; font-weight: 600;">
                    👤 <?= htmlspecialchars($_SESSION['nombres'] ?? 'Usuario') ?>
                </span>
                <a href="../auth/logout.php" class="btn-cerrar" style="display: inline-block;">🚪 Cerrar</a>
            </div>
        </div>
    </nav>

    <main class="admin-container">
        <div class="container mt-4">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
                    <div>
                        <h2 class="mb-1">Reservar asiento</h2>
                        <p class="mb-1">Ruta: <?= htmlspecialchars($viaje['origen']) ?> → <?= htmlspecialchars($viaje['destino']) ?></p>
                        <p class="mb-0">Fecha: <?= htmlspecialchars($viaje['fecha_salida']) ?> · Hora: <?= htmlspecialchars($viaje['hora_salida']) ?> · Bus: <?= htmlspecialchars($viaje['placa']) ?></p>
                    </div>
                    <div class="text-md-end">
                        <span class="badge bg-primary me-2">Precio: $<?= number_format($viaje['precio_base'], 2) ?></span>
                        <span class="badge bg-success">Asientos libres: <?= max(0, $capacity - count($reservedSeats)) ?></span>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-custom"><?= htmlspecialchars($error) ?></div>
                <?php elseif ($message): ?>
                    <div class="alert alert-success alert-custom"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" class="row g-4">
                    <input type="hidden" name="id_viaje" value="<?= $viaje_id ?>">

                    <div class="col-md-6">
                        <label class="form-label">Método de pago</label>
                        <div class="payment-options btn-group" role="group" aria-label="Métodos de pago">
                            <input type="radio" class="btn-check" name="metodo_pago" id="pago_yape" value="Yape" autocomplete="off" <?= $paymentMethod === 'Yape' ? 'checked' : '' ?> >
                            <label class="btn btn-outline-primary" for="pago_yape">💸 Yape</label>

                            <input type="radio" class="btn-check" name="metodo_pago" id="pago_tarjeta" value="Tarjeta" autocomplete="off" <?= $paymentMethod === 'Tarjeta' ? 'checked' : '' ?> >
                            <label class="btn btn-outline-primary" for="pago_tarjeta">💳 Tarjeta</label>

                            <input type="radio" class="btn-check" name="metodo_pago" id="pago_efectivo" value="Efectivo" autocomplete="off" <?= $paymentMethod === 'Efectivo' ? 'checked' : '' ?> >
                            <label class="btn btn-outline-primary" for="pago_efectivo">💵 Efectivo</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="legend-item"><span class="legend-color" style="background:#198754;"></span> Libre</div>
                        <div class="legend-item"><span class="legend-color" style="background:#6c757d;"></span> Ocupado</div>
                        <div class="legend-item"><span class="legend-color" style="background:#ffc107;"></span> Tu asiento</div>
                        <div class="legend-item"><span class="legend-color" style="background:#0d6efd;"></span> Conductor / Copiloto</div>
                    </div>

                    <div class="col-12">
                        <div class="bus-container">
                            <div class="bus-front">
                                <div class="bus-seat-static"><span class="bus-icon">⚙️</span>Conductor</div>
                                <div class="bus-seat-static"><span class="bus-icon">🎧</span>Copiloto</div>
                            </div>

                            <div class="bus-layout">
                                <?php
                                $rows = ceil($capacity / 4);
                                for ($row = 0; $row < $rows; $row++):
                                    $seatA = $row * 4 + 1;
                                    $seatB = $row * 4 + 2;
                                    $seatC = $row * 4 + 3;
                                    $seatD = $row * 4 + 4;
                                ?>
                                    <div class="bus-row">
                                        <?php foreach ([$seatA, $seatB] as $seat): ?>
                                            <div class="seat-slot">
                                                <?php if ($seat <= $capacity):
                                                    $status = 'available';
                                                    $label = 'Libre';
                                                    if (isset($reservedSeats[$seat])) {
                                                        $status = $reservedSeats[$seat]['id_usuario'] === $_SESSION['id_usuario'] ? 'mine' : 'taken';
                                                        $label = $status === 'mine' ? 'Tu asiento' : 'Ocupado';
                                                    }
                                                ?>
                                                    <label class="seat <?= $status ?><?= $selectedSeat === $seat ? ' selected' : '' ?>">
                                                        <?php if ($status === 'available'): ?>
                                                            <input type="radio" class="seat-input" name="asiento" value="<?= $seat ?>" <?= $selectedSeat === $seat ? 'checked' : '' ?>>
                                                        <?php endif; ?>
                                                        <span class="seat-number"><?= $seat ?></span>
                                                        <span class="seat-status"><?= $label ?></span>
                                                    </label>
                                                <?php else: ?>
                                                    <div class="seat empty"></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>

                                        <div class="bus-aisle"></div>

                                        <?php foreach ([$seatC, $seatD] as $seat): ?>
                                            <div class="seat-slot">
                                                <?php if ($seat <= $capacity):
                                                    $status = 'available';
                                                    $label = 'Libre';
                                                    if (isset($reservedSeats[$seat])) {
                                                        $status = $reservedSeats[$seat]['id_usuario'] === $_SESSION['id_usuario'] ? 'mine' : 'taken';
                                                        $label = $status === 'mine' ? 'Tu asiento' : 'Ocupado';
                                                    }
                                                ?>
                                                    <label class="seat <?= $status ?><?= $selectedSeat === $seat ? ' selected' : '' ?>">
                                                        <?php if ($status === 'available'): ?>
                                                            <input type="radio" class="seat-input" name="asiento" value="<?= $seat ?>" <?= $selectedSeat === $seat ? 'checked' : '' ?>>
                                                        <?php endif; ?>
                                                        <span class="seat-number"><?= $seat ?></span>
                                                        <span class="seat-status"><?= $label ?></span>
                                                    </label>
                                                <?php else: ?>
                                                    <div class="seat empty"></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <div class="bus-back">
                                <div class="bus-toilet"><span class="bus-icon">🚺</span>Baño Damas</div>
                                <div class="bus-toilet"><span class="bus-icon">🚹</span>Baño Caballeros</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary btn-lg">Reservar asiento seleccionado</button>
                        <a href="mis_viajes.php" class="btn btn-outline-light">Ver mis viajes</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('.seat-input').forEach(function(input) {
            input.addEventListener('change', function() {
                document.querySelectorAll('.seat').forEach(function(label) {
                    label.classList.remove('selected');
                });
                if (this.checked) {
                    this.closest('.seat').classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>
