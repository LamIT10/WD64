<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RoleRequest;
use App\Repositories\Auth\PermissionRepository;
use App\Repositories\Auth\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RoleController extends Controller
{
    private $permissionRepo;
    /**
     * @var  \App\Repositories\Auth\RoleRepository;
     */
    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepo)
    {
        $this->handleRepository = $roleRepository;
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = request()->all();
        $perPage = request()->get('perPage', 15);
        // lấy data cho ô tìm kiếm
        $permissions = $this->permissionRepo->getDropDownPermission();
        $listRoles = $this->handleRepository->getDataListRole($data, $perPage);
        return Inertia::render(
            "admin/Roles/Index",
            [
                'listRoles' => $listRoles,
                'permissions' => $permissions
            ]
        );
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->handleRepository->renderForm();
        return Inertia::render(
            "admin/Roles/CreateRole",
            [
                'permissions' => $data['permissions']
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $role = $this->handleRepository->handleCreate($data);

        return $this->returnInertia($role, "Thêm vai trò thành công", "admin.role.index");
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
        $role = $this->handleRepository->findById($id);
        if ($role->name == 'admin') {
            $data = [
                'status' => false,
                'message' => 'Không thể cập nhật quyền của admin'
            ];
            return $this->returnInertia($data, 'Không thể cập nhật quyền của admin', 'admin.role.index');
        }
        $role['permissions'] = $role->permissions()->pluck('id')->toArray();
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
        $role = $this->handleRepository->handleUpdate($id, $data);
        return $this->returnInertia($role, "Cập nhật vai trò thành công", "admin.role.edit", $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->handleRepository->handleDelete($id);
        return $this->returnInertia($role, "Xoá thành công", "admin.role.index");
    }
}
