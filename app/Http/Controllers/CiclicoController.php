<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioSap;
use App\Models\Ciclico;
use App\Models\CiclicoItem;
use App\Models\MaterialSap;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventarioSapImport;

class CiclicoController extends Controller
{
    public function index()
    {
        $ciclicos = Ciclico::withCount('items')->orderBy('created_at', 'desc')->get();
        return view('inventario.index', compact('ciclicos'));
    }

    /**
     * Inicia una nueva sesión de inventario (crea el registro primero)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $ciclico = Ciclico::create([
            'nombre' => $request->nombre,
            'status' => 'Abierto'
        ]);

        return redirect()->route('inventario.show', $ciclico->id);
    }

    public function show(Ciclico $ciclico)
    {
        $itemsRaw = $ciclico->items()->get();
        // Solo el Administrador puede ver datos sensibles (stock, valor, diferencia, estado detallado)
        $isFullAdmin = auth()->user()->hasRole('Administrador');
        // Varios roles pueden realizar la selección y el conteo
        $canSelect = auth()->user()->hasAnyRole(['Administrador', 'Costos', 'Jefe Costos', 'Avanzado']);

        $fase = $ciclico->fase;

        $items = $itemsRaw->map(function ($item) use ($isFullAdmin) {
            if (!$isFullAdmin) {
                $item->stock_sap = 0;
                $item->valor_sap = 0;
                $item->diferencia = 0;
                $item->valor_diferencia = 0;
            }
            return $item;
        });

        $totalItems = $itemsRaw->count();
        $contadosItems = $itemsRaw->where('contado', true)->count();
        $avance = $totalItems > 0 ? round(($contadosItems / $totalItems) * 100, 2) : 0;

        return view('inventario.show', compact('ciclico', 'items', 'totalItems', 'contadosItems', 'avance', 'isFullAdmin', 'canSelect', 'fase'));
    }

    /**
     * Inicia la fase de conteo, guardando los items seleccionados
     */
    public function startCounting(Request $request, Ciclico $ciclico)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:ciclico_items,id'
        ]);

        // Desmarcar todos primero
        CiclicoItem::where('ciclico_id', $ciclico->id)->update(['seleccionado' => false]);

        // Marcar los seleccionados
        CiclicoItem::whereIn('id', $request->items)->update(['seleccionado' => true]);

        // Cambiar fase
        $ciclico->update(['fase' => 'conteo']);

        return response()->json(['success' => true, 'message' => 'Fase de conteo iniciada.']);
    }

    /**
     * Importa el Excel directamente a la sesión activa
     */
    public function import(Request $request, Ciclico $ciclico)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $ciclico->items()->delete();

            InventarioSap::truncate();
            Excel::import(new InventarioSapImport, $request->file('file'));

            $materialesStaging = InventarioSap::all();

            foreach ($materialesStaging as $m) {
                CiclicoItem::create([
                    'ciclico_id' => $ciclico->id,
                    'material' => $m->material,
                    'descripcion' => $m->texto_breve_de_material,
                    'centro' => $m->centro,
                    'almacen' => $m->almacen,
                    'stock_sap' => $m->libre_utilizacion,
                    'valor_sap' => $m->valor_libre_util,
                    'um' => $m->unidad_medida_base,
                ]);
            }

            $count = $ciclico->items()->count();

            return response()->json([
                'success' => 'Reporte SAP cargado correctamente en la sesión.',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Registra el conteo físico de un material
     */
    public function registerCount(Request $request, Ciclico $ciclico)
    {
        $request->validate([
            'id' => 'required|exists:ciclico_items,id',
            'cantidad' => 'required|numeric'
        ]);

        $item = CiclicoItem::where('ciclico_id', $ciclico->id)->findOrFail($request->id);
        $intento = $ciclico->intento_actual; // 1, 2 o 3

        $campoConteo = 'conteo_' . $intento;

        // Sumar la nueva cantidad a la existente del intento actual
        $item->$campoConteo = (float) ($item->$campoConteo ?? 0) + (float) $request->cantidad;

        // La cantidad fisica general de la fila siempre refleja el conteo MAS RECIENTE
        $item->cantidad_fisica = $item->$campoConteo;
        $item->contado = true;

        $item->diferencia = $item->cantidad_fisica - $item->stock_sap;

        // Calculo de costo unitario para la variacion de valor
        $costoUnitario = $item->stock_sap != 0 ? ($item->valor_sap / $item->stock_sap) : 0;
        $item->valor_diferencia = $item->diferencia * $costoUnitario;

        $item->save();

        return response()->json([
            'success' => true,
            'message' => "Conteo registrado en Intento $intento.",
            'item' => [
                'id' => $item->id,
                'cantidad_fisica' => $item->cantidad_fisica,
                'conteo_actual' => $item->$campoConteo,
                'intento' => $intento,
                'diferencia' => $item->diferencia,
                'valor_diferencia' => $item->valor_diferencia
            ]
        ]);
    }

    /**
     * Avanza al siguiente intento de reconteo (Máximo 3)
     */
    public function nextAttempt(Ciclico $ciclico)
    {
        if ($ciclico->intento_actual < 3) {
            $prev = $ciclico->intento_actual;
            $ciclico->increment('intento_actual');

            return back()->with('success', "Se ha iniciado el Reconteo {$ciclico->intento_actual}. Los nuevos conteos se guardarán por separado.");
        }
        return back()->with('error', 'Ya se ha alcanzado el límite de 3 conteos.');
    }

    /**
     * Agrega un material al ciclo si no existe (basado en cod sap)
     */
    public function addItem(Request $request, Ciclico $ciclico)
    {
        $request->validate([
            'cod_sap' => 'required|string'
        ]);

        $codSap = $request->cod_sap;

        // Verificar si ya existe en la sesión
        $item = CiclicoItem::where('ciclico_id', $ciclico->id)
            ->where('material', $codSap)
            ->first();

        if ($item) {
            return response()->json([
                'success' => true,
                'item' => $item
            ]);
        }

        // Si no existe, buscar en el maestro de materiales
        $maestro = MaterialSap::where('cod', $codSap)->first();

        if (!$maestro) {
            return response()->json([
                'error' => "El material con código SAP '$codSap' no existe en el maestro de materiales."
            ], 404);
        }

        // Crear el item en la sesión
        $newItem = CiclicoItem::create([
            'ciclico_id' => $ciclico->id,
            'material' => $maestro->cod,
            'descripcion' => $maestro->material,
            'centro' => '-',
            'almacen' => '-',
            'stock_sap' => 0,
            'valor_sap' => 0,
            'um' => 'UND', // Valor por defecto si no se conoce
            'seleccionado' => true,
        ]);

        return response()->json([
            'success' => true,
            'item' => $newItem,
            'is_new' => true
        ]);
    }

    /**
     * Finaliza la sesión (solo cambia estado)
     */
    public function close(Ciclico $ciclico)
    {
        $ciclico->update(['status' => 'Cerrado']);
        return redirect()->route('inventario.index')->with('success', 'Sesión de inventario finalizada.');
    }

    public function destroy(Ciclico $ciclico)
    {
        $ciclico->delete();
        return back()->with('success', 'Sesión eliminada.');
    }
}
