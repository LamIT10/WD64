<?php

namespace App\Providers;

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
        // $this->app->bind( ProductServiceInterface::class, ProductService::class);
        // $this->app->bind( CategoryServiceInterface::class, CategoryService::class);
        // $this->app->bind( ProductRepositoryInterface::class, ProductRepository::class);
        // $this->app->bind( CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inertia::share([
        //     'success' => function () {
        //         return session('success');
        //     },
        //     'errors' => function () {
        //         return session('errors');
        //     },
        // ]);
        // Inertia::share([
        //     'auth' => function () {
        //         $user = Auth::user();
        //         return [
        //             'user' => $user ? [
        //                 'id' => $user->id,
        //                 'name' => $user->name,
        //                 'email' => $user->email,
        //                 'position' => $user->position ?? 'Nhân viên',
        //                 'avatar' => $user->avatar
        //                     ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
        //                     : null,
        //             ] : null,
        //         ];
        //     },
        // ]);
        Inertia::share([
    'auth' => function () {
        $user = Auth::user();

        if (!$user) return null;

        // Lấy dữ liệu liên quan
        $department = DB::table('departments')->where('id', $user->department_id)->value('name');
        $role = DB::table('roles')->where('id', $user->role_id)->value('name');

        // Lấy quyền của user
        $permissions = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $user->role_id)
            ->pluck('permissions.name')
            ->toArray();

        // Lấy activity logs gần đây
        $activities = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['action', 'description', 'created_at as time']);

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'position' => $user->position ?? 'Nhân viên',
                'avatar' => $user->avatar
                    ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                    : null,
                'department' => $department,
                'employee_code' => $user->employee_code,
                'role' => $role,
                'shift' => $user->shift,
                'birthday' => $user->birthday,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'last_login' => $user->last_login,
                'status' => $user->status,
                'activities' => $activities,
                'permissions' => $permissions,
            ]
        ];
    },
]);
    }
}
