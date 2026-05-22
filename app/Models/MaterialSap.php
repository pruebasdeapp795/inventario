<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialSap extends Model
{
    protected $table = 'materiales_sap';

    protected $fillable = [
        'cod',
        'material',
    ];
}
