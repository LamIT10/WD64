<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseZone;
use App\Repositories\DashboardRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->handleRepository = $dashboardRepository;
    }
    
    public function index()
    {
        $query = request()->all();

        $data = $this->handleRepository->getDataForDashBoard($query);

        $zones = WarehouseZone::pluck('name');
        return Inertia::render('admin/Inventory/Index', [
            'zones' => $zones,
            'cardData' => [
            'count_product' => $data['count_product'],
            'product_is_out_of_stock' => $data['product_is_out_of_stock'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    
}
