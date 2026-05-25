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
        // Traemos los items ya cargados si los hay
        $items = $ciclico->items()->get();
        return view('inventario.show', compact('ciclico', 'items'));
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
            // Limpiamos items previos de esta sesión si se desea sobrescribir con un nuevo Excel
            $ciclico->items()->delete();

            // Usamos el importador pero ahora guardaremos directamente en CiclicoItem
            // O podemos usar InventarioSap staging y luego mover.
            // Para mantener consistencia con lo anterior, cargamos en staging y movemos a la sesión.
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
