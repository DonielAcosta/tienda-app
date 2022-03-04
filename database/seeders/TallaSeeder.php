<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Talla;

class TallaSeeder extends Seeder{
  
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
    
    $type = new Talla();
    $type->name = 'XS';
    $type->save();

    $type = new Talla();
    $type->name = 'S';
    $type->save();

    $type = new Talla();
    $type->name = 'M';
    $type->save();

    $type = new Talla();
    $type->name = 'L';
    $type->save();

    $type = new Talla();
    $type->name = 'XL';
    $type->save();

    $type = new Talla();
    $type->name = 'XXL';
    $type->save();


  }
}
