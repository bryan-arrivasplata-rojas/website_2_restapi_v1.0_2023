<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UsabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Crear php artisan make:controller UserController --resource --model=UserModel
Route::apiResource('user',UserController::class);
Route::get('user/login/{email}', [UserController::class, 'login']);
Route::apiResource('extension',ExtensionController::class);
Route::apiResource('file',FileController::class);
Route::get('file/search/{idUsability}&{idType}', [FileController::class, 'search']);
Route::apiResource('profile',ProfileController::class);
Route::apiResource('type',TypeController::class);
Route::apiResource('upload',UploadController::class);
Route::apiResource('usability',UsabilityController::class);

//Se simulara para carga de imagen el put como post
Route::post('upload/{idUpload}', [UploadController::class, 'update']);
Route::apiResource('upload', UploadController::class)->except(['update']);

//Controlaremos en el caso no envien id
Route::match(['put', 'delete'], '{resource}', function ($resource) {
    return response()->json(['message' => 'Debe especificar un ID para continuar con ' . $resource], 400);
})->where('resource', 'user|extension|file|profile|type|upload|usability')->fallback();