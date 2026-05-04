<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MascotController;
use App\Http\Controllers\Api\MonthlySettingController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Profile
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::delete('/profile', [AuthController::class, 'deleteAccount']);

});
// Account
Route::apiResource('/api-accounts', AccountController::class) ->middleware('auth:sanctum');
// Bill
Route::apiResource('/api-bills', BillController::class) ->middleware('auth:sanctum');
// Mascot
Route::middleware('auth:sanctum')->get('/api-mascot', [MascotController::class, 'index']);
// Category
Route::apiResource('/api-categories', CategoryController::class)
    ->middleware('auth:sanctum');
// Transaction
Route::apiResource('/api-transactions', TransactionController::class)
    ->middleware('auth:sanctum');
// Monthly Setting
Route::apiResource('/api-monthly-settings', MonthlySettingController::class) ->middleware('auth:sanctum');