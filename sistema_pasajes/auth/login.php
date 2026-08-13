<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1) {
        header('Location: ../admin/dashboard.php');
    } else {
        header('Location: ../cliente/dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Iniciar Sesión - BusSystem</title>
    <style>
        .login-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
    </style>
</head>
<body class="login-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <span class="brand">🚌 BusSystem</span>
            <p class="text-muted">Accede a tu cuenta para reservar viajes</p>
        </div>
        
        <form action="validar_login.php" method="POST">
            <div class="mb-4">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control form-control-plus" placeholder="tu@correo.com" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control form-control-plus" placeholder="Tu contraseña" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Ingresa como...</label>
                <select name="id_rol" class="form-control form-control-plus" required>
                    <option value="" disabled selected>Selecciona tu rol</option>
                    <option value="2">👤 Cliente / Usuario</option>
                    <option value="1">🔐 Administrador</option>
                </select>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 mb-3" style="height: 48px; font-size: 16px; font-weight: 700;">Iniciar Sesión</button>
        </form>
        
        <hr style="border-color: rgba(99, 102, 241, 0.1);">
        
        <p class="text-center text-muted mb-0">¿No tienes cuenta? 
            <a href="registro.php" class="auth-link">Regístrate aquí</a>
        </p>
    </div>
</body>
</html>