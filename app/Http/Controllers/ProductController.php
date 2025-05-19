<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    private $product_service;
    private $category_service;
    public function __construct(
        ProductServiceInterface $service,
        CategoryServiceInterface $category_service_interface,
    ) {
        $this->product_service = $service;
        $this->category_service = $category_service_interface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products =  $this->product_service->getDataProduct();
        $categories = $this->category_service->getAllCategory();

        return Inertia::render("Home", ['products' => $products, 'categories' => $categories]);
    }


    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $this->product_service->uploadOneFile($data['file']);
        $this->product_service->create($data);

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $oldProduct = $this->product_service->getById($id);
        $data = $request->validated();
       
        $data['image'] = $this->product_service->handleUpdateFile($data['file'], $oldProduct['image']);
        unset($data['file']);

        $this->product_service->update($id, $data);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $oldProduct = $this->product_service->getByid($id);
        if($oldProduct['image'] !== null && Storage::disk('public')->exists($oldProduct['image'])){
            $this->product_service->deleteFile($oldProduct['image']);
        }
        $this->product_service->delete($id);
    }
}


 
