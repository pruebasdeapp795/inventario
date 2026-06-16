<?php

namespace App\Http\Controllers;

use App\Models\MaterialSap;
use Illuminate\Http\Request;

class MaterialSapController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialSap::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('cod', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
        }

        $materiales = $query->orderBy('cod')->paginate(100)->withQueryString();
        return view('materiales.index', compact('materiales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cod' => 'required|string|max:50|unique:materiales_sap,cod',
            'material' => 'required|string|max:255',
        ]);

        MaterialSap::create($request->only('cod', 'material'));

        return back()->with('success', 'Material agregado correctamente.');
    }

    public function update(Request $request, MaterialSap $material)
    {
        $request->validate([
            'cod' => 'required|string|max:50|unique:materiales_sap,cod,' . $material->id,
            'material' => 'required|string|max:255',
        ]);

        $material->update($request->only('cod', 'material'));

        return back()->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(MaterialSap $material)
    {
        $material->delete();
        return back()->with('success', 'Material eliminado.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $delimiter = strpos($content, ';') !== false ? ';' : ',';

        $handle = fopen($file->getRealPath(), "r");
        
        $data = [];
        $header = fgetcsv($handle, 1000, $delimiter); // Asumimos que la primera fila es el encabezado
        
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            if (count($row) >= 2) {
                $cod = trim($row[0]);
                $material = trim($row[1]);
                if ($cod && $material) {
                    $data[] = [
                        'cod' => $cod,
                        'material' => $material,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        fclose($handle);

        if (!empty($data)) {
            foreach (array_chunk($data, 1000) as $chunk) {
                MaterialSap::upsert($chunk, ['cod'], ['material', 'updated_at']);
            }
        }

        return back()->with('success', 'Carga masiva completada. Se procesaron ' . count($data) . ' materiales.');
    }

    /**
     * Returns JSON for a single QR or all, used by the QR generation JS.
     */
    public function qrData(Request $request)
    {
        $ids = $request->query('ids'); // comma-separated ids, or 'all'

        if ($ids === 'all' || !$ids) {
            $materiales = MaterialSap::orderBy('cod')->get(['id', 'cod', 'material']);
        } else {
            $idArray = explode(',', $ids);
            $materiales = MaterialSap::whereIn('id', $idArray)->orderBy('cod')->get(['id', 'cod', 'material']);
        }

        return response()->json($materiales);
    }
}
