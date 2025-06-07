<template>
    <AppLayout>
        <div class="bg-gray-100 min-h-screen p-6">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow p-4 mb-6 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-indigo-700">Chi tiết nhân viên</h1>
                <div class="flex space-x-3">
                    <Waiting route-name="admin.users.edit" :route-params="{ id: user.id }" 
                        class="bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded flex items-center space-x-1 transition-colors duration-200">
                        <i class="fas fa-edit"></i>
                        <span>Chỉnh sửa</span>
                    </Waiting>
                    <Waiting route-name="admin.users.index" :route-params="{}" 
                        class="bg-indigo-50 text-indigo-700 px-3 py-2 rounded flex items-center space-x-1 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </Waiting>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow overflow-hidden animate-fade-in">
                <!-- Avatar -->
                <div class="p-6 text-center">
                    <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://hoanghamobile.com/tin-tuc/wp-content/uploads/2024/05/anh-meme-meo-13.jpg'"
                        alt="Avatar"
                        class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 shadow mx-auto mb-4 transform hover:scale-105 transition-transform duration-300">
                </div>

                <!-- Content Sections -->
                <div class="p-6">
                    <!-- Personal Information -->
                    <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin cá nhân</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-badge text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Mã nhân viên</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.employee_code || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Họ và tên</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.name || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">CMND/CCCD</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.identity_number || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-birthday-cake text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Ngày sinh</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.birthday ? formatDate(user.birthday) : 'Chưa cập nhật' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-venus-mars text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Giới tính</label>
                            </div>
                            <div class="mt-1 border-b border-indigo-200 pb-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-blue-100 text-blue-800': user.gender === 'male',
                                        'bg-pink-100 text-pink-800': user.gender === 'female',
                                        'bg-gray-100 text-gray-800': !user.gender
                                    }">
                                    {{ user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin liên hệ</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-phone text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Số điện thoại</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.phone || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-envelope text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Email</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.email || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fab fa-facebook text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Facebook</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">
                                <a v-if="user.facebook" :href="user.facebook" target="_blank"
                                    class="text-indigo-600 hover:underline flex items-center space-x-1">
                                    <i class="fab fa-facebook-f text-sm"></i>
                                    <span class="break-all">{{ user.facebook }}</span>
                                </a>
                                <span v-else>Chưa cập nhật</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Địa chỉ</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.address || 'Chưa cập nhật' }}</div>
                        </div>
                    </div>

                    <!-- Work Information -->
                    <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin công việc</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-briefcase text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Chức vụ</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.position || 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user-tag text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Vai trò</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">
                                <span v-if="user.roles && user.roles.length" class="flex flex-wrap gap-2">
                                    <span v-for="role in user.roles" :key="role.id"
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ role.name || role }}
                                    </span>
                                </span>
                                <span v-else>Chưa cập nhật</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-day text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Ngày bắt đầu làm việc</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.start_date ? formatDate(user.start_date) : 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-info-circle text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Trạng thái</label>
                            </div>
                            <div class="mt-1 border-b border-indigo-200 pb-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': user.status === 'active',
                                        'bg-red-100 text-red-800': user.status === 'inactive',
                                        'bg-gray-100 text-gray-800': !user.status
                                    }">
                                    {{ user.status === 'active' ? 'Đang làm việc' : user.status === 'inactive' ? 'Đã nghỉ làm' : 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information (Notes) -->
                    <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin bổ sung</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sticky-note text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Ghi chú</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1 whitespace-pre-wrap">{{ user.note || 'Chưa cập nhật' }}</div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin hệ thống</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-sign-in-alt text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Đăng nhập lần cuối</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.last_login_at ? formatDate(user.last_login_at) : 'Chưa đăng nhập' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-plus text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Ngày tạo</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.created_at ? formatDate(user.created_at) : 'Chưa cập nhật' }}</div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-check text-indigo-500"></i>
                                <label class="text-sm font-medium text-gray-700">Ngày cập nhật</label>
                            </div>
                            <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ user.updated_at ? formatDate(user.updated_at) : 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';

const props = defineProps({
    user: Object
});

function formatDate(date) {
    if (!date) return null;
    const d = new Date(date);
    return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
}
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.break-all {
    word-break: break-all;
}

button, a {
    transition: all 0.2s ease-in-out;
}
</style>