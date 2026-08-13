<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $pasaje_id = (int)$_GET['id'];
    $usuario_id = $_SESSION['id_usuario'];

    // Solo permitimos cancelar si el usuario es el dueño del pasaje
    $stmt = $conexion->prepare("UPDATE pasajes SET estado_pasaje = 'Cancelado' WHERE id_pasaje = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $pasaje_id, $usuario_id);
    $stmt->execute();
    header("Location: mis_viajes.php?mensaje=cancelado");
} else {
    header("Location: mis_viajes.php");
}
?>