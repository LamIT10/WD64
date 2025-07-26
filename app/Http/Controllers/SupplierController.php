<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierProductRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\SupplierProductVariant;
use App\Repositories\ProductRepository;
use App\Repositories\SupplierRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected $supplierRepository;
    protected $productRepository;
    public function __construct(
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository
    ) {
        $this->supplierRepository = $supplierRepository;
        $this->productRepository = $productRepository;
    }
    public function getList(Request $request)
    {
        $suppliers = $this->supplierRepository->getList($request->all());
        return inertia('admin/Supplier/List', [
            'suppliers' => $suppliers
        ]);
    }
    public function create()
    {
        return inertia('admin/Supplier/Create');
    }
    public function store(SupplierRequest $request)
    {
        $data = $request->validated();
        $supplier = $this->supplierRepository->createData($data);
        return $this->returnInertia($supplier, 'Tạo mới nhà cung cấp thành công', 'admin.suppliers.index');
    }
    public function edit($id)
    {
        $supplier = $this->supplierRepository->findById($id);
        return inertia('admin/Supplier/Edit', [
            'supplier' => $supplier
        ]);
    }
    public function update(SupplierRequest $request, $id)
    {
        $data = $request->validated();
        $supplier = $this->supplierRepository->updateData($id, $data);
        return $this->returnInertia($supplier, 'Cập nhật nhà cung cấp thành công', 'admin.suppliers.edit', [$id]);
    }
    public function destroy($id)
    {
        $success = $this->supplierRepository->deleteData($id);
        return $this->returnInertia($success, 'Xóa nhà cung cấp thông', 'admin.suppliers.index');
    }

    public function getProducts($id)
    {

        $data = $this->supplierRepository->getProductBySupplierId($id);
        if (isset($data['status']) && $data['status'] == false) {

            return $this->returnInertia($data, '', '');
        }
        return Inertia::render('admin/Supplier/Products', [
            'supplier' => $data['supplier'],
            'products' => $data['products'],
            'listVariants' => $data['listVariants'],
        ]);
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('search');
        $products = $this->productRepository->search($search);
        return response()->json(['products' => $products]);
    }

    public function getProductVariants($productId, $supplierId)
    {
        $variants = $this->productRepository->getAvailableVariants($productId, $supplierId);
        return response()->json(['variants' => $variants]);
    }

    public function storeSupplierProducts(StoreSupplierProductRequest $request, $supplierId)
    {
        $data = $request->validated();
        $data['supplier_id'] = $supplierId;
        $supplierProduct = $this->supplierRepository->handleCreateSupplierProduct($data);
        return $this->returnInertia($supplierProduct, "Thêm biến thể cho nhà cung cấp thành công", 'admin.suppliers.products', ['id' => $supplierId]);
    }
    public function destroySupplierProducts($id, $variantId)
    {
        //   $data = $$variantId->validated();
        $data = [];
        $data['id'] = $id;
        $data['variantId'] = $variantId;
        $supplierProduct = $this->supplierRepository->handleDeleteProductVariant($data);
        return $this->returnInertia($supplierProduct, "Xoá biến thể của nhà cung cấp thành công", 'admin.suppliers.products',['id' => $data['id']]);
    }
    public function getVariantsByProductId($supplierId, $productId)
    {
        $variants = $this->productRepository->getProductVariantsById($productId);
        // Kiểm tra xem sản phẩm có được liên kết với nhà cung cấp không
        $variantIds = collect($variants)->pluck('variant_id')->unique()->toArray();
        $isLinked = SupplierProductVariant::where('supplier_id', $supplierId)
            ->whereIn('product_variant_id', $variantIds)
            ->exists();


        if (!$isLinked) {
            return response()->json(['variants' => []], 200);
        }

        return response()->json(['variants' => $variants]);
    }
}
