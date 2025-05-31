<?php

namespace App\Http\Controllers;

use App\Exports\SaleOrderExport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SaleOrderController extends Controller
{
    public function export()
    {
        return Excel::download(new SaleOrderExport, 'DonHangXuat.xlsx');
    }
    public function index()
    {
        return inertia('admin/sale-orders/List');
    }
}
