<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles - Portal de Inventario</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-blue: #3b82f6;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar {
            background: #1e293b;
            min-height: calc(100vh - 56px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-content {
            padding: 2rem;
        }

        h1, h2, h3 {
            font-family: 'Outfit', sans-serif;
        }

        .role-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .role-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-blue);
        }

        .group-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            margin-top: 3rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-blue);
            width: fit-content;
        }

        .group-header i {
            margin-right: 1rem;
            color: var(--accent-blue);
        }

        .btn-primary {
            background: var(--accent-blue);
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }

        .badge-role {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-block;
            margin: 0.25rem;
        }

        .modal-content {
            background: var(--card-bg);
            color: var(--text-main);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 12px;
            padding: 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-blue);
            color: white;
            box-shadow: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <i class="fa-solid fa-shield-halved me-2 text-primary"></i>
            <span style="font-family: 'Outfit'; font-weight: 700;">Admin Panel</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.roles') }}">Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Usuarios</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <main class="main-content col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-1">Gestión de Roles</h1>
                    <p class="text-muted">Administra los accesos y jerarquías del sistema.</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                    <i class="fa-solid fa-plus me-2"></i> Nuevo Rol
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            @foreach($roles as $group => $groupRoles)
                <div class="group-header">
                    <i class="fa-solid fa-layer-group"></i>
                    <h2 class="h4 mb-0">{{ $group }}</h2>
                </div>
                
                <div class="row g-4">
                    @foreach($groupRoles as $role)
                        <div class="col-md-3">
                            <div class="role-card">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h3 class="h5 mb-0">{{ $role->name }}</h3>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este rol?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                                <span class="badge-role">
                                    ID: #{{ $role->id }}
                                </span>
                                <div class="mt-3">
                                    <small class="text-muted">Guard: {{ $role->guard_name }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </main>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">Crear Nuevo Rol</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre del Rol</label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Supervisor de Mezclas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Grupo / Área</label>
                        <select name="group" class="form-select" required>
                            <option value="Control Planta">Control Planta</option>
                            <option value="Mezclas (Control Planta)">Mezclas (Control Planta)</option>
                            <option value="Logística (Control Planta)">Logística (Control Planta)</option>
                            <option value="Usuarios Generales">Usuarios Generales</option>
                            <option value="Sistema">Sistema</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-white text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
