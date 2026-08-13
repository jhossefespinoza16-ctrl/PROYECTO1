<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false && (!isset($_SESSION['id_rol']) || intval($_SESSION['id_rol']) !== 1)) {
    header("Location: ../cliente/dashboard.php");
    exit();
}


