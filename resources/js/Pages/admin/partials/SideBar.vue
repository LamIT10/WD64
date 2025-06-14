<template>
    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed inset-y-0 left-0 w-50 md:w-56 bg-white shadow border-r border-gray-200 sidebar lg:translate-x-0 sidebar-hidden">
        <!-- Logo & Close Button -->
        <div class="flex items-center justify-center gap-2 h-16 border-b border-gray-200 bg-white px-3">
            <div
                class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center">
                <i class="fas fa-warehouse text-white text-base"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-900">SUVAN</h1>
                <p class="text-[10px] text-gray-500">Giải pháp số cho kho hàng</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-4 px-3 pb-3 overflow-y-auto h-[calc(100%-4rem)]">
            <!-- Dashboard -->
            <div class="mb-3">
                <a href="index.html"
                    class="flex items-center p-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-chart-pie text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </div>

            <!-- Warehouse Operations -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('warehouse-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-warehouse text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Quản lý kho</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="warehouse-icon"></i>
                </button>

                <div id="warehouse-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <a href="import.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-arrow-down text-xs"></i>
                        </div>
                        <span class="text-xs">Nhập kho</span>
                    </a>
                    <a href="export.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-arrow-up text-xs"></i>
                        </div>
                        <span class="text-xs">Xuất kho</span>
                    </a>
                    <a href="inventory.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-clipboard-check text-xs"></i>
                        </div>
                        <span class="text-xs">Kiểm kê</span>
                    </a>
                    <a href="stock.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-boxes text-xs"></i>
                        </div>
                        <span class="text-xs">Tồn kho</span>
                    </a>
                </div>
            </div>
            <!-- User Management -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('user-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-red-100 text-red-600 group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-users sx-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Nhân viên</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="user-icon"></i>
                </button>

                <div id="user-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <Link :href="route('admin.users.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-user-tag text-xs"></i>
                    </div>
                    <span class="text-xs">Quản lý nhân viên</span>
                    </Link>
                </div>
            </div>
            <!-- Product Management -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('product-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-cube text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Sản phẩm</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="product-icon"></i>
                </button>

                <div id="product-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <a href="variants.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-layer-group text-xs"></i>
                        </div>
                        <span class="text-xs">Biến thể sản phẩm</span>
                    </a>
                    <Link :href="route('admin.categories.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                        <i class="fas fa-tags text-xs"></i>
                    </div>
                    <span class="text-xs">Danh mục</span>
                    </Link>
                </div>
            </div>

            <!-- Orders -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('order-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-orange-100 text-orange-600 group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-shopping-bag text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Đơn hàng</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="order-icon"></i>
                </button>

                <div id="order-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <a href="purchase-orders.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </div>
                        <span class="text-xs">Đơn hàng nhập</span>
                    </a>
                    <a href="sales-orders.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-truck text-xs"></i>
                        </div>
                        <span class="text-xs">Đơn hàng xuất</span>
                    </a>
                </div>
            </div>

            <!-- Suppliers -->
            <div class="mb-2">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('supplier-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-orange-100 text-orange-600 group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-shopping-bag text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Nhà cung cấp</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="supplier-icon"></i>
                </button>

                <div id="supplier-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <a href="purchase-orders.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </div>
                        <Link :href="route('admin.suppliers.index')" class="text-xs">Quản lý</Link>
                    </a>
                    <a href="sales-orders.html"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-truck text-xs"></i>
                        </div>
                        <span class="text-xs">Công nợ</span>
                    </a>
                </div>
            </div>

            <!-- Customers -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-purple-50 rounded-lg transition-all duration-200 group"
                    @click="toggleDropdown('customer-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-user-friends text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Khách hàng</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="customer-icon"></i>
                </button>

                <div id="customer-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <Link :href="route('admin.customers.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                        <i class="fas fa-address-book text-xs"></i>
                    </div>
                    <span class="text-xs">Quản lý khách hàng</span>
                    </Link>
                    <Link :href="route('admin.ranks.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <span class="text-xs">Quản lý hạng</span>
                    </Link>
                    <a href="#"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                            <i class="fas fa-file-invoice-dollar text-xs"></i>
                        </div>
                        <span class="text-xs">Công nợ khách hàng</span>
                    </a>
                </div>
            </div>
            <!-- Customers transaction -->
            <div class="mb-1">
                <button
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-purple-50 rounded-lg transition-all duration-200 group"
                    @click="toggleDropdown('customer-transaction-menu')">
                    <div class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-money-bill-wave text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Quản lý giao dịch</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="customer-transaction-icon"></i>
                </button>

                <div id="customer-transaction-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <Link :href="route('admin.customer-transaction.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                            <i class="fas fa-file-invoice-dollar text-xs"></i>
                        </div>
                        <span class="text-xs">Công nợ khách hàng</span>
                    </Link>
                    <Link :href="route('admin.supplier-transaction.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-purple-600 rounded-lg transition-all duration-200 group">
                        <div
                            class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-purple-100 transition-colors">
                            <i class="fas fa-file-invoice-dollar text-xs"></i>
                        </div>
                        <span class="text-xs">Công nợ nhà cung cấp</span>
                    </Link>
                   
               
                </div>
            </div>

            <!-- Role -->
            <div v-can="'admin.role.index'" class="mb-40">
                <button 
                    class="flex items-center w-full p-3 text-gray-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    onclick="toggleDropdown('admin-menu')">
                    <div
                        class="w-8 h-8 flex items-center justify-center mr-3 rounded-lg bg-red-100 text-red-600 group-hover:bg-red-200 transition-colors">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <span class="text-sm font-medium flex-1 text-left">Quản trị hệ thống</span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200 dropdown-icon"
                        id="admin-icon"></i>
                </button>

                <div id="admin-menu"
                    class="ml-5 mt-1 space-y-1 dropdown-menu dropdown-menu-hidden pl-2 border-l-2 border-gray-100">
                    <Link :href="route('admin.role.index')"
                        class="flex items-center p-2 text-gray-600 hover:text-indigo-600 rounded-lg transition-all duration-200 group">
                    <div
                        class="w-6 h-6 flex items-center justify-center mr-2 rounded-full bg-gray-100 group-hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-user-tag text-xs"></i>
                    </div>
                    <span class="text-xs">Quản lý vai trò</span>
                    </Link>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
function toggleDropdown(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId.replace("-menu", "-icon"));

    menu.classList.toggle("dropdown-menu-open");
    menu.classList.toggle("dropdown-menu-hidden");
    icon.classList.toggle("rotate-180");
}
</script>

<style lang="css" scoped>
.dropdown-menu {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

.dropdown-menu-open {
    max-height: 500px;
    opacity: 1;
}

.dropdown-menu-hidden {
    max-height: 0;
    opacity: 0;
}

.rotate-180 {
    transform: rotate(180deg);
}
</style>
