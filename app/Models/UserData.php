<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserData extends Model{

  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  // protected $table="user_data";
  // protected $primaryKey = 'id';
  protected $fillable = [
      'users_id',
      'name',
      'phone',
      'identification',
      'date_of_birth',
      'sexo',
  ];


  /**
   * Function to get User
   */


  public function User(){

    return $this->belongsTo(User::class,'users_id');
  }
  // public function User(){
    
  //   return $this->belongsTo(User::class);
  // }
}
