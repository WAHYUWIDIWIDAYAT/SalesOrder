<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\VoucherController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/city', [TaskController::class, 'getCity']);
Route::get('/district', [TaskController::class, 'getDistrict']);

Route::get('/select_product', [PurchaseOrderController::class, 'select_product']);
Route::post('/checkVoucher', [VoucherController::class, 'checkVoucher'])->name('checkVoucher');
