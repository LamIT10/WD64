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
        return $this->handleModel->get();
    }
    public function createData(){
        return $this->returnError("lỗi");
    }
}
