<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200"
            >
                <h5 class="text-lg text-purple-700 font-semibold">
                    Danh sách Nhà cung cấp
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Tìm kiếm nhà cung cấp..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        />
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"
                        ></i>
                    </div>
                    <Link
                        :href="route('admin.suppliers.create')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2"
                    >
                        <i class="fas fa-plus"></i>
                        <span>Thêm mới</span>
                    </Link>
                </div>
            </div>

            <!-- Supplier Table -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Tên NCC
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Mã số thuế
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Địa chỉ
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Người liên hệ
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Trạng thái
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                ></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Supplier 1 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-md flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-building text-purple-600"
                                            ></i>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                Công ty TNHH ABC
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                abc@example.com
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    0101234567
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    Số 1, đường ABC, Hà Nội
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Nguyễn Văn A
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        0987654321
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                    >
                                        Hoạt động
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                                        >
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <!-- Dropdown menu - Hidden by default -->
                                        <div
                                            class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                        >
                                            <div class="py-1">
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-edit mr-2 text-purple-600"
                                                    ></i>
                                                    Sửa
                                                </a>
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-trash-alt mr-2 text-red-500"
                                                    ></i>
                                                    Xóa
                                                </a>
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-ban mr-2 text-yellow-500"
                                                    ></i>
                                                    Tạm ngừng
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Supplier 2 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-md flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-warehouse text-blue-600"
                                            ></i>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                Công ty CP XYZ
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                xyz@example.com
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    0107654321
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    Số 10, đường XYZ, TP.HCM
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Trần Thị B
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        0912345678
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800"
                                    >
                                        Tạm ngừng
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <div
                                        class="relative inline-block text-left"
                                    >
                                        <button
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none"
                                        >
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>

                                        <!-- Dropdown menu - Hidden by default -->
                                        <div
                                            class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                        >
                                            <div class="py-1">
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-edit mr-2 text-purple-600"
                                                    ></i>
                                                    Sửa
                                                </a>
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-trash-alt mr-2 text-red-500"
                                                    ></i>
                                                    Xóa
                                                </a>
                                                <a
                                                    href="#"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                >
                                                    <i
                                                        class="fas fa-check-circle mr-2 text-green-500"
                                                    ></i>
                                                    Kích hoạt
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
                >
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">1</span> đến
                        <span class="font-medium">2</span> của
                        <span class="font-medium">10</span> kết quả
                    </div>
                    <div class="flex space-x-1">
                        <button
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 disabled:opacity-50"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button
                            class="px-3 py-1 bg-purple-600 text-white rounded-md"
                        >
                            1
                        </button>
                        <button
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50"
                        >
                            2
                        </button>
                        <button
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";

const props = defineProps({
    suppliers: {
        type: Array,
        default: () => [],
    },
});
</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #c4c4c4;
    border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}
</style>
