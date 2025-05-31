<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-purple-700 font-semibold">Thêm nhân viên mới</h5>
                <Link :href="route('admin.users.index')"
                    class="px-4 py-2 bg-purple-50 rounded hover:text-purple-500 text-purple-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Quay lại
                </Link>
            </div>
            <div class="mx-auto bg-white rounded shadow-md overflow-hidden">
                <!-- Main Content -->
                <div class="flex flex-col md:flex-row">
                    <!-- Left Panel - Fields -->
                    <div class="w-full p-6">
                        <div class="space-y-6">
                            <!-- Row 1: Full Name + Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ tên *</label>
                                    <input v-model="form.fullname" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập họ tên đầy đủ..." />
                                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.fullname }}
                                    </p>
                                </div>
                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input v-model="form.email" type="email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập địa chỉ email..." />
                                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 2: Phone + Position -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Điện thoại *</label>
                                    <input v-model="form.phone" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập số điện thoại..." />
                                    <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.phone }}
                                    </p>
                                </div>
                                <!-- Position -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chức vụ</label>
                                    <input v-model="form.position" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập chức vụ..." />
                                    <p v-if="form.errors.position" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.position }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 3: Role + Gender + Status -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Role -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò *</label>
                                    <select v-model="form.role_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="" disabled>Chọn vai trò...</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.role_id" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.role_id }}
                                    </p>
                                </div>
                                <!-- Gender -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Giới tính</label>
                                    <select v-model="form.gender"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="" disabled>Chọn giới tính...</option>
                                        <option value="male">Nam</option>
                                        <option value="female">Nữ</option>
                                        <option value="other">Khác</option>
                                    </select>
                                    <p v-if="form.errors.gender" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.gender }}
                                    </p>
                                </div>
                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                                    <select v-model="form.status"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                                        <option value="" disabled>Chọn trạng thái...</option>
                                        <option value="active">Hoạt động</option>
                                        <option value="inactive">Không hoạt động</option>
                                    </select>
                                    <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.status }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 4: Avatar + Address -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Avatar -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                                    <input type="file" accept="image/*" @change="form.avatar = $event.target.files[0]"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" />
                                    <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.avatar }}
                                    </p>
                                </div>
                                <!-- Address -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                    <input v-model="form.address" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập địa chỉ..." />
                                    <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.address }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 5: Password + Confirm Password -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu *</label>
                                    <input v-model="form.password" type="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập mật khẩu..." />
                                    <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </p>
                                </div>
                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu
                                        *</label>
                                    <input v-model="form.password_confirmation" type="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập lại mật khẩu..." />
                                    <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 6: Note -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                <textarea v-model="form.note"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                    placeholder="Nhập ghi chú..." rows="4"></textarea>
                                <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.note }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
                    <Link :href="route('admin.users.index')"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    Hủy bỏ
                    </Link>
                    <button type="submit" @click="submit"
                        class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md"
                        :disabled="form.processing">
                        <i class="fas fa-save mr-2"></i> Lưu nhân viên
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    roles: {
        type: Array,
        default: () => [],
    },
});
console.log('Roles:', props.roles);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    role_id: '',
    position: '',
    gender: '',
    avatar: null,
    address: '',
    status: '',
    note: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/admin/users', {
        onError: (errors) => {
            console.error(errors);
        },
    });
}
</script>