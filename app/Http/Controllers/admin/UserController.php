<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Auth\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userRepo;
    private $roleRepo;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepository)
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepository;
    }
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'active');

        $filters = [
            'absoluteFilter' => ['status' => $status],
        ];

        if (!empty(trim($search))) {
            $filters['search'] = [
                'name' => $search,
                'employee_code' => $search,
            ];
        }
        $users = $this->userRepo->allWithPaginate($filters);

        return Inertia::render('admin/users/Index', array_merge([
            'users' => $users,
            'status' => $status,
        ], !empty(trim($search)) ? ['search' => $search] : []));
    }



    public function show($id)
    {
        $user = $this->userRepo->getById((int)$id);
        $user['roles'] = $user->roles()->pluck('name')->toArray();
        return Inertia::render('admin/users/Show', [
            'user' => $user
        ]);
    }

    public function create()
    {
        $listRoles = $this->roleRepo->getAll();
        return Inertia::render('admin/users/Create', ['listRoles' => $listRoles]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar');
        }
        unset($data['password_confirmation']);
        $user = $this->userRepo->createUser($data);

        return $this->returnInertia($user, 'Tạo mới nhân viên thành công', 'admin.users.index');
    }

    public function edit($id)
    {
        $listRoles = $this->roleRepo->getAll();
        return Inertia::render('admin/users/Edit', [
            'user' => $this->userRepo->getDataRenderEdit((int)$id),
            'listRoles' => $listRoles
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        $user = $this->userRepo->updateUser((int)$id, $data);
        return $this->returnInertia($user, 'Cập nhật nhân viên thành công', 'admin.users.index');
    }

    public function bulkUpdateStatus(Request $request)
{
    $request->validate([
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'integer|exists:users,id',
        'status' => 'required|in:active,inactive',
    ]);

    $result = $this->userRepo->bulkUpdateStatus(
        $request->input('user_ids'),
        $request->input('status')
    );

    return $this->returnInertia(
        $result,
        'Cập nhật trạng thái nhân viên thành công',
        'admin.users.index',
        ['status' => $request->query('status', 'inactive')]
    );
}



    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $userIds = $request->input('user_ids');

        $this->userRepo->bulkDelete($userIds);

        return $this->returnInertia(null, 'Xóa nhân viên thành công', 'admin.users.index', [
            'status' => $request->query('status', 'inactive')
        ]);
    }
}
