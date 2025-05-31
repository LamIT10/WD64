<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-purple-700 font-semibold">
                    Danh sách Nhân viên
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input type="text" placeholder="Tìm kiếm nhân viên..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <Link :href="route('admin.users.create')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Thêm mới</span>
                    </Link>
                </div>
            </div>

            <!-- User Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Họ tên
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Điện thoại
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                            
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giới tính
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Địa chỉ
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Chức vụ
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(user, index) in users.data" :key="user.id"
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-purple-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.fullname }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ user.phone || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ user.status || 'Hoạt động' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                   {{ user.gender === 'male' ? 'Nam' : (user.gender === 'female' ? 'Nữ' : 'Chưa cập nhật') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ user.address || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ user.position || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <Link :href="route('admin.users.show', user.id)"
                                            class="text-gray-400 hover:text-purple-600 transition-colors"
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('admin.users.edit', user.id)"
                                            class="text-gray-400 hover:text-blue-600 transition-colors"
                                            title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                        </Link>
                                        <button @click="hanldeDelete(user.id)"
                                            class="text-gray-400 hover:text-red-600 transition-colors" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between mt-4">
                    <div class="text-sm text-gray-500">
                        Hiển thị
                        <span class="font-medium">{{ users.from }}</span> đến
                        <span class="font-medium">{{ users.to }}</span> của
                        <span class="font-medium">{{ users.total }}</span> kết quả
                    </div>

                    <div class="flex space-x-1">
                        <Link v-if="users.prev_page_url" :href="users.prev_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>

                        <Link v-if="users.next_page_url" :href="users.next_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
    users: Object
});

const hanldeDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá người dùng này không?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.users.destroy', id));
    }
}

function formatLabel(label) {
    return label
        .replace(/«/g, '«')
        .replace(/»/g, '»')
        .replace(/Previous/i, 'Trước')
        .replace(/Next/i, 'Tiếp');
}
function goTo(url) {
    if (url) {
        router.visit(url);
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