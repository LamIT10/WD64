<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['name', 'code', 'stock_status']);

        $products = $this->productRepository->getAll($filters, 20);


        return Inertia::render('admin/products/ListProduct', [
            'products' => $products,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->productRepository->getCreateData();
        return Inertia::render('admin/products/CreateProduct', $data);
    }
    public function generateCode()
    {
        $code = app(ProductRepository::class)->generateProductCode();
        return response()->json(['code' => $code]);
    }

    public function generateVariantCode()
    {
        $code = app(ProductRepository::class)->generateVariantCode();
        return response()->json(['code' => $code]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = $this->productRepository->store($data);

        return $this->returnInertia($product, 'Thêm thành công', 'admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productRepository->show($id);

        if (isset($product['status']) && $product['status'] == false) {
            return abort(404);
        }
        return Inertia::render('admin/products/ShowProduct', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->productRepository->getEditData($id);

        if (isset($data['status']) && $data['status'] == false) {
            return abort(404);
        }

        return Inertia::render('admin/products/EditProduct', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $data = $request->validated();

        $product = $this->productRepository->update($id, $data);

        return $this->returnInertia($product, 'Cập nhật thành công', 'admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = $this->productRepository->destroy($id);
        return $this->returnInertia($success, 'Xóa sản phẩm thành công', 'admin.products.index');
    }
}
