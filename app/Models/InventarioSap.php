<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSap extends Model
{
    protected $fillable = [
        'material',
        'texto_breve_de_material',
        'unidad_medida_base',
        'centro',
        'almacen',
        'libre_utilizacion',
        'valor_libre_util',
        'seleccionado',
    ];

}
