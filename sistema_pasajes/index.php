<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusSystem - Reserva tus Pasajes en Línea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .hero-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.15), transparent 35%),
                        radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.1), transparent 28%);
            opacity: 0.8;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            font-weight: 500;
        }
        
        .hero-search-card {
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 24px;
            padding: 2.5rem;
            margin: 0 auto;
            max-width: 1100px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
        }
        
        .hero-search-card .form-control {
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            height: 50px;
            font-weight: 500;
            border-radius: 14px;
        }
        
        .hero-search-card .form-control:focus {
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.2);
            box-shadow: 0 0 0 5px rgba(255,255,255,0.1);
            color: white;
        }
        
        .hero-search-card .form-control::placeholder {
            color: rgba(255,255,255,0.8);
        }
        
        .hero-search-card .btn-search {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            font-weight: 700;
            height: 50px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        
        .hero-search-card .btn-search:hover {
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        
        .features-section {
            padding: 80px 0;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 35px rgba(99, 102, 241, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.2);
            transform: translateY(-8px);
            border-color: rgba(99, 102, 241, 0.3);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .feature-card h3 {
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.75rem;
            font-size: 1.3rem;
        }
        
        .feature-card p {
            color: var(--muted);
            margin: 0;
            font-size: 0.95rem;
        }
        
        .navbar-home {
            background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.96) 100%);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);
        }
        
        .navbar-home .navbar-brand {
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .footer-home {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }
        
        .footer-home p {
            margin: 0;
            opacity: 0.9;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-home">
        <div class="container">
            <a class="navbar-brand" href="#">🚌 BusSystem</a>
            <div class="d-flex gap-2">
                <a href="auth/login.php" class="btn btn-outline-light" style="border: 2px solid var(--primary); color: var(--primary); border-radius: 12px; font-weight: 700;">🔐 Iniciar Sesión</a>
                <a href="auth/registro.php" class="btn btn-primary" style="border-radius: 12px; font-weight: 700;">✍️ Registrarse</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-10">
                    <h1 class="hero-title">✈️ Viaja Seguro, Viaja Fácil</h1>
                    <p class="hero-subtitle">Reserva tus pasajes en línea en simples pasos. Viaja a donde quieras, cuando quieras.</p>
                    
                    <div class="hero-search-card mt-5">
                        <form action="cliente/index.php" method="GET">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <input type="text" name="origen" class="form-control" placeholder="📍 ¿Desde dónde?" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="destino" class="form-control" placeholder="📍 ¿A dónde vas?" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="fecha" class="form-control" required>
                                </div>
                                <div class="col-md-4 d-grid">
                                    <button type="submit" class="btn btn-search">🔍 Buscar Viajes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-weight: 900; color: var(--text); margin-bottom: 1rem;">¿Cómo Funciona?</h2>
                <p style="color: var(--muted); font-size: 1.1rem;">Tres pasos simples para reservar tu viaje</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon">🔍</span>
                        <h3>Busca</h3>
                        <p>Encuentra tu ruta ideal, compara horarios y selecciona el que mejor se adapte a ti.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon">🎫</span>
                        <h3>Reserva</h3>
                        <p>Elige tu asiento favorito en el mapa interactivo del bus y confirma tu reserva.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon">🚌</span>
                        <h3>Viaja</h3>
                        <p>Realiza tu pago, obtén tu código de confirmación y sube al bus con seguridad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-home">
        <div class="container">
            <p>🚌 BusSystem &copy; 2026 - Tus Viajes, Nuestro Compromiso</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.8;">Sistema de Reserva de Pasajes | Viajes Seguros y Confiables</p>
        </div>
    </footer>
</body>
</html>