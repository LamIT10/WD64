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
    }

    public function index()
    {
        $perPage = request()->get('perPage', 10);
        $filters = [
            'search' => ['search' => request()->input('search')],
            'absoluteFilter' => request()->only(['status']),
            'between' => [
                'min_total_spent' => [
                    'min' => request()->input('min_total_spent_min'),
                    'max' => request()->input('min_total_spent_max'),
                ],
                'discount_percent' => [
                    'min' => request()->input('discount_percent_min'),
                    'max' => request()->input('discount_percent_max'),
                ],
                'credit_percent' => [
                    'min' => request()->input('credit_percent_min'),
                    'max' => request()->input('credit_percent_max'),
                ],
            ],
        ];

        $ranks = $this->rankRepo->getAll($perPage, $filters);
        Log::info('Dữ liệu ranks trả về:', ['ranks' => $ranks->toArray()]);

        return $this->renderView(['ranks' => $ranks], 'admin/Ranks/Index');
    }

    public function create()
    {
        return $this->renderView([], 'admin/Ranks/Create');
    }

    public function store(StoreRankRequest $request)
    {
        Log::info('Dữ liệu đầu vào store:', $request->all());
        Log::info('Dữ liệu validated trong store:', $request->validated());
        $result = $this->rankRepo->createRank($request->validated());
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.ranks.index');
        }
        return $this->returnInertia($result, 'Thêm hạng mới thành công', 'admin.ranks.index');
    }

    public function edit(Rank $rank)
    {
        if (config('app.debug')) {
            Log::info('Chỉnh sửa hạng', ['rank' => $rank->toArray()]);
        }
        return $this->renderView(['rank' => $rank], 'admin/Ranks/Edit');
    }

    public function update(StoreRankRequest $request, Rank $rank)
    {
        Log::info('Dữ liệu đầu vào update:', $request->all());
        Log::info('Dữ liệu validated trong update:', $request->validated());
        $result = $this->rankRepo->updateRank($rank, $request->validated());
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.ranks.index');
        }
        return $this->returnInertia($result, 'Cập nhật hạng thành công', 'admin.ranks.index');
    }

    public function destroy(Rank $rank)
    {
        if (strtolower($rank->name) === 'sắt') {
            return $this->returnInertia(
                ['status' => false, 'message' => 'Không thể xóa hạng mặc định "Sắt"'],
                '',
                'admin.ranks.index'
            );
        }

        if ($rank->customers()->exists()) {
            $defaultRank = Rank::where('name', 'Sắt')->where('status', 'active')->first();
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
        if (is_array($result) && isset($result['status']) && !$result['status']) {
            return $this->returnInertia($result, $result['message'], 'admin.ranks.index');
        }
        return $this->returnInertia($result, 'Xóa hạng thành công', 'admin.ranks.index');
    }
}