<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{   
    /**
     * @var \Spatie\Permission\Models\Permission
     */
    public function __construct(Permission $permission)
    {
        $this->handleModel = $permission;
    }
    public function getDataPermission($data)
    {
        try {
            $query = $this->handleModel->select(['*']);
            if (!empty($data)) {
                $filters = [];
                $query = $this->filterData($query, $filters);
            }
            $query = $query->orderBy('id', 'desc');
            return $query->paginate(20);
        } catch (\Throwable $th) {
            Log::error("Lấy danh sách quyền lỗi, " . $th->getMessage());
            return false;
        }
    }
    public function getAll()
    {
        try {
            return $this->handleModel->select(['*'])->get();
        } catch (\Throwable $th) {
            Log::error("Lấy danh sách quyền lỗi, " . $th->getMessage());
            return [];
        }
    }
    public function handleCreate($data)
    {
        try {
            DB::beginTransaction();
            $this->handleModel::create($data);
            DB::commit();
        } catch (\Throwable $th) {
            Log::error("Tạo quyền lỗi, " . $th->getMessage());
            DB::rollBack();
        }
    }
    public function handleUpdate(int $id, array $data = [])
    {
        try {
            DB::beginTransaction();

            $permission = $this->getById($id);

            $oldRole = $this->getById($id);

            if (!empty($oldRole)) {
                return false;
            }
            $this->update($id, $data);
            $oldRole->syncPermissions($permission);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error("Sửa role lỗi, " . $th->getMessage());
            DB::rollBack();
        }
    }
}
