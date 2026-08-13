<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $capacidad_asientos = intval($_POST['capacidad_asientos'] ?? 0);
    $modelo = trim($_POST['modelo'] ?? '');
    $estado = trim($_POST['estado'] ?? 'Activo');
    $id_bus = isset($_POST['id_bus']) ? intval($_POST['id_bus']) : 0;

    if ($id_bus > 0) {
        $stmt = $conexion->prepare("UPDATE buses SET placa = ?, modelo = ?, capacidad_asientos = ?, estado = ? WHERE id_bus = ?");
        $stmt->bind_param("ssisi", $placa, $modelo, $capacidad_asientos, $estado, $id_bus);
        $result = $stmt->execute();
        $redirect = "gestionar_buses.php?mensaje=actualizado";
    } else {
        $stmt = $conexion->prepare("INSERT INTO buses (placa, modelo, capacidad_asientos, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $placa, $modelo, $capacidad_asientos, $estado);
        $result = $stmt->execute();
        $redirect = "gestionar_buses.php?mensaje=registrado";
    }

    if ($result) {
        header("Location: $redirect");
        exit();
    }
    echo "Error: " . htmlspecialchars($conexion->error);
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_bus = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM buses WHERE id_bus = ?");
    $stmt->bind_param("i", $id_bus);
    $stmt->execute();
    header("Location: gestionar_buses.php?mensaje=eliminado");
    exit();
}

header("Location: gestionar_buses.php");
exit();