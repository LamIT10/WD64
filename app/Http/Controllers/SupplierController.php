<?php

namespace App\Http\Controllers;

use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierRepository;
    public function __construct
    (
     SupplierRepository $supplierRepository
    )
    {
        $this->supplierRepository = $supplierRepository;
    }
    public function getList(){
        $suppliers = $this->supplierRepository->getList();
        return inertia('admin/Supplier/List', [
            'suppliers' => $suppliers
        ]);
    }
    public function create()
    {$data = $this->supplierRepository->createData();
        return $this->returnInertia($data, 'Create Supplier Success', 'admin.suppliers.index');
       return inertia('admin/Supplier/Create');
    }
    public function store(){
        $data = $this->supplierRepository->createData();
        return $this->returnInertia($data, 'Create Supplier Success', 'admin.suppliers.index');
    }
}
