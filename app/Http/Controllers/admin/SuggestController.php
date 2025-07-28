<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SuggestRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuggestController extends Controller
{
    protected $reportRepository;

    public function __construct(SuggestRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function suggest(Request $request)
    {
        $weeks = (int) $request->input('history_weeks', 12);
        $reserveDays = (int) $request->input('reserve_days', 10);
        $search = $request->input('product_search', '');
        $data = $this->reportRepository->getSuggestOrder($weeks, $reserveDays, $search);
        return Inertia::render('admin/Reports/SuggestOrder', ['suggestions' => $data]);
    }
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('product_search', '');
        $page = $request->input('page', 1);

        $result = $this->reportRepository->getRevenueReport($startDate, $endDate, $search, 5);

        return Inertia::render('admin/Reports/Revenue', [
            'revenues' => $result['data'],
            'meta' => $result['meta'],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'product_search' => $search,
            ],
        ]);
    }
}
