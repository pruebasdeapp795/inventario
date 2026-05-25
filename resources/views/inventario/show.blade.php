@extends('layout.app')

@section('title', 'Sesión de Inventario - ' . $ciclico->nombre)

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

        .main-panel {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .title-area h2 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 5px;
            color: #1e293b;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #22c55e;
            text-transform: uppercase;
        }

        .status-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);
        }

        .btn-red {
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-red:hover {
            background: #e60000;
            color: white;
        }

        .btn-white {
            background: white;
            color: #1e293b;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-white:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .upload-zone {
            border: 2px dashed #e2e8f0;
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            margin-bottom: 40px;
            background: #fff;
        }

        .upload-icon-circle {
            width: 64px;
            height: 64px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .btn-action-primary {
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-action-outline {
            background: white;
            color: #1e293b;
            border: 2px solid #1e293b;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
        }

        .table-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .table-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }

        .search-container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .search-container i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s;
            font-size: 14px;
        }

        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            padding: 16px 24px;
            border: none;
        }

        .table tbody td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
    </style>

    <div class="main-panel">
        <div class="header-section">
            <div class="title-area">
                <a href="{{ route('inventario.index') }}"
                    class="text-decoration-none text-muted small mb-2 d-inline-block"><i class="fa-solid fa-arrow-left"></i>
                    Volver al historial</a>
                <h2>{{ $ciclico->nombre }}</h2>
                <div class="status-pill">
                    Estado: <span class="ms-2 dot"></span> {{ $ciclico->status }}
                </div>
            </div>
            <div class="actions-area d-flex gap-3">
                @if($ciclico->status == 'Abierto')
                    <form action="{{ route('inventario.close', $ciclico->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-red"
                            onclick="return confirm('¿Seguro que desea finalizar este conteo? No podrá cargar más materiales.')">
                            <i class="fa-solid fa-square"></i> Finalizar Inventario
                        </button>
                    </form>
                @endif
                <button class="btn-white" onclick="location.reload()">
                    <i class="fa-solid fa-rotate"></i> Actualizar Lista
                </button>
            </div>
        </div>

        @if($ciclico->status == 'Abierto')
            <div class="upload-zone">
                <div class="upload-icon-circle">
                    <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="32" alt="Excel">
                </div>
                <h3>Cargar Reporte SAP</h3>
                <p>Seleccione el archivo Excel generado por SAP para sincronizar los materiales y existencias de esta sesión.
                </p>

                <div class="d-flex justify-content-center gap-3">
                    <input type="file" id="sapFile" style="display: none;" accept=".xlsx, .xls">
                    <button class="btn-action-outline" onclick="document.getElementById('sapFile').click()">
                        <i class="fa-solid fa-folder-open"></i> Seleccionar Archivo
                    </button>
                    <button class="btn-action-primary" id="btnProcess" onclick="processUpload()">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Procesar Carga
                    </button>
                </div>
                <div id="fileNameDisplay" class="mt-3 fw-bold text-success" style="font-size: 14px;"></div>
            </div>
        @endif

        {{-- Solo se muestra si hay items cargados --}}
        @if(count($items) > 0)
            <div id="selectionSection">
                <h3 class="section-title">Materiales en Conteo</h3>

                <div class="table-card">
                    <div class="table-header">
                        <div class="search-container">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" id="tableSearch" class="search-input"
                                placeholder="Buscar material o descripción...">
                        </div>
                    </div>

                    <div style="overflow-x: auto; max-height: 500px;">
                        <table class="table table-hover mb-0" id="materialsTable">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Descripción</th>
                                    <th>Centro/Almacén</th>
                                    <th class="text-end">Stock SAP</th>
                                    <th class="text-center">UM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->material }}</td>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>{{ $item->centro }} / {{ $item->almacen }}</td>
                                        <td class="text-end fw-bold">{{ number_format((float) $item->stock_sap, 3) }}</td>
                                        <td class="text-center small">{{ $item->um }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sapFile')?.addEventListener('change', function (e) {
            const name = e.target.files[0]?.name;
            document.getElementById('fileNameDisplay').textContent = name ? 'Seleccionado: ' + name : '';
        });

        document.getElementById('tableSearch')?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#materialsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        function processUpload() {
            const fileInput = document.getElementById('sapFile');
            if (!fileInput.files.length) { alert('Seleccione un archivo.'); return; }

            const btn = document.getElementById('btnProcess');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Procesando...';

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("inventario.import", $ciclico->id) }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert('ERROR: ' + data.error);
                    }
                })
                .catch(err => alert('Error de conexión'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Procesar Carga';
                });
        }
    </script>
@endsection