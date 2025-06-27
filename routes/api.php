<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryAuditController;
use App\Http\Controllers\Api\InventoryController;

Route::get('/inventory-audit/information-create', [InventoryAuditController::class, 'showInformationCreate']);
Route::get('/inventory/stats', [InventoryController::class, 'stats']);
