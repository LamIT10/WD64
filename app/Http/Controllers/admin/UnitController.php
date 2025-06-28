<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnitController extends Controller
{
    protected $unitRepository;

    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    public function index()
    {
        $units = $this->unitRepository->getAll();

        return Inertia::render('admin/units/List', [
            'units' => $units,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'symbol' => 'required|string|max:50|unique:units,symbol',
        ], [
            'name.required' => 'Tên đơn vị là bắt buộc.',
            'name.unique' => 'Tên đơn vị đã tồn tại.',
            'name.max' => 'Tên đơn vị không được vượt quá 255 ký tự.',
            'symbol.required' => 'Ký hiệu đơn vị là bắt buộc.',
            'symbol.unique' => 'Ký hiệu đơn vị đã tồn tại.',
            'symbol.max' => 'Ký hiệu không được vượt quá 50 ký tự.',
        ]);

        $unit = $this->unitRepository->store($data);

        if ($request->inertia()) {
            return redirect()->back()->with('data', $unit);
        }
        return $this->returnInertia($unit, 'Thêm thành công', 'admin.units.index');
    }

    public function destroy(string $id)
    {
        $result = $this->unitRepository->destroy($id);

        return $this->returnInertia($result, 'Xóa thành công', 'admin.units.index');
    }
}
