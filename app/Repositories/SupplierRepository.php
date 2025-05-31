<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository extends BaseRepository
{
    public function __construct(Supplier $supplier)
    {
        $this->handleModel = $supplier;
    }

    public function getList()
    {
        $query = $this->handleModel->select(['*']);
        $query->paginate(2);
        return $query->paginate(2);;
    }
    public function createData(){
        return true;
        return $this->returnError("Lỗi khi thêm nhà cung cấp");
    }
}
