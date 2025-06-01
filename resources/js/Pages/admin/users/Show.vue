<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-purple-700 font-semibold">Chi tiết nhân viên</h5>
                <div class="flex space-x-3">
                    <Link :href="route('admin.users.edit', user.id)"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Chỉnh sửa</span>
                    </Link>
                    <Link :href="route('admin.users.index')"
                        class="px-4 py-2 bg-purple-50 rounded hover:text-purple-500 text-purple-600 transition-colors flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </Link>
                </div>
            </div>
            <div class="mx-auto bg-white rounded-lg shadow-md overflow-hidden animate-fade-in">
                <!-- Main Content -->
                <div class="p-6">
                    <!-- Avatar -->
                    <div class="flex justify-center mb-6">
                        <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://via.placeholder.com/150'"
                            alt="Avatar"
                            class="w-32 h-32 rounded-full object-cover border-4 border-purple-100 shadow-md">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Họ tên</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ user.name || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-envelope text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Email</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ user.email || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-phone text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Điện thoại</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ user.phone || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Position -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-briefcase text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Chức vụ</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ user.position || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Gender -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-venus-mars text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Giới tính</label>
                            </div>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-blue-100 text-blue-800': user.gender === 'male',
                                        'bg-pink-100 text-pink-800': user.gender === 'female',
                                        'bg-gray-100 text-gray-800': user.gender === 'other'
                                    }">
                                    {{ user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : user.gender === 'other' ? 'Khác' : 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                        <!-- Status -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-info-circle text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Trạng thái</label>
                            </div>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': user.status === 'active',
                                        'bg-red-100 text-red-800': user.status === 'inactive',
                                        'bg-yellow-100 text-yellow-800': user.status === 'suspended'
                                    }">
                                    {{ user.status === 'active' ? 'Hoạt động' : user.status === 'inactive' ? 'Không hoạt động' : user.status === 'suspended' ? 'Tạm ngưng' : 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow md:col-span-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Địa chỉ</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ user.address || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Note -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow md:col-span-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sticky-note text-purple-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Ghi chú</label>
                            </div>
                            <div class="mt-2 text-gray-600 whitespace-pre-wrap">
                                {{ user.note || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <!-- Last Login, Created At, Updated At -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:col-span-2">
                            <!-- Last Login -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-sign-in-alt text-purple-600"></i>
                                    <label class="text-sm font-semibold text-gray-700">Đăng nhập lần cuối</label>
                                </div>
                                <div class="mt-2 text-gray-600">
                                    {{ formatDate(user.last_login_at) || 'Chưa đăng nhập' }}
                                </div>
                            </div>
                            <!-- Created At -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar-plus text-purple-600"></i>
                                    <label class="text-sm font-semibold text-gray-700">Ngày tạo</label>
                                </div>
                                <div class="mt-2 text-gray-600">
                                    {{ formatDate(user.created_at) || 'Chưa cập nhật' }}
                                </div>
                            </div>
                            <!-- Updated At -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar-check text-purple-600"></i>
                                    <label class="text-sm font-semibold text-gray-700">Ngày cập nhật</label>
                                </div>
                                <div class="mt-2 text-gray-600">
                                    {{ formatDate(user.updated_at) || 'Chưa cập nhật' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    user: Object
});

function formatDate(date) {
    if (!date) return null;
    const d = new Date(date);
    return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
}
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>