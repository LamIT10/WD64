<?php

use App\Constant\PermissionConstant;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\InventoryAuditController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\Admin\SupplierProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\GoodReceiptController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\admin\CustomerTransactionController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Admin\SuggestController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProxyController;

use App\Http\Controllers\RankController;
use App\Models\InventoryAudit;
use App\Http\Controllers\Admin\SupplierTransactionController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\WarehouseZoneController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use App\Http\Controllers\SaleOrderController;


Route::prefix('admin')->as('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('inventory-audit', InventoryAuditController::class);
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/statistics', [InventoryController::class, 'statistics'])->name('inventory.statistics');
    Route::get('inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
    Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    // Routes cho Categories (Danh mục) // Tao  xử lí cònlic
    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Routes cho Products (Sản phẩm)
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/get-inactive', [ProductController::class, 'getInactive'])->name('get_inactive');
        Route::post('/restore/{id}', [ProductController::class, 'restore'])->name('restore');
        Route::get('/print-barcode', [ProductController::class, 'printBarcode'])->name('print_barcode');
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

        Route::get('/search', [SupplierController::class, 'searchProducts'])->name('search');
        Route::get('/{productId}/variants/{supplierId}', [SupplierController::class, 'getProductVariants'])->name('variants');
    });

    Route::prefix('attributes')->as('attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::post('/', [AttributeController::class, 'store'])->name('store');
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('attribute-values')->as('attribute-values.')->group(function () {
        Route::post('/', [AttributeController::class, 'storeValue'])->name('store');
        Route::delete('/{id}', [AttributeController::class, 'destroyValue'])->name('destroy');
    });
    Route::prefix('units')->as('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::delete('/{id}', [UnitController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('warehouse-zones')->as('warehouse-zones.')->group(function () {
        Route::get('/', [WarehouseZoneController::class, 'index'])->name('index');
        Route::post('/', [WarehouseZoneController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [WarehouseZoneController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WarehouseZoneController::class, 'update'])->name('update');
        Route::delete('/{id}', [WarehouseZoneController::class, 'destroy'])->name('destroy');
    });

    Route::group([
        'prefix' => 'customers',
        'as' => 'customers.'
    ], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::patch('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulk-delete');
        Route::get('customers/import', [CustomerController::class, 'import'])->name('customers.import');
        Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::get('/{customer}/debt-orders', [CustomerTransactionController::class, 'debtOrdersByCustomer'])
            ->name('debt-orders');
    });


    Route::prefix('ranks')->as('ranks.')->group(function () {
        Route::get('/', [RankController::class, 'index'])->name('index');        // Danh sách rank của customer
        Route::get('/create', [RankController::class, 'create'])->name('create'); // Form tạo mới
        Route::post('/', [RankController::class, 'store'])->name('store');        // Lưu rank mới
        Route::get('/{rank}', [RankController::class, 'show'])->name('show');     // Chi tiết rank
        Route::get('/{rank}/edit', [RankController::class, 'edit'])->name('edit'); // Form sửa
        Route::patch('/{rank}', [RankController::class, 'update'])->name('update');  // Cập nhật rank
        Route::patch('/{rank}/destroy', [RankController::class, 'destroy'])->name('destroy'); // Xóa rank
    });

    Route::prefix('permission')->as('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    });



    // Route::prefix('permission')->as('permission.')->group(function () {

    //     Route::get('/', [PermissionController::class, 'index'])->middleware('has_permission:' . PermissionConstant::PERMISSION_INDEX)->name('index');
    //     Route::get('/create', [PermissionController::class, 'create'])->middleware('has_permission:'. PermissionConstant::PERMISSION_CREATE)->name('create');
    //     Route::post('/', [PermissionController::class, 'store'])->name('store');
    //     Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
    //     Route::patch('/{id}', [PermissionController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    //     Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    // });

    Route::prefix('role')->as('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');

        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('has_permission:' . PermissionConstant::ROLE_INDEX);
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('has_permission:' . PermissionConstant::ROLE_EDIT);
    });


    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::post('store', [SupplierController::class, 'store'])->name('store');
        Route::patch('{id}/update', [SupplierController::class, 'update'])->name('update');
        Route::delete('{id}', [SupplierController::class, 'destroy'])->name('destroy');

        Route::get('{id}/products', [SupplierController::class, 'getProducts'])->name('products');
        Route::post('/{supplierId}/products', [SupplierController::class, 'storeSupplierProducts'])->name('products.store');
        Route::delete('{id}/products/{variantId}/destroy', [SupplierController::class, 'destroySupplierProducts'])->name('products.destroy');
        Route::get('/{supplierId}/products/{productId}/variants', [SupplierController::class, 'getVariantsByProductId'])->name('variants');
    });

    Route::group(['prefix' => 'customer-transaction', 'as' => 'customer-transaction.'], function () {
        Route::post('/{order}/add', [CustomerTransactionController::class, 'addTransaction'])->name('add');
        Route::post('/{order}/update-due-date', [CustomerTransactionController::class, 'updateDueDate'])->name('updateDueDate');
        Route::get('/{order}/show', [CustomerTransactionController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'supplier-transaction', 'as' => 'supplier-transaction.'], function () {
        Route::get('{id}/show', [SupplierTransactionController::class, 'show'])->name('show')->middleware('has_permission:' . PermissionConstant::SUPPLIER_TRANSACTION_SHOW);
        Route::patch('{id}/update', [SupplierTransactionController::class, 'update'])->name('update')->middleware('has_permission:' . PermissionConstant::SUPPLIER_TRANSACTION_UPDATE_CREDIT_DUE_DATE);
        Route::patch('{id}/update-payment', [SupplierTransactionController::class, 'updatePayment'])->name('updatePayment')->middleware('has_permission:' . PermissionConstant::SUPPLIER_TRANSACTION_UPDATE_CREDIT_PAID_AMOUNT);
    });
    Route::group(['prefix' => 'purchases', 'as' => 'purchases.'], function () {
        Route::get('/', [PurchaseOrderController::class, 'getList'])->name('index');
        Route::get('create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('{id}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
        Route::get('{id}/get-variants', [PurchaseOrderController::class, 'getVariants'])->name('getVariants');
        Route::get('{id}/get-supplier-and-unit', [PurchaseOrderController::class, 'getSupplierAndUnit'])->name('getSupplierAndUnit');
        Route::post('store', [PurchaseOrderController::class, 'store'])->name('store');
        Route::post('{id}/update', [PurchaseOrderController::class, 'update'])->name('update');
        Route::get('{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::post('{id}/cancel', [PurchaseOrderController::class, 'cancel'])->name('cancel');
    });
    Route::group(['prefix' => 'receiving', 'as' => 'receiving.'], function () {
        Route::get('/', [GoodReceiptController::class, 'getList'])->name('index');
        Route::get('{id}/create', [GoodReceiptController::class, 'createFromPurchaseOrder'])->name('create');
        Route::post('{id}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
        Route::get('{id}/get-variants', [PurchaseOrderController::class, 'getVariants'])->name('getVariants');
        Route::get('{id}/get-supplier-and-unit', [PurchaseOrderController::class, 'getSupplierAndUnit'])->name('getSupplierAndUnit');
        Route::post('store', [GoodReceiptController::class, 'store'])->name('store');
    });
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
          Route::get('export', [ReportExportController::class, 'export'])->name('export');
    });

  



    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => ['auth'] // nếu có middleware thì thêm vào đây
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/update-status', action: [UserController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
    });
    Route::prefix('sale-orders')->as('sale-orders.')->group(function () {
        Route::get('/', [SaleOrderController::class, 'index'])->name('index');
        Route::get('/create', [SaleOrderController::class, 'create'])->name('create');
        Route::get('/search/products', [SaleOrderController::class, 'searchProductJson'])->name('products.search');
        Route::get('/variants/{productId}', [SaleOrderController::class, 'getAllVariantsJson'])->name('variants.all');
        Route::get('/unit-conversions/{productId}', [SaleOrderController::class, 'getAllUnitJson'])->name('unit.all');
        Route::get('/search/customers', [SaleOrderController::class, 'searchCustomerJson'])->name('customer.search');
        Route::get('/inventory/{productVariantId}', [SaleOrderController::class, 'getInventoryQuantity'])->name('inventory');
        Route::post('/{id}/reject', [SaleOrderController::class, 'rejectSaleOrder'])->name('reject');
        Route::post('/{id}/approve', [SaleOrderController::class, 'approve'])->name('approve');
        Route::post('/store', [SaleOrderController::class, 'store'])->name('store');
        Route::get('export', [SaleOrderController::class, 'export'])->name('export');
        Route::post('{id}/complete', [SaleOrderController::class, 'complete'])->name('complete');
        Route::post('/{id}/generate-qr', [SaleOrderController::class, 'generateQR'])->name('generate-qr');
        Route::get('/find-page', [SaleOrderController::class, 'findPage'])->name('find-page');
    });
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/show-all', [NotificationController::class, 'showAll'])->name('notifications.show-all');
    Route::prefix('reports')->as('reports.')->group(function () {
        Route::get('suggest', [SuggestController::class, 'suggest'])->name('suggest');
        Route::get('revenue', [SuggestController::class, 'revenue'])->name('revenue');
      

    });
});



// API địa chỉ
Route::get('/proxy/provinces', [ProxyController::class, 'getProvinces']);
Route::get('/proxy/districts/{provinceId}', [ProxyController::class, 'getDistricts']);
Route::get('/proxy/wards/{districtId}', [ProxyController::class, 'getWards']);
Route::get('/provinces', [LocationController::class, 'getProvinces']);
Route::get('/wards/{province_code}', [LocationController::class, 'getWardsByProvince']);
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/', function () {
    return Inertia::render('Dashboard');
});
// Authentication routes
Route::middleware('check_logined')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
// Route Profile
Route::get('profile', function () {
    return Inertia::render('Auth/Profile');
})->name('profile');
