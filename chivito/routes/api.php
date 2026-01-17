<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\MagicLinkController;

Route::post('/createCustomer', [UserController::class, 'create'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');
Route::get('/magic-login', [MagicLinkController::class, 'login'])->middleware('throttle:10,1')->name('magic-login');


Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'show']);
Route::get('/subcategories', [SubcategoryController::class, 'index']);
Route::apiResource('providers', ProviderController::class)->only(['index', 'show']);
Route::delete('/providers', [ProviderController::class, 'destroyAll']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers', [UserController::class, 'getAllCustomers']);
    Route::get('/profile', [UserController::class, 'me']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::delete('/profile', [UserController::class, 'destroySelf']);
    Route::post('/password/change', [AuthController::class, 'changePassword']);
    Route::post('/logout/all', [AuthController::class, 'logoutAll']);
    Route::get('/my-providers', [ProviderController::class, 'myProviders']);
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks/{provider}', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{provider}', [BookmarkController::class, 'destroy']);
    Route::apiResource('providers', ProviderController::class)->only(['store', 'update', 'destroy']);
    Route::post('/subcategories', [SubcategoryController::class, 'store']);
    Route::put('/subcategories/{subcategory}', [SubcategoryController::class, 'update']);
    Route::delete('/subcategories/{subcategory}', [SubcategoryController::class, 'destroy']);

});
