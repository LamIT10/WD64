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
            $newPermission = [];
            $newPermission['name'] = $data['name'] ?? "";
            $newPermission['description'] = $data['description'] ?? "";

            $permission = $this->handleModel::create($newPermission);
            if (!$permission) {
                throw new \Exception("Lỗi khi tạo quyền");
            }
            DB::commit();
            return $permission;
        } catch (\Throwable $th) {
            Log::error("Tạo quyền lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function handleUpdate(int $id, array $data = [])
    {
        try {
            DB::beginTransaction();

            $permission = $this->handleModel->findOrFail($id);

            // $oldRole = $this->handleModel->findOrFail($id);
            $newPermission = [];
            $newPermission["name"] = $data["name"] ?? "";
            $newPermission["description"] = $data["description"] ?? "";

            $permission = $this->update($id, $newPermission);
            if ($permission) {
                throw new \Exception("Có lỗi khi cập nhật");
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            Log::error("Sửa role lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function handleDelete($id)
    {
        try {
            $this->findById($id);
            $permission = $this->delete($id);
            if (!$permission) {
                throw new \Exception("Có lỗi khi xoá quyền");
            }
            DB::commit();
            return $permission;
        } catch (\Throwable $th) {
            Log::error("Xoá quyền lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
}
