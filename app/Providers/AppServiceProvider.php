<?php

namespace App\Providers;

use App\Models\ActivityLog;
// use App\Repositories\Category\CategoryRepository;
// use App\Repositories\Category\CategoryRepositoryInterface;
// use App\Repositories\Product\ProductRepository;
// use App\Repositories\Product\ProductRepositoryInterface;
// use App\Services\Category\CategoryService;
// use App\Services\Category\CategoryServiceInterface;
// use App\Services\Product\ProductService;
// use App\Services\Product\ProductServiceInterface;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Nếu có binding Service/Repository thì để ở đây
        // $this->app->bind(ProductServiceInterface::class, ProductService::class);
        // $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        // $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        // $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share user
        Inertia::share('auth.user', function () {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $department = DB::table('departments')
            ->where('id', $user->department_id)
            ->value('name') ?? 'Chưa cập nhật';

        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['action', 'description', 'created_at as time']);

        // Lấy roles qua model_has_roles
        $roles = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id', 'roles.name', 'roles.guard_name')
            ->get()
            ->toArray();

        // Lấy permissions từ roles
        $permissions = DB::table('roles')
            ->join('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('roles.id', array_column($roles, 'id'))
            ->select('permissions.id', 'permissions.name', 'permissions.guard_name')
            ->distinct()
            ->get()
            ->toArray();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'position' => $user->position ?? 'Nhân viên',
            'avatar' => $user->avatar
                ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                : null,
            'department' => $department,
            'employee_code' => $user->employee_code ?? 'Chưa cập nhật',
            'shift' => $user->shift ?? 'Chưa cập nhật',
            'birthday' => $user->birthday ?? 'Chưa cập nhật',
            'gender' => $user->gender ?? 'Chưa cập nhật',
            'phone' => $user->phone ?? 'Chưa cập nhật',
            'last_login' => $user->last_login ?? 'Chưa có',
            'status' => $user->status ?? 'Chưa cập nhật',
            'activities' => $activities,
            'roles' => $roles,             // ✅ gói roles vào user
            'permissions' => $permissions, // ✅ gói permissions vào user
        ];
    });
    }
}
