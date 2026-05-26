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
    </style>

    <div class="main-panel">
        <div class="header-section">
            <div class="title-area">
                <a href="{{ route('inventario.index') }}"
                    class="text-decoration-none text-muted small mb-2 d-inline-block"><i class="fa-solid fa-arrow-left"></i>
                    Volver al historial</a>
                <div class="d-flex align-items-center gap-3">
                    <h2 class="mb-0">{{ $ciclico->nombre }}</h2>
                    <div class="status-pill m-0">
                        <span class="dot"></span> {{ $ciclico->status }}
                    </div>
                </div>

                @if($isAdmin)
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
                    @if($isAdmin)
                        <form action="{{ route('inventario.close', $ciclico->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-red"
                                onclick="return confirm('¿Seguro que desea finalizar este conteo? No podrá cargar más materiales.')">
                                <i class="fa-solid fa-square"></i> Finalizar Inventario
                            </button>
                        </form>
                    @endif
                    <button class="btn btn-primary px-4 py-2" style="border-radius: 12px; font-weight: 600;"
                        onclick="openGlobalScanner()">
                        <i class="fa-solid fa-qrcode me-2"></i> ESCANEAR PRODUCTO
                    </button>
                @endif
                <button class="btn-white" onclick="location.reload()">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </div>
        </div>

        @if($ciclico->status == 'Abierto' && $isAdmin)
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
                                    <th>Material</th>
                                    <th>Descripción</th>
                                    <th>Centro/Alm.</th>
                                    @if($isAdmin)
                                        <th class="text-end">Stock SAP</th>
                                        <th class="text-end">Conteo</th>
                                        <th class="text-end">Diferencia</th>
                                        <th class="text-end">Valor Var.</th>
                                    @endif
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $diffClass = $item->diferencia == 0 ? 'text-muted' : ($item->diferencia > 0 ? 'text-success' : 'text-danger');
                                    @endphp
                                    <tr onclick="openScanner('{{ $item->id }}', '{{ $item->material }}', '{{ $item->descripcion }}', '{{ $item->stock_sap }}', '{{ $item->um }}')"
                                        style="cursor: pointer;" id="row-{{ $item->id }}">
                                        <td class="fw-bold">{{ $item->material }}</td>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>{{ $item->centro }} / {{ $item->almacen }}</td>
                                        @if($isAdmin)
                                            <td class="text-end">{{ number_format((float) $item->stock_sap, 2) }} <span
                                                    class="small text-muted">{{ $item->um }}</span></td>
                                            <td class="text-end fw-bold text-primary" id="conteo-{{ $item->id }}">
                                                {{ $item->contado ? number_format((float) $item->cantidad_fisica, 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold {{ $diffClass }}" id="diff-{{ $item->id }}">
                                                {{ $item->contado ? number_format((float) $item->diferencia, 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold {{ $diffClass }}" id="valdiff-{{ $item->id }}">
                                                {{ $item->contado ? '$' . number_format((float) $item->valor_diferencia, 2) : '-' }}
                                            </td>
                                        @endif
                                        <td class="text-center">
                                            @if($item->contado)
                                                <span
                                                    class="badge bg-success-subtle text-success border border-success-subtle px-2">Contado</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Conteo / Scanner -->
        <div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalMaterialCode">Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p id="modalMaterialDesc" class="text-muted mb-4"></p>

                        <div id="qr-reader-container" class="mb-4">
                            <div id="qr-reader"
                                style="width: 100%; border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; background: #f8fafc;">
                            </div>
                            <div id="qr-status" class="mt-2 text-center small fw-bold">
                                <span class="text-muted"><i class="fa-solid fa-camera me-1"></i> Escanee el código del
                                    material para verificar</span>
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-4" style="border-radius: 16px; display: none;"
                            id="verificationSuccess">
                            <div class="card-body d-flex justify-content-between align-items-center py-2">
                                <span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Material
                                    Verificado</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetScanner()"><i
                                        class="fa-solid fa-redo"></i> Re-escanear</button>
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-4" style="border-radius: 16px;">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                @if($isAdmin)
                                    <div>
                                        <small class="text-muted d-block">Stock Teórico (SAP)</small>
                                        <span class="fw-bold h5 mb-0" id="modalStockSap">0.00</span>
                                    </div>
                                @endif
                                <div class="text-end">
                                    <small class="text-muted d-block">Unidad</small>
                                    <span class="badge bg-secondary" id="modalUm">UM</span>
                                </div>
                            </div>
                        </div>

                        <form id="countForm" style="opacity: 0.5; pointer-events: none;">
                            <input type="hidden" id="modalItemId">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">CANTIDAD CONTADA</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.01" id="modalQuantity"
                                        class="form-control border-primary shadow-sm"
                                        style="border-radius: 12px; font-weight: 700; font-size: 24px; text-align: center;"
                                        placeholder="0.00" required autofocus>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold"
                                style="border-radius: 12px; background: #3b82f6; border: none;">
                                <i class="fa-solid fa-check-double me-2"></i> REGISTRAR CONTEO
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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

        let scannerModal = null;
        let html5QrCode = null;
        let selectedMaterialCode = null;
        let isGlobalScan = false;

        // Mapeo local de materiales para búsqueda rápida en el scanner global
        const sessionItems = @json($items);

        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('scannerModal');
            if (modalEl) {
                scannerModal = new bootstrap.Modal(modalEl);

                modalEl.addEventListener('hidden.bs.modal', function () {
                    stopScanner();
                });

                document.getElementById('countForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitCount();
                });
            }
        });

        function openScanner(id, material, desc, stockSap, um) {
            @if($ciclico->status != 'Abierto')
                return;
            @endif

            isGlobalScan = false;
            selectedMaterialCode = material;
            document.getElementById('modalItemId').value = id;
            document.getElementById('modalMaterialCode').textContent = material;
            document.getElementById('modalMaterialDesc').textContent = desc;
            document.getElementById('modalStockSap').textContent = parseFloat(stockSap).toFixed(2);
            document.getElementById('modalUm').textContent = um;
            document.getElementById('modalQuantity').value = '';

            // Reset UI
            document.getElementById('verificationSuccess').style.display = 'none';
            document.getElementById('qr-reader-container').style.display = 'block';
            document.getElementById('qr-status').innerHTML = '<span class="text-muted"><i class="fa-solid fa-camera me-1"></i> Escanee el código del material para verificar</span>';
            document.getElementById('countForm').style.opacity = '0.5';
            document.getElementById('countForm').style.pointerEvents = 'none';

            scannerModal.show();

            setTimeout(() => {
                startScanner();
            }, 500);
        }

        function openGlobalScanner() {
            @if($ciclico->status != 'Abierto')
                return;
            @endif

            isGlobalScan = true;
            selectedMaterialCode = null;
            document.getElementById('modalItemId').value = '';
            document.getElementById('modalMaterialCode').textContent = 'Escaneo de Sesión';
            document.getElementById('modalMaterialDesc').textContent = 'Escanee cualquier producto para registrar su conteo.';
            document.getElementById('modalQuantity').value = '';

            // Reset UI
            document.getElementById('verificationSuccess').style.display = 'none';
            document.getElementById('qr-reader-container').style.display = 'block';
            document.getElementById('qr-status').innerHTML = '<span class="text-primary fw-bold"><i class="fa-solid fa-qrcode me-1"></i> LISTO: ESCANEE PRODUCTO</span>';
            document.getElementById('countForm').style.opacity = '0.5';
            document.getElementById('countForm').style.pointerEvents = 'none';

            scannerModal.show();

            setTimeout(() => {
                startScanner();
            }, 500);
        }

        function startScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    initQrScanner();
                }).catch(() => {
                    initQrScanner();
                });
            } else {
                initQrScanner();
            }
        }

        function initQrScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess
            ).catch(err => {
                console.error("No se pudo iniciar el scanner", err);
                document.getElementById('qr-status').innerHTML = '<span class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> Error de cámara o permiso denegado</span>';
                // Fallback: Permitir ingreso manual si falla la cámara (opcional)
                enableManualEntry();
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            const scannedCode = decodedText.trim();
            console.log(`Scan result: ${scannedCode}`);

            if (isGlobalScan) {
                // Buscamos el material en los items de la sesión
                const item = sessionItems.find(i => i.material.trim() === scannedCode);
                if (item) {
                    // Cargar datos del item encontrado
                    selectedMaterialCode = item.material;
                    document.getElementById('modalItemId').value = item.id;
                    document.getElementById('modalMaterialCode').textContent = item.material;
                    document.getElementById('modalMaterialDesc').textContent = item.descripcion;
                    document.getElementById('modalStockSap').textContent = parseFloat(item.stock_sap).toFixed(2);
                    document.getElementById('modalUm').textContent = item.um;
                    verifySuccess();
                } else {
                    document.getElementById('qr-status').innerHTML = `<span class="text-danger"><i class="fa-solid fa-xmark"></i> El material "${scannedCode}" no pertenece a este cargue</span>`;
                    const reader = document.getElementById('qr-reader');
                    reader.style.borderColor = '#ef4444';
                    setTimeout(() => { reader.style.borderColor = '#e2e8f0'; }, 1000);
                }
            } else {
                // Verificamos si el código escaneado coincide con el material seleccionado manualmente
                if (scannedCode === selectedMaterialCode.trim()) {
                    verifySuccess();
                } else {
                    document.getElementById('qr-status').innerHTML = `<span class="text-danger"><i class="fa-solid fa-xmark"></i> El código "${scannedCode}" no coincide con el material "${selectedMaterialCode}"</span>`;
                    const reader = document.getElementById('qr-reader');
                    reader.style.borderColor = '#ef4444';
                    setTimeout(() => { reader.style.borderColor = '#e2e8f0'; }, 1000);
                }
            }
        }

        function verifySuccess() {
            // Sonido de éxito si se desea (opcional)
            stopScanner();

            document.getElementById('qr-reader-container').style.display = 'none';
            document.getElementById('verificationSuccess').style.display = 'block';
            document.getElementById('countForm').style.opacity = '1';
            document.getElementById('countForm').style.pointerEvents = 'all';
            document.getElementById('modalQuantity').focus();
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().catch(err => console.error(err));
            }
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
    </script>
@endsection