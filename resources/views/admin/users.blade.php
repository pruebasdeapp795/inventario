<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Portal de Inventario</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #0f172a;
            --accent-blue: #3b82f6;
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

        .main-content {
            padding: 2rem;
        }

        .user-table {
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table {
            color: var(--text-main);
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(255, 255, 255, 0.02);
        }

        .table th {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            padding: 1.25rem;
        }

        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.25rem;
            vertical-align: middle;
        }

        .badge-role {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-blue);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-right: 0.25rem;
        }

        .btn-primary {
            background: var(--accent-blue);
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
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

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-blue);
            color: white;
            box-shadow: none;
        }

        .role-selector {
            max-height: 200px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.02);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.roles') }}">Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.users') }}">Usuarios</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1 h2">Gestión de Usuarios</h1>
                <p class="text-muted">Administra los usuarios y sus permisos asignados.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="user-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Roles Asignados</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $user->name }}</div>
                            </td>
                            <td class="text-muted">{{ $user->username }}</td>
                            <td class="text-muted">{{ $user->email ?? 'N/A' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge-role">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <button class="btn btn-link text-white p-0 me-3" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Editar Roles: {{ $user->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label text-muted mb-3">Seleccionar Roles</label>
                                            <div class="role-selector">
                                                @foreach($roles as $group => $groupRoles)
                                                    <div class="mb-3">
                                                        <small class="text-primary fw-bold d-block mb-2">{{ $group }}</small>
                                                        @foreach($groupRoles as $role)
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role{{ $user->id }}_{{ $role->id }}"
                                                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="role{{ $user->id }}_{{ $role->id }}">
                                                                    {{ $role->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-primary w-100">Actualizar Roles</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre Completo</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Usuario (Username)</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Email (Opcional)</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Asignar Roles</label>
                        <div class="role-selector">
                            @foreach($roles as $group => $groupRoles)
                                <div class="mb-3">
                                    <small class="text-primary fw-bold d-block mb-2">{{ $group }}</small>
                                    @foreach($groupRoles as $role)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="new_role_{{ $role->id }}">
                                            <label class="form-check-label" for="new_role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
