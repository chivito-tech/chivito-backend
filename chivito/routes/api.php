<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProviderController;

Route::post('/createCustomer', [UserController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);


Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'show']);
Route::apiResource('providers', ProviderController::class)->only(['index', 'store', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers', [UserController::class, 'getAllCustomers']);
});
