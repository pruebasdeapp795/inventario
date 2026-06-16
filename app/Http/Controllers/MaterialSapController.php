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
