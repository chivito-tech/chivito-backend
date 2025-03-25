<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::get('/customers', [UserController::class, "getAllCustomers"]);
Route::post('/createCustomer', [UserController::class, "create"]);


Route::post('/login', [AuthController::class, 'login']);