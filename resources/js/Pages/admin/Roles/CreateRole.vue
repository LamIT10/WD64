<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-purple-700 font-semibold">Thêm Vai Trò Mới</h5>
                <Link :href="route('admin.role.index')">
                <button class="px-4 py-2 bg-purple-50 rounded hover:text-purple-500 text-purple-600 transition-colors">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </button>
                </Link>
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên vai trò..." required />
                                <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Quyền hạng <span class="text-red-500">*</span>
                                </label>
                                <span v-if="form.errors.permissions" class="text-red-500 text-xs">{{ form.errors.permissions
                                }}</span>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                                    <div v-for="permission in permissions" :key="permission.id"
                                        class="flex items-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="hidden peer" :value="permission.id"
                                                @change="handleSelect(permission.id)"
                                                :checked="form.permissions.includes(permission.id)">
                                            <div
                                                class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600">
                                            </div>
                                            <span class="ms-3 text-sm font-medium text-gray-700">{{ permission.name
                                            }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                <Link :href="route('admin.role.index')">
                                <button type="button"
                                    class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                                    Hủy bỏ
                                </button>
                                </Link>
                                <button type="submit" :disabled="form.processing"
                                    class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
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
</script>