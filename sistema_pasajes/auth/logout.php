<?php
// 1. Inicializar la sesión para poder manipularla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Desvincular todas las variables de sesión
$_SESSION = array();

// 3. Destruir la sesión por completo en el servidor
session_destroy();

// 4. Redirigir al usuario al formulario de login
header("Location: ../auth/login.php"); 
exit();
?>