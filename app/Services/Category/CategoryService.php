<?php

namespace App\Services\Category;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Services\Service;

class CategoryService extends Service implements CategoryServiceInterface
{
    private $category_repository;
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->category_repository = $repository;
    }
    public function getAllCategory()
    {
        return $this->category_repository->getAll();
    }
}
