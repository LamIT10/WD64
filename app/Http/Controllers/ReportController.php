<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(ReportRepository $reportRepository)
    {
        $this->handleRepository = $reportRepository;
    }
    public function index()
    {
        $query = request()->all();
        $data = $this->handleRepository->getDataForReportImport($query);
        return Inertia::render("admin/Reports/Index", ['data' => $data]);
    }
}