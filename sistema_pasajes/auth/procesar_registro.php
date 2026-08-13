<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro.php');
    exit();
}

$nombres   = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$dni       = trim($_POST['dni'] ?? '');
$correo   = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');
$id_rol   = isset($_POST['id_rol']) && intval($_POST['id_rol']) === 1 ? 1 : 2;

if ($nombres === '' || $apellidos === '' || $dni === '' || $correo === '' || $password === '') {
    echo "<script>alert('Completa todos los campos obligatorios.'); window.location='registro.php';</script>";
    exit();
}

$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
$stmt->bind_param('s', $correo);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "<script>alert('Ya existe una cuenta con ese correo.'); window.location='registro.php';</script>";
    exit();
}

$hashPassword = password_hash($password, PASSWORD_DEFAULT);
$insert = $conexion->prepare("INSERT INTO usuarios (id_rol, dni, nombres, apellidos, correo, password) VALUES (?, ?, ?, ?, ?, ?)");
$insert->bind_param('isssss', $id_rol, $dni, $nombres, $apellidos, $correo, $hashPassword);

if ($insert->execute()) {
    echo "<script>alert('¡Usuario registrado con éxito!'); window.location='login.php';</script>";
} else {
    echo "<script>alert('Error al registrar: " . addslashes($conexion->error) . "'); window.location='registro.php';</script>";
}
exit();
