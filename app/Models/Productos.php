<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Talla;
use App\Models\Color;
use App\Models\User;
use App\Models\Carrito;
use App\Models\CarritoProductos;


class Productos extends Model{

  use HasFactory;

  protected $fillable = [
    'tallas_id',
    'colors_id',
    'name',
    'precio',
  ];


  public function Talla(){

    return $this->belongsTo(Talla::class, 'tallas_id');
  }

  
  public function Color(){

    return $this->belongsTo(Color::class,'productos_id');
  }

  public function CarritoP(){

    return $this->hasOne(CarritoProductos::class,'carrito__productos_id');
  }
  
  // public function CarritoP(){

  //   return $this->belongsToMany(CarritoProductos::class, 'carrito__productos', 'Productos_id','carritos_id');

  //   // return $this->belongsToMany(User::class, 'install_order_users', 'installation_orders_id','carritos_id');
  // }

  // public function InstallOrderUser(){
  //     return $this->belongsToMany(InstallationOrders::class, 'install_order_users', 'users_id','installation_orders_id');
  // }

}
