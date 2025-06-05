<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRankRequest;
use App\Models\Rank;
use App\Repositories\RankRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RankController extends Controller
{
    protected RankRepository $rankRepo;

    public function __construct(RankRepository $rankRepo)
    {
        $this->rankRepo = $rankRepo;
        // $this->middleware('auth:admin')->only(['destroy']);
    }

    public function index()
    {
        $perPage = request()->get('perPage', 10);
        $filters = [
            'search' => request()->only(['name', 'note']),
            'absoluteFilter' => request()->only(['status']),
            'between' => [
                'min_total_spent' => request()->only(['min_total_spent_min', 'min_total_spent_max']),
                'discount_percent' => request()->only(['discount_percent_min', 'discount_percent_max']),
                'credit_percent' => request()->only(['credit_percent_min', 'credit_percent_max']),
            ],
        ];
        $ranks = Cache::remember('ranks_page_' . request()->get('page', 1), 3600, function () use ($perPage, $filters) {
            return $this->rankRepo->getAll($perPage, $filters);
        });
        return $this->renderView(['ranks' => $ranks], 'admin/Ranks/Index');
    }

    public function create()
    {
        return $this->renderView([], 'admin/Ranks/Create');
    }

    public function store(StoreRankRequest $request)
    {
        $result = $this->rankRepo->createRank($request->validated());
        return $this->returnInertia($result, 'Thêm hạng mới thành công', 'admin.ranks.index');
    }

    public function edit(Rank $rank)
    {
        Log::info('Chỉnh sửa hạng', ['rank' => $rank->toArray()]);
        return $this->renderView(['rank' => $rank], 'admin/Ranks/Edit');
    }

    public function update(StoreRankRequest $request, Rank $rank)
    {
        $result = $this->rankRepo->updateRank($rank, $request->validated());
        return $this->returnInertia($result, 'Cập nhật hạng thành công', 'admin.ranks.index');
    }

    public function destroy(Rank $rank)
    {
        if ($rank->name === 'Sắt') {
            return $this->returnInertia(
                ['status' => false, 'message' => 'Không thể xóa hạng mặc định "Sắt"'],
                '',
                'admin.ranks.index'
            );
        }
        if ($rank->customers()->exists()) {
            $defaultRank = Rank::where('name', 'Sắt')->first();
            if (!$defaultRank) {
                return $this->returnInertia(
                    ['status' => false, 'message' => 'Không tìm thấy hạng mặc định "Sắt"'],
                    '',
                    'admin.ranks.index'
                );
            }
            $rank->customers()->update(['rank_id' => $defaultRank->id]);
        }
        $result = $this->rankRepo->deleteRank($rank);
        return $this->returnInertia($result, 'Xóa hạng thành công', 'admin.ranks.index');
    }
}