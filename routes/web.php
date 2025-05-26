<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get(uri:'/login', action: [AuthController::class, 'index'])
    ->name('login');

Route::get(uri:'/sales', action: [SalesController::class, 'index'])
    ->name('sales');

Route::get('/dashboard', function () {
    return view('dashboard/dashboard');
})->name('dashboard');

