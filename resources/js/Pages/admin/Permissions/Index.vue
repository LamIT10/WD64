<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200"
            >
                <h5 class="text-lg text-purple-700 font-semibold">
                    Danh sách Quyền
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Tìm kiếm quyền..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        />
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"
                        ></i>
                    </div>
                    <Link
                        :href="route('admin.permission.create')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2"
                    >
                        <i class="fas fa-plus"></i>
                        <span>Thêm mới</span>
                    </Link>
                </div>
            </div>

            <!-- Permission Table -->
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
                                    STT
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Tên quyền
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Mô tả
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Permission Rows -->
                            <tr 
                                v-for="(permission, index) in permissions.data" 
                                :key="permission.id"
                                class="hover:bg-gray-50 transition-colors"
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ permission.name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ permission.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link v-can="'admin.permission.edit'"
                                            :href="route('admin.permission.edit', permission.id)"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-600 rounded-md hover:bg-purple-200 transition-colors"
                                        >
                                            <i class="fas fa-edit text-sm"></i>
                                            <span class="text-sm">Sửa</span>
                                        </Link>
                                        <button 
                                            @click="hanldeDelete(permission.id)"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors"
                                        >
                                            <i class="fas fa-trash text-sm"></i>
                                            <span class="text-sm">Xóa</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="permissions.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Không có quyền nào được tìm thấy
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
                        <span class="font-medium">{{ permissions.data.length }}</span> của
                        <span class="font-medium">{{ permissions.total }}</span> kết quả
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
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const { permissions } = defineProps({
    permissions: {
        type: Object,
        default: () => ({}),
    },
});
const hanldeDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá quyền này không?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.permission.destroy', id));
    }
}

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