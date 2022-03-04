<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Carrito;
use App\Models\User;
use App\Models\CarritoProductos;

use Validator;


class CarritoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){

    $paginate = request()->get('paginate');
    if ($paginate == null) {
        $paginate = 10;
    }
    $search = request()->get('search');
    $by = 'status'; // Order query by X column
    if (request()->has('orderBy')) {
        $by = request()->get('orderBy');
    }
    $dir = 'desc'; // Direction of the Order by
    if (request()->has('dirDesc')) {
        if (request()->get('dirDesc') === 'true') {
            $dir = 'desc';
        } else {
            $dir = 'asc';
        }
    }
   
    $carrito = Carrito::with(['User'])
    ->when($search, function ($query, $search) {
          return $query->where(function ($q) use ($search) {
                  $q->where("precio_total", 'LIKE', "%" . $search . "%")
                  ->orWhere("status", "LIKE", "%" . $search . "%");
          });
      })
      ->orderBy($by, $dir)
      ->orderBy('id', 'desc')
      ->paginate($paginate);
   
    return response()->json(
        [
            'listed' => True,
            'data' => $carrito,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
  }

 
  public function store(Request $request){

    $validator = Validator::make(
      $request->all(),
      [
      
        'users_id' => 'required|numeric',
        'status' => 'required|string|max:255',
        'precio_total' => 'required|regex:/^\d+(\.\d{1,2})?$/',
      ]
    );
    if ($validator->fails()) {
      return response()
          ->json(['error' => $validator->errors()], 422);
    }
    $arr = [
      'users_id' => $request->get('users_id'),
      'status' => $request->input('status'),
      'precio_total' => $request->input('precio_total'),
    ];
    $carrito = Carrito::create($arr);
    return response()->json(
        [
            'created' => true,
            'data' => $carrito,
            'message' => 'Elemento creado exitosamente'
        ],
        200
    );
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id){
    $carrito = Carrito::with(['User'])->find($id);
    if (!$carrito) {
      return response()->json(['error' =>'does_not_exist'], 404);
    }
    return response()->json(
      [
        'showed' => True,
        'data' => $carrito,
        'message' => 'Elemento obtenido exitosamente'
      ],
      200
    );
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id){
      
    $validator = Validator::make(
      $request->all(),
      [
        'users_id' => 'required|numeric',
        'status' => 'required|string|max:255',
        'precio_total' => 'required|regex:/^\d+(\.\d{1,2})?$/',
      ]
    );

    if ($validator->fails()) {
      return response()
          ->json(['error' => $validator->errors()], 422);
    }

    $carrito->fill([
      'users_id' => $user->id,
      'status' => $request->get('status'),
      'precio_total' => $request->get('precio_total')
    ]);
    $carrito = Carrito::findOrFail($id);
    $carrito->fill($request->all());
    $carrito->save();
    return response()->json(
        [
            'updated' => True,
            'data' => $carrito,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
   }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
