@extends('layout.app')

@section('title', 'Gestión de Usuarios')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Restore some specific colors if Bootstrap overrides them */
        body {
            background-color: var(--sidebar-bg) !important;
            color: var(--text-main) !important;
        }

        main {
            background-color: var(--bg-color) !important;
        }

        .panel {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 0;
        }

        .badge-role {
            background: rgba(166, 198, 75, 0.15);
            color: #7b962a;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-right: 6px;
            display: inline-block;
        }

        /* Modal Styling matching theme */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            background: white;
            border-radius: 16px 16px 0 0;
        }

        .modal-body {
            background: #f7f8fa;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            background: white;
            border-radius: 0 0 16px 16px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(166, 198, 75, 0.25);
        }

        .btn-theme {
            background: var(--sidebar-bg);
            color: white;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            border: none;
        }

        .btn-theme:hover {
            background: #111;
            color: white;
        }

        /* Table styles from dashboard */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            text-align: left;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
            font-weight: 500;
        }

        td {
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        a {
            text-decoration: none;
        }

        /* Custom Tabs */
        .nav-tabs {
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 20px;
            gap: 10px;
        }
        .nav-tabs .nav-link {
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
            transition: all 0.2s;
        }
        .nav-tabs .nav-link:hover {
            color: var(--text-main);
            background: rgba(255,255,255,0.05);
        }
        .nav-tabs .nav-link.active {
            color: var(--accent-color);
            background: transparent;
            border-bottom: 2px solid var(--accent-color);
        }
    </style>

    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-pane" type="button" role="tab">Usuarios</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles-pane" type="button" role="tab">Roles</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <!-- Pestaña de Usuarios -->
        <div class="tab-pane fade show active" id="users-pane" role="tabpanel" tabindex="0">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h1 class="panel-title">Gestión de Usuarios</h1>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Administra los usuarios y sus
                            permisos asignados en el sistema.</div>
                    </div>
                    <button class="btn-theme d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fa-solid fa-user-plus me-2"></i> Nuevo Usuario
                    </button>
                </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="background: rgba(46, 214, 163, 0.1); border: 1px solid #2ed6a3; color: #219672; border-radius: 12px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Roles Asignados</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td style="display: flex; align-items: center; gap: 12px;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                    style="width: 36px; height: 36px; border-radius: 50%;">
                                <div style="font-weight: 600; font-size: 15px;">{{ $user->name }}</div>
                            </td>
                            <td style="color: var(--text-muted);">{{ $user->username }}</td>
                            <td style="color: var(--text-muted);">{{ $user->email ?? 'N/A' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge-role"><i class="fa-solid fa-shield-halved me-1"></i> {{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td style="text-align: right;">
                                <button class="btn btn-link text-muted p-0 me-3 shadow-none" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal{{ $user->id }}" style="text-decoration: none;">
                                    <i class="fa-solid fa-pen-to-square" style="font-size: 18px;"></i>
                                </button>
                                <button class="btn btn-link text-danger p-0 shadow-none"
                                    style="text-decoration: none;"
                                    onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}', '{{ $user->name }}')"
                                    type="button">
                                    <i class="fa-solid fa-trash-can" style="font-size: 18px;"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" style="font-family: inherit;">Modificar Usuario:
                                                {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 border-0">
                                            <div class="row">
                                                <!-- Datos del Usuario -->
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <label class="form-label text-muted fw-bold" style="font-family: inherit;">Nombre Completo</label>
                                                    <input type="text" name="name" class="form-control mb-3" value="{{ $user->name }}" required>
                                                    
                                                    <label class="form-label text-muted fw-bold" style="font-family: inherit;">Usuario (Username)</label>
                                                    <input type="text" name="username" class="form-control mb-3" value="{{ $user->username }}" required>
                                                    
                                                    <label class="form-label text-muted fw-bold" style="font-family: inherit;">Email</label>
                                                    <input type="email" name="email" class="form-control mb-3" value="{{ $user->email }}">

                                                    <label class="form-label text-muted fw-bold" style="font-family: inherit;">Cambiar Contraseña <small class="text-secondary fw-normal">(opcional)</small></label>
                                                    <input type="password" name="password" class="form-control mb-4" placeholder="Ej. nueva clave...">

                                                    <div class="form-check form-switch mb-2">
                                                        <input class="form-check-input" type="checkbox" id="switchPass{{ $user->id }}" name="force_password_change" value="1">
                                                        <label class="form-check-label text-muted" for="switchPass{{ $user->id }}" style="font-size: 14px;">Requerir cambiar contraseña al ingresar</label>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="switchEmail{{ $user->id }}" name="notify_credentials" value="1">
                                                        <label class="form-check-label text-muted" for="switchEmail{{ $user->id }}" style="font-size: 14px;">Notificar credenciales al correo asignado</label>
                                                    </div>
                                                </div>

                                                <!-- Roles -->
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted fw-bold d-block mb-3"
                                                        style="font-family: inherit;">Seleccionar Roles</label>
                                                    <div class="bg-white p-3 rounded" style="border: 1px solid var(--border-color); height: auto; max-height: 400px; overflow-y: auto;">
                                                        @foreach($roles as $group => $groupRoles)
                                                            <div class="mb-3">
                                                                <small class="d-block mb-2"
                                                                    style="color: var(--sidebar-bg); font-weight: 700; text-transform: uppercase;">{{ $group }}</small>
                                                                @foreach($groupRoles as $role)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                                                            value="{{ $role->name }}" id="role{{ $user->id }}_{{ $role->id }}"
                                                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                                        <label class="form-check-label text-dark"
                                                                            for="role{{ $user->id }}_{{ $role->id }}"
                                                                            style="font-family: inherit;">
                                                                            {{ $role->name }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn-theme w-100">Guardar Cambios</button>
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

    <!-- ============ MODAL: Confirmar Eliminar Usuario ============ -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-body p-4 text-center">
                    <div style="width: 64px; height: 64px; background: rgba(255,71,87,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 28px; color: #ff4757;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: inherit;">¿Eliminar usuario?</h5>
                    <p id="deleteModalBody" class="text-muted mb-4" style="font-size: 14px;">Esta acción no se puede deshacer.</p>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn w-50" data-bs-dismiss="modal" style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 10px;">Cancelar</button>
                        <form id="deleteUserForm" method="POST" style="width: 50%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100" style="background: #ff4757; color: white; border-radius: 8px; font-family: inherit; font-weight: 600; padding: 10px;">Sí, eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL: Confirmación Eliminar Rol ============ -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <div style="width: 64px; height: 64px; background: rgba(255,71,87,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-shield-halved" style="font-size: 28px; color: #ff4757;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: inherit;">¿Eliminar rol?</h5>
                    <p id="deleteRoleModalBody" class="text-muted mb-4" style="font-size: 14px;">Los usuarios con este rol perderán el acceso asociado.</p>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn w-50" data-bs-dismiss="modal" style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 10px;">Cancelar</button>
                        <form id="deleteRoleForm" method="POST" style="width: 50%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100" style="background: #ff4757; color: white; border-radius: 8px; font-family: inherit; font-weight: 600; padding: 10px;">Sí, eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODAL: Nuevo Rol ============ -->
    <div class="modal fade" id="createRoleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-family: inherit;">Crear Nuevo Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 border-0" style="background: #f7f8fa;">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Nombre del Rol</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Supervisor de Mezclas" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Grupo / Área</label>
                            <select name="group" class="form-select" required>
                                <option value="Control Planta">Control Planta</option>
                                <option value="Mezclas (Control Planta)">Mezclas (Control Planta)</option>
                                <option value="Logística (Control Planta)">Logística (Control Planta)</option>
                                <option value="Usuarios Generales">Usuarios Generales</option>
                                <option value="Sistema">Sistema</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 8px 20px;">Cancelar</button>
                        <button type="submit" class="btn-theme px-4">Guardar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

            </div>
        </div>

        <!-- Pestaña de Roles -->
        <div class="tab-pane fade" id="roles-pane" role="tabpanel" tabindex="0">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Gestión de Roles</h2>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Administra los roles y grupos de acceso del sistema.</div>
                    </div>
                    <button class="btn-theme d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fa-solid fa-shield-halved me-2"></i> Nuevo Rol
                    </button>
                </div>

                @foreach($roles as $group => $groupRoles)
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--accent-color);">
                            <i class="fa-solid fa-layer-group" style="color: var(--accent-color);"></i>
                            <h3 style="font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; color: var(--text-main);">{{ $group }}</h3>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                            @foreach($groupRoles as $role)
                                <div style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; min-width: 200px; transition: box-shadow 0.2s;">
                                    <div style="background: rgba(166,198,75,0.15); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="fa-solid fa-shield-halved" style="color: #7b962a;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 14px;">{{ $role->name }}</div>
                                        <div style="font-size: 12px; color: var(--text-muted);">Guard: {{ $role->guard_name }}</div>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 shadow-none"
                                        style="text-decoration: none;"
                                        onclick="confirmDeleteRole('{{ route('admin.roles.destroy', $role->id) }}', '{{ $role->name }}')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ============ MODAL: Crear Nuevo Usuario ============ -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-family: inherit;">Crear Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Nombre
                                Completo</label>
                            <input type="text" name="name" class="form-control p-2" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Usuario
                                (Username)</label>
                            <input type="text" name="username" class="form-control p-2" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Email
                                (Opcional)</label>
                            <input type="email" name="email" class="form-control p-2">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold" style="font-family: inherit;">Contraseña</label>
                            <input type="password" name="password" class="form-control p-2" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold d-block" style="font-family: inherit;">Asignar
                                Roles</label>
                            <div class="bg-white p-3 rounded"
                                style="border: 1px solid var(--border-color); max-height: 200px; overflow-y: auto;">
                                @foreach($roles as $group => $groupRoles)
                                    <div class="mb-3">
                                        <small class="d-block mb-2"
                                            style="color: var(--sidebar-bg); font-weight: 700; text-transform: uppercase;">{{ $group }}</small>
                                        @foreach($groupRoles as $role)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="roles[]"
                                                    value="{{ $role->name }}" id="new_role_{{ $role->id }}">
                                                <label class="form-check-label text-dark" for="new_role_{{ $role->id }}"
                                                    style="font-family: inherit;">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-theme w-100">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(actionUrl, userName) {
            document.getElementById('deleteModalBody').textContent = 'Estás a punto de eliminar a "' + userName + '". Esta acción no se puede deshacer.';
            document.getElementById('deleteUserForm').action = actionUrl;
            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        }

        function confirmDeleteRole(actionUrl, roleName) {
            document.getElementById('deleteRoleModalBody').textContent = 'Estás a punto de eliminar el rol "' + roleName + '". Los usuarios con este rol perderán el acceso asociado.';
            document.getElementById('deleteRoleForm').action = actionUrl;
            new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
        }
    </script>
@endsection