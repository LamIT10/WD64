<?php

namespace App\Repositories;

use App\Models\Attribute;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeRepository extends BaseRepository
{
    public function __construct(Attribute $attribute)
    {
        $this->handleModel = $attribute;
    }

    public function getAll()
    {
        try {
            return $this->handleModel->paginate(10);
        } catch (\Throwable $th) {
            Log::error('Lỗi Danh Sách  ' . $th->getMessage());
        }
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $newAttribute = [
                'name' => $data['name'] ?? null,
            ];
            $attribute = $this->handleModel->create($newAttribute);
            if (!$attribute) {
                throw new Exception('Lỗi, thêm không thành công');
            }
            DB::commit();
            return $attribute;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi tạo thuộc tính: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $attribute = $this->handleModel->with('values.productVariants')->find($id);

            if (!$attribute) {
                throw new Exception('Không tìm thấy giá trị thuộc tính với ID: ' . $id);
            }

            if ($attribute->values->count() > 0) {
                throw new Exception('Không thể xoá: Thuộc tính có giá trị con liên quan.');
            }
            $hasRelatedProduct = $attribute->values->some(function ($value) {
                return $value->productVariants->isNotEmpty();
            });

            if ($hasRelatedProduct) {
                throw new Exception('Không thể xoá: Một số giá trị thuộc tính đang được sử dụng trong sản phẩm.');
            }

            $attribute->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi xóa thuộc tính: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
