<?php
 namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Service;

 class ProductService extends Service implements ProductServiceInterface{


    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getDataProduct()
    {
       return  $this->repository->getAllDataProduct();
    }

 }