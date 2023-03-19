<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\LowProfileController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Type\TypeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;



Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('users', UserController::class)->except('create');

    //Items Routes///////////////////////////////////////////////////////////////////////////////////
    Route::resource('items', ItemController::class)->except('create');
    Route::get('items/{item}/brand', [ItemController::class, 'brand']);
    Route::get('items/{item}/category', [ItemController::class, 'category']);
    Route::get('items/{item}/type', [ItemController::class, 'type']);
    Route::get('items/{item}/suppliers', [ItemController::class, 'suppliers']);
    Route::get('items/{item}/orders', [ItemController::class, 'orders']);
    Route::get('items/{item}/orderSuppliers', [ItemController::class, 'orderSuppliers']);

    //Orders Routes//////////////////////////////////////////////////////////////////////////////////
    Route::resource('orders', OrderController::class)->except('create');
    Route::get('orders/{order}/items', [OrderController::class, 'items']);
    Route::get('orders/{order}/suppliers', [OrderController::class, 'suppliers']);

    //Suppliers Routes///////////////////////////////////////////////////////////////////////////////
    Route::resource('suppliers', SupplierController::class)->except('create');
    Route::get('suppliers/{supplier}/items', [SupplierController::class, 'items']);

    //Brands Routes///////////////////////////////////////////////////////////////////////////////////
    Route::resource('brands', BrandController::class)->except('create');
    Route::get('brands/{brand}/categories', [BrandController::class, 'categories']);
    Route::get('brands/{brand}/types', [BrandController::class, 'types']);

    //categories Routes///////////////////////////////////////////////////////////////////////////////
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/brands', [CategoryController::class, 'brands']);
    Route::get('categories/{category}/types', [CategoryController::class, 'types']);

    //types Routes////////////////////////////////////////////////////////////////////////////////////
    Route::resource('types', TypeController::class);
    Route::get('types/{type}/category', [TypeController::class, 'category']);
    Route::get('types/{type}/brands', [TypeController::class, 'brands']);

    //Low Profile Data////////////////////////////////////////////////////////////////////////////////
    Route::prefix('lowprofile')->group(function() {
        Route::get('brands', [LowProfileController::class, 'brands']);
        Route::get('categories', [LowProfileController::class, 'categories']);
        Route::get('types', [LowProfileController::class, 'types']);
        Route::get('suppliers', [LowProfileController::class, 'suppliers']);
    });

});
