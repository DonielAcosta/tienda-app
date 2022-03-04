<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talla;
use Validator;

class TallaController extends Controller{
 
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
    $talla = Talla::with(['Productos'])
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
            'data' => $talla,
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
    $talla = Talla::create($arr);
    return response()->json(
      [
          'created' => true,
          'data' => $talla,
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

    $talla = Talla::with(['Productos'])->find($id);
    if (!$talla) {
        return response()->json(['error' => 'color_does_not_exist'], 404);
    }
    return response()->json(
        [
            'showed' => True,
            'data' => $talla,
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

    $talla = Talla::findOrFail($id);
    $talla->fill($request->all());
    $talla->save();
    return response()->json(
        [
            'updated' => True,
            'data' => $talla,
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

    $talla = Talla::findorFail($id);
    $talla->delete();
    return response()->json([
        'deleted' => True,
        'message' => 'Elemento eliminado exitosamente',
    ], 200);
  }
}