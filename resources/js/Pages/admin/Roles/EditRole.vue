<template>
    <div>
        <AppLayout>
            <div class="px-5 mt-5">
                <div class="w-full bg-white rounded-lg px-2.5 py-2.5 mt-2.5 shadow-sm">
                    <div class="w-full mx-auto my-2.5 gap-x-5">
                        <form @submit.prevent="hanldeSubmitForm" class="gap-2.5 items-end w-full">
                            <div class="flex flex-col gap-1.25 w-full">
                                <label class="text-xs text-gray-900">Tên vai trò <span class="text-red-500">*</span></label>
                                <input type="text" v-model="form.name"
                                    class="w-full px-3 py-2 text-xs text-gray-900 rounded-md bg-white border border-gray-200 focus:ring-2 focus:ring-red-200 focus:border-red-500">
                                <span v-if="errors.name" class="text-red-500 text-xs">{{ errors.name }}</span>
                            </div>
                            
                            <div class="flex flex-col gap-1.25 w-full mt-3">
                                <label class="text-xs text-gray-900">Quyền hạng <span class="text-red-500">*</span></label>
                                <span v-if="errors.permissions" class="text-red-500 text-xs">{{ errors.permissions }}</span>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-2">
                                    <div v-for="permission in permissions" :key="permission.id" 
                                         class="flex items-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   class="hidden peer" 
                                                   :value="permission.id"
                                                   @change="handleSelect(permission.id)"
                                                   :checked="form.permissions.includes(permission.id)">
                                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                                            <span class="ms-3 text-sm font-medium text-gray-700">{{ permission.name }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-full flex justify-end mt-4">
                                <button :disabled="form.processing"
                                    class="mt-2.5 px-3 py-1.5 border rounded-md bg-red-700 hover:bg-red-800 text-white font-bold text-xs transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { reactive } from 'vue';

const { role, permissions } = defineProps({
    role: {
        type: Object,
        default: {}
    },
    permissions: {
        type: Object,
        default: {}
    }
})

const form = useForm({
    name: role.name,
    permissions: role.permissions
})

const errors = reactive({
    name: "",
    permissions: ""
})

const hanldeSubmitForm = () => {
    form.patch(route('admin.role.update', role.id), {
        onError: (xhr) => {
            console.log(xhr);
            errors.name = xhr.name;
            errors.permissions = xhr.permissions;
        }
    })
}

const handleSelect = (id) => {
    if (form.permissions.includes(id)) {
        form.permissions = form.permissions.filter(permissionId => permissionId !== id);
    } else {
        form.permissions = [...form.permissions, id];
    }
}
</script>