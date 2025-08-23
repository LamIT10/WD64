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
    // Share user info
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

            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'position'      => $user->position ?? 'Nhân viên',
                'avatar'        => $user->avatar
                    ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                    : null,
                'department'    => $department,
                'employee_code' => $user->employee_code ?? 'Chưa cập nhật',
                'shift'         => $user->shift ?? 'Chưa cập nhật',
                'birthday'      => $user->birthday ?? 'Chưa cập nhật',
                'gender'        => $user->gender ?? 'Chưa cập nhật',
                'phone'         => $user->phone ?? 'Chưa cập nhật',
                'last_login'    => $user->last_login ?? 'Chưa có',
                'status'        => $user->status ?? 'Chưa cập nhật',
                'activities'    => $activities,
            ];
        });

        // Share roles (trả về full object từng role)
        Inertia::share('auth.role', function () {
            $user = Auth::user();
            if (!$user) {
                return null;
            }

            $role = DB::table('roles')
                ->where('id', $user->role_id)
                ->select('id', 'name', 'guard_name')
                ->first();

            return $role;
        });

        // Share permissions (trả về full object từng permission)
        Inertia::share('auth.permissions', function () {
            $user = Auth::user();
            if (!$user) {
                return [];
            }

            return DB::table('roles')
                ->join('role_has_permissions', 'roles.role_id', '=', 'role_has_permissions.role_id')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->where('roles.model_id', $user->id)
                ->select(
                    'permissions.id',
                    'permissions.name',
                    'permissions.guard_name',
                    'permissions.created_at',
                    'permissions.updated_at'
                )
                ->distinct()
                ->get();
        });

        // Share models (nếu cần)
        Inertia::share('models', [
            'activity_log' => ActivityLog::class,
        ]);
    }
}
