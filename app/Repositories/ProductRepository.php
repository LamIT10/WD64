<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Unit;

class ProductRepository
{

    protected $handleModel;
    protected $repositoryCategory;
    public function __construct(Product $product, CategoryRepository $category)
    {
        $this->handleModel = $product;
        $this->repositoryCategory = $category;
    }

    public function getAll($perPage = 15)
    {
        return $this->handleModel::with([
            'category',
            'images',
            'productVariants' => function ($query) {
                $query->with([
                    'attributes',
                    'inventory',
                    'inventoryLocations.zone',
                    'supplierVariants'
                ]);
            },
            'unitConversions.fromUnit',
            'unitConversions.toUnit',
        ])->paginate($perPage);
    }

    public function findById($id)
    {
        return $this->handleModel::with(['category'])->find($id);
    }

    public function getCreateData()
    {
        $attributes = Attribute::with('values')->get();

        $attributeValues = [];
        foreach ($attributes as $attribute) {
            $attributeValues[$attribute->id] = $attribute->values;
        }

        return [
            'categories' => $this->repositoryCategory->getHierarchicalCategories(),
            'units' => Unit::all(),
            'attributes' => $attributes->makeHidden('values'), // tránh gửi lặp values trong attributes
            'attributeValues' => $attributeValues,
        ];
    }
}
