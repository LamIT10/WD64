<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserRepository extends BaseRepository
{

    public function __construct(User $user)
    {
        $this->handleModel = $user;
    }

    public function allWithPaginate($filter=[],$limit = 10)    
    {
            $query=$this->handleModel->query();
            $query=$this->filterData($query, $filter);
             return $query->orderBy('created_at', 'desc')->paginate($limit);
    }

    public function createUser(array $data)
{
    try {
        DB::beginTransaction();

        $dataUser = [];
        $dataUser['name'] = $data['name'] ?? null;
        $dataUser['email'] = $data['email'] ?? null;
        $dataUser['address'] = $data['address'] ?? null;
        $dataUser['position'] = $data['position'] ?? null;
        $dataUser['phone'] = $data['phone'] ?? null;
        $dataUser['note'] = $data['note'] ?? null;
        $dataUser['facebook'] = $data['facebook'] ?? null;
        $dataUser['birthday'] = $data['birthday'] ?? null;
        $dataUser['gender'] = $data['gender'] ?? null;
        $dataUser['start_date'] = $data['start_date'] ?? null;
        $dataUser['identity_number'] = $data['identity_number'] ?? null;
        $dataUser['password'] = Hash::make($data['password']);

        if (!empty($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $dataUser['avatar'] = $this->handleUploadOneFile($data['avatar']);
        }

        // Tự sinh employee_code nếu không nhập
        if (empty($data['employee_code'])) {
            $lastUser = $this->handleModel->orderByDesc('id')->first();
            $nextNumber = $lastUser ? $lastUser->id + 1 : 1;
            $dataUser['employee_code'] = 'NV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $dataUser['employee_code'] = $data['employee_code'];
        }

        // Xóa dd để tiếp tục xử lý
        // dd($dataUser);

        $user = $this->handleModel->create($dataUser);

        if (!$user) {
            throw new \Exception('Có lỗi khi thêm nhân viên');
        }

        // Xử lý vai trò
        if (!empty($data['roles'])) {
            // Chuyển ID vai trò thành tên vai trò
            $roleNames = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
            if (empty($roleNames)) {
                throw new \Exception('Không tìm thấy vai trò hợp lệ');
            }
            $user->syncRoles($roleNames); // Gán vai trò bằng tên
        }

        DB::commit();
        return $user;
    } catch (\Throwable $th) {
        Log::error('Lỗi khi thêm mới người dùng: ' . $th->getMessage());
        DB::rollBack();
        return $this->returnError($th->getMessage());
    }
    }


    public function updateUser(int $id, array $data)
    {
        try {
            $user = $this->getById($id);
            if (!$user) {
                throw new \Exception('Không tìm thấy người dùng');
            }
            DB::beginTransaction();
             $dataUser = [];
            $dataUser['name'] = $data['name'] ?? null;
            $dataUser['email'] = $data['email'] ?? null;
            $dataUser['address'] = $data['address'] ?? null;
            $dataUser['position'] = $data['position'] ?? null;
            $dataUser['phone'] = $data['phone'] ?? null;
            $dataUser['note'] = $data['note'] ?? null;
            $dataUser['facebook'] = $data['facebook'] ?? null;
            $dataUser['birthday'] = $data['birthday'] ?? null;
            $dataUser['gender'] = $data['gender'] ?? null;
            $dataUser['start_date'] = $data['start_date'] ?? null;
            $dataUser['identity_number'] = $data['identity_number'] ?? null;
           
                        // Xử lý cập nhật ảnh
            if (!empty($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
                $dataUser['avatar'] = $this->handleUpdateFile($data['avatar'], $user->avatar);
            }
                 // Tự sinh employee_code nếu không nhập
            if (empty($data['employee_code'])) {
                $lastUser = $this->handleModel->orderByDesc('id')->first();
                $nextNumber = $lastUser ? $lastUser->id + 1 : 1;

                // Đảm bảo mã theo định dạng NV001, NV002, ...
                $dataUser['employee_code'] = 'NV' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $dataUser['employee_code'] = $data['employee_code'];
            }
                if ($data['role']) {
                $role = $user->syncRoles(($data['role']));
                if (!$role) {
                    throw new \Exception('Có lỗi khi thêm vai trò người dùng');
                }
            }else{
                $role = $user->syncRoles([]);
            }
            $user->update($dataUser);
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi cập nhật người dùng: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

   public function bulkUpdateStatus(array $userIds, string $status)
    {
        return $this->handleModel->whereIn('id', $userIds)->update(['status' => $status]);
    }

    public function bulkDelete(array $userIds)
    {
      try {
            DB::beginTransaction();
            $users = $this->handleModel->whereIn('id', $userIds)->get();
            if ($users->isEmpty()) {
                throw new \Exception('Không tìm thấy người dùng để xóa');
            }
            foreach ($users as $user) {
                $user->delete();
            }
            DB::commit();
            return $users;
        } catch (\Throwable $th) {
            Log::error("Xoá người dùng lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
     public function getDataRenderEdit(int $id)
    {
        $query = $this->handleModel::where("id", $id)->firstOrFail();
        $query['role'] = array_values($query->roles->pluck("id")->toArray());

        return $query;
    }
   
}
