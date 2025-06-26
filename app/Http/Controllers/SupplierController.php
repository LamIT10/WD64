<?php

namespace App\Http\Controllers;

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
        $suppliers = $this->supplierRepository->getList($request);
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
        $query = Supplier::with([
            'variants' => function ($query) {
                $query->with(['product.category', 'attributes']);
            }
        ])->find($id);

        if (!$query) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Không tìm thấy nhà cung cấp');
        }
        // dd($query);
        // Nhóm biến thể theo product_id
        $groupedVariants = $query->variants->groupBy(function ($variant) {
            return $variant->product_id ?? 'Không rõ sản phẩm';
        });

        $listVariantByProduct = Supplier::with(['supplierVariants'])->find(($id)); 
        $listVariants = ProductVariant::with(["product", 'attributes', 'attributes.attribute'])->whereNotIn('Id', $listVariantByProduct->supplierVariants->pluck("product_variant_id"))->get();
        // dd($listVariants->toArray());
        // Chuẩn hóa dữ liệu cho frontend
        $products = $groupedVariants->map(function ($variants, $productId) {
            $product = $variants->first()->product;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category ? ['name' => $product->category->name] : null,
                'product_variants' => $variants->map(function ($variant) {
                    $att = [
                        'att_value' => "",
                        "att" => "",
                    ];

                    foreach ($variant->attributes as $key => $item) {
                        $att['att_value'] = $key != 0  ? $att['att_value'] . " - " . $item->name : $item->name;
                        $att['att'] = $key != 0  ?  $att['att'] . " - " . $item->attribute->name : $item->attribute->name;
                    }
                    return [
                        'id' => $variant->id,
                        'code' => $variant->code,
                        'barcode' => $variant->barcode,
                        'sale_price' => $variant->sale_price, // Lấy từ product_variants
                        'attributes' => $att
                    ];
                })->toArray()
            ];
        })->values()->toArray();
        return Inertia::render('admin/Supplier/Products', [
            'supplier' => [
                'id' => $query->id,
                'name' => $query->name
            ],
            'products' => $products,
            'listVariants' => $listVariants,
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

    public function storeSupplierProducts(Request $request, $supplierId)
    {
      $data = $request->all();
        try {
            DB::beginTransaction();
            $newObj = [];
            $newObj['cost_price'] = $data['cost_price'];
            $newObj['min_order_quantity'] = $data['min_order_quantity'];
            $newObj['product_variant_id'] = $data['id'];
            $newObj['supplier_id'] = $supplierId;
            $newObj = SupplierProductVariant::create($newObj);
            if(!$newObj){
                throw new Exception("Có lỗi khi thêm biên thể cho nhà cung cấp");
            }
            DB::commit();
            return redirect()->route('admin.suppliers.products', $supplierId)->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi thêm sản phẩm cho nhà cung cấp: ' . $th->getMessage());
            return back()->with('error', 'Lỗi khi thêm sản phẩm: ' . $th->getMessage());
        }
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
