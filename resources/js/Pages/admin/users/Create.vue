<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">Thêm nhân viên mới</h5>
                <Waiting route-name="admin.users.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>
            <div class="mx-auto bg-white rounded shadow-md overflow-hidden">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-4 px-6 pt-4">
                        <button class="pb-2 px-1 border-b-2 border-indigo-600 text-indigo-600 font-medium">Thông
                            tin</button>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="flex flex-col md:flex-row p-6">
                    <!-- Left Panel - Photo Upload -->
                    <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <div class="w-32 h-32 mx-auto bg-gray-100 rounded flex items-center justify-center">
                                <i class="fas fa-camera text-gray-400 text-2xl"></i>
                            </div>
                            <button
                                class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                Chọn ảnh
                            </button>
                        </div>
                    </div>

                    <!-- Right Panel - Fields -->
                    <div class="w-full md:w-3/4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin khởi tạo</h3>
                        <div class="space-y-6">
                            <!-- Row 1: Employee ID + Name -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mã nhân viên </label>
                                    <input v-model="form.employee_code" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Mã nhân viên tự động" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên nhân viên *</label>
                                    <input v-model="form.name" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Nhập tên nhân viên..." />
                                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 2: Phone + Branch -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                                    <input v-model="form.phone" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Nhập số điện thoại..." />
                                    <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.phone }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input v-model="form.email" type="text"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Nhập email..." />
                                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </p>
                            
                                </div>
                                <!-- Position - Toggle Switch Version -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Chức vụ</label>
                                    <div class="flex flex-wrap gap-4">
                                        <!-- Toggle for Manager -->
                                        <div v-for="role in listRoles" :key="role.id" class="flex items-center">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" @change="handleRole(role.id)" class="sr-only peer">
                                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                                <span class="ms-3 text-sm font-medium text-gray-700">{{ role.name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.position" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.position }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu *</label>
                                    <input v-model="form.password" type="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Nhập mật khẩu..." />
                                    <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1"> Xác nhận mật khẩu
                                        *</label>
                                    <input v-model="form.password_confirmation" type="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Nhập mật khẩu..." />
                                    <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors.password }}
                                    </p>
                                </div>

                            </div>
                            <!-- Additional Information Section -->
                            <div v-if="showAdditionalInfo">
                                <!-- Work Information -->
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin công việc</h3>
                                <div class="space-y-6">
                                    <!-- Row 4: Start Date + Department -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày bắt đầu làm
                                                việc</label>
                                            <input v-model="form.start_date" type="date"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                                            <p v-if="form.errors.startDate" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.start_date }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Chức
                                                danh</label>
                                            <select v-model="form.position"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all">
                                                <option value="" disabled>Chọn chức danh...</option>
                                            </select>
                                            <p v-if="form.errors.position" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.position }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Row 6: Note -->
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                            <textarea v-model="form.note"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                                placeholder="Nhập ghi chú..." rows="3"></textarea>
                                            <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.note }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin cá nhân</h3>
                                <div class="space-y-6">
                                    <!-- Row 7: ID Number + Date of Birth -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Số
                                                CMND/CCCD</label>
                                            <input v-model="form.identity_number" type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                                placeholder="Nhập số CMND/CCCD..." />
                                            <p v-if="form.errors.identity_number" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.identity_number }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày
                                                sinh</label>
                                            <input v-model="form.birthday" type="date"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                                            <p v-if="form.errors.birthday" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.birthday }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Row 8: Gender -->
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Giới
                                                tính</label>
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input v-model="form.gender" type="radio" value="male"
                                                        class="mr-2 border-gray-300 focus:ring-indigo-500" />
                                                    Nam
                                                </label>
                                                <label class="flex items-center">
                                                    <input v-model="form.gender" type="radio" value="female"
                                                        class="mr-2 border-gray-300 focus:ring-indigo-500" />
                                                    Nữ
                                                </label>
                                            </div>
                                            <p v-if="form.errors.gender" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.gender }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin liên hệ </h3>
                                <div class="space-y-6">
                                    <!-- Row 7: ID Number + Date of Birth -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                            <input v-model="form.address" type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                                placeholder="Nhập địa chỉ" />
                                            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.address }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                                            <input v-model="form.facebook" type="text"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all"
                                                placeholder="Nhập Facebook..." />
                                            <p v-if="form.errors.facebook" class="text-red-500 text-sm mt-1">
                                                {{ form.errors.facebook }}
                                            </p>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Button -->
                <div class="px-6 pb-6">
                    <button type="button" @click="toggleAdditionalInfo"
                        class="text-indigo-600 hover:text-indigo-800 flex items-center">
                        <i :class="showAdditionalInfo ? 'fas fa-minus' : 'fas fa-plus'" class="mr-2"></i>
                        {{ showAdditionalInfo ? 'Ẩn thông tin' : 'Thêm thông tin' }}
                    </button>
                </div>

                <!-- Footer Actions -->
                <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
                    <button type="reset"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Bỏ qua
                    </button>
                    <button type="button" @click="submit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md"
                        :disabled="form.processing">
                        <i class="fas fa-save mr-2"></i> Lưu
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { ref } from 'vue';

const showAdditionalInfo = ref(false);
const {listRoles} = defineProps({
    listRoles: {},
})
const form = useForm({
     employee_code: '',


  
    
   
    roles: [],
    avatar: null,
  

    note: '',
    password: '',
    password_confirmation: '',
    name: '',
    phone: '',
    start_date: '',
    position: '',
  
    identity_number: '',
    birthday: '',
    gender: '',
    facebook: '',
    email: '',
    address: '',
  


});

function toggleAdditionalInfo() {
    showAdditionalInfo.value = !showAdditionalInfo.value;
}

const handleRole = (id) => {
    console.log(id)
    if (form.roles.includes(id)) {
        form.roles = form.roles.filter(permissionId => permissionId !== id);
    } else {
        form.roles = [...form.roles, id];
    }
}
function submit() {
    console.log(form);
    form.post(route('admin.users.store'), {
        onError: (errors) => {
            console.error(errors);
        }
    });
}
</script>