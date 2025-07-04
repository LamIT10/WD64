<?php

use App\Http\Controllers\admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryAuditController;
use App\Http\Controllers\Api\InventoryController;

Route::get('/inventory-audit/information-create', [InventoryAuditController::class, 'showInformationCreate']);
Route::patch('/inventory-audit/update', [InventoryAuditController::class, 'update']);
Route::get('/inventory/stats', [InventoryController::class, 'stats']);
Route::patch('/inventory/update', [InventoryController::class, 'update']);

Route::get('/generate-code', [ProductController::class, 'generateCode'])->name('generate-code');
Route::get('/generate-variant-code', [ProductController::class, 'generateVariantCode'])->name('generate-variant-code');
