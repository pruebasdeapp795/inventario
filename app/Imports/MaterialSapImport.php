<?php

namespace App\Imports;

use App\Models\MaterialSap;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaterialSapImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // SAP column aliases:
        // Codigo: 'material', 'codigo', 'cod', 'articulo'
        // Descripcion: 'texto_breve_de_material', 'descripcion', 'material_desc', 'nombre'

        $cod = $row['material'] ?? $row['codigo'] ?? $row['cod'] ?? null;
        $material = $row['texto_breve_de_material'] ?? $row['descripcion'] ?? $row['material_desc'] ?? $row['material'] ?? null;

        if (!$cod)
            return null;

        return MaterialSap::updateOrCreate(
            ['cod' => $cod],
            ['material' => $material ?? $cod]
        );
    }
}

