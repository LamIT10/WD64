<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRankRequest;
use App\Models\Rank;
use App\Repositories\RankRepository;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RankController extends Controller
{
    protected RankRepository $rankRepo;

    public function __construct(RankRepository $rankRepo)
    {
        $this->rankRepo = $rankRepo;
    }

    public function index()
    {
        $perPage = request()->get('perPage', 10);
        $ranks = $this->rankRepo->getAll($perPage);

        return Inertia::render('admin/Ranks/Index', [
            'ranks' => $ranks,
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Ranks/Create');
    }

    public function store(StoreRankRequest $request)
    {
        $this->rankRepo->createRank($request->validated());

        return redirect()->route('admin.ranks.index')->with('success', 'Thêm hạng mới thành công');
    }

    public function edit(Rank $rank)
    {
        Log::info('Chỉnh sửa hạng', ['rank' => $rank->toArray()]);

        return Inertia::render('admin/Ranks/Edit', [
            'rank' => $rank,
        ]);
    }

    public function update(StoreRankRequest $request, Rank $rank)
    {
        $this->rankRepo->updateRank($rank, $request->validated());

        return redirect()->route('admin.ranks.index')->with('success', 'Cập nhật hạng thành công');
    }

    public function destroy(Rank $rank)
    {
        if ($rank->customers()->exists()) {
            // Đặt rank_id của khách hàng thành null
            $rank->customers()->update(['rank_id' => null]);
        }

        $this->rankRepo->deleteRank($rank);

        return redirect()->route('admin.ranks.index')->with('success', 'Xóa hạng thành công');
    }
}