<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrito;
use App\Models\Productos;

class CarritoProductos extends Model
{
    use HasFactory;

    protected $fillable = [
      'carritos_id',
      'productos_id',
    ];
  
    public function CarritoProductos(){

      return $this->belongsTo(Carrito::class,'carritos_id');
    }

    public function CarritoP(){

      return $this->belongsTo(Productos::class,'productos_id');
    }
  
}
