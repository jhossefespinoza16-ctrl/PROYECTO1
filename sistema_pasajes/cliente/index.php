<?php
require_once '../includes/seguridad.php';
require_once '../config/conexion.php';

// Consulta asignada a la variable $query
$query = $conexion->query("SELECT v.*, r.origen, r.destino, r.precio_base AS precio 
                           FROM viajes v 
                           JOIN rutas r ON v.id_ruta = r.id_ruta 
                           WHERE v.fecha_salida >= CURDATE()
                           ORDER BY v.fecha_salida ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viajes Disponibles - BusSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .viajes-page {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 3rem;
        }
        
        .viajes-header {
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 35px rgba(99, 102, 241, 0.2);
        }
        
        .viajes-header h1 {
            font-weight: 900;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .viajes-container {
            display: grid;
            gap: 1.5rem;
        }
        
        .viaje-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.1);
            border: 2px solid rgba(99, 102, 241, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .viaje-card:hover {
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.2);
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.3);
        }
        
        .viaje-card-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(236, 72, 153, 0.05) 100%);
            padding: 1.5rem;
            border-bottom: 2px solid rgba(99, 102, 241, 0.1);
        }
        
        .ruta-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }
        
        .ruta-city {
            text-align: center;
            flex: 1;
        }
        
        .ruta-city .city-name {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
        }
        
        .ruta-city .city-icon {
            font-size: 1.8rem;
            display: block;
        }
        
        .ruta-arrow {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 700;
        }
        
        .viaje-card-body {
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .detail-icon {
            font-size: 1.8rem;
        }
        
        .detail-content label {
            display: block;
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 0.3rem;
        }
        
        .detail-content p {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }
        
        .viaje-card-footer {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.5) 0%, rgba(241, 245, 249, 0.5) 100%);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 2px solid rgba(99, 102, 241, 0.1);
        }
        
        .precio-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .precio-label {
            font-size: 0.9rem;
            color: var(--muted);
            font-weight: 600;
        }
        
        .precio-valor {
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-reservar {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn-reservar:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .empty-text {
            color: var(--muted);
            font-size: 1.1rem;
        }
    </style>
</head>
<body class="viajes-page">
    <nav class="navbar topbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">🚌 BusSystem</a>
            <div class="d-flex gap-3 align-items-center">
                <span style="color: var(--text); font-size: 14px; font-weight: 600;">
                    👤 Buscando viajes...
                </span>
                <a href="dashboard.php" class="btn btn-outline-light" style="border: 2px solid var(--primary); color: var(--primary); border-radius: 10px;">← Volver</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="viajes-header">
            <h1>✈️ Viajes Disponibles</h1>
            <p style="margin: 0; opacity: 0.95;">Selecciona tu viaje y elige el asiento perfecto para ti</p>
        </div>

        <div class="viajes-container">
            <?php 
            if($query->num_rows === 0) {
                echo '<div class="empty-state">';
                echo '<div class="empty-icon">🚌</div>';
                echo '<p class="empty-text">No hay viajes disponibles en este momento.</p>';
                echo '</div>';
            } else {
                while($row = $query->fetch_assoc()) { 
            ?>
                <div class="viaje-card">
                    <div class="viaje-card-header">
                        <div class="ruta-display">
                            <div class="ruta-city">
                                <span class="city-icon">📍</span>
                                <span class="city-name"><?php echo htmlspecialchars($row['origen']); ?></span>
                            </div>
                            <div class="ruta-arrow">→</div>
                            <div class="ruta-city">
                                <span class="city-icon">📍</span>
                                <span class="city-name"><?php echo htmlspecialchars($row['destino']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="viaje-card-body">
                        <div class="detail-item">
                            <span class="detail-icon">📅</span>
                            <div class="detail-content">
                                <label>Fecha</label>
                                <p><?php echo date("d/m/Y", strtotime($row['fecha_salida'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-icon">⏰</span>
                            <div class="detail-content">
                                <label>Hora</label>
                                <p><?php echo substr($row['hora_salida'], 0, 5); ?></p>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-icon">🚌</span>
                            <div class="detail-content">
                                <label>Clase</label>
                                <p>Regular</p>
                            </div>
                        </div>
                    </div>

                    <div class="viaje-card-footer">
                        <div class="precio-info">
                            <span class="precio-label">💰 Precio:</span>
                            <span class="precio-valor">S/ <?php echo number_format($row['precio'], 2); ?></span>
                        </div>
                        
                        <a href="reservar.php?id=<?php echo intval($row['id_viaje']); ?>" class="btn-reservar">🎫 Reservar</a>
                    </div>
                </div>
            <?php 
                }
            }
            ?>
        </div>
    </main>

    <footer style="margin-top: 4rem; padding: 2rem; text-align: center; color: var(--muted);">
        <p style="margin: 0; font-weight: 500;">© 2026 BusSystem - Reserva tus Pasajes Hoy</p>
    </footer>
</body>
</html>