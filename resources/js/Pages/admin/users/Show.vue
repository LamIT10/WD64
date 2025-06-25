<template>
    <AppLayout>
        <div class="bg-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h1 class="text-xl font-semibold text-indigo-700">Chi tiết nhân viên</h1>
                <div class="flex space-x-3">
                    <Waiting route-name="admin.users.edit" :route-params="{ id: user.id }"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center space-x-2 transition-colors duration-200">
                        <i class="fas fa-edit"></i>
                        <span>Chỉnh sửa</span>
                    </Waiting>
                    <Waiting route-name="admin.users.index"
                        class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded flex items-center space-x-2 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </Waiting>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left Side - Avatar and Basic Info -->
                <div class="lg:w-1/3 space-y-6">
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <img :src="user.avatar ? `/storage/${user.avatar}` : '/images/default-ava.png'" alt="Avatar"
                            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 shadow mx-auto mb-4">
                        <h2 class="text-xl font-bold text-gray-800">{{ user.name || 'Chưa cập nhật' }}</h2>
                        <p class="text-indigo-600 font-medium">{{ user.position || 'Chưa cập nhật chức vụ' }}</p>
                        
                        <div class="mt-6 space-y-4 text-left">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium break-all">{{ user.email || 'Chưa cập nhật' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Trạng thái</p>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full "
                                    :class="{
                                        'bg-green-100 text-green-800': user.status === 'active',
                                        'bg-red-100 text-red-800': user.status === 'inactive'
                                    
                                    }">
                                    {{ user.status === 'active' ? 'Đang làm việc' : 'Đã nghỉ làm'}}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-b pb-2">Thông tin đăng nhập</h3>
                        <div class="space-y-4">
                            <InfoItem icon="fa-sign-in-alt" label="Đăng nhập lần cuối"
                                :value="user.last_login_at ? formatDate(user.last_login_at) : 'Chưa đăng nhập'" />
                            <InfoItem icon="fa-calendar-plus" label="Ngày tạo"
                                :value="user.created_at ? formatDate(user.created_at) : ''" />
                            <InfoItem icon="fa-calendar-check" label="Ngày cập nhật"
                                :value="user.updated_at ? formatDate(user.updated_at) : ''" />
                        </div>
                    </div>
                </div>

                <!-- Right Side - Detailed Info -->
                <div class="lg:w-2/3 space-y-6">
                    <!-- Personal Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-b pb-2 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            Thông tin cá nhân
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <InfoItem icon="fa-id-badge" label="Mã nhân viên" :value="user.employee_code || 'Chưa cập nhật'" />
                            <InfoItem icon="fa-id-card" label="CMND/CCCD" :value="user.identity_number || 'Chưa cập nhật'" />
                            <InfoItem icon="fa-birthday-cake" label="Ngày sinh"
                                :value="user.birthday ? formatDate(user.birthday) : 'Chưa cập nhật'" />
                            <div>
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <i class="fas fa-venus-mars text-indigo-500"></i>
                                    <label class="text-sm font-medium">Giới tính</label>
                                </div>
                                <div class="mt-1">
                                    <span class="px-2 py-1 text-sm rounded"
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
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-b pb-2 flex items-center">
                            <i class="fas fa-address-book mr-2"></i>
                            Thông tin liên hệ
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <InfoItem icon="fa-phone" label="Số điện thoại" :value="user.phone || 'Chưa cập nhật'" />
                            <InfoItem icon="fa-map-marker-alt" label="Địa chỉ" :value="user.address || 'Chưa cập nhật'" />
                            <div class="sm:col-span-2">
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <i class="fab fa-facebook text-indigo-500"></i>
                                    <label class="text-sm font-medium">Facebook</label>
                                </div>
                                <div class="mt-1">
                                    <a v-if="user.facebook" :href="user.facebook" target="_blank"
                                        class="text-indigo-600 hover:underline inline-flex items-center space-x-1 break-all">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                        <span class="truncate max-w-xs">{{ user.facebook }}</span>
                                    </a>
                                    <span v-else class="text-gray-500">Chưa cập nhật</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-b pb-2 flex items-center">
                            <i class="fas fa-briefcase mr-2"></i>
                            Thông tin công việc
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <InfoItem icon="fa-calendar-day" label="Ngày bắt đầu làm việc"
                                :value="user.start_date ? formatDate(user.start_date) : 'Chưa cập nhật'" />
                            <div>
                                <div class="flex items-center space-x-2 text-gray-600">
                                    <i class="fas fa-user-tag text-indigo-500"></i>
                                    <label class="text-sm font-medium">Vai trò</label>
                                </div>
                                <div class="mt-2">
                                    <div v-if="user.roles && user.roles.length" class="flex flex-wrap gap-2">
                                        <span v-for="role in user.roles" :key="role.id"
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 flex items-center">
                                            <i class="fas fa-user-shield mr-1 text-xs"></i>
                                            {{ role.name || role }}
                                        </span>
                                    </div>
                                    <span v-else class="text-gray-500">Chưa cập nhật</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info - Full Width -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h3 class="text-lg font-semibold text-indigo-600 mb-4 border-b pb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Thông tin bổ sung
                </h3>
                <div class="bg-gray-50 p-4 rounded border border-gray-200 text-gray-700 whitespace-pre-wrap min-h-20">
                    {{ user.note || 'Chưa cập nhật thông tin bổ sung' }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import InfoItem from '../../components/InfoItem.vue';

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