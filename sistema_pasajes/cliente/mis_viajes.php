<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

$usuario_id = $_SESSION['id_usuario'];
$query = $conexion->prepare("SELECT p.id_pasaje, p.numero_asiento, v.fecha_salida, v.hora_salida, ru.origen, ru.destino, p.estado_pasaje 
                             FROM pasajes p 
                             JOIN viajes v ON p.id_viaje = v.id_viaje 
                             JOIN rutas ru ON v.id_ruta = ru.id_ruta 
                             WHERE p.id_usuario = ?");
$query->bind_param("i", $usuario_id);
$query->execute();
$reservas = $query->get_result();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
<div class="container mt-5">
    <h2>Mis Reservas</h2>
    <table class="table table-striped">
        <thead>
            <tr><th>Origen</th><th>Destino</th><th>Fecha</th><th>Hora</th><th>Asiento</th><th>Estado</th><th>Acción</th></tr>
        </thead>
        <tbody>
            <?php while($row = $reservas->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['origen'] ?></td>
                <td><?= $row['destino'] ?></td>
                <td><?= $row['fecha_salida'] ?></td>
                <td><?= $row['hora_salida'] ?></td>
                <td><?= htmlspecialchars($row['numero_asiento']) ?></td>
                <td><span class="badge bg-info"><?= htmlspecialchars($row['estado_pasaje']) ?></span></td>
                <td>
    <?php if ($row['estado_pasaje'] === 'Reservado') { ?>
        <a href="cancelar_reserva.php?id=<?= $row['id_pasaje'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
    <?php } elseif ($row['estado_pasaje'] === 'Cancelado') { echo "Cancelado"; } else { echo "No disponible"; } ?>
</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Volver a buscar viajes</a>
</div>