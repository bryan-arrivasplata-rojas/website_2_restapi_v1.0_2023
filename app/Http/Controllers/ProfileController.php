<?php

namespace App\Http\Controllers;

use App\Models\ProfileModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $profiles = ProfileModel::with('user')->orderBy('name_profile','asc')->get();
            return response()->json($profiles);
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
                'name_profile' => 'required',
                'description_profile' => 'required',
                'number' => 'required',
                'birthday' => 'required',
                'idUser' => [
                    'required',
                    Rule::unique('profile'),
                ],
            ]);
            
            // Crear nuevo usuario
            $profile = new ProfileModel();
            $profile->name_profile = $request->input('name_profile');
            $profile->description_profile = $request->input('description_profile');
            $profile->number = $request->input('number');
            $profile->birthday = $request->input('birthday');
            $profile->idUser = $request->input('idUser');
            $profile->save();
            
            return response()->json(['message' => 'Perfil creado correctamente','response'=>$profile], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idProfile)
    {
        try {
            $profile = ProfileModel::with('user')->where('idProfile', $idProfile)->orderBy('name_profile', 'asc')->first();
            // Verificar si se encontro el perfil
            if ($profile) {
                return response()->json($profile);
            } else {
                return response()->json(['message' => 'El perfil no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileModel $profileModel, $idProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfileModel $profileModel, $idProfile)
    {
        try {
            // Verificar si el usuario existe
            $profile = $profileModel->findOrFail($idProfile);

            // Validar los campos del formulario
            $request->validate([
                'name_profile' => 'required',
                'description_profile' => 'required',
                'number' => 'required',
                'birthday' => 'required',
                'idUser' => [
                    'required',
                    Rule::unique('profile')->ignore($idProfile, 'idProfile'),
                ],
            ]);

            // Actualizar los campos del usuario
            $profile->name_profile = $request->input('name_profile');
            $profile->description_profile = $request->input('description_profile');
            $profile->number = $request->input('number');
            $profile->birthday = $request->input('birthday');
            $profile->idUser = $request->input('idUser');
            $profile->save();

            return response()->json($profile);
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
    public function destroy(ProfileModel $profileModel, $idProfile)
    {
        try {
            // Verificar si el perfil existe
            $profile = $profileModel->findOrFail($idProfile);

            // Eliminar el perfil
            $profile->delete();
            return response()->json(['message' => 'Perfil eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El perfil no existe'], 400);
        }
    }
}
