<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Repository;

class ProductRepository extends Repository implements ProductRepositoryInterface
{

    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function getAllDataProduct() {
        $products = $this->model::join("categories as c",'products.category_id', '=', 'c.id' )
        ->select(['products.id', 'products.name','products.category_id', 'products.price', 'products.image', 'products.description', 'c.name as category_name'])
        ->get();
        
        return $products;
    }

}
