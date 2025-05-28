<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
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
        try {
            $data = $request->validated();
            $category = $this->categoryRepository->findById($id);
            if (!$category) {
                return redirect()->back()
                    ->with('error', 'Đã xảy ra lỗi, không tồn tại id. Vui lòng thử lại.');
            }

            $this->categoryRepository->update($id, $data);
        } catch (\Throwable $th) {
            \Log::error('Lỗi khi tạo danh mục: ' . $th->getMessage());

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi, cập nhật không thành công. Vui lòng thử lại.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data['status'] = $this->categoryRepository->destroy($id);


        return $this->returnInertia($data, 'Xóa thành công', 'admin.categories.index');
    }
}
