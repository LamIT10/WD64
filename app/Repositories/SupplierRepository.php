<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierRepository extends BaseRepository
{
    public function __construct(Supplier $supplier)
    {
        $this->handleModel = $supplier;
    }

    public function getList()
    {
        $query = $this->handleModel->select(['*']);
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
            return $this->returnError("Lỗi khi tạo nhà cung cấp");
        }
    }
}
