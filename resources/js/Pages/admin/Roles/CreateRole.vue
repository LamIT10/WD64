<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">Thêm Vai Trò Mới</h5>
                <Waiting route-name="admin.role.index">
               
                    <i class="fas fa-arrow-left"></i> Quay lại
             
                </Waiting>
            </div>
            <div class="mx-auto bg-white rounded shadow-md overflow-hidden">
                <!-- Main Content -->
                <div class="flex flex-col md:flex-row">
                    <!-- Left Panel - Fields -->
                    <div class="w-full p-6">
                        <form @submit.prevent="hanldeSubmitForm" class="space-y-6">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tên vai trò <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên vai trò..." required />
                                <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                            </div>
                            <!-- Permissions - Improved Section with Select All -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Quyền hạng <span class="text-red-500">*</span>
                                </label>
                                <span v-if="form.errors.permissions" class="text-red-500 text-xs">{{
                                    form.errors.permissions
                                    }}</span>

                                <div class="space-y-6 mt-4">
                                    <div v-for="permission in permissions" :key="permission.id" class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex justify-between items-center mb-3">
                                            <h3 class="font-medium text-gray-800 flex items-center">
                                                <span class="bg-indigo-100 text-indigo-800 p-1 rounded mr-2">
                                                    <i class="fas fa-folder-open text-sm"></i>
                                                </span>
                                                {{ permission.group_description }}
                                            </h3>
                                            <button 
                                                type="button" 
                                                @click="toggleAllPermissions(permission.permission)"
                                                class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition-colors"
                                            >
                                                Chọn tất cả
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-6">
                                            <div v-for="value in permission.permission" :key="value.id"
                                                class="flex items-center justify-between p-2 hover:bg-gray-100 rounded transition-colors">
                                                <label class="flex items-center cursor-pointer w-full">
                                                    <div class="relative inline-flex items-center">
                                                        <input type="checkbox" class="sr-only peer" :value="value.id"
                                                            @change="handleSelect(value.id)"
                                                            :checked="form.permissions.includes(value.id)">
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                                                        </div>
                                                    </div>
                                                    <span class="ml-3 text-sm text-gray-700 flex-1">{{ value.description }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Footer Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                <Waiting route-name="admin.role.index" color="bg-indigo-400 hover:bg-indigo-600 text-white" >
                              
                                    Hủy bỏ
                           
                                </Waiting>
                                <button type="submit" :disabled="form.processing"
                                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-save mr-2"></i> Lưu vai trò
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { reactive } from 'vue';
import { route } from 'ziggy-js';
import Waiting from '../../components/Waiting.vue';

const { permissions } = defineProps({
    permissions: {
        type: Object,
        default: {}
    }
})

const form = useForm({
    name: "",
    permissions: []
})

const errors = reactive({
    name: "",
    permissions: ""
})

const hanldeSubmitForm = () => {
    form.post(route('admin.role.store'));
}

const handleSelect = (id) => {
    if (form.permissions.includes(id)) {
        form.permissions = form.permissions.filter(permissionId => permissionId !== id);
    } else {
        form.permissions = [...form.permissions, id];
    }
}

// Thêm hàm xử lý chọn tất cả permissions trong nhóm
const toggleAllPermissions = (permissionItems) => {
    const permissionIds = permissionItems.map(item => item.id);
    const allSelected = permissionIds.every(id => form.permissions.includes(id));
    
    if (allSelected) {
        // Nếu đã chọn tất cả thì bỏ chọn tất cả
        form.permissions = form.permissions.filter(id => !permissionIds.includes(id));
    } else {
        // Nếu chưa chọn tất cả thì thêm những permission chưa có
        const newPermissions = [...form.permissions];
        permissionIds.forEach(id => {
            if (!newPermissions.includes(id)) {
                newPermissions.push(id);
            }
        });
        form.permissions = newPermissions;
    }
}
</script>

