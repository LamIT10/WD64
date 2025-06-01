<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
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
    {
       return inertia('admin/Supplier/Create');
    }
    public function store(SupplierRequest $request){
        $data = $request->validate();
        $supplier = $this->supplierRepository->createData($data);
        return $this->returnInertia($supplier, 'Create Supplier Success', 'admin.suppliers.index');
    }
    public function edit($id){
      dd(123);
    }
}
