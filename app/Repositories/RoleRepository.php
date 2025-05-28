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
            // dd($data);
            //  $page= (int)$data['page'] ?? 1;
               
            Paginator::currentPageResolver(function  ()  {
                return request()->get('page', 2);
            });
    
            $query = $this->handleModel->with('permissions')->select(["*"]);
            if (!empty($data)) {
                $filters = [
                    'search' => [
                        'name' => $data['name'],
                    ]
                ];
                $query = $this->filterData($query, $filters);
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
            $role = $this->handleModel::create($data);
            if (!empty($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }
            DB::commit();
        } catch (\Throwable $th) {
            Log::error("Thêm role lỗi, " . $th->getMessage());
            DB::rollBack();
        }
    }
    public function handleUpdate(int $id, array $data = [])
    {
        try {
            DB::beginTransaction();

            $permission = $data['permissions'];
            unset($data['permissions']);


            $oldRole = $this->getById($id);


            if (empty($oldRole)) {
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
    public function renderForm()
    {
        try {
            $data = [];
            $data['permissions'] = $this->permissionRepository->getAll();
            return $data;
        } catch (\Throwable $th) {
            Log::error("Lấy quyền lỗi, " . $th->getMessage());
        }
    }
}
