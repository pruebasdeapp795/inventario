@extends('layout.app')

@section('title', 'Inventarios Cíclicos')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: var(--sidebar-bg) !important;
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
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .btn-theme {
            background: var(--sidebar-bg);
            color: white;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-theme:hover {
            background: #111;
            color: white;
        }

        .btn-outline-theme {
            background: transparent;
            color: var(--sidebar-bg);
            border: 1.5px solid var(--sidebar-bg);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-outline-theme:hover {
            background: var(--sidebar-bg);
            color: white;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-open {
            background: #dcfce7;
            color: #22c55e;
        }

        .status-closed {
            background: #f1f5f9;
            color: #64748b;
        }
    </style>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h1 class="panel-title">Inventarios Cíclicos</h1>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Historial y gestión de sesiones de
                    inventario.</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('inventario.create') }}" class="btn-theme">
                    <i class="fa-solid fa-plus-circle me-1"></i> Iniciar Nuevo Inventario
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div style="overflow-x: auto; border: 1px solid var(--border-color); border-radius: 12px;">
            <table class="table table-hover mb-0">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 15px;">Nombre / Sesión</th>
                        <th style="padding: 15px;">Fecha</th>
                        <th style="padding: 15px;">Materiales</th>
                        <th style="padding: 15px;">Estado</th>
                        <th style="padding: 15px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ciclicos as $c)
                        <tr>
                            <td style="padding: 15px; font-weight: 600;">{{ $c->nombre }}</td>
                            <td style="padding: 15px; color: var(--text-muted);">{{ $c->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 15px;"><span class="badge bg-secondary">{{ $c->items_count }} ítems</span></td>
                            <td style="padding: 15px;">
                                <span class="status-badge {{ $c->status == 'Abierto' ? 'status-open' : 'status-closed' }}">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <a href="#" class="btn btn-link text-muted p-0 me-3"><i class="fa-solid fa-eye"></i></a>
                                <form action="{{ route('inventario.destroy', $c->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0"
                                        onclick="return confirm('¿Eliminar este inventario?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 60px; color: var(--text-muted);">
                                <i class="fa-solid fa-clipboard-list"
                                    style="font-size: 40px; margin-bottom: 15px; display: block; opacity: 0.5;"></i>
                                No hay inventarios registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection