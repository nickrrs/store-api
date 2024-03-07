<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'products'], function () {
    Route::get('all', [ProductController::class, 'getProducts'])->name('product.all');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('all', [SaleController::class, 'getSales'])->name('sale.all');
    Route::get('/{id}', [SaleController::class, 'getSale'])->name('sale.get');
    Route::post('store', [SaleController::class, 'newSale'])->name('sale.store');
});