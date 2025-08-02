<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;
use Illuminate\Foundation\Providers\FoundationServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportExportController extends Controller
{
    public function __construct( DashboardRepository $dashboardRepository)
    {
        
        $this->handleRepository =$dashboardRepository;
    }
  public function export()
{
    $query = request()->all();
    $year = request()->get('year', Carbon::now()->year); 
    $month = request()->get('month', Carbon::now()->month); // lấy tháng nếu có

    $top10 = $this->handleRepository->getDataForDashBoard($query);

    $top10['top_10_product_variants'] = [
        'week' => $top10['top_10_product_variants']['week']->values()->toArray(),
        'month' => $top10['top_10_product_variants']['month']->values()->toArray(),
        'year' => $top10['top_10_product_variants']['year']->values()->toArray(),
    ];

    $monthlyStats = $this->handleRepository->getMonthlyExportStatsByYear($year);

    $exportData = [
        'year' => $year,
        'data' => collect($monthlyStats)->map(fn ($item) => [
            'month' => $item->month,
            'count' => (int) $item->total_orders
        ])
    ];

    $exportValue = [
        'year' => $year,
        'data' => collect($monthlyStats)->map(fn ($item) => [
            'month' => $item->month,
            'total' => (int) $item->total_amount
        ])
    ];

    $exportStatusSummary = $this->handleRepository->getExportStatusSummaryByMonth($month);

    $years = DB::table('sale_orders')
        ->selectRaw('YEAR(order_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year')
        ->toArray();

    return Inertia::render("admin/Reports/ReportExport", [
        'top10' => $top10,
        'exportData' => $exportData,
        'exportValue' => $exportValue,
        'years' => $years,
        'year' => $year,
        'exportStatusSummary' => $exportStatusSummary, // ✅
        'selectedMonth' => $month 
    ]);
}

}