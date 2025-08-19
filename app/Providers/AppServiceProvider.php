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
        Inertia::share([
            'auth' => function () {
                $user = Auth::user();
                return [
                    'user' => $user ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'position' => $user->position ?? 'Nhân viên',
                        'avatar' => $user->avatar
                            ? asset('storage/' . $user->avatar) . '?v=' . $user->updated_at->timestamp
                            : null,
                    ] : null,
                ];
            },
        ]);
    }
}
