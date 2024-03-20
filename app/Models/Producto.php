<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    use HasFactory;
    public function imagenes(){
        return $this->hasMany(Imagen::class);
    }
    public function detallePedidos(){
        return $this->hasMany(DetallePedido::class);
    }
    public function ordenes(){
        return $this->hasMany(Orden::class);
    }
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }
    public function clasificacion(){
        return $this->belongsTo(Clasificacion::class);
    }
    public function marca(){
        return $this->belongsTo(Marca::class);
    }
}
