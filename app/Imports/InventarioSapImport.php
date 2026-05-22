<?php

namespace App\Imports;

use App\Models\InventarioSap;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class InventarioSapImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private $requiredHeaders = [
        'material',
        'texto_breve_de_material',
        'unidad_medida_base',
        'centro',
        'almacen',
        'libre_utilizacion',
        'valor_libre_util'
    ];

    /**
     * @param array $row
     */
    public function model(array $row)
    {
        // Headers according to USER:
        // Material, Texto breve de material, Unidad medida base, Centro, Almacén, Libre utilización, Valor libre util.

        // Check if essential headers are present in the row keys
        foreach ($this->requiredHeaders as $header) {
            if (!array_key_exists($header, $row)) {
                $foundHeaders = implode(', ', array_keys($row));
                throw new \Exception("Falta la cabecera requerida: '{$header}'. Cabeceras detectadas: [{$foundHeaders}]. Asegúrese de usar el formato SAP exacto.");
            }
        }


        // Skip if Material is missing or looks like a totalization row
        if (
            empty($row['material']) ||
            str_contains(strtolower((string) $row['material']), 'total') ||
            str_contains(strtolower((string) ($row['texto_breve_de_material'] ?? '')), 'total')
        ) {
            return null;
        }


        return new InventarioSap([
            'material' => $row['material'],
            'texto_breve_de_material' => $row['texto_breve_de_material'] ?? null,
            'unidad_medida_base' => $row['unidad_medida_base'] ?? null,
            'centro' => $row['centro'] ?? null,
            'almacen' => $row['almacen'] ?? null,
            'libre_utilizacion' => $this->parseNumber($row['libre_utilizacion'] ?? 0),
            'valor_libre_util' => $this->parseNumber($row['valor_libre_util'] ?? 0),
        ]);
    }

    private function parseNumber($value)
    {
        if (is_numeric($value))
            return $value;
        if (empty($value))
            return 0;

        // Remove thousands separator and use dot for decimals if needed
        // Assuming potential SAP formats like 1.234,56 or 1,234.56
        $clean = str_replace(',', '.', str_replace('.', '', (string) $value));
        return is_numeric($clean) ? (float) $clean : 0;
    }
}

