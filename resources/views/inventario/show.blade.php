@extends('layout.app')

@section('title', 'Sesión de Inventario - ' . $ciclico->nombre)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
        }

        body {
            background-color: #f3f4f6 !important;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--gray-800);
        }

        .main-panel {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        /* Nav & Header */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .title-area h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--gray-800);
        }

        /* Progress Bar */
        .progress-container {
            background: var(--gray-100);
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 15px;
            border: 1px solid var(--gray-200);
        }

        /* Table Professional Styling */
        .table-responsive-custom {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.9rem;
        }

        .table-custom th {
            background: var(--gray-50);
            padding: 12px 15px;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-custom td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Row status colors */
        .row-counted { background-color: rgba(16, 185, 129, 0.03); }
        .row-pending { background-color: transparent; }

        /* Fixed Column for Material on Mobile */
        @media (max-width: 768px) {
            .sticky-col {
                position: sticky;
                left: 0;
                background: white;
                box-shadow: 2px 0 5px rgba(0,0,0,0.05);
                z-index: 5;
            }
            .table-custom th.sticky-col { z-index: 11; background: var(--gray-50); }
            
            /* Actions area stack */
            .actions-area {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .actions-area form, .actions-area button { width: 100%; }
            .btn-main { padding: 10px 5px !important; font-size: 0.8rem; }
        }

        /* Buttons */
        .btn-main {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .btn-primary-custom { background: var(--primary); color: white; }
        .btn-primary-custom:hover { background: var(--primary-dark); }
        
        .btn-outline-custom { background: white; border-color: var(--gray-200); color: var(--gray-600); }
        .btn-outline-custom:hover { background: var(--gray-50); }

        /* Full-screen Overlays (Simplified) */
        .overlay-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 9999;
            display: none;
            flex-direction: column;
        }

        .overlay-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }

        .overlay-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Large input for counting */
        .counting-input-large {
            font-size: 3.5rem;
            font-weight: 800;
            text-align: center;
            width: 100%;
            padding: 20px;
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            margin: 20px 0;
            color: var(--primary);
        }

        .counting-input-large:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Search input */
        .search-wrapper {
            position: relative;
            margin-bottom: 15px;
        }
        .search-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }
        .search-wrapper input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            width: 100%;
            height: 42px;
        }

        /* History details */
        .history-pill {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
            margin-right: 5px;
        }
    </style>

    <div class="main-panel">
        <div class="header-section">
            <div class="title-area">
                <a href="{{ route('inventario.index') }}" class="text-decoration-none text-muted small mb-2 d-inline-block">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
                <div class="d-flex align-items-center gap-3">
                    <h2>{{ $ciclico->nombre }}</h2>
                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">
                        {{ $ciclico->status }}
                    </span>
                </div>
                <div class="mt-2 text-muted small fw-bold">
                    <i class="fa-solid fa-layer-group me-1"></i> INTENTO ACTUAL: #{{ $ciclico->intento_actual }} | FASE: {{ strtoupper($fase) }}
                </div>
            </div>

            <div class="actions-area">
                @if($ciclico->status == 'Abierto')
                    @if($canSelect && $fase == 'configuracion' && $ciclico->tipo != 'general')
                        <button class="btn-main btn-outline-custom text-primary" onclick="requestSuggestions('mayor_valor')" title="Sugerir 20 items de mayor valor que no se hayan contado este mes">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> SUGERIR (MAYOR VALOR)
                        </button>
                        <button class="btn-main btn-outline-custom text-warning" onclick="requestSuggestions('aleatorio')" title="Sugerir 20 items al azar que no se hayan contado este mes">
                            <i class="fa-solid fa-dice"></i> SUGERIR (AL AZAR)
                        </button>
                        <button class="btn-main btn-primary-custom" onclick="confirmSelection()">
                            <i class="fa-solid fa-play"></i> INICIAR CONTEO
                        </button>
                    @endif

                    @if($fase == 'conteo')
                        <button class="btn-main btn-primary-custom" onclick="openGlobalScanner()">
                            <i class="fa-solid fa-qrcode"></i> ESCANEAR
                        </button>
                        {{-- Finalizar conteo (visible para todos los que pueden contar) --}}
                        <form action="{{ route('inventario.finish_count', $ciclico->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-main btn-outline-custom text-success"
                                onclick="return confirm('¿Desea finalizar el conteo? La sesión quedará en modo revisión pero no se cerrará.')">
                                <i class="fa-solid fa-flag-checkered"></i> FINALIZAR CONTEO
                            </button>
                        </form>
                    @endif

                    @if($isFullAdmin)
                        @if($ciclico->intento_actual < 3 && ($fase == 'conteo' || $fase == 'revision'))
                            <form action="{{ route('inventario.next_attempt', $ciclico->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-main btn-outline-custom text-warning" 
                                    onclick="return confirm('¿Iniciar nuevo intento?')">
                                    <i class="fa-solid fa-repeat"></i> RECONTEO
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('inventario.close', $ciclico->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-main btn-outline-custom text-danger" 
                                onclick="return confirm('¿Finalizar sesión?')">
                                <i class="fa-solid fa-stop"></i> CERRAR
                            </button>
                        </form>
                    @endif
                @endif
                <button class="btn-main btn-outline-custom" onclick="location.reload()">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </div>
        </div>

        @if($totalItems > 0)
            <div class="progress-container">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold">PROGRESO DEL INVENTARIO</span>
                    <span class="badge bg-primary">{{ $avance }}%</span>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $avance }}%"></div>
                </div>
                <div class="mt-2 text-muted x-small" style="font-size: 0.75rem;">
                    {{ $contadosItems }} de {{ $totalItems }} materiales registrados
                </div>
            </div>
        @endif
    </div>

    @if($ciclico->status == 'Abierto' && $isFullAdmin)
        <div class="main-panel border-start border-4 border-primary">
            <h5><i class="fa-solid fa-file-excel text-success me-2"></i>Cargar Datos SAP</h5>
            <p class="text-muted small">Seleccione el reporte Excel para actualizar existencias y materiales.</p>
            <div class="d-flex gap-2 flex-wrap">
                <input type="file" id="sapFile" class="form-control form-control-sm w-auto" accept=".xlsx, .xls">
                <button class="btn-main btn-primary-custom" id="btnProcess" onclick="processUpload()">
                    <i class="fa-solid fa-upload"></i> Subir
                </button>
            </div>
        </div>
    @endif

    @if($fase == 'revision')
        <div class="alert alert-warning d-flex align-items-center gap-2 mx-0 mt-0 mb-3"
             style="border-radius: 10px; border-left: 4px solid var(--warning);">
            <i class="fa-solid fa-flag-checkered"></i>
            <span>
                <b>Conteo finalizado.</b> La sesión está en revisión. El administrador puede iniciar un
                <b>Reconteo</b> o <b>Cerrar</b> la sesión definitivamente.
            </span>
        </div>
    @endif

    <div class="main-panel">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="tableSearch" placeholder="Buscar por código o nombre...">
        </div>

        <div class="table-responsive-custom">
            <table class="table-custom" id="materialsTable">
                <thead>
                    <tr>
                        @if($fase == 'configuracion' && $ciclico->tipo != 'general')
                            <th style="width: 40px;"><input type="checkbox" id="selectAllItems"></th>
                        @endif
                        <th class="sticky-col">Material</th>
                        <th>Descripción</th>
                        <th class="text-center">Alm.</th>
                        <th class="text-end">Conteo</th>
                        @if($isFullAdmin)
                            <th class="text-end">Stock</th>
                            <th class="text-end">Valor SAP</th>
                            <th class="text-end">Dif.</th>
                            <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php 
                            if($fase == 'conteo' && !$item->seleccionado && !$item->contado) continue;
                            $rowClass = $item->contado ? 'row-counted' : 'row-pending';
                            $diffColor = $item->diferencia == 0 ? '' : ($item->diferencia > 0 ? 'text-success' : 'text-danger');
                        @endphp
                        <tr class="{{ $rowClass }}" 
                            @if($fase == 'conteo') onclick="openScanner('{{ $item->id }}', '{{ $item->material }}', '{{ $item->descripcion }}', '{{ $item->stock_sap }}', '{{ $item->um }}')" 
                            @else onclick="toggleRowSelection(this)" @endif
                            id="row-{{ $item->id }}">
                            
                            @if($fase == 'configuracion' && $ciclico->tipo != 'general')
                                <td><input type="checkbox" class="item-checkbox" value="{{ $item->id }}" @if($item->seleccionado) checked @endif></td>
                            @endif
                            <td class="sticky-col fw-bold">{{ $item->material }}</td>
                            <td class="text-muted small">{{ $item->descripcion }}</td>
                            <td class="text-center small">{{ $item->almacen }}</td>
                            <td class="text-end fw-bold text-primary">
                                @if($item->contado)
                                    {{ number_format((float) $item->cantidad_fisica, 2) }}
                                @else
                                    <span class="text-muted opacity-25">-</span>
                                @endif
                            </td>
                            @if($isFullAdmin)
                                <td class="text-end small">{{ number_format((float) $item->stock_sap, 2) }}</td>
                                <td class="text-end small">${{ number_format((float) $item->valor_sap, 2) }}</td>
                                <td class="text-end fw-bold {{ $diffColor }}">
                                    {{ $item->contado ? number_format((float) $item->diferencia, 2) : '-' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light py-0 px-2" onclick="toggleHistory('{{ $item->id }}', event)">
                                        <i class="fa-solid fa-clock-rotate-left small"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                        {{-- Detalle Histórico --}}
                        <tr id="history-{{ $item->id }}" style="display: none; background: #fcfcfc;">
                            <td colspan="10" class="p-3 border-start border-4 border-info">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="x-small fw-bold text-muted me-2">INTENTOS:</span>
                                    <span class="history-pill">C1: <b>{{ $item->conteo_1 ?? '-' }}</b></span>
                                    <span class="history-pill">C2: <b>{{ $item->conteo_2 ?? '-' }}</b></span>
                                    <span class="history-pill">C3: <b>{{ $item->conteo_3 ?? '-' }}</b></span>
                                    @if($item->contado)
                                        <span class="small text-muted ms-auto">Valor Var: <b>${{ number_format($item->valor_diferencia, 2) }}</b></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scanner Overlay -->
    <div id="scannerScreen" class="overlay-screen">
        <div class="overlay-header">
            <h6 class="mb-0 fw-bold" id="scannerTitle">ESCANEANDO...</h6>
            <button class="btn-close" onclick="hideScannerScreen()"></button>
        </div>
        <div id="qr-reader-full" style="width: 100%; height: 60vh; background: #000;"></div>
        <div class="p-3 text-center">
            <p class="small text-muted mb-0">Apunte al QR del material</p>
        </div>
    </div>

    <div id="countingScreen" class="overlay-screen">
        <div class="overlay-header bg-dark text-white" style="border-bottom: 1px solid #374151;">
            <div>
                <h6 class="mb-0 fw-bold">REGISTRAR CONTEO</h6>
                <small id="csAttemptLabel" class="opacity-75">Conteo #{{ $ciclico->intento_actual }}</small>
            </div>
            <button class="btn btn-sm btn-outline-light" onclick="closeCountingScreen()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="overlay-body">
            <div class="mb-3 text-center">
                <h4 id="csMaterialCode" class="fw-bold mb-1">MATERIAL</h4>
                <p id="csMaterialDesc" class="text-muted small mb-0"></p>
            </div>

            <div class="card border-0 bg-light mb-3">
                <div class="card-body py-2">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block" style="font-size:0.7rem;">STOCK SAP</small>
                            <span class="fw-bold" id="csStockSap">0.00</span> <small id="csUm" class="text-muted"></small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block" style="font-size:0.7rem;">YA CONTADO</small>
                            <span class="fw-bold text-primary" id="csYaContado">0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aviso de suma cuando ya tiene conteo --}}
            <div id="csSumaAviso" class="alert alert-info border-0 small py-2 d-none">
                <i class="fa-solid fa-circle-info me-1"></i>
                <span id="csSumaAvisoTexto"></span>
            </div>

            <label class="small fw-bold text-muted text-center d-block mb-2">CANTIDAD A AGREGAR</label>
            <input type="number" id="csQuantity" class="counting-input-large" placeholder="0.00" inputmode="decimal" step="0.01">

            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-light w-100 py-3 fw-bold" onclick="closeCountingScreen()">CANCELAR</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-primary w-100 py-3 fw-bold" onclick="submitCountCS()">
                        <i class="fa-solid fa-plus me-1"></i> SUMAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="tempItemId">
    <input type="hidden" id="tempMaterialCode">
    <input type="hidden" id="tempMaterialDesc">
    <input type="hidden" id="tempStockSap">
    <input type="hidden" id="tempUm">
    <input type="hidden" id="tempCurrentCount" value="0">

    <script>
        // Funciones de utilidad y lógica (Mantenemos la misma lógica pero con UI mejorada)
        function processUpload() {
            const fileInput = document.getElementById('sapFile');
            const file = fileInput.files[0];
            if (!file) return alert('Seleccione un archivo');

            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            const btn = document.getElementById('btnProcess');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            fetch('{{ route("inventario.import", $ciclico->id) }}', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) { alert(data.success); location.reload(); }
                    else { alert(data.error); btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-upload"></i> Subir'; }
                });
        }

        function toggleRowSelection(row) {
            const cb = row.querySelector('.item-checkbox');
            if (cb) cb.checked = !cb.checked;
        }

        function confirmSelection() {
            const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) return alert('Seleccione materiales');
            
            fetch('{{ route("inventario.start_counting", $ciclico->id) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids: selected })
            }).then(res => res.json()).then(data => { if (data.success) location.reload(); });
        }

        function requestSuggestions(tipo) {
            fetch('{{ route("inventario.suggest", $ciclico->id) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ tipo: tipo })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
                    let marcados = 0;
                    const tbody = document.querySelector('#materialsTable tbody');
                    
                    // Iterar en reversa para insertarlos al principio conservando el orden de la sugerencia
                    [...data.items].reverse().forEach(id => {
                        const cb = document.querySelector(`.item-checkbox[value="${id}"]`);
                        if (cb) {
                            cb.checked = true;
                            marcados++;
                            
                            // Mover las filas visualmente al tope de la tabla
                            const row = document.getElementById(`row-${id}`);
                            const historyRow = document.getElementById(`history-${id}`);
                            if (row && tbody) {
                                if (historyRow) tbody.prepend(historyRow);
                                tbody.prepend(row);
                            }
                        }
                    });
                    
                    alert(`Se han sugerido y marcado ${marcados} ítems.\nSe excluyeron ${data.excluded_count} ítems que ya fueron contados en otros inventarios de este mes.\n\nLos ítems sugeridos se han movido a la parte superior de la lista.`);
                }
            });
        }

        let html5QrCode = null;
        let selectedMaterialCode = null;
        let isGlobalScan = false;
        const sessionItems = @json($items);

        function openScanner(id, material, desc, stockSap, um) {
            @if($ciclico->status != 'Abierto' || $fase == 'revision') return; @endif
            isGlobalScan = false;
            selectedMaterialCode = material;
            document.getElementById('tempItemId').value = id;
            document.getElementById('tempMaterialCode').value = material;
            document.getElementById('tempMaterialDesc').value = desc;
            document.getElementById('tempStockSap').value = stockSap;
            document.getElementById('tempUm').value = um;
            // Buscar conteo actual
            const item = sessionItems.find(i => i.id == id);
            document.getElementById('tempCurrentCount').value = item ? (item.cantidad_fisica ?? 0) : 0;
            document.getElementById('scannerTitle').textContent = 'VALIDANDO: ' + material;
            showScannerScreen();
        }

        function openGlobalScanner() {
            @if($ciclico->status != 'Abierto' || $fase == 'revision') return; @endif
            isGlobalScan = true;
            document.getElementById('scannerTitle').textContent = 'ESCANEADO LIBRE';
            showScannerScreen();
        }

        function showScannerScreen() {
            document.getElementById('scannerScreen').style.display = 'flex';
            initQrScanner();
        }

        function hideScannerScreen() {
            stopScanner().then(() => { document.getElementById('scannerScreen').style.display = 'none'; });
        }

        function initQrScanner() {
            html5QrCode = new Html5Qrcode("qr-reader-full");
            html5QrCode.start({ facingMode: "environment" }, { fps: 15, qrbox: 250 }, onScanSuccess)
                .catch(err => { alert("No cámara"); hideScannerScreen(); });
        }

        function onScanSuccess(decodedText) {
            let scannedCode = decodedText.trim();
            if (scannedCode.includes('|')) scannedCode = scannedCode.split('|')[0].trim();
            
            stopScanner().then(() => {
                if (isGlobalScan) {
                    const item = sessionItems.find(i => i.material.trim() === scannedCode);
                    if (item) {
                        setAndCount(item);
                    } else {
                        addAndCount(scannedCode);
                    }
                } else {
                    if (scannedCode === selectedMaterialCode.trim()) verifySuccess();
                    else { alert('CÓDIGO DIFERENTE: ' + scannedCode); startScanner(); }
                }
            });
        }

        function setAndCount(item) {
            document.getElementById('tempItemId').value = item.id;
            document.getElementById('tempMaterialCode').value = item.material;
            document.getElementById('tempMaterialDesc').value = item.descripcion;
            document.getElementById('tempStockSap').value = item.stock_sap;
            document.getElementById('tempUm').value = item.um;
            document.getElementById('tempCurrentCount').value = item.cantidad_fisica ?? 0;
            verifySuccess();
        }

        function addAndCount(codSap) {
            fetch('{{ route("inventario.add_item", $ciclico->id) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ cod_sap: codSap })
            }).then(res => res.json()).then(data => {
                if (data.success) setAndCount(data.item);
                else { alert(data.error); initQrScanner(); }
            });
        }

        function verifySuccess() {
            document.getElementById('scannerScreen').style.display = 'none';
            document.getElementById('csMaterialCode').textContent = document.getElementById('tempMaterialCode').value;
            document.getElementById('csMaterialDesc').textContent = document.getElementById('tempMaterialDesc').value;
            document.getElementById('csStockSap').textContent = parseFloat(document.getElementById('tempStockSap').value).toFixed(2);
            document.getElementById('csUm').textContent = document.getElementById('tempUm').value;
            document.getElementById('csQuantity').value = '';

            // Mostrar cuánto ya se ha contado en este intento
            const yaContado = parseFloat(document.getElementById('tempCurrentCount').value) || 0;
            document.getElementById('csYaContado').textContent = yaContado.toFixed(2);
            const aviso = document.getElementById('csSumaAviso');
            const avisoTexto = document.getElementById('csSumaAvisoTexto');
            if (yaContado > 0) {
                aviso.classList.remove('d-none');
                avisoTexto.textContent = 'Este material ya tiene ' + yaContado.toFixed(2) + ' registrado. El nuevo valor se sumará.';
            } else {
                aviso.classList.add('d-none');
            }

            document.getElementById('countingScreen').style.display = 'flex';
            setTimeout(() => document.getElementById('csQuantity').focus(), 300);
        }

        function closeCountingScreen() { document.getElementById('countingScreen').style.display = 'none'; }

        function submitCountCS() {
            const id = document.getElementById('tempItemId').value;
            const quantity = document.getElementById('csQuantity').value;
            if(!quantity) return alert('Ingrese cantidad');

            fetch('{{ route("inventario.register_count", $ciclico->id) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id: id, cantidad: quantity })
            }).then(res => res.json()).then(data => { if (data.success) location.reload(); });
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) return html5QrCode.stop();
            return Promise.resolve();
        }

        function toggleHistory(id, event) {
            event.stopPropagation();
            const row = document.getElementById('history-' + id);
            row.style.display = (row.style.display === 'none') ? 'table-row' : 'none';
        }

        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const text = this.value.toLowerCase();
            const rows = document.querySelectorAll('#materialsTable tbody tr[id^="row-"]');
            rows.forEach(row => {
                const material = row.cells[1].textContent.toLowerCase();
                const desc = row.cells[2].textContent.toLowerCase();
                row.style.display = (material.includes(text) || desc.includes(text)) ? '' : 'none';
            });
        });
    </script>
@endsection