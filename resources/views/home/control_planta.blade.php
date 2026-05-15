<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tubosa - Control Planta</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #0c205c 0%, #040d2b 100%);
            min-height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(6, 15, 45, 0.6);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.6rem;
            color: white;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            position: relative;
        }
        
        .logo-icon::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background-color: #a3c639;
            border-radius: 50%;
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
        }

        .nav-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-btn.ciclico i { color: #8da4d0; }
        .nav-btn.sap i { color: #a3c639; }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .badge-digital {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #a3c639;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
        }
        
        .badge-digital i {
            font-size: 0.4rem;
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -1px;
            text-align: center;
        }

        .subtitle {
            color: #8da4d0;
            font-size: 1.15rem;
            margin-bottom: 4rem;
            text-align: center;
        }

        /* Cards Container */
        .cards-container {
            display: flex;
            gap: 2.5rem;
            max-width: 950px;
            width: 100%;
        }

        .module-card {
            background: #142a5c;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .icon-box {
            width: 65px;
            height: 65px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 2rem;
        }

        .icon-box.ciclico {
            background: linear-gradient(135deg, #4b83e6 0%, #2f60c7 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(47, 96, 199, 0.4);
        }

        .icon-box.sap {
            background: linear-gradient(135deg, #aed43a 0%, #8eb51f 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(142, 181, 31, 0.4);
        }

        .module-card h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .module-card p {
            color: #92a7ce;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            flex: 1;
        }

        .action-btn {
            padding: 0.85rem 1.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            width: fit-content;
            transition: all 0.3s;
            border: none;
        }

        .action-btn.ciclico {
            background: #1e3f8a;
            color: white;
            border: 1px solid #2d55b3;
        }

        .action-btn.ciclico:hover {
            background: #254b9f;
            color: white;
        }

        .action-btn.sap {
            background: #7d9e1b;
            color: white;
            border: 1px solid #95b828;
        }

        .action-btn.sap:hover {
            background: #8aa825;
            color: white;
        }

        @media (max-width: 768px) {
            .cards-container {
                flex-direction: column;
            }
            h1 {
                font-size: 2.5rem;
            }
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            .main-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="logo-icon">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            TUBOSA
        </a>
        <div class="nav-buttons">
            <a href="#" class="nav-btn ciclico">
                <i class="fa-solid fa-camera"></i> Ciclico
            </a>
            <a href="#" class="nav-btn sap">
                <i class="fa-solid fa-cube"></i> SAP
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="badge-digital">
            <i class="fa-solid fa-circle"></i> PLATAFORMA DIGITAL
        </div>
        
        <h1>Bienvenido a Tubosa</h1>
        <p class="subtitle">Selecciona el módulo que deseas utilizar.</p>

        <div class="cards-container">
            <!-- Card Cíclico -->
            <div class="module-card">
                <div class="icon-box ciclico">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <h2>Ciclico</h2>
                <p>Escanea códigos QR en tiempo real con la cámara del dispositivo y obtén la información al instante.</p>
                <a href="#" class="action-btn ciclico">
                    <i class="fa-solid fa-play" style="font-size: 0.8em;"></i> Iniciar escáner
                </a>
            </div>

            <!-- Card SAP -->
            <div class="module-card">
                <div class="icon-box sap">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <h2>Cargue de Inventario SAP</h2>
                <p>Gestiona y carga el inventario directamente en SAP de forma rápida, estructurada y sin errores.</p>
                <a href="#" class="action-btn sap">
                    <i class="fa-solid fa-play" style="font-size: 0.8em;"></i> Ir al módulo
                </a>
            </div>
        </div>
    </div>

</body>
</html>
