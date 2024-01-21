<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
//home
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\VoucherController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin == 1) {
            return redirect()->route('home');
        } else {
            return redirect()->route('sales.dashboard');
        }
    } else {
        return redirect()->route('login');
    }
});




Route::get('sales/location/{id}', [App\Http\Controllers\Sales\SalesController::class, 'getSalesLocation'])->name('location');
Route::get('/login', [LoginController::class, 'LoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::post('/profile/update', [AccountController::class, 'update'])->name('profile.update');
Route::post('/profile/update/password', [AccountController::class, 'updatePassword'])->name('profile.update.password');
Route::post('/profile/delete', [AccountController::class, 'deleteAccount'])->name('profile.delete');
Route::post('/profile/update/location', [AccountController::class, 'updateLocation'])->name('update.location');

Route::prefix('supervisor')->middleware('is_admin')->group(function(){
    Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home');
    Route::get('/task', [App\Http\Controllers\Task\TaskController::class, 'index'])->name('task');
    Route::get('/task/create', [App\Http\Controllers\Task\TaskController::class, 'task'])->name('task.create');
    Route::post('/task/store', [App\Http\Controllers\Task\TaskController::class, 'store'])->name('task.store');
    Route::get('/task/edit/{id}', [App\Http\Controllers\Task\TaskController::class, 'edit'])->name('task.edit');
    //getEstimation

    Route::put('/task/update/{id}', [App\Http\Controllers\Task\TaskController::class, 'update'])->name('task.update');
    Route::get('/task/detail/{id}', [App\Http\Controllers\Task\TaskController::class, 'show'])->name('task.detail');
    Route::get('/account/user', [AccountController::class, 'getUser'])->name('account.user');
    Route::get('/account/admin', [AccountController::class, 'getAdmin'])->name('account.admin');    
    Route::post('/account/create', [AccountController::class, 'storeUsers'])->name('account.store');
    Route::get('/account/create', [AccountController::class, 'addUsers'])->name('account.create');
    Route::delete('/account/delete/{id}', [AccountController::class, 'deleteUsers'])->name('account.delete');
    Route::get('/account/edit/{id}', [AccountController::class, 'editUsers'])->name('account.edit');
    Route::post('/account/update/{id}', [AccountController::class, 'updateUsers'])->name('account.update');
    Route::get('/profile', [AccountController::class, 'index'])->name('profile');
    Route::get('sales/location', [App\Http\Controllers\Admin\AdminController::class, 'salesLocation'])->name('sales.location');

    Route::get('sales/task', [SalesController::class, 'getAllSales'])->name('sales.all');
    //getSalesTask
    Route::get('sales/task/{id}', [SalesController::class, 'getSalesTask'])->name('list_task_sales');
    Route::get('sales/task/detail/{id}', [App\Http\Controllers\Task\TaskController::class, 'show'])->name('sales.task.detail');
    Route::get('/task/estimation/{id}', [App\Http\Controllers\Task\TaskController::class, 'getEstimation'])->name('task.estimation');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    //store
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    //edit
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');

    Route::get('/product', [ProductController::class, 'index'])->name('product');
    //store
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    //edit
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    //detail
    Route::get('/product/detail/{id}', [ProductController::class, 'show'])->name('product.detail');
    //delete
    Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');

    Route::get('/purchase_order', [PurchaseOrderController::class, 'index'])->name('purchase_order');
    Route::get('/select_product', [PurchaseOrderController::class, 'select_product'])->name('select_product');

    Route::post('/checkout', [PurchaseOrderController::class, 'checkout'])->name('checkout');
    Route::get('/list_purchase_order', [PurchaseOrderController::class, 'list_order'])->name('list_order');
    //detail_order
    Route::get('/detail_order/{id}', [PurchaseOrderController::class, 'detail_order'])->name('detail_order');
    //checkVoucher
    Route::post('/checkVoucher', [VoucherController::class, 'checkVoucher'])->name('checkVoucher');
    //invoice
    Route::get('/invoice/{type}', [PurchaseOrderController::class, 'invoice'])->name('invoice');


}); 

Route::prefix('sales')->middleware('is_sales')->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\Sales\SalesController::class, 'dashboard'])->name('sales.dashboard');
    Route::get('/home', [App\Http\Controllers\Sales\SalesController::class, 'index'])->name('sales.home');
    Route::get('/profile', [AccountController::class, 'index'])->name('sales.profile');
    Route::get('/task/{id}', [App\Http\Controllers\Sales\SalesController::class, 'show'])->name('sales.task');
    Route::post('/task/confirm/{id}', [App\Http\Controllers\Sales\SalesController::class, 'confirmTask'])->name('sales.task.confirm');
    Route::post('/task/proff/{id}', [App\Http\Controllers\Sales\SalesController::class, 'storeProff'])->name('sales.task.proff');
    Route::get('/task/estimation/{id}', [App\Http\Controllers\Task\TaskController::class, 'getEstimation'])->name('sales.task.estimation');

});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Symbolic link has been created';
});