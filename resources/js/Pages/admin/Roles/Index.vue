<template>
    <div>
        <AppLayout>
            <div class="container mx-auto px-4 py-6">
                <!-- Header, Search Button, and Add Button -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Danh sách vai trò</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Search Toggle Button -->
                        <button @click="toggleSearchForm"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-search"></i>
                            <span>Tìm kiếm</span>
                        </button>
                        <!-- Add Role Button -->
                        <Link :href="route('admin.role.create')"
                            class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Thêm vai trò</span>
                        </Link>
                    </div>
                </div>

                <!-- Search Form (Hidden by Default) -->
                <div v-if="showSearchForm" class="mb-6 bg-white rounded-xl shadow-md p-6">
                    <form @submit.prevent="submitSearch" class="flex flex-col gap-4 w-full">
                        <div class="grid grid-cols-2  gap-6 mb-6 w-full">
                            <!-- Role Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-user-tag mr-2 text-blue-500"></i>
                                    Tên vai trò
                                </label>
                                <div class="relative">
                                    <input v-model="searchForm.name" type="text" placeholder="Nhập tên vai trò..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Permission -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-key mr-2 text-purple-500"></i>
                                    Quyền hạn
                                </label>
                                <div class="relative">
                                    <select v-model="searchForm.permission"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 appearance-none transition-all">
                                        <option value="">Tất cả quyền hạn</option>
                                        <option v-for="permission in permissions" :value="permission.id"
                                            :key="permission.id">
                                            {{ permission.name }}
                                        </option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                    <i
                                        class="fas fa-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Status -->
                            <!-- <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-toggle-on mr-2 text-green-500"></i>
                                    Trạng thái
                                </label>
                                <select v-model="searchForm.status"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 appearance-none transition-all">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <i
                                    class="fas fa-toggle-on absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div> -->
                            <!-- </div> -->

                            <!-- Second Row -->
                            <!-- Date Range -->
                            <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="far fa-calendar-alt mr-2 text-orange-500"></i>
                                    Ngày tạo
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input v-model="searchForm.date_from" type="date"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                        <i
                                            class="far fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                    <div class="relative">
                                        <input v-model="searchForm.date_to" type="date"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                        <i
                                            class="far fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Sort Options -->
                            <!-- <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-sort-amount-down mr-2 text-indigo-500"></i>
                                    Sắp xếp
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <select v-model="searchForm.sort_field"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="created_at">Ngày tạo</option>
                                        <option value="name">Tên</option>
                                        <option value="updated_at">Ngày cập nhật</option>
                                    </select>
                                    <select v-model="searchForm.sort_direction"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-all">
                                        <option value="asc">Tăng dần (A-Z)</option>
                                        <option value="desc">Giảm dần (Z-A)</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>

                        <!-- Action Buttons -->
                        <div class="justify-center text-center space-x-3 pt-4 border-t border-gray-200 w-full">
                            <div class="flex justify-center">
                                <button type="button" @click="resetSearch"
                                    class="flex items-center me-10 gap-2 px-5 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-undo-alt"></i>
                                    Đặt lại
                                </button>
                                <button type="submit"
                                    class="flex items-center ms-5 gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-search"></i>
                                    Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Table Header -->
                    <div
                        class="grid grid-cols-12 gap-4 bg-gray-100 px-6 py-4 font-semibold text-gray-700 uppercase text-sm">
                        <div class="col-span-1 text-center">#</div>
                        <div class="col-span-3 text-center">Tên vai trò</div>
                        <div class="col-span-5 text-center">Quyền hạn</div>
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
                                    class="flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                                <span>Sửa</span>
                                </Link>
                                <!-- Delete Button -->
                                <button @click="handleDelete(role.id)"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
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
                        Hiển thị từ {{ props.listRoles.from }} đến {{ props.listRoles.to }} trong tổng số {{
                            props.listRoles.total }} kết quả
                    </div>
                    <div class="flex space-x-1">
                        <!-- Previous Page Link -->
                        <Link v-if="props.listRoles.prev_page_url" :href="props.listRoles.prev_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                        « Trước
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            « Trước
                        </span>

                        <!-- Page Numbers -->
                        <template v-for="page in props.listRoles.links">
                            <Link v-if="page.url && !page.active && page.label !== '...'" :href="page.url"
                                class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                            {{ page.label }}
                            </Link>
                            <span v-else-if="page.active"
                                class="px-3 py-1 border border-gray-300 rounded-md bg-blue-500 text-white">
                                {{ page.label }}
                            </span>
                            <span v-else-if="page.label === '...'" class="px-3 py-1 border border-gray-300 rounded-md">
                                {{ page.label }}
                            </span>
                        </template>

                        <!-- Next Page Link -->
                        <Link v-if="props.listRoles.next_page_url" :href="props.listRoles.next_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100">
                        Sau »
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            Sau »
                        </span>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';

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
    permissions : {
        type: Object,
        default: {}
    }
});

// Search form state
const showSearchForm = ref(false);
const searchForm = useForm({
    name: '',
    permission: '',
});

// Toggle search form visibility
const toggleSearchForm = () => {
    showSearchForm.value = !showSearchForm.value;
};

// Submit search form
const submitSearch = () => {
    searchForm.get(route('admin.role.index'));
};

// Reset search form
const resetSearch = () => {
    searchForm.reset();
    searchForm.get(route('admin.role.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

// Handle delete role
const handleDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá vai trò này?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.role.destroy', id));
    }
};
</script>