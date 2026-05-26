<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventarioSap;
use App\Models\Ciclico;
use App\Models\CiclicoItem;
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

    /**
     * Workspace de una sesión específica
     */
    public function show(Ciclico $ciclico)
    {
        $itemsRaw = $ciclico->items()->get();
        $isAdmin = auth()->user()->hasAnyRole(['Administrador', 'Costos', 'Jefe Costos', 'Avanzado']);

        $items = $itemsRaw->map(function ($item) use ($isAdmin) {
            $clone = clone $item;
            if (!$isAdmin) {
                $clone->stock_sap = 0;
                $clone->valor_sap = 0;
                $clone->diferencia = 0;
                $clone->valor_diferencia = 0;
            }
            return $clone;
        });

        $totalItems = $itemsRaw->count();
        $contadosItems = $itemsRaw->where('contado', true)->count();
        $avance = $totalItems > 0 ? round(($contadosItems / $totalItems) * 100, 2) : 0;

        return view('inventario.show', compact('ciclico', 'items', 'totalItems', 'contadosItems', 'avance', 'isAdmin'));
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

        $item->cantidad_fisica = $request->cantidad;
        $item->contado = true;
        $item->diferencia = $item->cantidad_fisica - $item->stock_sap;

        // Calculo de costo unitario para la variacion de valor
        // Si no hay stock SAP pero hay valor, usamos el valor. 
        // Si no hay stock SAP ni valor, el costo es 0 (o no podemos determinarlo).
        $costoUnitario = $item->stock_sap != 0 ? ($item->valor_sap / $item->stock_sap) : 0;
        $item->valor_diferencia = $item->diferencia * $costoUnitario;

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Conteo registrado correctamente.',
            'item' => [
                'id' => $item->id,
                'cantidad_fisica' => $item->cantidad_fisica,
                'diferencia' => $item->diferencia,
                'valor_diferencia' => $item->valor_diferencia
            ]
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
