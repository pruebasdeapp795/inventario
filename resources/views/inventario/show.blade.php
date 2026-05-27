@extends('layout.app')

@section('title', 'Sesión de Inventario - ' . $ciclico->nombre)

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

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

        /* Pantalla de Conteo Full-screen */
        #countingScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #1e293b;
            z-index: 9999;
            display: none;
            flex-direction: column;
            color: white;
            padding: 20px;
        }

        .counting-header {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
        }

        .counting-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 500px;
            margin: 0 auto;
            width: 100%;
        }

        .large-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 4px solid #3b82f6;
            color: white;
            font-size: 80px;
            text-align: center;
            font-weight: 800;
            margin-bottom: 40px;
            outline: none;
        }

        .large-input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .counting-btn {
            width: 100%;
            padding: 24px;
            border-radius: 16px;
            font-size: 20px;
            font-weight: 700;
            transition: all 0.2s;
        }

        .material-info-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 24px;
            width: 100%;
            margin-bottom: 40px;
        }

        /* Pantalla de Scanner Full-screen */
        #scannerScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #000;
            z-index: 9998;
            display: none;
            flex-direction: column;
            color: white;
        }

        .scanner-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10;
        }

        #qr-reader-full {
            flex: 1;
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }

        #qr-reader-full video {
            object-fit: cover !important;
        }
    </style>

    <!-- Inputs temporales para transferencia de datos -->
    <input type="hidden" id="tempItemId">
    <input type="hidden" id="tempMaterialCode">
    <input type="hidden" id="tempMaterialDesc">
    <input type="hidden" id="tempStockSap">
    <input type="hidden" id="tempUm">

    <div class="main-panel">
        <div class="header-section">
            <div class="title-area">
                <a href="{{ route('inventario.index') }}"
                    class="text-decoration-none text-muted small mb-2 d-inline-block"><i class="fa-solid fa-arrow-left"></i>
                    Volver al historial</a>
                <div class="d-flex align-items-center gap-3">
                    <h2 class="mb-0">{{ $ciclico->nombre }}</h2>
                    <div class="status-pill m-0">
                        <span class="dot"></span> {{ $ciclico->status }} ({{ $fase }})
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-primary px-3 py-2" style="border-radius: 8px;">
                        <i class="fa-solid fa-list-ol me-1"></i> CONTEO #{{ $ciclico->intento_actual }}
                    </span>
                </div>

                @if($totalItems > 0)
                    <div class="mt-3" style="max-width: 300px;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small fw-bold">AVANCE DEL INVENTARIO</span>
                            <span class="text-primary small fw-bold">{{ $avance }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 4px; background: #e2e8f0;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: {{ $avance }}%; background: #3b82f6;" aria-valuenow="{{ $avance }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mt-1 small text-muted">
                            {{ $contadosItems }} de {{ $totalItems }} ítems contados
                        </div>
                    </div>
                @endif
            </div>
            <div class="actions-area d-flex gap-3">
                @if($ciclico->status == 'Abierto')
                    @if($canSelect && $fase == 'configuracion')
                        <button class="btn btn-success px-4 py-2" style="border-radius: 12px; font-weight: 600;"
                            onclick="confirmSelection()">
                            <i class="fa-solid fa-check-double me-2"></i> INICIAR CONTEO
                        </button>
                    @endif

                    @if($fase == 'conteo')
                        <button class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;"
                            onclick="openGlobalScanner()">
                            <i class="fa-solid fa-qrcode me-2"></i> ESCANEAR PRODUCTO
                        </button>
                    @endif

                    @if($isFullAdmin)
                        @if($ciclico->intento_actual < 3 && $fase == 'conteo')
                            <form action="{{ route('inventario.next_attempt', $ciclico->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning px-4 py-2"
                                    style="border-radius: 12px; font-weight: 600; color: #856404;"
                                    onclick="return confirm('¿Iniciar un nuevo intento de reconteo? Los nuevos valores no sobrescribirán el historial anterior.')">
                                    <i class="fa-solid fa-repeat me-2"></i> INICIAR RECONTEO
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('inventario.close', $ciclico->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-red"
                                onclick="return confirm('¿Seguro que desea finalizar este conteo? No podrá cargar más materiales.')">
                                <i class="fa-solid fa-square"></i> Finalizar
                            </button>
                        </form>
                    @endif
                @endif
                <button class="btn-white" onclick="location.reload()">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </div>
        </div>

        @if($ciclico->status == 'Abierto' && $isFullAdmin)
            <div class="upload-zone">
                <div class="upload-icon-circle">
                    <img src="https://cdn-icons-png.flaticon.com/512/732/732220.png" width="32" alt="Excel">
                </div>
                <h3>Cargar Reporte SAP</h3>
                <p>Seleccione el archivo Excel generado por SAP para sincronizar los materiales y existencias de esta sesión.
                </p>
                <!-- ... -->

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
                                    @if($fase == 'configuracion')
                                        <th style="width: 40px;"><input type="checkbox" id="selectAllItems"></th>
                                    @endif
                                    <th>Material</th>
                                    <th>Descripción</th>
                                    <th>Centro/Alm.</th>
                                    <th class="text-end">Conteo</th>
                                    @if($isFullAdmin)
                                        <th class="text-center">Historial</th>
                                        <th class="text-end">Stock SAP</th>
                                        <th class="text-end">Diferencia</th>
                                        <th class="text-end">Valor Var.</th>
                                        <th class="text-center">Estado</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $diffClass = $item->diferencia == 0 ? 'text-muted' : ($item->diferencia > 0 ? 'text-success' : 'text-danger');
                                        // Si estamos en conteo, solo mostramos los seleccionados para evitar errores
                                        if ($fase == 'conteo' && !$item->seleccionado && !$item->contado)
                                            continue;
                                    @endphp
                                    <tr @if($fase == 'conteo')
                                        onclick="openScanner('{{ $item->id }}', '{{ $item->material }}', '{{ $item->descripcion }}', '{{ $item->stock_sap }}', '{{ $item->um }}')"
                                    @else onclick="toggleRowSelection(this)" @endif style="cursor: pointer;"
                                        id="row-{{ $item->id }}">
                                        @if($fase == 'configuracion')
                                            <td><input type="checkbox" class="item-checkbox" value="{{ $item->id }}"
                                                    @if($item->seleccionado) checked @endif></td>
                                        @endif
                                        <td class="fw-bold">{{ $item->material }}</td>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>{{ $item->centro }} / {{ $item->almacen }}</td>
                                        <td class="text-end fw-bold text-primary" id="conteo-{{ $item->id }}">
                                            @if($item->contado)
                                                {{ number_format((float) $item->cantidad_fisica, 2) }}
                                                <i class="fa-solid fa-circle-check text-success ms-1 small"></i>
                                            @else
                                                <span class="text-muted opacity-50">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-secondary border-0" 
                                                    onclick="toggleHistory('{{ $item->id }}', event)" 
                                                    title="Ver intentos de conteo">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                            </button>
                                        </td>
                                        @if($isFullAdmin)
                                            <td class="text-end">{{ number_format((float) $item->stock_sap, 2) }} <span
                                                    class="small text-muted">{{ $item->um }}</span></td>
                                            <td class="text-end fw-bold {{ $diffClass }}" id="diff-{{ $item->id }}">
                                                {{ $item->contado ? number_format((float) $item->diferencia, 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold {{ $diffClass }}" id="valdiff-{{ $item->id }}">
                                                {{ $item->contado ? '$' . number_format((float) $item->valor_diferencia, 2) : '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($item->contado)
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle px-2">Contado</span>
                                                @else
                                                    <span class="badge bg-light text-muted border px-2">Pendiente</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                    {{-- Fila de detalles (Historial) --}}
                                    <tr id="history-{{ $item->id }}" style="display: none; background-color: #f8fafc;">
                                        <td colspan="{{ $fase == 'configuracion' ? '10' : '9' }}" class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-4">
                                                <div class="small fw-bold text-muted">DETALLE DE INTENTOS:</div>
                                                <div class="d-flex gap-3">
                                                    <span class="badge border text-dark bg-white">
                                                        <small class="text-muted mr-1">Conteo 1:</small> 
                                                        <b>{{ $item->conteo_1 !== null ? number_format($item->conteo_1, 2) : '-' }}</b>
                                                    </span>
                                                    <span class="badge border text-dark bg-white">
                                                        <small class="text-muted mr-1">Conteo 2:</small> 
                                                        <b>{{ $item->conteo_2 !== null ? number_format($item->conteo_2, 2) : '-' }}</b>
                                                    </span>
                                                    <span class="badge border text-dark bg-white">
                                                        <small class="text-muted mr-1">Conteo 3:</small> 
                                                        <b>{{ $item->conteo_3 !== null ? number_format($item->conteo_3, 2) : '-' }}</b>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pantalla de Scanner Full-screen -->
        <div id="scannerScreen">
            <div class="scanner-header">
                <h5 class="mb-0" id="scannerTitle">Escaneando...</h5>
                <button class="btn btn-outline-light border-0" onclick="hideScannerScreen()">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div id="qr-reader-full"></div>
            <div class="p-3 text-center bg-dark" style="z-index: 10;">
                <p id="full-qr-status" class="small mb-0">Apunte al código QR del material</p>
            </div>
        </div>

        <!-- Pantalla de Conteo Full-screen -->
        <div id="countingScreen">
            <div class="counting-header">
                <button class="btn btn-link text-white float-start mt-2" onclick="closeCountingScreen()">
                    <i class="fa-solid fa-arrow-left fa-xl"></i>
                </button>
                <h2 class="fw-bold" id="csMaterialCode">MATERIAL</h2>
                <div class="status-pill justify-content-center">
                    <span class="dot"></span> MODO CONTEO
                </div>
            </div>

            <div class="counting-body">
                <div class="material-info-card text-center">
                    <h4 id="csMaterialDesc">Descripción del material</h4>
                    <div class="d-flex justify-content-around mt-4">
                        <div id="csStockSapArea">
                            <small class="text-muted d-block opacity-75">STOCK TEÓRICO</small>
                            <span class="h3 fw-bold" id="csStockSap">0.00</span>
                        </div>
                        <div>
                            <small class="text-muted d-block opacity-75">UNIDAD</small>
                            <span class="badge bg-primary" id="csUm">UND</span>
                        </div>
                    </div>
                </div>

                <input type="number" step="0.01" id="csQuantity" class="large-input" placeholder="0.00" autofocus>

                <button class="btn btn-success counting-btn shadow-lg" onclick="submitCountCS()">
                    <i class="fa-solid fa-floppy-disk me-2"></i> REGISTRAR CANTIDAD
                </button>

                <button class="btn btn-outline-light mt-3 border-0 opacity-50" onclick="closeCountingScreen()">
                    Cancelar
                </button>
            </div>
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

            let html5QrCode = null;
            let selectedMaterialCode = null;
            let isGlobalScan = false;

            // Mapeo local de materiales para búsqueda rápida en el scanner global
            const sessionItems = @json($items);

            function openScanner(id, material, desc, stockSap, um) {
                        @if($ciclico->status != 'Abierto') return; @endif

                isGlobalScan = false;
                selectedMaterialCode = material;

                document.getElementById('tempItemId').value = id;
                document.getElementById('tempMaterialCode').value = material;
                document.getElementById('tempMaterialDesc').value = desc;
                document.getElementById('tempStockSap').value = stockSap;
                document.getElementById('tempUm').value = um;

                document.getElementById('scannerTitle').textContent = 'Validando: ' + material;
                showScannerScreen();
            }

            function openGlobalScanner() {
                        @if($ciclico->status != 'Abierto') return; @endif

                isGlobalScan = true;
                selectedMaterialCode = null;
                document.getElementById('scannerTitle').textContent = 'Escaneo Libre';
                showScannerScreen();
            }

            function showScannerScreen() {
                document.getElementById('scannerScreen').style.display = 'flex';
                startScanner();
            }

            function hideScannerScreen() {
                stopScanner().then(() => {
                    document.getElementById('scannerScreen').style.display = 'none';
                });
            }

            function startScanner() {
                if (html5QrCode) {
                    stopScanner().then(() => initQrScanner());
                } else {
                    initQrScanner();
                }
            }

            function initQrScanner() {
                html5QrCode = new Html5Qrcode("qr-reader-full");
                const config = {
                    fps: 25,
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        let fontSize = Math.floor(minEdge * 0.6);
                        return { width: fontSize, height: fontSize };
                    }
                };

                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    onScanSuccess
                ).catch(err => {
                    console.error(err);
                    alert("No se pudo acceder a la cámara.");
                    hideScannerScreen();
                });
            }

            function onScanSuccess(decodedText, decodedResult) {
                let scannedCode = decodedText.trim();

                if (scannedCode.includes('|')) {
                    scannedCode = scannedCode.split('|')[0].trim();
                }

                console.log(`Scanned: ${scannedCode}`);

                // DETENER ESCANER DE INMEDIATO Y ESPERAR
                stopScanner().then(() => {
                    if (isGlobalScan) {
                        const item = sessionItems.find(i => i.material.trim() === scannedCode);
                        if (item) {
                            selectedMaterialCode = item.material;
                            document.getElementById('tempItemId').value = item.id;
                            document.getElementById('tempMaterialCode').value = item.material;
                            document.getElementById('tempMaterialDesc').value = item.descripcion;
                            document.getElementById('tempStockSap').value = item.stock_sap;
                            document.getElementById('tempUm').value = item.um;
                            verifySuccess();
                        } else {
                            addMaterialToSession(scannedCode);
                        }
                    } else {
                        if (scannedCode === selectedMaterialCode.trim()) {
                            verifySuccess();
                        } else {
                            alert(`ERROR: Escaneó "${scannedCode}" pero se esperaba "${selectedMaterialCode}".`);
                            startScanner(); // Reintentar
                        }
                    }
                });
            }

            function addMaterialToSession(codSap) {
                document.getElementById('full-qr-status').textContent = 'Agregando material ' + codSap + '...';

                fetch('{{ route("inventario.add_item", $ciclico->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cod_sap: codSap })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const item = data.item;
                            document.getElementById('tempItemId').value = item.id;
                            document.getElementById('tempMaterialCode').value = item.material;
                            document.getElementById('tempMaterialDesc').value = item.descripcion;
                            document.getElementById('tempStockSap').value = item.stock_sap;
                            document.getElementById('tempUm').value = item.um;
                            verifySuccess();
                        } else {
                            alert(data.error);
                            startScanner();
                        }
                    })
                    .catch(err => {
                        alert('Error de conexión');
                        startScanner();
                    });
            }

            function verifySuccess() {
                // Detener scanner si seguía activo
                stopScanner().then(() => {
                    // Ocultar pantalla de scanner
                    document.getElementById('scannerScreen').style.display = 'none';

                    // Cargar info en la pantalla de conteo
                    document.getElementById('csMaterialCode').textContent = document.getElementById('tempMaterialCode').value;
                    document.getElementById('csMaterialDesc').textContent = document.getElementById('tempMaterialDesc').value;

                    @if(!$isFullAdmin)
                        document.getElementById('csStockSapArea').style.display = 'none';
                    @endif

                    document.getElementById('csStockSap').textContent = parseFloat(document.getElementById('tempStockSap').value).toFixed(2);
                    document.getElementById('csUm').textContent = document.getElementById('tempUm').value;
                    document.getElementById('csQuantity').value = '';

                    // Mostrar pantalla de conteo
                    document.getElementById('countingScreen').style.display = 'flex';

                    setTimeout(() => {
                        document.getElementById('csQuantity').focus();
                        // Intentar abrir teclado numérico forzado
                        document.getElementById('csQuantity').click();
                    }, 100);
                });
            }

            function closeCountingScreen() {
                document.getElementById('countingScreen').style.display = 'none';
            }

            function submitCountCS() {
                const id = document.getElementById('tempItemId').value;
                const quantity = document.getElementById('csQuantity').value;

                if (!id) {
                    alert('Error: No se identificó el material. Por favor escanee de nuevo.');
                    closeCountingScreen();
                    return;
                }

                if (quantity === '' || isNaN(parseFloat(quantity))) {
                    alert('Por favor ingrese una cantidad válida.');
                    return;
                }

                const btn = document.querySelector('#countingScreen .btn-success');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Guardando...';

                console.log("Enviando conteo:", { id: id, cantidad: quantity });

                fetch('{{ route("inventario.register_count", $ciclico->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, cantidad: quantity })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error del servidor: ' + (data.error || data.message || 'Error desconocido'));
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> REGISTRAR CANTIDAD';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error de conexión o red. Verifique su señal.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> REGISTRAR CANTIDAD';
                    });
            }

            function confirmSelection() {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                if (checked.length === 0) {
                    alert('Debe seleccionar al menos un material para contar.');
                    return;
                }

                if (!confirm('¿Iniciar el conteo con los materiales seleccionados?')) return;

                const ids = Array.from(checked).map(cb => cb.value);

                fetch('{{ route("inventario.start_counting", $ciclico->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: ids })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error al iniciar conteo');
                        }
                    });
            }

            document.getElementById('selectAllItems')?.addEventListener('change', function () {
                const isChecked = this.checked;
                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.checked = isChecked;
                });
            });

            function toggleRowSelection(row) {
                const checkbox = row.querySelector('.item-checkbox');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            }

            function stopScanner() {
                return new Promise((resolve) => {
                    if (html5QrCode && html5QrCode.isScanning) {
                        html5QrCode.stop().then(() => {
                            html5QrCode.clear();
                            resolve();
                        }).catch(err => {
                            console.error(err);
                            resolve();
                        });
                    } else {
                        resolve();
                    }
                });
            }

            function resetScanner() {
                document.getElementById('verificationSuccess').style.display = 'none';
                document.getElementById('qr-reader-container').style.display = 'block';
                document.getElementById('countForm').style.opacity = '0.5';
                document.getElementById('countForm').style.pointerEvents = 'none';
                startScanner();
            }

            function enableManualEntry() {
                // Si la cámara falla, podríamos dejar que el usuario ingrese la cantidad directamente
                // o pedir que ingrese el código manualmente. Por ahora habilitamos para no bloquear.
                document.getElementById('qr-status').innerHTML += '<br><button class="btn btn-sm btn-link" onclick="verifySuccess()">Omitir verificación (Cámara no disponible)</button>';
            }

            function submitCount() {
                const id = document.getElementById('modalItemId').value;
                const quantity = document.getElementById('modalQuantity').value;

                if (quantity === '') return;

                fetch('{{ route("inventario.register_count", $ciclico->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, cantidad: quantity })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error al registrar conteo');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error de red');
                    });
            }
            function toggleHistory(id, event) {
            if (event) event.stopPropagation();
            const row = document.getElementById('history-' + id);
            if (row.style.display === 'none') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        }
    </script>
@endsection