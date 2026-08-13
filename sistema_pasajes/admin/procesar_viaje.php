<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: programar_viaje.php');
    exit();
}

$bus_id = intval($_POST['bus_id'] ?? 0);
$ruta_id = intval($_POST['ruta_id'] ?? 0);
$fecha = trim($_POST['fecha'] ?? '');
$hora = trim($_POST['hora'] ?? '');
$asientos = intval($_POST['asientos'] ?? 0);

$stmt = $conexion->prepare("INSERT INTO viajes (bus_id, ruta_id, fecha_salida, hora_salida, asientos_disponibles) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iissi", $bus_id, $ruta_id, $fecha, $hora, $asientos);
$success = $stmt->execute();

if ($success) {
    header("Location: programar_viaje.php?mensaje=creado");
    exit();
}

echo "Error: " . htmlspecialchars($conexion->error);
exit();
