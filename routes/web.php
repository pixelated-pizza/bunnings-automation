<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/get-orders', [NetoController::class, 'fetchBunningsOrders']);

