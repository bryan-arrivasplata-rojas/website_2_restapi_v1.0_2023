<?php

namespace App\Http\Controllers;

use App\Models\ExtensionModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $extensions = ExtensionModel::orderBy('idExtension', 'asc')->get();
            return response()->json($extensions);
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
                'name_extension' => 'required|unique:extension,name_extension',
            ]);
            
            // Crear nuevo usuario
            $extension = new ExtensionModel();
            $extension->name_extension = $request->input('name_extension');
            $extension->save();
            
            return response()->json(['message' => 'Extension creado correctamente','response'=>$extension], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idExtension)
    {
        try {
            $extension = ExtensionModel::where('idExtension', $idExtension)->first();
            // Verificar si se encontro la extension
            if ($extension) {
                return response()->json($extension);
            } else {
                return response()->json(['message' => 'La extension no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExtensionModel $extensionModel, $idExtension)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExtensionModel $extensionModel, $idExtension)
    {
        try {
            // Verificar si la extension existe
            $extension = $extensionModel->findOrFail($idExtension);

            // Validar los campos del formulario
            $request->validate([
                'name_extension' => [
                    'required',
                    Rule::unique('extension')->ignore($idExtension, 'idExtension'),
                ],
            ]);

            // Actualizar los campos de la extension
            $extension->name_extension = $request->input('name_extension');
            $extension->save();

            return response()->json($extension);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La extension no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExtensionModel $extensionModel, $idExtension)
    {
        try {
            // Verificar si el usuario existe
            $extension = $extensionModel->findOrFail($idExtension);

            // Eliminar el usuario
            $extension->delete();
            return response()->json(['message' => 'Extension eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'La extension no existe'], 400);
        }
    }
}
