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
        Inertia::share([
            'auth' => function () {
                $user = Auth::user();

                if (!$user) {
                    return null;
                }

                // Lấy tên phòng ban
                $department = DB::table('departments')
                    ->where('id', $user->department_id)
                    ->value('name') ?? 'Chưa cập nhật';

                // Lấy roles thủ công
                $roles = DB::table('role_user')
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->where('role_user.user_id', $user->id)
                    ->pluck('roles.name')
                    ->toArray();

                // Lấy permissions từ roles thủ công
                $permissions = DB::table('role_user')
                    ->join('role_permissions', 'role_user.role_id', '=', 'role_permissions.role_id')
                    ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_user.user_id', $user->id)
                    ->pluck('permissions.name')
                    ->unique()
                    ->values()
                    ->toArray();

                // Lấy 10 activity logs gần đây
                $activities = ActivityLog::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(['action', 'description', 'created_at as time']);

                return [
                    'user' => [
                        'id'            => $user->id,
                        'name'          => $user->name,
                        'email'         => $user->email,
                        'position'      => $user->position ?? 'Nhân viên',
                        'avatar'        => $user->avatar
                            ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                            : null,
                        'department'    => $department,
                        'employee_code' => $user->employee_code ?? 'Chưa cập nhật',
                        'roles'         => $roles, // mảng roles
                        'role'          => count($roles) ? implode(', ', $roles) : 'Chưa có vai trò',
                        'shift'         => $user->shift ?? 'Chưa cập nhật',
                        'birthday'      => $user->birthday ?? 'Chưa cập nhật',
                        'gender'        => $user->gender ?? 'Chưa cập nhật',
                        'phone'         => $user->phone ?? 'Chưa cập nhật',
                        'last_login'    => $user->last_login ?? 'Chưa có',
                        'status'        => $user->status ?? 'Chưa cập nhật',
                        'activities'    => $activities,
                        'permissions'   => $permissions,
                    ]
                ];
            },
        ]);
    }
}
