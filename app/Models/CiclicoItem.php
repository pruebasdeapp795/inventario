<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiclicoItem extends Model
{
    protected $fillable = [
        'ciclico_id',
        'material',
        'descripcion',
        'centro',
        'almacen',
        'stock_sap',
        'valor_sap',
        'um',
        'seleccionado',
        'conteo_1',
        'conteo_2',
        'conteo_3',
        'cantidad_fisica',
        'contado',
        'diferencia',
        'valor_diferencia'
    ];
}
