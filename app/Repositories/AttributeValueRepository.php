<?php

namespace App\Repositories;

use App\Models\AttributeValue;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeValueRepository extends BaseRepository
{
    public function __construct(AttributeValue $attributeValue)
    {
        $this->handleModel = $attributeValue;
    }
    public function getByAttributeIds(array $attributeIds)
    {
        try {
            return $this->handleModel
                ->whereIn('attribute_id', $attributeIds)
                ->orderBy('attribute_id')
                ->get();
        } catch (\Throwable $th) {
            Log::error('Lỗi lấy danh sách giá trị thuộc tính: ' . $th->getMessage());
            return [];
        }
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $newAttributeValue = [
                'attribute_id' => $data['attribute_id'],
                'name' => $data['name'],
            ];

            $attributeValue = $this->handleModel->create($newAttributeValue);

            if (!$attributeValue) {
                throw new Exception('Lỗi, thêm không thành công');
            }

            DB::commit();
            return $attributeValue;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi tạo giá trị thuộc tính: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $attributeValue = $this->handleModel->with('productVariants')->find($id);

            if (!$attributeValue) {
                throw new Exception('Không tìm thấy giá trị thuộc tính với ID: ' . $id);
            }

            if ($attributeValue->productVariants->isNotEmpty()) {
                throw new Exception('Không thể xoá: Giá trị thuộc tính đang được sử dụng trong sản phẩm.');
            }

            $attributeValue->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi xóa giá trị thuộc tính: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
