<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->allWithPaginate();
        return Inertia::render('admin/users/Index', [
            'users' => $users
        ]);
    }

    public function show($id)
    {
        return Inertia::render('admin/users/Show', [
            'user' => $this->userRepo->getById((int)$id)
        ]);
    }

    public function create()
    {
    return Inertia::render('admin/users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        unset($data['password_confirmation']);
        $user = $this->userRepo->createUser($data);
        return $this->returnInertia($user, 'Tạo mới người dùng thành công', 'admin.users.index');
    }

    public function edit($id)
    {
        return Inertia::render('admin/users/Edit', [
            'user' => $this->userRepo->getById((int)$id)
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        $user = $this->userRepo->updateUser((int)$id, $data);
        return $this->returnInertia($user, 'Cập nhật người dùng thành công', 'admin.users.index');
    }

    public function destroy($id)
    {
        $user = $this->userRepo->deleteUser((int)$id);

        return $this->returnInertia($user, 'Xóa người dùng thành công', 'admin.users.index');
    }
}
