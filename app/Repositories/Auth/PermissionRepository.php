<?php

namespace App\Repositories\Auth;

use App\Repositories\BaseRepository;
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
    public function getDateRenderCreateRole()
    {
        try {
            $group_permission = $this->handleModel::select("group_permission", "group_description")
            ->distinct()
            ->get()
            ->toArray();
            $dataPermision = [];

            foreach ($group_permission as $key => $value) {
                $dataPermision[] = [
                    'group_permission' => $value['group_permission'],
                    'group_description' => $value['group_description'],
                    'permission' => $this->handleModel::where("group_permission", $value)->get(["id","name", "description"])->toArray(),
                    
                ];
            }

            return $dataPermision;
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

            $newPermission = [];
            $newPermission["name"] = $data["name"] ?? "";
            $newPermission["description"] = $data["description"] ?? "";

            $permission = $permission->update($newPermission);
            if (!$permission) {
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
            $permission = $this->findById($id);
            $permission->delete($id);
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
