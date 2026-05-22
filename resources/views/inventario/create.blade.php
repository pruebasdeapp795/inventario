@extends('layout.app')

@section('title', 'Inventario Cíclico - Activo')

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
            color: var(--text-muted);
        }

        .status-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);
        }

        .status-text-active {
            color: #22c55e;
            text-transform: uppercase;
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
            transform: translateY(-1px);
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
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .upload-zone {
            border: 2px dashed #e2e8f0;
            border-radius: 24px;
            padding: 60px 40px;
            text-align: center;
            margin-bottom: 40px;
            background: #fff;
            transition: all 0.3s;
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

        .upload-zone h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1e293b;
        }

        .upload-zone p {
            color: #64748b;
            font-size: 15px;
            max-width: 500px;
            margin: 0 auto 30px;
            line-height: 1.6;
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

        .btn-action-primary:hover {
            background: #0f172a;
            transform: translateY(-1px);
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

        .btn-action-outline:hover {
            background: #f1f5f9;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
            margin-top: 20px;
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

        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(166, 198, 75, 0.1);
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
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .btn-confirm-selection {
            background: #a6c64b;
            color: #1e293b;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-confirm-selection:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>

    <div class="main-panel">
        <div class="header-section">
            <div class="title-area">
                <h2>Inventario Cíclico</h2>
                <div class="status-pill">
                    Estado: <span class="ms-2 dot"></span> <span class="status-text-active">ACTIVO</span>
                </div>
            </div>
            <div class="actions-area d-flex gap-3">
                <button class="btn-red" id="btnFinalizar" style="display: none;" onclick="showFinalizeModal()">
                    <i class="fa-solid fa-square"></i> Finalizar Inventario
                </button>
                <a href="{{ route('inventario.index') }}" class="btn-white">
                    <i class="fa-solid fa-rotate"></i> Actualizar Lista
                </a>
            </div>
        </div>

        <div class="upload-zone" id="uploadZone">
            <div class="upload-icon-circle">
                <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="32" alt="Excel">
            </div>
            <h3>Cargar Reporte SAP</h3>
            <p>Seleccione el archivo Excel generado por SAP para sincronizar los materiales y existencias del inventario
                actual.</p>

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

        <div id="selectionSection" style="display: none;">
            <h3 class="section-title">Materiales en Conteo</h3>

            <div class="table-card">
                <div class="table-header">
                    <div class="search-container">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="tableSearch" class="search-input"
                            placeholder="Buscar material o descripción...">
                    </div>
                    <button class="btn-confirm-selection" onclick="confirmarMateriales()">
                        <i class="fa-solid fa-check"></i> Confirmar Selección
                    </button>
                </div>

                <div style="overflow-x: auto; max-height: 600px;">
                    <table class="table table-hover mb-0" id="materialsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;"><input type="checkbox" id="checkAll"
                                        style="width: 18px; height: 18px;"></th>
                                <th>Material</th>
                                <th>Descripción</th>
                                <th>Centro/Almacén</th>
                                <th class="text-end">Stock SAP</th>
                                <th class="text-center">UM</th>
                            </tr>
                        </thead>
                        <tbody id="materialsBody">
                            <!-- Se llena vía AJAX o se recarga -->
                            @foreach($materiales as $mat)
                                <tr>
                                    <td><input type="checkbox" class="mat-check" value="{{ $mat->id }}"
                                            style="width: 18px; height: 18px;"></td>
                                    <td class="fw-bold">{{ $mat->material }}</td>
                                    <td>{{ $mat->texto_breve_de_material }}</td>
                                    <td>{{ $mat->centro }} / {{ $mat->almacen }}</td>
                                    <td class="text-end fw-bold">{{ number_format((float) $mat->libre_utilizacion, 3) }}</td>
                                    <td class="text-center">{{ $mat->unidad_medida_base }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Nombre de Sesión -->
    <div class="modal fade" id="finalizeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3">Nombre del Inventario</h5>
                    <p class="text-muted small">Asigne un nombre a esta sesión para guardarla en el historial.</p>
                    <input type="text" id="sessionName" class="form-control form-control-lg mb-4"
                        placeholder="Ej: Conteo Almacén General - Mayo" style="border-radius: 12px;">

                    <div class="d-flex gap-3 mt-4">
                        <button type="button" class="btn-white w-100" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn-red w-100" onclick="saveSession()">Guardar y Finalizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Al cargar, si hay materiales en la tabla (de una carga previa NO guardada), mostrar sección
        window.addEventListener('DOMContentLoaded', () => {
            const rowCount = document.querySelectorAll('#materialsBody tr').length;
            if (rowCount > 0) {
                document.getElementById('selectionSection').style.display = 'block';
                document.getElementById('btnFinalizar').style.display = 'inline-flex';
            }
        });

        document.getElementById('sapFile').addEventListener('change', function (e) {
            const name = e.target.files[0]?.name;
            document.getElementById('fileNameDisplay').textContent = name ? 'Seleccionado: ' + name : '';
        });

        // Buscador
        document.getElementById('tableSearch').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#materialsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // Seleccionar todos
        document.getElementById('checkAll').addEventListener('change', function () {
            document.querySelectorAll('.mat-check').forEach(cb => {
                if (cb.closest('tr').style.display !== 'none') {
                    cb.checked = this.checked;
                }
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

            fetch('{{ route("inventario.import") }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (parseInt(data.count) === 0) {
                            alert('Atención: El archivo se leyó pero no se importó ningún registro. Verifique que las cabeceras coincidan con: Material, Texto breve de material, Unidad medida base, Centro, Almacén, Libre utilización, Valor libre util.');
                        } else {
                            alert('Reporte cargado: ' + data.count + ' materiales encontrados.');
                            location.reload();
                        }
                    } else {
                        alert('ERROR DE IMPORTACIÓN: ' + (data.error || 'Formato no válido'));
                    }
                })

                .catch(err => alert('Error al procesar carga'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Procesar Carga';
                });
        }

        function showFinalizeModal() {
            const selected = document.querySelectorAll('.mat-check:checked').length;
            if (selected === 0) {
                alert('Debe confirmar la selección de materiales primero.');
                return;
            }
            const modal = new bootstrap.Modal(document.getElementById('finalizeModal'));
            modal.show();
        }

        function confirmarMateriales() {
            const selected = document.querySelectorAll('.mat-check:checked').length;
            if (selected === 0) {
                alert('Por favor seleccione los materiales que incluirá en el conteo.');
                return;
            }

            // Visualmente confirmamos
            document.getElementById('btnFinalizar').style.display = 'inline-flex';
            alert('Materiales marcados para la sesión. Ahora proceda a Finalizar.');
        }

        function saveSession() {
            const nombre = document.getElementById('sessionName').value;
            const ids = Array.from(document.querySelectorAll('.mat-check:checked')).map(cb => cb.value);

            if (!nombre) { alert('Asigne un nombre.'); return; }

            fetch('{{ route("inventario.store") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ nombre, ids })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) { window.location.href = data.redirect; }
                });
        }
    </script>
@endsection