<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    public function getList()
    {
        return Inertia::render('admin/PurchaseOrders/FormCreate');
    }
}
