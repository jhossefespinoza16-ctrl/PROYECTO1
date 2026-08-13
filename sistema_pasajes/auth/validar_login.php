<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$correo     = trim($_POST['correo'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$id_rol     = isset($_POST['id_rol']) ? intval($_POST['id_rol']) : 0;

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "<script>alert('El correo electrónico no se encuentra registrado.'); window.location.href='login.php';</script>";
    exit();
}

$usuario = $resultado->fetch_assoc();
$storedPassword = trim($usuario['password']);
$isValidPassword = password_verify($contrasena, $storedPassword) || $contrasena === $storedPassword;

if (!$isValidPassword) {
    echo "<script>alert('La contraseña introducida es incorrecta.'); window.location.href='login.php';</script>";
    exit();
}

if (intval($usuario['id_rol']) !== $id_rol) {
    echo "<script>alert('El correo existe, pero no corresponde al rol seleccionado.'); window.location.href='login.php';</script>";
    exit();
}

$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['nombres']    = $usuario['nombres'];
$_SESSION['apellidos']  = $usuario['apellidos'];
$_SESSION['correo']     = $usuario['correo'];
$_SESSION['id_rol']     = intval($usuario['id_rol']);

if ($id_rol === 1) {
    header("Location: ../admin/dashboard.php");
    exit();
}

header("Location: ../cliente/index.php");
exit();
