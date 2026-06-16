@extends('layout.app')

@section('title', 'Materiales SAP')

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
        }

        .btn-outline-theme:hover {
            background: var(--sidebar-bg);
            color: white;
        }

        .btn-accent {
            background: var(--accent-color);
            color: var(--sidebar-bg);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 14px;
            font-family: inherit;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            text-align: left;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            padding: 0 8px 12px;
            font-weight: 500;
        }

        td {
            padding: 14px 8px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr.selected-row {
            background: rgba(166, 198, 75, 0.08);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-family: inherit;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(166, 198, 75, 0.25);
        }

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

        /* QR print styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #qr-print-area,
            #qr-print-area * {
                visibility: visible;
            }

            #qr-print-area {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
            }
        }

        .qr-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            background: white;
        }

        .qr-card canvas,
        .qr-card img {
            display: block;
            margin: 0 auto 8px;
        }

        .qr-card .qr-cod {
            font-weight: 700;
            font-size: 13px;
        }

        .qr-card .qr-mat {
            font-size: 11px;
            color: var(--text-muted);
        }

        .search-input {
            padding: 8px 12px 8px 36px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            width: 250px;
            outline: none;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: var(--accent-color);
        }

        .search-wrap {
            position: relative;
            display: inline-block;
        }

        .search-wrap i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }
    </style>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h1 class="panel-title">Materiales SAP</h1>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Gestión del catálogo de materiales
                    y generación de códigos QR.</div>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <!-- Search -->
                <form action="{{ route('materiales.index') }}" method="GET" class="search-wrap" style="margin: 0;">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" name="search" class="search-input" placeholder="Buscar material..." value="{{ request('search') }}">
                </form>
                <button class="btn-outline-theme" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                    <i class="fa-solid fa-plus"></i> Agregar Material
                </button>
                <button class="btn-outline-theme" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                    <i class="fa-solid fa-file-csv"></i> Importar CSV
                </button>
                <button class="btn-outline-theme" onclick="generateQrSelected()">
                    <i class="fa-solid fa-qrcode"></i> QR Seleccionados
                </button>
                <button class="btn-theme" onclick="generateQrAll()">
                    <i class="fa-solid fa-print"></i> Imprimir Todo
                </button>
                <button class="btn-outline-theme" onclick="printPaginated()">
                    <i class="fa-regular fa-file-pdf"></i> Imprimir Paginado
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="background: rgba(46,214,163,0.1); border: 1px solid #2ed6a3; color: #219672; border-radius: 12px; font-family: inherit;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table id="materialesTable">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" style="cursor: pointer; width: 16px; height: 16px;">
                        </th>
                        <th>Código SAP</th>
                        <th>Material</th>
                        <th style="text-align: right; width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($materiales as $mat)
                        <tr data-id="{{ $mat->id }}" data-cod="{{ $mat->cod }}" data-material="{{ $mat->material }}">
                            <td>
                                <input type="checkbox" class="row-check" value="{{ $mat->id }}"
                                    style="cursor: pointer; width: 16px; height: 16px;">
                            </td>
                            <td><span
                                    style="font-weight: 600; font-family: monospace; background: #f7f8fa; padding: 4px 8px; border-radius: 6px;">{{ $mat->cod }}</span>
                            </td>
                            <td>{{ $mat->material }}</td>
                            <td style="text-align: right;">
                                <button class="btn btn-link text-muted p-0 me-2 shadow-none" style="text-decoration:none;"
                                    onclick="editMaterial(this)">
                                    <i class="fa-solid fa-pen-to-square" style="font-size: 17px;"></i>
                                </button>
                                <button class="btn btn-link p-0 me-2 shadow-none"
                                    style="text-decoration:none; color: var(--accent-color);" title="Generar QR"
                                    onclick="generateQrSingle(this)">
                                    <i class="fa-solid fa-qrcode" style="font-size: 17px;"></i>
                                </button>
                                <button class="btn btn-link text-danger p-0 shadow-none" style="text-decoration:none;"
                                    data-url="{{ route('materiales.destroy', $mat->id) }}"
                                    onclick="confirmDelete(this)">
                                    <i class="fa-solid fa-trash-can" style="font-size: 17px;"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fa-solid fa-box-open"
                                    style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                                No hay materiales registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 12px; font-size: 13px; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                Total: <strong id="totalCount">{{ $materiales->total() }}</strong> materiales • Seleccionados: <strong
                    id="selectedCount">0</strong>
            </div>
            <div>
                {{ $materiales->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- QR Preview Modal -->
    <div class="modal fade" id="qrPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" style="font-family: inherit;">Vista Previa de QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="qrGridPreview"
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px;">
                        <!-- QR cards injected by JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 8px 20px;">Cerrar</button>
                    <button type="button" class="btn-theme" onclick="printQrArea()">
                        <i class="fa-solid fa-print me-1"></i> Imprimir QRs
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden print area -->
    <div id="qr-print-area" style="display: none;"></div>

    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('materiales.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-family: inherit;">Agregar Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-family: inherit;">Código SAP</label>
                            <input type="text" name="cod" class="form-control" placeholder="Ej. MAT-00123" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-family: inherit;">Material</label>
                            <input type="text" name="material" class="form-control" placeholder="Descripción del material"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 8px 20px;">Cancelar</button>
                        <button type="submit" class="btn-theme">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="importCsvModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('materiales.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-family: inherit;">Importar CSV de Materiales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-family: inherit;">Archivo CSV</label>
                            <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                            <small class="text-muted mt-2 d-block">
                                El archivo debe tener columnas separadas por comas o punto y coma. <br>
                                Formato esperado: <strong>Columna 1: Código, Columna 2: Descripción</strong>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 8px 20px;">Cancelar</button>
                        <button type="submit" class="btn-theme" onclick="this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\'></i> Importando...';">Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editMaterialForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" style="font-family: inherit;">Editar Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-family: inherit;">Código SAP</label>
                            <input type="text" id="editCod" name="cod" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-family: inherit;">Material</label>
                            <input type="text" id="editMaterial" name="material" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 8px 20px;">Cancelar</button>
                        <button type="submit" class="btn-theme">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content">
                <div class="modal-body p-4 text-center" style="background: white; border-radius: 16px;">
                    <div
                        style="width: 64px; height: 64px; background: rgba(255,71,87,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 28px; color: #ff4757;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: inherit;">¿Eliminar material?</h5>
                    <p id="deleteModalBody" class="text-muted mb-4" style="font-size: 14px;"></p>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn w-50" data-bs-dismiss="modal"
                            style="border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; padding: 10px;">Cancelar</button>
                        <form id="deleteForm" method="POST" style="width: 50%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn w-100"
                                style="background: #ff4757; color: white; border-radius: 8px; font-family: inherit; font-weight: 600; padding: 10px;">Sí,
                                eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        // ---- Select All ----
        document.getElementById('selectAll').addEventListener('change', function () {
            document.querySelectorAll('.row-check').forEach(cb => {
                cb.checked = this.checked;
                cb.closest('tr').classList.toggle('selected-row', this.checked);
            });
            updateSelectedCount();
        });
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.addEventListener('change', function () {
                this.closest('tr').classList.toggle('selected-row', this.checked);
                updateSelectedCount();
            });
        });
        function updateSelectedCount() {
            document.getElementById('selectedCount').textContent = document.querySelectorAll('.row-check:checked').length;
        }

        // ---- Search ----
        // Búsqueda manejada desde el backend ahora

        // ---- Edit ----
        function editMaterial(btn) {
            const row = btn.closest('tr');
            document.getElementById('editCod').value = row.dataset.cod;
            document.getElementById('editMaterial').value = row.dataset.material;
            document.getElementById('editMaterialForm').action = '/materiales/' + row.dataset.id;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editMaterialModal')).show();
        }

        // ---- Delete Confirm ----
        function confirmDelete(btn) {
            const row = btn.closest('tr');
            const name = row.dataset.material;
            const url = btn.dataset.url;
            document.getElementById('deleteModalBody').textContent = 'Estás a punto de eliminar "' + name + '". Esta acción no se puede deshacer.';
            document.getElementById('deleteForm').action = url;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
        }

        // ---- QR Generation ----
        function buildQrCard(cod, material) {
            const card = document.createElement('div');
            card.className = 'qr-card';
            
            const img = document.createElement('img');
            img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' + encodeURIComponent(cod + ' | ' + material);
            img.width = 120;
            img.height = 120;
            card.appendChild(img);

            const codEl = document.createElement('div');
            codEl.className = 'qr-cod';
            codEl.textContent = cod;
            const matEl = document.createElement('div');
            matEl.className = 'qr-mat';
            matEl.textContent = material;
            
            card.appendChild(codEl);
            card.appendChild(matEl);
            
            return card;
        }

        function showQrModal(items) {
            const grid = document.getElementById('qrGridPreview');
            grid.innerHTML = '';
            items.forEach(item => grid.appendChild(buildQrCard(item.cod, item.material)));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('qrPreviewModal')).show();
        }

        function generateQrSingle(btn) {
            const row = btn.closest('tr');
            showQrModal([{ cod: row.dataset.cod, material: row.dataset.material }]);
        }

        function generateQrSelected() {
            const checked = document.querySelectorAll('.row-check:checked');
            if (!checked.length) { alert('Selecciona al menos un material.'); return; }
            const items = Array.from(checked).map(cb => {
                const row = cb.closest('tr');
                return { cod: row.dataset.cod, material: row.dataset.material };
            });
            showQrModal(items);
        }

        function generateQrAll() {
            const rows = document.querySelectorAll('#tableBody tr[data-id]');
            const items = Array.from(rows).map(row => ({ cod: row.dataset.cod, material: row.dataset.material }));
            showQrModal(items);
        }

        function printPaginated() {
            // Print multiple QRs per page in a grid
            const rows = document.querySelectorAll('#tableBody tr[data-id]');
            const items = Array.from(rows).map(row => ({ cod: row.dataset.cod, material: row.dataset.material }));

            const printWin = window.open('', '_blank');
            printWin.document.write('<html><head><title>QR Materiales</title><style>');
            printWin.document.write('body{font-family:sans-serif;} .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;padding:16px;} .card{border:1px solid #e5e7eb;border-radius:12px;padding:12px;text-align:center;} .cod{font-weight:700;font-size:13px;margin-top:6px;} .mat{font-size:11px;color:#8d929a;}');
            printWin.document.write('</style></head><body><div class="grid">');
            items.forEach(item => {
                printWin.document.write('<div class="card">');
                printWin.document.write('<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(item.cod + '|' + item.material) + '" width="150" height="150">');
                printWin.document.write('<div class="cod">' + item.cod + '</div>');
                printWin.document.write('<div class="mat">' + item.material + '</div>');
                printWin.document.write('</div>');
            });
            printWin.document.write('</div></body></html>');
            printWin.document.close();
            printWin.onload = () => { printWin.print(); };
        }

        function printQrArea() {
            const grid = document.getElementById('qrGridPreview');
            const cards = grid.querySelectorAll('.qr-card');
            const printWin = window.open('', '_blank');
            printWin.document.write('<html><head><title>QR Materiales</title><style>');
            printWin.document.write('body{font-family:sans-serif;} .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;padding:16px;} .card{border:1px solid #e5e7eb;border-radius:12px;padding:12px;text-align:center;} .cod{font-weight:700;font-size:13px;margin-top:6px;} .mat{font-size:11px;color:#8d929a;}');
            printWin.document.write('</style></head><body><div class="grid">');
            cards.forEach(card => {
                const cod = card.querySelector('.qr-cod').textContent;
                const mat = card.querySelector('.qr-mat').textContent;
                printWin.document.write('<div class="card">');
                printWin.document.write('<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(cod + '|' + mat) + '" width="150" height="150">');
                printWin.document.write('<div class="cod">' + cod + '</div>');
                printWin.document.write('<div class="mat">' + mat + '</div>');
                printWin.document.write('</div>');
            });
            printWin.document.write('</div></body></html>');
            printWin.document.close();
            printWin.onload = () => { printWin.print(); };
        }
    </script>
@endsection