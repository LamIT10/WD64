<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AttributeRepository;
use App\Repositories\AttributeValueRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    protected $attributeRepository;
    protected $attributeValueRepository;

    public function __construct(AttributeRepository $attributeRepository, AttributeValueRepository $attributeValueRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
    }
    public function index () {
        $attributes = $this->attributeRepository->getAll();
        $attributeIds = $attributes->pluck('id')->toArray();
        $attributeValuesRaw = $this->attributeValueRepository->getByAttributeIds($attributeIds);

        $attributeValues = collect($attributeValuesRaw)
            ->groupBy('attribute_id')
            ->map(fn($group) => $group->values());

        return Inertia::render('admin/attributes/List', [
            'attributes' => $attributes,
            'attributeValues' => $attributeValues,
        ]);
    
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ], [
            'name.required' => 'Tên thuộc tính là bắt buộc.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
            'name.max' => 'Tên thuộc tính không được vượt quá 255 ký tự.',
        ]);
        $data = $request;
        $attribute = $this->attributeRepository->store($data);

        if ($request->inertia()) {
            return redirect()->back()->with('data', $attribute);
        }
        return $this->returnInertia($attribute, 'Thêm thành công', 'admin.attributes.index');
    }

    public function storeValue(Request $request)
    {
        $data = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'name' => 'required|string|max:255|unique:attribute_values,name',
        ], [
            'attribute_id.required' => 'Thiếu thuộc tính.',
            'attribute_id.exists' => 'Thuộc tính không hợp lệ.',
            'name.required' => 'Tên giá trị thuộc tính là bắt buộc.',
            'name.max' => 'Tên giá trị thuộc tính không được quá 255 ký tự.',
            'name.unique' => 'Tên thuộc tính đã tồn tại.',
        ]);
        $attributeValue = $this->attributeValueRepository->store($data);

        if ($request->inertia()) {
            return redirect()->back()->with('data', $attributeValue);
        }
        return $this->returnInertia($attributeValue, 'Thêm thành công', 'admin.attributes.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->attributeRepository->destroy($id);

        return $this->returnInertia($data, 'Xóa thành công', 'admin.attributes.index');
    }
    public function destroyValue(string $id)
    {
        $data = $this->attributeValueRepository->destroy($id);

        return $this->returnInertia($data, 'Xóa thành công', 'admin.attributes.index');
    }
}
