<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Productos;


class Talla extends Model{

  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable = [
    'name',
  ];


  public function Productos(){

        return $this->hasMany(Productos::class, 'tallas_id');
    }


}
