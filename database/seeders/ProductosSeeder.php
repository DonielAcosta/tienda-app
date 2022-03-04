<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Productos;
use Faker\Factory as Faker;

class ProductosSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){
      
    $faker = Faker::create();

    for ($color = 0; $color < 5; $color++) { 
      for ($talla=0; $talla < 6; $talla++) { 
        $data = [
          'tallas_id' => $talla+1,
          'colors_id' => $color+1,
          'name' => 'Camiseta Manga larga',
          'precio' => $faker->randomDigit(),  
        ];
        Productos::create($data);
        $data['name'] = 'Camiseta Manga corta';
        $data['precio'] = $faker->randomDigit();
        Productos::create($data);
      }
    }
   
  }
}
