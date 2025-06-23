<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Inertia\Inertia;

class DashboardController extends Controller
{
   
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->handleRepository = $dashboardRepository;
    }
    public function index(){
        $query = request()->all();
        $data = $this->handleRepository->getDataForDashBoard($query);
        return Inertia::render("Dashboard", ['data' => $data]);
    }
}
