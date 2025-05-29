<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

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
            Log::error('Lỗi Danh Sách Danh Mục: ' . $th->getMessage());
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
        $category = $this->findById($id);

        if (!$category) {
            return $this->returnError('Không tìm thấy danh mục');
        }
        return $category;
    }


    public function store($data)
    {
        try {
            DB::beginTransaction();
            $newCategory = [];
            $newCategory['name'] = $data['name'] ?? null;
            $newCategory['parent_id'] = $data['parent_id'] ?? null;
            $newCategory['description'] = $data['description'] ?? null;

            $category = $this->handleModel->create($newCategory);
            if (!$category) {
                throw new Exception('Lỗi, thêm không thành công');
            }
            DB::commit();
            return $category;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi tạo danh mục: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            $category = $this->getById($id);
            if(!$category) {
                throw new Exception('Danh mục sản phẩm không tồn tại');
            }
            $dataCategory = [];
            $dataCategory['name'] = $data['name'] ?? null;
            $dataCategory['parent_id'] = $data['parent_id'] ?? null;
            $dataCategory['description'] = $data['description'] ?? null;

            $category->update($dataCategory);

            DB::commit();
            return $category;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi tạo danh mục: ' . $th->getMessage());
            return $this->returnError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $category = $this->handleModel->with(['children', 'products'])->find($id);

            if (!$category) {
                return $this->returnError('Không tìm thấy danh mục với ID: ' . $id);
            }

            if ($category->children && $category->children->count() > 0) {
                throw new Exception('Không thể xóa danh mục cha khi còn danh mục con.');
            }

            if ($category->products && $category->products->count() > 0) {
                throw new Exception('Không thể xóa danh mục khi còn sản phẩm.');
            }
            $category->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi xóa danh mục: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
