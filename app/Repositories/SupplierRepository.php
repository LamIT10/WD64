<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierRepository extends BaseRepository
{
    protected $purchaseOrderRepository;
    public function __construct(Supplier $supplier)
    {
        $this->handleModel = $supplier;
    }

    public function getList($data)
    {
        $query = $this->handleModel->select(['*']);
        $filters = [
            'search' => [
                'name' => $data->search ?? "",
            ]
        ];
        $query = $this->filterData($query, $filters);
        $query->orderBy('id', 'desc'); 
        return $query->paginate(10);;
    }
    public function createData($data = [])
    {
        
        try {
            $newSupplier = [];
            DB::beginTransaction();
            $newSupplier['name'] = $data['name'] ?? null;
            $newSupplier['phone'] = $data['phone'] ?? null;
            $newSupplier['address'] = $data['address'] ?? null;
            $newSupplier['email'] = $data['email'] ?? null;
            $newSupplier['contact_person'] = $data['contact_person'] ?? null;

            $supplier = $this->handleModel->create($newSupplier);
            DB::commit();
            return $supplier;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi tạo nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi tạo nhà cung cấp");
        }
    }
    public function updateData($id, $data){
        try {
            $supplier = $this->handleModel->find($id);
            if(!$supplier){
                return $this->returnError("Không tìm thấy nhà cung cấp");
            }
            DB::beginTransaction();
            $supplierUpdate = [];
            $supplierUpdate['name'] = $data['name'] ?? null;
            $newSupplier['phone'] = $data['phone'] ?? null;
            $newSupplier['address'] = $data['address'] ?? null;
            $newSupplier['email'] = $data['email'] ?? null;
            $newSupplier['contact_person'] = $data['contact_person'] ?? null;

            $supplier->update($supplierUpdate);
            DB::commit();
            return $supplier;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi cập nhật nhà cung cấp");
        }
    }
    public function deleteData($id){
        try {
            DB::beginTransaction();
            $supplier = $this->handleModel->find($id);
            if(!$supplier){
                return $this->returnError("Không tìm thấy nhà cung cấp");
            }
            $supplier->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi xoá nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi xoá nhà cung cấp");
        }
    }
}
