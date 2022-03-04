<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Productos;

class Color extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];


  public function Productos(){

    return $this->hasMany(Productos::class, 'colors_id');
  }


}
