<?php

namespace App\Repositories;

use App\Models\CustomerTransaction;
use App\Repositories\BaseRepository;

class CustomerTransactionRepository extends BaseRepository
{
    public function __construct(CustomerTransaction $model)
    {
        $this->handleModel = $model;
    }
    public function getData($data){
        $query = $this->handleModel->select(["*"]);

        $query = $query->orderBy("id","desc");
        return $query->paginate(10);


    }
}