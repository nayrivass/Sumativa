<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
    public function delibery(){
        return $this->belongsTo(Delibery::class);
    }
}
