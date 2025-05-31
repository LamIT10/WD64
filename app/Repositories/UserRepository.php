<?php

namespace App\Repositories;

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

    public function allWithPaginate($limit = 10)
    {
        return $this->handleModel->orderBy('created_at', 'desc')->paginate($limit);
    }

    public function createUser(array $data)
    {
        try {
            DB::beginTransaction();
            $dataUser = [];
            $dataUser['name'] = $data['name'] ?? null;
            $dataUser['email'] = $data['email'] ?? null;
            $dataUser['status'] = $data['status'] ?? 'active';
            $dataUser['address'] = $data['address'] ?? null;
            $dataUser['position'] = $data['position'] ?? null;
            $dataUser['phone'] = $data['phone'] ?? null;
            $dataUser['note'] = $data['note'] ?? null;
            $dataUser['gender'] = $data['gender'] ?? null;
            $dataUser['password'] = Hash::make($data['password']);
            $user = $this->handleModel->create($dataUser);
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
            $dataUser['status'] = $data['status'] ?? 'active';
            $dataUser['address'] = $data['address'] ?? null;
            $dataUser['position'] = $data['position'] ?? null;
            $dataUser['phone'] = $data['phone'] ?? null;
            $dataUser['note'] = $data['note'] ?? null;
            $dataUser['gender'] = $data['gender'] ?? null;
            $user->update($dataUser);
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi cập nhật người dùng: ' . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    public function deleteUser(int $id)
    {
        try {
            DB::beginTransaction();
            $this->getById($id);
            $user = $this->delete($id);
            if (!$user) {
                throw new \Exception('Có lỗi khi xóa người dùng');
            }
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            Log::error("Xoá người dùng lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
