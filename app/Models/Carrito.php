<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CarritoProductos;
use App\Models\Productos;


class Carrito extends Model{

  use HasFactory;

  protected $fillable = [
    'users_id',
    // 'carrito__productos_id',
    'precio_total',
    'status',
  ];

  public function User(){

    return $this->belongsTo(User::class,'users_id');
  }

  public function CarritoProductos()
  {
      return $this->hasOne(CarritoProductos::class,'carrito__productos_id');
  }
  
}
