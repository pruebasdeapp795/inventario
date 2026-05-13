<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Inventario - Selección de Acceso</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-blue: #3b82f6;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body, html {
            min-height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-dark);
        }

        .hero-section {
            position: relative;
            min-height: 100vh;
            width: 100%;
            background-image: url('{{ asset("imagenes/hinve.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 58, 138, 0.7) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
            max-width: 1000px;
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: white;
            font-size: 3rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-bottom: 3.5rem;
            font-weight: 300;
        }

        .access-cards {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .access-card {
            background: var(--glass-bg);
            border-radius: 24px;
            padding: 3rem 2rem;
            width: 380px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .access-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            background: white;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: #eff6ff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--accent-blue);
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .access-card:hover .icon-wrapper {
            background: var(--accent-blue);
            color: white;
            transform: rotate(5deg);
        }

        .access-card h2 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .access-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
            text-align: center;
        }

        .support-button {
            margin-top: 4rem;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .support-button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
            transform: translateY(-2px);
        }

        footer {
            position: relative;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            z-index: 2;
            padding: 2rem 1rem;
            letter-spacing: 0.05em;
            margin-top: auto;
        }

        /* Modal Customization */
        .modal-content {
            border-radius: 24px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary-dark);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }

        .modal-body {
            padding: 2.5rem 2rem;
        }

        .btn-success {
            background: var(--accent-blue);
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-success:hover {
            background: #2563eb;
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.2rem; margin-top: 1rem; }
            .subtitle { font-size: 0.95rem; margin-bottom: 2rem; padding: 0 1rem; }
            .access-cards { gap: 1rem; width: 100%; padding: 0 1rem; }
            .access-card { 
                width: 100%; 
                max-width: 100%; 
                padding: 1.5rem 1rem;
                border-radius: 20px;
            }
            .access-card h2 { font-size: 1.35rem; margin-bottom: 0.5rem; }
            .icon-wrapper { width: 60px; height: 60px; font-size: 1.5rem; margin-bottom: 1rem; }
            .hero-section { background-attachment: scroll; padding: 1rem 0; }
            .support-button { margin-top: 2rem; width: 90%; max-width: 280px; font-size: 0.8rem; }
            .modal-body { padding: 1.5rem 1rem; }
            .g-recaptcha { transform: scale(0.85); transform-origin: 0 0; margin-left: auto; margin-right: auto; display: block !important; width: 258px; }
        }
    </style>
</head>

<body>

    <div class="hero-section">
        <div class="hero-overlay"></div>
        
        <div class="hero-content">
            <h1>Portal de Inventario</h1>
            <p class="subtitle">Gestión logística avanzada y control de suministros en tiempo real.</p>

            <div class="access-cards">
                <a href="{{ route('login.planta') }}" class="access-card">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-industry"></i>
                    </div>
                    <h2>Control planta</h2>
                    <p>Acceso para operarios y personal de planta.</p>
                </a>

                <a href="{{ route('login.general') }}" class="access-card">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </div>
                    <h2>Usuarios generales</h2>
                    <p>Acceso para administración y gestión.</p>
                </a>
            </div>

            <button type="button" class="support-button" data-bs-toggle="modal" data-bs-target="#soporteModal">
                <i class="fa-solid fa-circle-question me-2"></i> Contactar con soporte
            </button>
        </div>

        <footer>
            © Tubosas S.A.
        </footer>
    </div>

    <!-- Modal remains functional but styled slightly better -->
    <div class="modal fade" id="soporteModal" tabindex="-1" aria-labelledby="soporteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="soporteModalLabel">
                        <i class="fa-solid fa-headset me-2"></i> Soporte Técnico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body text-center">
                    <p class="mb-4">Por seguridad, por favor verifica que no eres un robot para contactar con soporte.</p>

                    <div class="g-recaptcha d-inline-block mb-4" 
                         data-sitekey="6LfsgvsrAAAAAMb5xZ5mYT8n-A4oIorr5sI8PruP"
                         data-callback="recaptchaCallback" 
                         data-expired-callback="recaptchaExpired">
                    </div>

                    <div id="recaptchaError" class="alert alert-danger d-none" role="alert">
                        Por favor, completa la verificación de seguridad.
                    </div>

                    <p class="text-muted small">
                        Nuestro equipo te responderá lo antes posible.
                    </p>
                </div>

                <div class="modal-footer border-0 justify-content-center pb-4">
                    <a href="mailto:impuestos@tubosa.com" id="enviarCorreoBtn" class="btn btn-success disabled">
                        <i class="fa-solid fa-paper-plane me-2"></i> Enviar correo
                    </a>
                    <button type="button" class="btn btn-light px-4 ms-2" data-bs-dismiss="modal" style="border-radius: 12px;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function recaptchaCallback() {
            document.getElementById('enviarCorreoBtn').classList.remove('disabled');
            document.getElementById('recaptchaError').classList.add('d-none');
        }

        function recaptchaExpired() {
            document.getElementById('enviarCorreoBtn').classList.add('disabled');
        }
    </script>
</body>

</html>
