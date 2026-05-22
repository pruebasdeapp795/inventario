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
        'um'
    ];
}
