<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierRepository;
    protected $productRepository;  
    public function __construct
    (
     SupplierRepository $supplierRepository,
     ProductRepository $productRepository
    )
    {
        $this->supplierRepository = $supplierRepository;
        $this->productRepository = $productRepository;
    }
    public function getList(Request $request){
        $suppliers = $this->supplierRepository->getList($request);
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
    public function destroy($id){
        $success = $this->supplierRepository->deleteData($id);
        return $this->returnInertia($success, 'Xóa nhà cung cấp thông', 'admin.suppliers.index');
    }

    public function getProducts($id)
    {
        $supplier = $this->supplierRepository->findById($id);
        if (!$supplier) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Không tìm thấy nhà cung cấp');
        }

        $products = $this->productRepository->getProductsBySupplier($id);

        return inertia('admin/Supplier/Products', [
            'supplier' => $supplier,
            'products' => $products
        ]);
    }

}
