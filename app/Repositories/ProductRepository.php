<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{

    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function getAll()
    {
        return $this->model::with([
            'category',
            'supplier',
            'productVariants' => function ($query) {
                $query->with('attributeValues.attribute');
            },
            'unitConversions' => function ($query) {
                $query->with(['fromUnit', 'toUnit']);
            }
        ])->paginate(10);
    }

    public function findById($id)
    {
        return $this->model::with(['category'])->find($id);
    }
}
