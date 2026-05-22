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

    public function create()
    {
        $materiales = InventarioSap::all();
        return view('inventario.create', compact('materiales'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ids' => 'required|array'
        ]);

        $ciclico = Ciclico::create([
            'nombre' => $request->nombre,
            'status' => 'Abierto'
        ]);

        $materials = InventarioSap::whereIn('id', $request->ids)->get();

        foreach ($materials as $m) {
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

        // Clear staging table after successfully creating the inventory session
        InventarioSap::truncate();

        return response()->json(['success' => 'Inventario cíclico guardado correctamente.', 'redirect' => route('inventario.index')]);


    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            InventarioSap::truncate();
            Excel::import(new InventarioSapImport, $request->file('file'));

            $count = InventarioSap::count();
            return response()->json([
                'success' => 'Reporte SAP cargado correctamente.',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar el archivo: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Ciclico $ciclico)
    {
        $ciclico->delete();
        return back()->with('success', 'Inventario eliminado.');
    }
}
