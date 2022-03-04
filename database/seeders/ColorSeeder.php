<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){

    $type = new Color();
    $type->name = 'Blanco';
    $type->save();

    $type = new Color();
    $type->name = 'Negro';
    $type->save();

    $type = new Color();
    $type->name = 'Rojo';
    $type->save();

    $type = new Color();
    $type->name = 'Verde';
    $type->save();

    $type = new Color();
    $type->name = 'Azul.';
    $type->save();


  }
}
