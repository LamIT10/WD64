<script>
// <CategoryItem v-for="(category, index) in categories.data" :key="index" :category="category" /> 
// import AppLayout from '../Layouts/AppLayout.vue'
// import { Link, router } from '@inertiajs/vue3'
// import CategoryItem from '../../components/CategoryItem.vue'


// const props = defineProps({
//     categories: Object,
// })
</script>

<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 p-6">
            <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">
                <!-- Header với tiêu đề và nút thêm -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Danh sách Danh mục Sản phẩm</h1>
                    <button
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Thêm danh mục mới
                    </button>
                </div>

                <!-- Bảng danh mục -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                                    Tên danh mục
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Mô tả
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Danh mục cha 1 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <button class="mr-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <span>Điện tử</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    ELEC
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        class="mr-2 px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                        Xem
                                    </button>
                                    <button
                                        class="mr-2 px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                                        Sửa
                                    </button>
                                    <button
                                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                                        Xóa
                                    </button>
                                </td>
                            </tr>

                            <!-- Danh mục con 1 (được mở rộng) -->
                            <tr class="hover:bg-gray-50 bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center pl-10">
                                        <span class="text-gray-600">Điện thoại di động</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    MOBILE
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        75
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        class="mr-2 px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                        Xem
                                    </button>
                                    <button
                                        class="mr-2 px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                                        Sửa
                                    </button>
                                    <button
                                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                                        Xóa
                                    </button>
                                </td>
                            </tr>

                            <!-- Danh mục không có con -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span>Thời trang</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    FASHION
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        156
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        class="mr-2 px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                        Xem
                                    </button>
                                    <button
                                        class="mr-2 px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                                        Sửa
                                    </button>
                                    <button
                                        class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal thêm/sửa danh mục (hidden) -->
                <div class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Thêm danh mục mới
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="category-name">
                                    Tên danh mục <span class="text-red-500">*</span>
                                </label>
                                <input id="category-name" type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Nhập tên danh mục" />
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="category-code">
                                    Mã danh mục <span class="text-red-500">*</span>
                                </label>
                                <input id="category-code" type="text"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Nhập mã danh mục" />
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="category-parent">
                                    Danh mục cha
                                </label>
                                <select id="category-parent"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">-- Không có --</option>
                                    <option value="1">Điện tử</option>
                                    <option value="2">Đồ gia dụng</option>
                                </select>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t flex justify-end">
                            <button
                                class="mr-2 px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                                Hủy
                            </button>
                            <button
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                Thêm mới
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal xác nhận xóa (hidden) -->
                <div class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                        <div class="px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Xác nhận xóa
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 mb-4">
                                Bạn có chắc chắn muốn xóa danh mục "Điện thoại di động"?
                            </p>
                            <p class="text-red-500 text-sm">
                                Lưu ý: Tất cả danh mục con và sản phẩm trong danh mục này sẽ bị xóa.
                            </p>
                        </div>
                        <div class="px-6 py-4 border-t flex justify-end">
                            <button
                                class="mr-2 px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                                Hủy
                            </button>
                            <button
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';

// Không có logic, chỉ là giao diện
</script>

<style scoped>
/* Có thể thêm style tùy chỉnh nếu cần */
</style>