<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Quản lý quyền</h1>
                <Link :href="route('admin.permission.create')" 
                      class="flex items-center gap-2 px-4 py-2  bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-plus"></i>
                    <span>Thêm quyền mới</span>
                </Link>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Table Header -->
                <div class="grid grid-cols-12 bg-gray-100 px-6 py-4 font-semibold text-gray-700 uppercase text-sm">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-4 text-center">Tên quyền</div>
                    <div class="col-span-4 text-center">Mô tả</div>
                    <div class="col-span-3 text-center">Thao tác</div>
                </div>

                <!-- Table Rows -->
                <div v-for="(permission, index) in permissions.data" :key="permission.id" 
                     class="grid grid-cols-12 px-6 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                    <div class="col-span-1 text-center text-gray-600">{{ index + 1 }}</div>
                    <div class="col-span-4 text-center font-medium text-gray-800">{{ permission.name }}</div>
                    <div class="col-span-4 text-center text-gray-600">{{ permission.description }}</div>
                    <div class="col-span-3 flex justify-center space-x-2">
                        <Link :href="route('admin.permission.edit', permission.id)"
                              class="flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Sửa</span>
                        </Link>
                        <button @click="hanldeDelete(permission.id)"
                                class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash text-sm"></i>
                            <span class="text-sm">Xoá</span>
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="permissions.data.length === 0" class="p-8 text-center text-gray-500">
                    Không có quyền nào được tìm thấy
                </div>
            </div>

            <!-- Pagination (if needed) -->
            <!-- <Pagination :links="permissions.links" class="mt-6" /> -->
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const { permissions } = defineProps({
    permissions: {
        type: Object,
        default: {}
    }
});

const hanldeDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá quyền này không?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.permission.destroy', id));
    }
}
</script>

<style scoped>
</style>