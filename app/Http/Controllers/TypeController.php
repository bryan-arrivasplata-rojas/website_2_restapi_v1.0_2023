<?php

namespace App\Http\Controllers;

use App\Models\TypeModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $types = TypeModel::with('file')->orderBy('position_type','asc')->orderBy('idType','asc')->get();
            return response()->json($types);
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
                'name_type' => 'required|unique:type,name_type',
                'description_type' => 'required',
            ]);
            
            // Crear el nuevo tipo
            $type = TypeModel::create([
                'name_type' => $request->input('name_type'),
                'description_type' => $request->input('description_type'),
                'position_type' => $request->input('position_type', 0), // Establecer un valor predeterminado de 0 si está vacío
            ]);
            
            return response()->json(['message' => 'Tipo creado correctamente','response'=>$type], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idType)
    {
        try {
            $type = TypeModel::with('file')->where('idType', $idType)->first();
            // Verificar si se encontro el tipo dado
            if ($type) {
                return response()->json($type);
            } else {
                return response()->json(['message' => 'El tipo no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeModel $typeModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeModel $typeModel, $idType)
    {
        try {
            // Verificar si el type existe
            $type = $typeModel->findOrFail($idType);

            // Validar los campos del formulario
            $request->validate([
                'name_type' => [
                    'required',
                    Rule::unique('type')->ignore($idType, 'idType'),
                ],
                'description_type' => 'required',
            ]);

            // Actualizar los campos del type
            $type->name_type = $request->input('name_type');
            $type->description_type = $request->input('description_type');
            $type->position_type = $request->input('position_type',0);
            $type->save();

            return response()->json($type);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El tipo no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeModel $typeModel, $idType)
    {
        try {
            // Verificar si el type existe
            $type = $typeModel->findOrFail($idType);

            // Eliminar el type
            $type->delete();
            return response()->json(['message' => 'Tipo eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El tipo no existe'], 400);
        }
    }
}
