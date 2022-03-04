<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Productos;
use Validator;

class ColorController extends Controller{

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
    $color = Color::with(['Productos'])
      ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                    $q->where("name", 'LIKE', "%" . $search . "%");
            });
        })
        ->orderBy($by, $dir)
        ->orderBy('id', 'desc')
        ->paginate($paginate);

    return response()->json(
        [
            'listed' => True,
            'data' => $color,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
  }

  public function store(Request $request){

    $validator = Validator::make(
      $request->all(),
      [
          'name' => 'required|string'
      ]
    );
    if ($validator->fails()) {
      return response()
          ->json(['error' => $validator->errors()], 422);
    }
    $arr = [
        'name' => $request->input('name')
    ];
    $color = Color::create($arr);
    return response()->json(
      [
          'created' => true,
          'data' => $color,
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

    $color = Color::with(['Productos'])->find($id);
    if (!$color) {
        return response()->json(['error' => 'color_does_not_exist'], 404);
    }
    return response()->json(
        [
            'showed' => True,
            'data' => $color,
            'message' => 'Elemento obtenido exitosamente'
        ],
      200
    );
  }

  
  public function update(Request $request, $id){

    $validator = Validator::make(
      $request->all(),
      [
          'name' => 'required|string'
      ]
    );
    if ($validator->fails()) {
        return response()
            ->json(['error' => $validator->errors()], 422);
    }

    $color = Color::findOrFail($id);
    $color->fill($request->all());
    $color->save();
    return response()->json(
        [
            'updated' => True,
            'data' => $color,
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

    $color = Color::findorFail($id);
    $color->delete();
    return response()->json([
        'deleted' => True,
        'message' => 'Elemento eliminado exitosamente',
    ], 200);
  }
}
