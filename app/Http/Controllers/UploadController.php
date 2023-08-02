<?php

namespace App\Http\Controllers;


use App\Models\UploadModel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExtensionController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $uploads = UploadModel::with('extension')->orderBy('created_at','desc')->orderBy('name_upload','asc')->get();
            return response()->json($uploads);
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
                'name_upload' => 'required|unique:upload,name_upload',
                'idExtension' => 'required'
            ]);

            if($request->input('url_upload')){
                // Crear el nuevo tipo
                $upload = UploadModel::create([
                    'name_upload' => $request->input('name_upload'),
                    'url_upload' => $request->input('url_upload'),
                    'idExtension' => $request->input('idExtension'),
                    'created_at' => $request->input('created_at', Carbon::now('America/Lima')), // Establecer un valor predeterminado de 0 si está vacío
                ]);
                
                return response()->json(['message' => 'Archivo creado correctamente','response'=>$upload], 200);
            }else{
                if ($request->hasFile('file_upload')) {
                    $file = $request->file('file_upload');
                    
                    // Obtener nombre único para el archivo
                    $filename = $file->getClientOriginalName();
                    $extensionController  = new ExtensionController();
                    $extensionResponse = $extensionController->show($request->input('idExtension'));
                    $extension = $extensionResponse->getOriginalContent();
                    if ($extensionResponse->getStatusCode() == 200) {
                        if(isset($extension->name_extension)){
                            $nameExtension = $extension->name_extension;
                            if ($file->move(public_path('uploads'), $request->input('name_upload').'.'.$nameExtension)){
                        
                                // Obtener la URL completa del archivo subido
                                $url = url('uploads/' . $request->input('name_upload').'.'.$nameExtension);
                                // Crear el nuevo tipo
                                $upload = UploadModel::create([
                                    'name_upload' => $request->input('name_upload'),
                                    'url_upload' => $url,
                                    'idExtension' => $request->input('idExtension'),
                                    'created_at' => $request->input('created_at', Carbon::now('America/Lima')),
                                ]);
                    
                                return response()->json(['message' => 'Archivo creado correctamente', 'response' => $upload], 200);
                            }else{
                                return response()->json(['message' => 'Archivo no pudo cargarse al Servidor'], 500);
                            }
                        }else{
                            return response()->json(['message' => 'Extension solicitada no cuenta con name_extension'], 400);
                        }
                    }else{
                        return response()->json(['message' => 'Problemas con encontrar la extension'], 400);
                    }
                } else {
                    return response()->json(['message' => 'Archivo aún no se ha cargado correctamente'], 500);
                }
            }
            
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idUpload)
    {
        try {
            $upload = UploadModel::where('idUpload', $idUpload)->first();
            // Verificar si se encontro el tipo dado
            if ($upload) {
                return response()->json($upload);
            } else {
                return response()->json(['message' => 'El archivo no existe'], 400);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UploadModel $uploadModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UploadModel $uploadModel, $idUpload)
    {
        try {
            // Verificar si el type existe
            $upload = $uploadModel->findOrFail($idUpload);
            $created_at_actual = $upload->created_at;
            //$url_upload_actual = $upload->url_upload;
            $extensionController  = new ExtensionController();
            $extensionResponse_actual = $extensionController->show($upload->idExtension);
            $extension_actual = $extensionResponse_actual->getOriginalContent();
            if ($extensionResponse_actual->getStatusCode() == 200) {
                // Obtener la ruta actual del archivo en el servidor
                if(isset($extension_actual->name_extension)){
                    $nameExtension_actual = $extension_actual->name_extension;
                    $ruta_actual = public_path('uploads/' . $upload->name_upload.'.'.$nameExtension_actual);
                    // Validar campos
                    $request->validate([
                        'name_upload' => [
                            'required',
                            Rule::unique('upload')->ignore($idUpload, 'idUpload'),
                        ],
                        'idExtension' => 'required'
                    ]);
                    $extensionResponse = $extensionController->show($request->input('idExtension'));
                    $extension = $extensionResponse->getOriginalContent();
                    if ($extensionResponse->getStatusCode() == 200) {
                        if(isset($extension->name_extension)){
                            $nameExtension = $extension->name_extension;
                            if ($request->hasFile('file_upload')) {
                                $file = $request->file('file_upload');
                                if (file_exists($ruta_actual)) {
                                    if(unlink($ruta_actual)){
                                        if ($file->move(public_path('uploads'), $request->input('name_upload').'.'.$nameExtension)){
                                            $url = url('uploads/' . $request->input('name_upload').'.'.$nameExtension);
                                            $upload->name_upload = $request->input('name_upload');
                                            $upload->url_upload = $url;
                                            $upload->idExtension = $request->input('idExtension');
                                            $upload->created_at = $created_at_actual;
                                            $upload->save();
                                            return response()->json($upload);
                                        }else{
                                            return response()->json(['message' => 'Error de servidor'], 500);
                                        }
                                        return response()->json(['message' => 'Archivo eliminado correctamente']);
                                    }else{
                                        return response()->json(['message' => 'Error de servidor'], 500);
                                    }
                                }else{
                                    if ($file->move(public_path('uploads'), $request->input('name_upload').'.'.$nameExtension)){
                                        $url = url('uploads/' . $request->input('name_upload').'.'.$nameExtension);
                                        $upload->name_upload = $request->input('name_upload');
                                        $upload->url_upload = $url;
                                        $upload->idExtension = $request->input('idExtension');
                                        $upload->created_at = $created_at_actual;
                                        $upload->save();
                                        return response()->json($upload);
                                    }else{
                                        return response()->json(['message' => 'Error de servidor'], 500);
                                    }
                                    //$upload->delete();
                                    //return response()->json(['message' => 'El archivo ya no existe, se procedio eliminar el registro'.$url]);
                                }
                                //return response()->json(['message' => 'Archivo tiene imagen'], 200);
                            }else{
                                $ruta_nueva = public_path('uploads/' . $request->input('name_upload').'.'.$nameExtension);
                                if(rename($ruta_actual, $ruta_nueva)){
                                    $url = url('uploads/' . $request->input('name_upload').'.'.$nameExtension);
                                    $upload->name_upload = $request->input('name_upload');
                                    $upload->url_upload = $url;
                                    $upload->idExtension = $request->input('idExtension');
                                    $upload->created_at = $created_at_actual;
                                    $upload->save();
                                    return response()->json($upload);
                                }else{
                                    return response()->json(['message' => 'Error de conexion a la base de datos'], 500);
                                }
                            }
                        }else{
                            return response()->json(['message' => 'Extension solicitada no cuenta con name_extension'], 400);
                        }
                    }else{
                        return response()->json(['message' => 'Problemas con encontrar la extension'], 400);
                    }
                    
                }else{
                    return response()->json(['message' => 'Extension actual no cuenta con name_extension'], 400);
                }
            }else{
                return response()->json(['message' => 'Problemas con encontrar la extension'], 400);
            }
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El archivo no existe'], 400);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UploadModel $uploadModel, $idUpload)
    {
        try {
            // Verificar si el archivo existe
            $upload = $uploadModel->findOrFail($idUpload);

            $extensionController  = new ExtensionController();
            $extensionResponse_actual = $extensionController->show($upload->idExtension);
            $extension_actual = $extensionResponse_actual->getOriginalContent();

            if(isset($extension_actual->name_extension)){
                $nameExtension_actual = $extension_actual->name_extension;
                $ruta_actual = public_path('uploads/' . $upload->name_upload.'.'.$nameExtension_actual);
                if (file_exists($ruta_actual)) {
                    if(unlink($ruta_actual)){
                        // Eliminar el archivo
                        $upload->delete();
                        return response()->json(['message' => 'Archivo eliminado correctamente']);
                    }else{
                        return response()->json(['error'=>'Detección de error','message' => 'Error de servidor'], 500);
                    }
                }else{
                    $upload->delete();
                    return response()->json(['error'=>'Detección de error','message' => 'El archivo ya no existe, se procedio eliminar el registro'.$url]);
                }
            }else{
                return response()->json(['error'=>'Detección de error','message' => 'Extension actual no cuenta con name_extension'], 400);
            }
            
        } catch (QueryException $e) {
            return response()->json(['error'=>'Detección de error','message' => 'Error de conexion a la base de datos: '.$e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Detección de error','message' => 'El archivo no existe'], 400);
        }
    }
}
