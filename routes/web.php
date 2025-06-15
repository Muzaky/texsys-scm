<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\JITController;
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


Route::get('/',[AuthController::class, 'index'])
    ->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');




Route::get(uri:'/sales', action: [SalesController::class, 'index'])
    ->name('sales');
Route::post(uri:'/sales/store', action: [SalesController::class, 'store'])
    ->name('sales.store');
Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update'); 
Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy'); 

Route::get(uri:'/materialstock', action: [MaterialController::class, 'index'])
    ->name('materialstock');
Route::post('/materialstock/add-stock/{id}', [MaterialController::class, 'addStock'])->name('materialstock.addstock');

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

Route::get('/jit-analysis', [JITController::class, 'index'])->name('jit.index');
Route::post('/jit-analysis', [JITController::class, 'analyze'])->name('jit.analyze');

Route::get('/notifications/fetch', [JITController::class, 'fetchNotifications'])->name('notifications.fetch');
Route::post('/jit-recommendations/{recommendation}/acknowledge', [JITController::class, 'acknowledge'])->name('jit.acknowledge');


