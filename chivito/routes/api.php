<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/customers', [UserController::class, "getAllCustomers"]);
Route::post('/createCustomer', [UserController::class, "create"]);