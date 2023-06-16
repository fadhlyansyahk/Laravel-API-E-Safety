<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/check-token', [AuthController::class, 'checkToken']);

Route::post('/foto',[FotoController::class, 'store']);
Route::get('/foto',[FotoController::class, 'getDetailFoto']);
Route::put('/foto',[FotoController::class, 'update']);
Route::delete('/foto',[FotoController::class, 'delete']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

