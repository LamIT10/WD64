<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepository->getAll();

        return Inertia::render('admin/categories/List', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryRepository->parent();

        return Inertia::render('admin/categories/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoryRepository->store($data);

        return $this->returnInertia($category, 'Thêm thành công', 'admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryRepository->show($id);

        if (isset($category['status']) && $category['status'] == false) {
            return abort(404);
        }
        return Inertia::render('admin/categories/Show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = $this->categoryRepository->findById($id);
        $categories = $this->categoryRepository->parent();
        return Inertia::render('admin/categories/Edit', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditCategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $category = $this->categoryRepository->update($id, $data);
        return $this->returnInertia($category, 'Update thành công', 'admin.categories.edit', ['id' => $category->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->categoryRepository->destroy($id);

        return $this->returnInertia($data, 'Xóa thành công', 'admin.categories.index');
    }
}
