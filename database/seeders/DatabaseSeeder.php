<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserDataSeeder;
use Database\Seeders\Talla;
use Database\Seeders\Color;
use Database\Seeders\Productos;




class DatabaseSeeder extends Seeder{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run(){

    // \App\Models\User::factory(10)->create();
    $this->call([

      UserSeeder::class,
      UserDataSeeder::class,
      TallaSeeder::class,
      ColorSeeder::class,
      ProductosSeeder::class,

    ]);
  }
}
