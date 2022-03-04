<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserData;
use App\Models\Productos;
use App\Models\Carrito;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject{

  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
      'name',
      'email',
      'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
      'password',
      'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
      'email_verified_at' => 'datetime',
  ];

  public function getJWTIdentifier(){
    return $this->getKey();
  }

  public function getJWTCustomClaims(){
    return [];
  }

  public function UserData()
  {
      return $this->hasOne(UserData::class, 'users_id');
  }

  public function User()
  {
      return $this->hasOne(UserData::class,'users_id');
  }

  public function Carrito(){

    return $this->hasOne(Carrito::class);
  }

 
}
