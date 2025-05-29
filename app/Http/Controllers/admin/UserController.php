<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
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
        $user = $this->userRepo->getById((int) $id);
        return Inertia::render('admin/users/Show', ['user' => $user]);
    }
    public function create()
    {
        return Inertia::render('admin/users/Create'); 
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $this->userRepo->create($data);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công');
    }

    public function edit($id)
    {
        $user = $this->userRepo->getById((int) $id);
        return Inertia::render('admin/users/Edit', [
            'user' => $user
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepo->update($id, $data);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công');
    }

    public function destroy($id)
    {
        $this->userRepo->delete($id);

        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công');
    }
}
