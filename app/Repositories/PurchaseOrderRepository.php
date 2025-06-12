<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;

class PurchaseOrderRepository extends BaseRepository
{
    protected $productRepository;
    public function __construct(PurchaseOrder $model)
    {
        $this->handleModel = $model;
    }
    public function getDataForcreate(){
        $data = [];
        $data['products'] = Product::all();
        return $data;
    }
    public function getVariants($idProduct){
        $variants = Product::with([
            'productVariants' => function ($query) {
                $query->select(['*']);
            },
            'productVariants.attributes' => function ($query) {
                $query->select(['*']);
            },
            ])
            ->select('id', 'name')
            ->find($idProduct);
        return $variants;
    }
    public function getSupplierAndUnit($idVariant){
        $data = ProductVariant::with([
            'product' => function ($query) {
                $query->select(['*']);
            },
            'product.unitConversions' => function ($query) {
                $query->select(['*']);
            },
            'product.unitConversions.unit' => function ($query) {
                $query->select(['*']);
            },
            'product.unitConversions.unit.attribute' => function ($query) {
                $query->select(['*']);
            },
            ])
            ->select('id', 'name')
            ->find($idVariant);
        return $data;
    }
}