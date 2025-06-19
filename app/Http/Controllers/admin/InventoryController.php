<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseZone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index()
    {
        $zones = WarehouseZone::pluck('name');
        return Inertia::render('admin/Inventory/Index', [
            'zones' => $zones,
        ]);
    }

    public function store(Request $request)
    {
        //
    }
}
