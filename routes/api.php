<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryAuditController;
use App\Http\Controllers\Api\InventoryController;

Route::middleware(['web', 'auth'])->group(function () {

Route::get('/inventory-audit/information-create', [InventoryAuditController::class, 'showInformationCreate']);
Route::patch('/inventory-audit/update', [InventoryAuditController::class, 'update']);
Route::get('/inventory/stats', [InventoryController::class, 'stats']);
Route::patch('/inventory/update', [InventoryController::class, 'update']);
Route::get('/inventory-audit/{id}', [InventoryAuditController::class, 'show']);

});
