<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
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
}
