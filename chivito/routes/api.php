<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\BookmarkController;

Route::post('/createCustomer', [UserController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);


Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'show']);
Route::apiResource('providers', ProviderController::class)->only(['index', 'show']);
Route::delete('/providers', [ProviderController::class, 'destroyAll']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers', [UserController::class, 'getAllCustomers']);
    Route::get('/my-providers', [ProviderController::class, 'myProviders']);
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks/{provider}', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{provider}', [BookmarkController::class, 'destroy']);
    Route::apiResource('providers', ProviderController::class)->only(['store', 'update', 'destroy']);

});
