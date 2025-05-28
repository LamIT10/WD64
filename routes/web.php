<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleOrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('layouts.main');
});
Route::get('sale-order/export/', [SaleOrderController::class, 'export'])->name('order.export');
