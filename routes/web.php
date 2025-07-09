<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BunningsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/get-orders', [BunningsController::class, 'fetch_orders']);
Route::get('/api/oauth', [BunningsController::class, 'auth']);

