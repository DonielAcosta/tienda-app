<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){

    // password 123456789
    $type = new User();
    $type->name = 'lina';
    $type->email = 'slina@gmail.com';
    $type->password ='$2y$10$lFhdBabODfUu.oGJAIgvk.uMcf2y7eRRotcROLZtMM9GLQ2Ok6tP2';
    $type->save();

    $type = new User();
    $type->name = 'Dubexy';
    $type->email = 'dubexy@gmail.com';
    $type->password ='$2y$10$e8f3nN6A38D108n7GjJviONHGRKhHOeegNk2wzqDJzw0YAYCaM03G'; 
    $type->save();

    $type = new User();
    $type->name = 'Miguel';
    $type->email = 'mevr02@gmail.com';
    $type->password ='$2y$10$B6iLz3avnSWMGyNoeQDB5uSUw2CpOovXqySQXXM8NvtV7CAW0A7Vm';
    $type->save();

  }
}
