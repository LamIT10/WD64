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
        $data = $request->validated();
        $supplier = $this->supplierRepository->createData($data);
        return $this->returnInertia($supplier, 'Tạo mới nhà cung cấp thành công', 'admin.suppliers.index');
    }
    public function edit($id){
        $supplier = $this->supplierRepository->findById($id);
        return inertia('admin/Supplier/Edit', [
            'supplier' => $supplier
        ]);
    }
    public function update(SupplierRequest $request, $id){
        $data = $request->validated();
        $supplier = $this->supplierRepository->updateData($id, $data);
        return $this->returnInertia($supplier, 'Cập nhật nhà cung cấp thành công', 'admin.suppliers.edit', [$id]);
    }
}
