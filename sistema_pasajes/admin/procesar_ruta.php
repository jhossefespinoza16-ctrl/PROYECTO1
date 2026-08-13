<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origen = trim($_POST['origen'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    $duracion = trim($_POST['duracion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $id_ruta = isset($_POST['id_ruta']) ? intval($_POST['id_ruta']) : 0;

    if ($id_ruta > 0) {
        $stmt = $conexion->prepare("UPDATE rutas SET origen = ?, destino = ?, duracion_estimada = ?, precio_base = ? WHERE id_ruta = ?");
        $stmt->bind_param("sssdi", $origen, $destino, $duracion, $precio, $id_ruta);
        $result = $stmt->execute();
        $redirect = "gestionar_rutas.php?mensaje=actualizado";
    } else {
        $stmt = $conexion->prepare("INSERT INTO rutas (origen, destino, duracion_estimada, precio_base) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $origen, $destino, $duracion, $precio);
        $result = $stmt->execute();
        $redirect = "gestionar_rutas.php?mensaje=registrado";
    }

    if ($result) {
        header("Location: $redirect");
        exit();
    }
    echo "Error: " . htmlspecialchars($conexion->error);
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_ruta = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM rutas WHERE id_ruta = ?");
    $stmt->bind_param("i", $id_ruta);
    $stmt->execute();
    header("Location: gestionar_rutas.php?mensaje=eliminado");
    exit();
}

header("Location: gestionar_rutas.php");
exit();