<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Brand\BrandCategoryController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Brand\BrandTypeController;
use App\Http\Controllers\Category\CategoryBrandController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\CategoryTypeController;
use App\Http\Controllers\Item\ItemBrandController;
use App\Http\Controllers\Item\ItemCategoryController;
use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Item\ItemSupplierController;
use App\Http\Controllers\Item\ItemtypeController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderItemController;
use App\Http\Controllers\Order\OrderSupplierController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierItemController;
use App\Http\Controllers\Type\TypeBrandController;
use App\Http\Controllers\Type\TypeCategoryController;
use App\Http\Controllers\Type\TypeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('users', UserController::class)->except('create', 'edit');

    //Items Routes///////////////////////////////////////////////////////////////////////////////////
    Route::resource('items', ItemController::class)->except('create', 'edit');
    Route::resource('items.brand', ItemBrandController::class)->only('index');
    Route::resource('items.category', ItemCategoryController::class)->only('index');
    Route::resource('items.type', ItemtypeController::class)->only('index');
    Route::resource('items.suppliers', ItemSupplierController::class)->only('index');

    //Orders Routes//////////////////////////////////////////////////////////////////////////////////
    Route::resource('orders', OrderController::class)->except('create', 'edit');
    Route::resource('orders.items', OrderItemController::class)->only('index');
    Route::resource('orders.suppliers', OrderSupplierController::class)->only('index');

    //Suppliers Routes///////////////////////////////////////////////////////////////////////////////
    Route::resource('suppliers', SupplierController::class)->except('create', 'edit');
    Route::resource('suppliers.items', SupplierItemController::class)->only('index');

    //Brands Routes///////////////////////////////////////////////////////////////////////////////////
    Route::resource('brands', BrandController::class)->except('create', 'edit');
    Route::resource('brands.categories', BrandCategoryController::class)->only('index');
    Route::resource('brands.types', BrandTypeController::class)->only('index');

    //categories Routes///////////////////////////////////////////////////////////////////////////////
    Route::resource('categories', CategoryController::class);
    Route::resource('categories.brands', CategoryBrandController::class)->only('index');
    Route::resource('categories.types', CategoryTypeController::class)->only('index');


    //types Routes////////////////////////////////////////////////////////////////////////////////////
    Route::resource('types', TypeController::class);
    Route::resource('types.category', TypeCategoryController::class);
    Route::resource('types.brands', TypeBrandController::class);

});
