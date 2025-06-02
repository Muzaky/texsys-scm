<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
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

Route::get(uri:'/materialstock', action: [MaterialController::class, 'index'])
    ->name('materialstock');

Route::get(uri:'/productstock', action: [ProductController::class, 'index'])
    ->name('productstock');
Route::post('/product-stock/add-stock', [ProductController::class, 'addStock'])->name('produkjadi.addstock');
Route::post('/product-stock/reduce-stock', [ProductController::class, 'reduceStock'])->name('produkjadi.reducestock');

    
Route::get(uri:'/transactionlogs', action: [LogController::class, 'index'])
    ->name('transactionlogs');


Route::get(uri:'/dashboard', action: [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get(uri:'/forecast', action: [ForecastController::class, 'index'])
    ->name('forecast');

// Route::get('/dashboard', function () {
//     return view('dashboard/dashboard');
// })->name('dashboard');

