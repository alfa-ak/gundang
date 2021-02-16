<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Barang
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    // Barang Stock
    Route::post('/barang/masuk/{id}', [BarangController::class, 'masuk']);
    Route::post('/barang/keluar/{id}', [BarangController::class, 'keluar']);
    // Barang Kuantitas
    Route::get('/barang/kuantitas', [BarangController::class, 'kuantitas']);
    Route::get('/barang/ratarata', [BarangController::class, 'ratarata']);

    // Supplier
    Route::get('/supplier', [SupplierController::class, 'index']);
    Route::post('/supplier', [SupplierController::class, 'store']);
    Route::put('/supplier/{id}', [SupplierController::class, 'update']);
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

    // Distributor
    Route::get('/distributor', [DistributorController::class, 'index']);
    Route::post('/distributor', [DistributorController::class, 'store']);
    Route::put('/distributor/{id}', [DistributorController::class, 'update']);
    Route::delete('/distributor/{id}', [DistributorController::class, 'destroy']);


});
