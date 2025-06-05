<template>
    <AppLayout>
        <div class="bg-gray-50 p-6 min-h-screen">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">Chi tiết nhân viên </h5>
                <div class="flex space-x-3">
                    <Waiting route-name="admin.users.edit" :route-params="{ id: user.id }"
                     >
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </Waiting>
                    <Waiting route-name="admin.users.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </Waiting>
                </div>
            </div>
            <div class="mx-auto bg-white rounded-lg shadow-md overflow-hidden animate-fade-in">
                <!-- Main Content -->
                <div class="p-6">
                    <!-- Avatar -->
                    <div class="flex justify-center mb-6">
                        <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://hoanghamobile.com/tin-tuc/wp-content/uploads/2024/05/anh-meme-meo-13.jpg'"
                            alt="Avatar"
                            class="w-36 h-36 rounded-full object-cover border-4 border-indigo-200 shadow-lg transform hover:scale-105 transition-transform duration-300">
                    </div>
                      <div class="mb-8">
                        <h6 class="text-md font-semibold text-indigo-600 border-b border-gray-200 pb-2 mb-4">Thông tin cá nhân</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-id-badge text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Mã nhân viên</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.employee_code || 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-id-card text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">CMND/CCCD</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.identity_number || 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-birthday-cake text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Ngày sinh</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.birthday ? formatDate(user.birthday) : 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-venus-mars text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Giới tính</label>
                                </div>
                                <div class="mt-2">
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
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h6 class="text-md font-semibold text-indigo-600 border-b border-gray-200 pb-2 mb-4">Thông tin liên hệ</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-envelope text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Email</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.email || 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-phone text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Điện thoại</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.phone || 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow md:col-span-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fab fa-facebook text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Facebook</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">
                                    <a v-if="user.facebook" :href="user.facebook" target="_blank"
                                        class="text-indigo-600 hover:underline flex items-center space-x-1">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                        <span class="break-all">{{ user.facebook }}</span>
                                    </a>
                                    <span v-else>Chưa cập nhật</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow md:col-span-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Địa chỉ</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.address || 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Information -->
                    <div class="mb-8">
                        <h6 class="text-md font-semibold text-indigo-600 border-b border-gray-200 pb-2 mb-4">Thông tin công việc</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-briefcase text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Chức vụ</label>
                                </div>
                                <div class="mt-4 p-2 rounded-lg">
                                    <div class="text-gray-700 text-sm grid grid-cols-7 gap-2">
                                        <div 
                                            v-for="role in props.user.roles" 
                                            :key="role.id"
                                        >
                                         <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-green-800">{{ role }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar-day text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Ngày bắt đầu làm việc</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm">{{ user.start_date ? formatDate(user.start_date) : 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-info-circle text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Trạng thái</label>
                                </div>
                                <div class="mt-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': user.status === 'active',
                                            'bg-red-100 text-red-800': user.status === 'inactive',
                                            'bg-gray-100 text-gray-800': !user.status
                                        }">
                                        {{ user.status === 'active' ? 'Hoạt động' : user.status === 'inactive' ? 'Không hoạt động' : 'Chưa cập nhật' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-8">
                        <h6 class="text-md font-semibold text-indigo-600 border-b border-gray-200 pb-2 mb-4">Thông tin bổ sung</h6>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-sticky-note text-indigo-500"></i>
                                    <label class="text-sm font-medium text-gray-700">Ghi chú</label>
                                </div>
                                <div class="mt-2 text-gray-600 text-sm whitespace-pre-wrap">{{ user.note || 'Chưa cập nhật' }}</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-sign-in-alt text-indigo-500"></i>
                                        <label class="text-sm font-medium text-gray-700">Đăng nhập lần cuối</label>
                                    </div>
                                    <div class="mt-2 text-gray-600 text-sm">{{ formatDate(user.last_login_at) || 'Chưa đăng nhập' }}</div>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar-plus text-indigo-500"></i>
                                        <label class="text-sm font-medium text-gray-700">Ngày tạo</label>
                                    </div>
                                    <div class="mt-2 text-gray-600 text-sm">{{ formatDate(user.created_at) || 'Chưa cập nhật' }}</div>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar-check text-indigo-500"></i>
                                        <label class="text-sm font-medium text-gray-700">Ngày cập nhật</label>
                                    </div>
                                    <div class="mt-2 text-gray-600 text-sm">{{ formatDate(user.updated_at) || 'Chưa cập nhật' }}</div>
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
import Waiting from '../../components/Waiting.vue';

const props = defineProps({
    user: Object
});
console.log(props.user);
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

/* Ensure long text like URLs don't overflow */
.break-all {
    word-break: break-all;
}
button:hover {
    transition: background-color 0.2s ease-in-out;
}
</style>
