<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\RoleRequest;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * @var  \App\Repositories\RoleRepository;
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->handleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = request()->all();
        $listRoles = $this->handleRepository->getDataListRole($data);

        return Inertia::render("admin/Roles/Index", ['listRoles' => $listRoles]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->handleRepository->renderForm();
        return Inertia::render("admin/Roles/CreateRole", ['permissions' => $data['permissions']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $this->handleRepository->handleCreate($data);
        return redirect()->route("admin.role.index");
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
        $data = $this->handleRepository->renderForm();
        $role = $this->handleRepository->getById($id);

        $role['permissions'] = $role->permissions()->pluck('id')->toArray();
        if (!$role) {
            return redirect()->route("admin.permission.index")->with("success", "");
        }
        return Inertia::render("admin/Roles/EditRole", ['role' => $role, 'permissions' => $data['permissions']]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
        $message =  [
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.min' => 'Tên phải có ít nhất :min ký tự.',
            'name.max' => 'Tên không được vượt quá :max ký tự.',
            'name.unique' => 'Tên quyền này đã tồn tại.',
            'permissions.required' => 'Vui lòng chọn ít nhất 1 quyền.',
            'permissions.array' => 'Danh sách quyền không hợp lệ.',
            'permissions.min' => 'Phải chọn ít nhất 1 quyền.',
        ];
        $rules =  [
            'permissions' => 'required|array|min:1',
            'name' => 'required|min:3|max:50|unique:roles,name,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $isCheck = $this->handleRepository->handleUpdate($id, $data);
        if (!$isCheck) {
            return  redirect()->back()->with('error', '');
        }
        return redirect()->route("admin.role.index")->with("success", "");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->handleRepository->getById($id)) {
            return redirect()->route("admin.role.index")->with("success", "");
        }
        $role = $this->handleRepository->delete($id);
        return redirect()->route("admin.role.index")->with("success", "");
    }
}
