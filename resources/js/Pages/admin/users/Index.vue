<template>
    <AppLayout>
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
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
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80 backdrop-blur-sm">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Họ tên
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Điện thoại
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Giới tính
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Địa chỉ
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Chức vụ
                                </th>
                                <th scope="col"
                                    class="px-6 py-4  text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr v-for="(user, index) in users.data" :key="user.id"
                                class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://via.placeholder.com/40'"
                                            alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border border-purple-200">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ user.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ user.phone || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm"
                                        :class="{
                                            'bg-green-100 text-green-700': user.status === 'active',
                                            'bg-red-100 text-red-700': user.status === 'inactive',
                                            'bg-yellow-100 text-yellow-700': user.status === 'suspended'
                                        }">
                                        {{ user.status === 'active' ? 'Hoạt động' : user.status === 'inactive' ? 'Không hoạt động' : 'Tạm ngưng' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm"
                                        :class="{
                                            'bg-blue-100 text-blue-700': user.gender === 'male',
                                            'bg-pink-100 text-pink-700': user.gender === 'female',
                                            'bg-gray-100 text-gray-700': user.gender === 'other'
                                        }">
                                        {{ user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : user.gender === 'other' ? 'Khác' : 'Chưa cập nhật' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ user.address || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ user.position || 'Chưa cập nhật' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link :href="route('admin.users.show', user.id)"
                                              class="flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-600 rounded-md hover:bg-purple-200 transition-colors">
                                            <i class="fas fa-eye text-sm"></i>
                                            <span>Xem</span>
                                        </Link>
                                        <Link :href="route('admin.users.edit', user.id)"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-purple-100 text-purple-600 rounded-md hover:bg-purple-200 transition-colors">
                                        <i class="fas fa-edit text-sm"></i>
                                        <span>Sửa</span>
                                        </Link>
                                        <button @click="hanldeDelete(user.id)"
                                           class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                            <span>Xóa</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <div class="text-sm text-gray-600 font-medium">
                        Hiển thị
                        <span class="font-semibold">{{ users.from }}</span> đến
                        <span class="font-semibold">{{ users.to }}</span> của
                        <span class="font-semibold">{{ users.total }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="users.prev_page_url" :href="users.prev_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="text-sm text-gray-600">
                            Trang {{ users.current_page }} / {{ users.last_page }}
                        </span>
                        <Link v-if="users.next_page_url" :href="users.next_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-600 transition-all duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
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


</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #a0aec0;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>