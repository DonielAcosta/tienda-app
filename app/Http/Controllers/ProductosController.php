<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use Validator;


class ProductosController extends Controller{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request){

    $paginate = request()->get('paginate');
    if ($paginate == null) {
      $paginate = 10;
    }
    $search = request()->get('search');
    $by = 'name'; // Order query by X column
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
    $productos = Productos::with(['Talla','Color','Technical.CarritoP'])
      ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                    $q->where("name", 'ILIKE', "%" . $search . "%")
                    ->orWhere("precio", "ILIKE", "%" . $search . "%");
            });
        })
        ->orderBy($by, $dir)
        ->orderBy('id', 'desc')
        ->paginate($paginate);

    return response()->json(
        [
            'listed' => True,
            'data' => $productos,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request){

    $validator = Validator::make(
      $request->all(),
      [
          'tallas_id' => 'required|numeric',
          'colors_id' => 'required|numeric',
          'name' => 'required|string|max:255',
          'precio' => 'required|regex:/^\d+(\.\d{1,2})?$/',
      ]
    );

    if ($validator->fails()) {
      return response()
          ->json(['error' => $validator->errors()], 422);
      }
    $arr = [
      'tallas_id' => $request->get('tallas_id'),
      'colors_id' => $request->get('colors_id'),
      'name' => $request->input('name'),
      'precio' => $request->input('precio'),
    ];
  $productos = Productos::create($arr);
  if ($request->has('CarritoP')) {
    $productos->CarritoP()->detach();
    $productos->CarritoP()->attach($request->input('CarritoP'));
}
  return response()->json(
      [
        'created' => true,
        'data' => $productos,
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
  public function show($id)
  {
    $productos = Productos::with(['Talla','Color'])->findOrFail($id);
    if (!$productos) {
        return response()->json(['error' => 'does_not_exist'], 404);
    }
    return response()->json(
        [
            'showed' => True,
            'data' => $productos,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
  }

  public function carrito(Request $request , $id){

    // $carri = [
    //   'tallas_id' => $request->get('tallas_id'),
    //   'colors_id' => $request->get('colors_id'),
    //   'name' => $request->input('name'),
    //   'precio' => $request->input('precio'),
    // ];

    $carri = Productos::with(['Talla','Color'])->find($id);
    dd($carri);
   

    return response()->json(
      [
          'showed' => True,
          'data' => $carri,
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
          'tallas_id' => 'required|numeric',
          'colors_id' => 'required|numeric',
          'name' => 'required|string|max:255',
          'precio' => 'required|regex:/^\d+(\.\d{1,2})?$/',
      ]
    );

    if ($validator->fails()) {
      return response()
          ->json(['error' => $validator->errors()], 422);
      }
    $productos = Productos::findOrFail($id);
    $productos->fill($request->all());
    $productos->save();
    if ($request->has('CarritoP')) {
      $productos->CarritoP()->detach();
      $productos->CarritoP()->attach($request->input('CarritoP'));
    } 
    if ($request->has('acceptByCarritoP')) {
      $productos->CarritoP()->detach();
    }
    return response()->json(
        [
            'updated' => True,
            'data' => $productos,
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
  public function destroy($id){

    $productos = Productos::find($id);
    $productos->delete();

    return response()->json([
        'deleted' => True,
        'message' => 'Elemento eliminado exitosamente',
    ], 200);
  }
}
