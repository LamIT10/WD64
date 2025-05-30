<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
    private $permissionRepository;
    public function __construct(Role $permission, PermissionRepository $permissionRepository)
    {

        /**
         * @var \App\Repositories\BaseRepository;
         */
        $this->handleModel = $permission;
        $this->permissionRepository = $permissionRepository;
    }
    public function getDataListRole($data, $perPage = 15)
    {
        try {

            $query = $this->handleModel->with('permissions')->select(["*"]);
            if (!empty($data)) {
                $filters = [
                    'search' => [
                        'name' => $data['name'],
                    ],
                ];
                $query = $this->filterData($query, $filters);
                $query = $query->whereHas('permissions', function ($q) use ($data) {
                    $q->where('id', $data['permission']);
                });
            }
            $query->orderBy('id', "desc");

            return $query->paginate($perPage);
        } catch (\Throwable $th) {
            Log::error("Lấy danh sách role lỗi, " . $th->getMessage());
        }
    }
    public function handleCreate(array $data = [])
    {
        try {
            DB::beginTransaction();
            $newRole = [];

            $newRole['name'] = $data['name'];
            $role = $this->handleModel::create($newRole);
            if (!empty($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }
            if (!$role) {
                throw new \Exception('Có lỗi khi thêm vai trò');
            }
            DB::commit();
            return $role;
        } catch (\Throwable $th) {
            Log::error("Thêm vai trò lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function handleUpdate(int $id, array $data = [])
    {
        try {
            DB::beginTransaction();

            $role = $this->findById($id);
            $newRole = [];
            $newRole['name'] = $data['name'];
            $permission = $data['permissions'];

            $role->update($newRole);
            if (!$role) {
                throw new \Exception('Có lỗi khi cập nhật');
            }

            $permission =  $role->syncPermissions($permission);
            if (!$permission) {
                throw new \Exception('Có lỗi khi cập nhật');
            }

            DB::commit();
            return $role;
        } catch (\Throwable $th) {
            Log::error("Cập nhật vai trò lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function handleDelete($id)
    {
        try {
            $role  =  $this->findById($id);
            $role->delete($id);
            if (!$role) {
                throw new \Exception("Có lỗi khi xoá vai trò");
            }
            DB::commit();
            return $role;
        } catch (\Throwable $th) {
            Log::error("Xoá vai trò lỗi, " . $th->getMessage());
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function renderForm()
    {
        try {
            $data = [];
            $data['permissions'] = $this->permissionRepository->getAll();
            return $data;
        } catch (\Throwable $th) {
            Log::error("Lấy quyền lỗi, " . $th->getMessage());
            return [];
        }
    }
}
