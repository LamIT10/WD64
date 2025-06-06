<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryAuditController extends Controller
{
    public function index()
    {
        // dd(123);
        return Inertia::render('admin/InventoryAudit/Index');
    }

    public function create()
    {
        return Inertia::render('admin/InventoryAudit/Create');
    }
    

}
