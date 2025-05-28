<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Log;

class CategoryRepository extends BaseRepository
{


    public function __construct(Category $category)
    {
        $this->handleModel = $category;
    }

    public function getAll()
    {
        try {
            return $this->handleModel
                ->with('children')
                ->whereNull('parent_id')
                ->paginate(10);
        } catch (\Throwable $th) {
            \Log::error('Lỗi Danh Sách Danh Mục: ' . $th->getMessage());
        }
    }

    public function findById($id)
    {
        return $this->handleModel::with(['products'])->find($id);
    }

    public function parent()
    {
        return $this->handleModel->whereNull('parent_id')->get();
    }

    public function show($id)
    {
        try {
            $category = $this->findById($id);

            if (!$category) {
                return false;
            }
            return $category;
        } catch (\Throwable $th) {
            \Log::error('Lỗi chi tiết category: ' . $th->getMessage());
        }
    }


    public function store($data)
    {
        try {
            DB::beginTransaction();
            $this->create($data);

            DB::commit();
        } catch (\Throwable $th) {
            \Log::error('Lỗi khi tạo danh mục: ' . $th->getMessage());
            DB::rollBack();
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->findById($id);

             if (!$category) {
                return $this->returnError('Không tìm thấy danh mục với ID: ' . $id);
            }

            $category->delete();

            return true;
        } catch (\Throwable $th) {
            \Log::error('Lỗi khi xóa danh mục: ' . $th->getMessage());

            return $this->returnError('Lỗi không thể xóa danh mục');
        }
    }
}
