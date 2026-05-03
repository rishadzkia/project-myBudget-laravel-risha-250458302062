<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\MascotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Account
Route::apiResource('/api-accounts', AccountController::class) ->middleware('auth:sanctum');
// Bill
Route::apiResource('/api-bills', BillController::class) ->middleware('auth:sanctum');
// Mascot
Route::middleware('auth:sanctum')->get('/api-mascot', [MascotController::class, 'index']);