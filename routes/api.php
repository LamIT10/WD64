<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventoryAuditController;

Route::get('/inventory-audit/information-create', [InventoryAuditController::class, 'showInformationCreate']);
