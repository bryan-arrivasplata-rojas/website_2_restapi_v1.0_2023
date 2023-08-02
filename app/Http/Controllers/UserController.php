<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = UserModel::with('profile')->orderBy('email','asc')->get();
            return response()->json($users);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validar campos
            $request->validate([
                'email' => 'required|email|unique:user,email',
                'password' => 'required',
            ]);
            
            // Crear nuevo usuario
            $user = new UserModel();
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->save();
            
            return response()->json(['message' => 'Usuario creado correctamente','response'=>$user], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }
    public function login($email)
    {
        try {
            $user = UserModel::with('profile')->where('email', $email)->orderBy('email', 'asc')->first();
            // Verificar si se encontro un usuario con el correo electronico dado
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['message' => 'El usuario no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($idUser)
    {
        try {
            $user = UserModel::with('profile')->where('idUser', $idUser)->orderBy('email', 'asc')->first();
            // Verificar si se encontro un usuario con el correo electronico dado
            if ($user) {
                return response()->json($user);
            } else {
                return response()->json(['message' => 'El usuario no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserModel $userModel, $idUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserModel $userModel, $idUser)
    {
        try {
            // Verificar si el usuario existe
            $user = $userModel->findOrFail($idUser);

            // Validar los campos del formulario
            $request->validate([
                'email' => 'required|email|unique:user,email,' . $idUser . ',idUser',
                'password' => 'required',
            ]);

            // Actualizar los campos del usuario
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->save();

            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El usuario no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserModel $userModel, $idUser)
    {
        try {
            // Verificar si el usuario existe
            $user = $userModel->findOrFail($idUser);

            // Eliminar el usuario
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El usuario no existe'], 400);
        }
    }
}
