<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclico extends Model
{
    protected $fillable = ['nombre', 'status', 'fase'];

    public function items()
    {
        return $this->hasMany(CiclicoItem::class);
    }
}
