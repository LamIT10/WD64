<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permissions\PermissionRequest;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PermissionController extends Controller
{
    /**
     * @var \App\Repositories\PermissionRepository;
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->handleRepository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = request()->all();
        $permissions = $this->handleRepository->getDataPermission($data);
        return Inertia::render("admin/Permissions/Index", ["permissions" => $permissions]);
    }
    // /**
    //  * Show the form for creating a new resource.
    //  */
    public function create()
    {
        return Inertia::render("admin/Permissions/CreatePermission");
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $this->handleRepository->handleCreate($request->all());
        return redirect()->route("admin.permission.index")->with("success", "");
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = $this->handleRepository->getById($id);
        if (!$permission) {
            return redirect()->route("admin.permission.index")->with("success", "");
        }
        return Inertia::render("admin/Permissions/EditPermission", ["permission" => $permission]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $message =  [
            'name.required' => 'Tên quyền hạng là bắt buộc.',
            'name.min'      => 'Tên quyền hạng phải có ít nhất :min ký tự.',
            'name.max'      => 'Tên quyền hạng không được vượt quá :max ký tự.',
            'name.unique'   => 'Tên quyền hạng này đã tồn tại.',
            'description.max'      => 'Mô tả được vượt quá :max ký tự.',
        ];
        $rules =  [
            "name" => "required|min:1|max:50|unique:permissions,name," . $id,
            "description" => "max:255"
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }
        $isTrue = $this->handleRepository->handleUpdate($id, $request->all());
        if(!$isTrue){
            return redirect()->back()->with("success","");
        }
        return redirect()->route("admin.permission.index")->with("success","");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->handleRepository->getById($id)) {
            return redirect()->route("admin.permission.index")->with("success", "");
        }
        $this->handleRepository->delete($id);
        return redirect()->route("admin.permission.index")->with("success", "");
    }
}
