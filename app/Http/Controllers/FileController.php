<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $files = FileModel::with('user','type','usability')->orderBy('position_file','desc')->orderBy('created_at','desc')->orderBy('idFile','asc')->get();
            return response()->json($files);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos'], 500);
        }
    }
    public function search($idUsability,$idType)
    {
        try {
            // Iniciar la consulta con todos los registros
            $query = FileModel::with('user', 'type', 'usability')->orderBy('created_at', 'desc')->orderBy('position_file', 'asc')->orderBy('idFile', 'asc');

            // Aplicar filtros según los valores recibidos
            if ($idUsability>-1) {
                $query->where('idUsability', $idUsability);
            }
            if($idType>-1){
                $query->where('idType', $idType);
            }

            // Obtener los registros filtrados
            $files = $query->get();
            return response()->json($files);
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
            $request->validate([
                'name_file' => 'required|unique:file,name_file',
                'description_file' => 'required',
                'idUsability' => 'required',
                'idType' => 'required',
                'idUser' => 'required',
            ]);
            
            // Crear nuevo usuario
            $file = new FileModel();
            $file->name_file = $request->input('name_file');
            $file->description_file = $request->input('description_file');
            $file->url_image = $request->input('url_image',null);
            $file->url_video = $request->input('url_video',null);
            $file->url_visit = $request->input('url_visit',null);
            $file->url_document = $request->input('url_document',null);
            $file->url_download = $request->input('url_download',null);
            $file->url_repository = $request->input('url_repository',null);
            $file->url_icon = $request->input('url_icon',null);
            $file->language = $request->input('language',null);
            $file->nivel = $request->input('nivel',null);
            $file->position_file = $request->input('position_file',0);
            $file->created_at = $request->input('created_at', Carbon::now('America/Lima'));
            $file->idUsability = $request->input('idUsability');
            $file->idType = $request->input('idType');
            $file->idUser = $request->input('idUser');
            $file->save();
            
            return response()->json(['message' => 'File creado correctamente','response'=>$file], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idFile)
    {
        try {
            $file = FileModel::where('idFile', $idFile)->first();
            // Verificar si se encontro el usability dado
            if ($file) {
                return response()->json($file);
            } else {
                return response()->json(['message' => 'El file no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileModel $fileModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileModel $fileModel, $idFile)
    {
        try {
            // Verificar si el usability existe
            $file = $fileModel->findOrFail($idFile);
            $created_at_actual = $file->created_at;
            // Validar los campos del formulario
            $request->validate([
                'name_file' => [
                    'required',
                    Rule::unique('file')->ignore($idFile, 'idFile'),
                ],
                'description_file' => 'required',
                'idUsability' => 'required',
                'idType' => 'required',
                'idUser' => 'required',
            ]);

            // Actualizar los campos del usability
            $file->name_file = $request->input('name_file');
            $file->description_file = $request->input('description_file');
            $file->url_image = $request->input('url_image',null);
            $file->url_video = $request->input('url_video',null);
            $file->url_visit = $request->input('url_visit',null);
            $file->url_document = $request->input('url_document',null);
            $file->url_download = $request->input('url_download',null);
            $file->url_repository = $request->input('url_repository',null);
            $file->url_icon = $request->input('url_icon',null);
            $file->language = $request->input('language',null);
            $file->nivel = $request->input('nivel',null);
            $file->position_file = $request->input('position_file',0);
            $file->created_at = $created_at_actual;
            $file->idUsability = $request->input('idUsability');
            $file->idType = $request->input('idType');
            $file->idUser = $request->input('idUser');
            $file->save();

            return response()->json($file);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El file no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileModel $fileModel, $idFile)
    {
        try {
            // Verificar si el usability existe
            $file = $fileModel->findOrFail($idFile);

            // Eliminar el usability
            $file->delete();
            return response()->json(['message' => 'File eliminado correctamente']);
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El file no existe'], 400);
        }
    }
}
