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
    public function getDataListRole($data)
    {
        try {

            $query = $this->handleModel->with('permissions')->select(["*"]);
            dd($query);
            if (!empty($data)) {
                $filters = [
                    'search' => [
                        'name' => $data['name'],
                    ]
                ];
                $query = $this->filterData($query, $filters);
                
                // $query = $query->with('role_has_permission', function ($q) {
                //     dd($q->where('permission_id','like','%'. "58" .'%'));
                // });
            }
            $query->orderBy('id', "desc");
            return $query->paginate(10);
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
            if(!$role){
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
          

            $oldRole = $this->findById($id);
            $newRole = [];
            $newRole ['name'] = $data['name'];
            $permission = $data['permissions'];

            $role = $this->update($id, $data);
            if(!$role){
                throw new \Exception('Có lỗi khi cập nhật');
            }

            $permission =  $oldRole->syncPermissions($permission);
            if(!$permission){
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
    public function handleDelete($id){
        try {
            $this->findById($id);
            $role = $this->delete($id);
            if(!$role){
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
