<?php

namespace App\Http\Controllers;

use App\Models\UsabilityModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UsabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $usabilities = UsabilityModel::with('file')->orderBy('position_usability', 'asc')->orderBy('idUsability', 'asc')->get();
            return response()->json($usabilities);
        } catch (QueryException $e) {
            // Error de conexion a la base de datos
            return response()->json(['message' => 'Error de conexion a la base de datos: ' . $e->getMessage()], 500);
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
                'name_usability' => 'required|unique:usability,name_usability',
                'description_usability' => 'required',
            ]);
            
            // Crear el nuevo usability
            $usability = UsabilityModel::create([
                'name_usability' => $request->input('name_usability'),
                'description_usability' => $request->input('description_usability'),
                'position_usability' => $request->input('position_usability',0), // Establecer un valor predeterminado de 0 si está vacío
            ]);
            
            return response()->json(['message' => 'Usability creado correctamente','response'=>$usability], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idUsability)
    {
        try {
            $usability = UsabilityModel::with('file')->where('idUsability', $idUsability)->first();
            // Verificar si se encontro el usability dado
            if ($usability) {
                return response()->json($usability);
            } else {
                return response()->json(['message' => 'El usability no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsabilityModel $usabilityModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsabilityModel $usabilityModel, $idUsability)
    {
        try {
            // Verificar si el usability existe
            $usability = $usabilityModel->findOrFail($idUsability);

            // Validar los campos del formulario
            $request->validate([
                'name_usability' => [
                    'required',
                    Rule::unique('usability')->ignore($idUsability, 'idUsability'),
                ],
                'description_usability' => 'required',
            ]);

            // Actualizar los campos del usability
            $usability->name_usability = $request->input('name_usability');
            $usability->description_usability = $request->input('description_usability');
            $usability->position_usability = $request->input('position_usability',0);
            $usability->save();

            return response()->json($usability);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El usability no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsabilityModel $usabilityModel, $idUsability)
    {
        try {
            // Verificar si el usability existe
            $usability = $usabilityModel->findOrFail($idUsability);

            // Eliminar el usability
            $usability->delete();
            return response()->json(['message' => 'Usability eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El usability no existe'], 400);
        }
    }
}
