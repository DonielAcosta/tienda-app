<?php   

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Models\UserData;
use Validator;


class UserController extends Controller{

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
    $by = 'email'; // Order query by X column
    if (request()->has('orderBy')) {
        $by = request()->get('orderBy');
    }
    $dir = 'desc'; // Order query by X column
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

    $users = User::with(['UserData'])
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                    $q->where("email", 'LIKE', "%" . $search . "%");
            });
        })
        ->orderBy($by, $dir)
        ->paginate($paginate);
    return response()->json(
        [
            'listed' => True,
            'data' => $users,
            'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
  }

  public function authenticate(Request $request){

    $credentials = $request->only('email', 'password');
    try {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'credenciales invalidas'], 400);
        } else {
            $user = User::with(['UserData'])
            ->where('email', $credentials['email'])->get();
            return response()->json(['user' => $user[0], 'token' => compact('token')]);
        }
    } catch (JWTException $e) {
        return response()->json(['message' => 'no se pudo acceder, error de servidor'], 500);
    }
  }

  public function getAuthenticatedUser(){

  try {
    if (!$user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
      }
    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
          return response()->json(['token_expired'], $e->getStatusCode());
    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
          return response()->json(['token_invalid'], $e->getStatusCode());
    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
          return response()->json(['token_absent'], $e->getStatusCode());
    }
    return response()->json(compact('user'));
  }


  public function register(Request $request){

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }

    $user = User::create([
      'name' => $request->get('name'),
      'email' => $request->get('email'),
      'password' => Hash::make($request->get('password')),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user','token'),201);
  }

  public function store(Request $request){

    $validator = Validator::make($request->all(), [

      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'password_confirmation' => 'required ',
      'identification' => 'required|string|max:12',
      'date_of_birth' => 'required|date',
      'phone' => 'required|string|max:15',
      'sexo' => 'required|string|max:30',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->toJson(), 400);
    }

    $user = User::create([
      'email' => $request->get('email'),
      'name' => $request->get('name'),
      'password' => Hash::make($request->get('password')),
    ]);

    $userData = UserData::create([
      'users_id' => $user->id,
      'name' => $request->get('name'),
      'phone' => $request->get('phone'),
      'identification' => $request->get('identification'),
      'date_of_birth' => $request->get('date_of_birth'),
      'sexo' => $request->get('sexo'),
    ]);

    return response()->json(
        [
          'listed' => True,
          'data' => [
              'user' => $user,
              'userData' => $userData,
          ],
          'message' => 'Elemento obtenido exitosamente'
        ],
        200
    );
      return response()->json(compact('user','token'),201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id){
    $users = User::with(['UserData'])->find($id);
    if (!$users) {
      return response()->json(['error' => 'user_does_not_exist'], 404);
    }
    return response()->json(
      [
        'showed' => True,
        'data' => $users,
        'message' => 'Elemento obtenido exitosamente'
      ],
      200
    );
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id){

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'password_confirmation' => 'required ',
      'identification' => 'required|string|max:12',
      'date_of_birth' => 'required|date',
      'phone' => 'required|string|max:15',
      'sexo' => 'required|string|max:30',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 422);
    }
    $user = User::findOrFail($id);
    $user_data = UserData::where('users_id', $id)->first();
    $user->fill([
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
    ]);
    $user_data->fill([
        'users_id' => $user->id,
        'name' => $request->get('name'),
        'phone' => $request->get('phone'),
        'identification' => $request->get('identification'),
        'date_of_birth' => $request->get('date_of_birth'),
        'sexo' => $request->get('sexo'),
    ]);
    $user->save();
    $user_data->save();
    return response()->json(
        [
          'updated' => True,
          'data' => [
              'user' => $user,
              'userData' => $user_data,
          ],
          'message' => 'Elemento actualizado exitosamente'
        ],
      200
    );
  }

  public function destroy($id){

    $users = User::find($id);
    $users->delete();

    return response()->json([
        'deleted' => True,
        'message' => 'Elemento eliminado exitosamente',
    ], 200);
  }
}