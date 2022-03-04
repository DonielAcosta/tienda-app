<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\TallaController;
use App\Http\Controllers\CarritoController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'App\Http\Controllers\UserController@register'); // solo correo y passw
Route::post('login', 'App\Http\Controllers\UserController@authenticate');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('car', [CarritoController::class, 'index']);
Route::post('register-car', [CarritoController::class, 'store']);
Route::get('car_show/{id}', [CarritoController::class, 'show']);
Route::put('actualizar-car/{id}', [CarritoController::class, 'update']);
Route::delete('delete-car/{id}', [CarritoController::class, 'destroy']);


Route::get('productos', [ProductosController::class, 'index']);
Route::post('register', [ProductosController::class, 'store']);
Route::get('productos_show/{id}', [ProductosController::class, 'show']);
Route::put('actualizar/{id}', [ProductosController::class, 'update']);
Route::delete('deleteproductos/{id}', [ProductosController::class, 'destroy']);

Route::get('colorcamisa', [ColorController::class, 'index']);
Route::post('color-store', [ColorController::class, 'store']);
Route::get('color-show/{id}', [ColorController::class, 'show']);
Route::put('color-update/{id}', [ColorController::class, 'update']);
Route::delete('deletecolor/{id}', [ColorController::class, 'destroy']);


Route::get('tallas', [TallaController::class, 'index']);
Route::post('tallas-store', [TallaController::class, 'store']);
Route::get('tallas-show/{id}', [TallaController::class, 'show']);
Route::put('tallas-update/{id}', [TallaController::class, 'update']);
Route::delete('deletetallas/{id}', [TallaController::class, 'destroy']);


Route::get('users', [UserController::class, 'index']);
Route::post('registercomplet', [UserController::class, 'store']); // este es el que se usa y pide toda la data
Route::get('users_show/{id}', [UserController::class, 'show']);
Route::put('usersup/{id}', [UserController::class, 'update']);
Route::delete('delete/{id}', [UserController::class, 'destroy']);




Route::resource('user_data', UserDataController::class);
Route::get('user_data', [UserDataController::class, 'index']);

Route::delete('user_data/{id}', [UserDataController::class, 'destroy']);
Route::resource('type-user', TypeUserController::class);
Route::delete('delete_type-user/{id}', [TypeUserController::class, 'destroy']);
Route::group(['middleware' => ['jwt.verify']], function() {

  Route::post('user','App\Http\Controllers\UserController@getAuthenticatedUser');

});