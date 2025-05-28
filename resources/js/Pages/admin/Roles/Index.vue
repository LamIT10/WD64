<template>
    <div>
        <AppLayout>
            <div class="container mx-auto px-4 py-6">
                <!-- Header and Add Button -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Danh sách vai trò</h1>
                    <Link :href="route('admin.role.create')" 
                          class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Thêm vai trò</span>
                    </Link>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Table Header -->
                    <div class="grid grid-cols-12 gap-4 bg-gray-100 px-6 py-4 font-semibold text-gray-700 uppercase text-sm">
                        <div class="col-span-1 text-center">#</div>
                        <div class="col-span-3 text-center">Tên vai trò</div>
                        <div class="col-span-5 text-center">Quyền hạng</div>
                        <div class="col-span-3 text-center">Thao tác</div>
                    </div>

                    <!-- Table Rows -->
                    <div v-for="(role, index) in props.listRoles.data" :key="role.id" 
                         class="grid grid-cols-12 gap-4 items-center px-6 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <div class="col-span-1 text-center text-gray-600">{{ props.listRoles.from + index }}</div>
                        <div class="col-span-3 text-center font-medium text-gray-800">{{ role.name }}</div>
                        <div class="col-span-5">
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span v-for="permission in role.permissions" :key="permission.id"
                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ permission.name }}
                                </span>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <div class="flex justify-center space-x-2">
                                <!-- Edit Button -->
                                <Link :href="route('admin.role.edit', role.id)"
                                      class="flex items-center gap-2 px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                    <span>Sửa</span>
                                </Link>
                                <!-- Delete Button -->
                                <button @click="hanldeDelete(role.id)"
                                        class="flex items-center gap-2 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span>Xoá</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="props.listRoles.data.length === 0" class="px-6 py-12 text-center text-gray-500">
                        Không có vai trò nào được tìm thấy
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="props.listRoles.last_page > 1" class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị từ {{ props.listRoles.from }} đến {{ props.listRoles.to }} trong tổng số {{ props.listRoles.total }} kết quả
                    </div>
                    <div class="flex space-x-1">
                        <!-- Previous Page Link -->
                        <Link v-if="props.listRoles.prev_page_url" 
                              :href="props.listRoles.prev_page_url"
                              class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                            &laquo; Trước
                        </Link>
                        <span v-else class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            &laquo; Trước
                        </span>

                        <!-- Page Numbers -->
                        <template v-for="page in props.listRoles.links">
                            <Link v-if="page.url && !page.active && page.label !== '...'" 
                                  :href="page.url"
                                  class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                                {{ page.label }}
                            </Link>
                            <span v-else-if="page.active" 
                                  class="px-3 py-1 border border-gray-300 rounded-md bg-blue-500 text-white">
                                {{ page.label }}
                            </span>
                            <span v-else-if="page.label === '...'" 
                                  class="px-3 py-1 border border-gray-300 rounded-md">
                                {{ page.label }}
                            </span>
                        </template>

                        <!-- Next Page Link -->
                        <Link v-if="props.listRoles.next_page_url" 
                              :href="props.listRoles.next_page_url"
                              class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                            Sau &raquo;
                        </Link>
                        <span v-else class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            Sau &raquo;
                        </span>
                    </div>
                </div>

                <!-- Flash Message -->
                <div v-if="props.flash.status" class="mt-4">
                    <div class="px-4 py-3 rounded-lg bg-green-100 text-green-800">
                        {{ props.flash.status }}
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
    listRoles: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            prev_page_url: null,
            next_page_url: null,
            from: 0,
            to: 0,
            total: 0,
            last_page: 0,
        })
    },
    flash: {
        type: Object,
        default: {}
    },
});
props.listRoles.links.pop();
props.listRoles.links.shift();
const hanldeDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá vai trò này?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.role.destroy', id));
    }
}
</script>