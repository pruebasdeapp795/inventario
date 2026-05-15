<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control Planta</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .login-card {
            position: relative;
            z-index: 2;
            background: var(--glass-bg);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            background: #eff6ff;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--accent-blue);
            font-size: 1.8rem;
        }

        h2 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        p.subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
            margin-bottom: 1.2rem;
            background-color: #f8fafc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            border-color: var(--accent-blue);
            background-color: #ffffff;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--accent-blue);
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="hero-overlay"></div>
        
        <div class="login-card">
            <div class="icon-wrapper">
                <i class="fa-solid fa-industry"></i>
            </div>
            <h2>Control Planta</h2>
            <p class="subtitle">Ingresa tus credenciales para acceder</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="text-start">
                    <label class="form-label text-muted small fw-bold">Usuario</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Ej. operario_01" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="text-start">
                    <label class="form-label text-muted small fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión
                </button>
            </form>

            <a href="{{ url('/') }}" class="back-link">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al inicio
            </a>
        </div>
    </div>

</body>
</html>
