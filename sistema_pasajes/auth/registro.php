<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BusSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .signup-wrapper {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .auth-card-large {
            max-width: 520px;
        }
    </style>
</head>
<body class="signup-wrapper">
    <div class="auth-card auth-card-large">
        <div class="auth-header">
            <span class="brand">🚌 BusSystem</span>
            <p class="text-muted">Crea una cuenta segura para gestionar viajes y reservas</p>
        </div>
        
        <form action="procesar_registro.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" class="form-control form-control-plus" placeholder="Tu nombre" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Apellidos *</label>
                <input type="text" name="apellidos" class="form-control form-control-plus" placeholder="Tus apellidos" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">DNI / Documento *</label>
                <input type="text" name="dni" class="form-control form-control-plus" placeholder="12345678" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Correo Electrónico *</label>
                <input type="email" name="correo" class="form-control form-control-plus" placeholder="tu@correo.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Contraseña *</label>
                <input type="password" name="password" class="form-control form-control-plus" placeholder="Mínimo 6 caracteres" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Tipo de Cuenta *</label>
                <select name="id_rol" class="form-control form-control-plus" required>
                    <option value="" disabled selected>Selecciona un rol</option>
                    <option value="2">👤 Cliente / Usuario</option>
                    <option value="1">🔐 Administrador</option>
                </select>
            </div>
            
            <button type="submit" name="registrar" class="btn btn-primary w-100 mb-3" style="height: 48px; font-size: 16px; font-weight: 700;">Crear Cuenta</button>
        </form>
        
        <hr style="border-color: rgba(99, 102, 241, 0.1);">
        
        <p class="text-center text-muted mb-0">¿Ya tienes cuenta? 
            <a href="login.php" class="auth-link">Inicia sesión</a>
        </p>
    </div>
</body>
</html>