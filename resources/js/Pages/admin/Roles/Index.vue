<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Vai trò
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search Toggle Button -->
                    <button @click="toggleSearchForm"
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <!-- Add Role Button -->

                    <!-- <Waiting v-can="'admin.role.create'" route-name="admin.role.create" :route-params="{}"
                        :color="' bg-indigo-600 hover:bg-indigo-700 text-white'">
                        <i class="fas fa-plus"></i>
                        <span>Thêm vai trò</span>
                    </Waiting> -->
                </div>
            </div>

            <!-- Search Form (Hidden by Default) -->
            <div v-if="showSearchForm" class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form @submit.prevent="submitSearch" class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-2 gap-6 mb-6 w-full">
                        <!-- Role Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-tag mr-2 text-indigo-500"></i>
                                Tên vai trò
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.name" type="text" placeholder="Nhập tên vai trò..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Permission -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-key mr-2 text-indigo-500"></i>
                                Quyền hạn
                            </label>
                            <div class="relative">
                                <select v-model="searchForm.permission"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                    style="max-height: 50px; overflow-y: auto;">
                                    <option value="">Tất cả quyền hạn</option>
                                    <option class="text-black" v-for="permission in props.permissions"
                                        :value="permission.id" :key="permission.id">
                                        {{ permission.description }}
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <i
                                    class="fas fa-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="justify-center text-center space-x-3 pt-4 border-t border-gray-200 w-full">
                        <div class="flex justify-center">
                            <button type="button" @click="resetSearch"
                                class="flex items-center me-10 gap-2 px-5 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-undo-alt"></i>
                                Đặt lại
                            </button>
                            <button type="submit"
                                class="flex items-center ms-5 gap-2 px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-search"></i>
                                Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Role Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên vai trò
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quyền hạn
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Role Rows -->
                            <tr v-for="(role, index) in props.listRoles.data" :key="role.id"
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ props.listRoles.from + index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ role.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <button @click="togglePermissionsDropdown(role.id)"
                                            class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 transition-all duration-200 ease-in-out">
                                            <span>Xem quyền hạn ({{ role.permissions.length }})</span>
                                            <i :class="{
                                                'fas fa-chevron-down transform transition-transform duration-200': !activeDropdowns[role.id],
                                                'fas fa-chevron-up transform transition-transform duration-200': activeDropdowns[role.id]
                                            }"></i>
                                        </button>

                                        <!-- Dropdown Permissions -->
                                        <transition enter-active-class="transition ease-out duration-200"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="transition ease-in duration-150"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1">
                                            <div v-if="activeDropdowns[role.id]"
                                                class="z-10 mt-1 w-64 max-h-60 overflow-y-auto bg-white rounded-md shadow-lg border border-indigo-100 transform origin-top">
                                                <div class="py-1">
                                                    <div v-for="permission in role.permissions" :key="permission.id"
                                                        class="px-4 py-2 text-sm text-black hover:bg-indigo-50 transition-colors duration-100 ease-in-out">
                                                        <i class="fas fa-key mr-2 text-indigo-400"></i>
                                                        {{ permission.description }}
                                                    </div>
                                                    <div v-if="role.permissions.length === 0"
                                                        class="px-4 py-2 text-sm text-indigo-400 italic">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Không có quyền hạn
                                                    </div>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link :href="route('admin.role.edit', role.id)" v-can="'admin.role.edit'"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-indigo-100 text-indigo-600 rounded-md hover:bg-indigo-200 transition-colors">
                                        <i class="fas fa-edit text-sm"></i>
                                        <span>Sửa</span>
                                        </Link>
                                        <!-- <ConfirmModal :route-name="'admin.role.destroy'" :route-params="{
                                            id: role.id,
                                        }" title="Xác nhận xóa nhà cung cấp"e
                                            :message="`Bạn có chắc chắn muốn xóa nhà cung cấp ${role.name}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xoá`">

                                            <template #trigger="{
                                                openModal,
                                            }">
                                                <button @click="
                                                    openModal
                                                " class="text-sm px-3 py-2 bg-white text-red-600">
                                                    <i class="fas fa-trash-alt mr-1"></i>
                                                    Xóa
                                                </button>
                                            </template>

                                        </ConfirmModal> -->
                                    </div>
                                </td>
                            </tr>
                            <!-- Empty State -->
                            <tr v-if="props.listRoles.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Không có vai trò nào được tìm thấy
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ props.listRoles.from }}</span> đến
                        <span class="font-medium">{{ props.listRoles.to }}</span> của
                        <span class="font-medium">{{ props.listRoles.total }}</span> kết quả
                    </div>
                    <div class="flex space-x-1">
                        <Link v-if="props.listRoles.prev_page_url" :href="props.listRoles.prev_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>

                        <!-- Page Numbers -->
                        <template v-for="page in props.listRoles.links">
                            <Link v-if="page.url && !page.active && page.label !== '...'" :href="page.url"
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                            {{ page.label }}
                            </Link>
                            <span v-else-if="page.active" class="px-3 py-1 bg-indigo-600 text-white rounded-md">
                                {{ page.label }}
                            </span>
                            <span v-else-if="page.label === '...'"
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600">
                                {{ page.label }}
                            </span>
                        </template>

                        <Link v-if="props.listRoles.next_page_url" :href="props.listRoles.next_page_url"
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span v-else
                            class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
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
    permissions: {
        type: Object,
        default: () => ({})
    }
});

// Thêm state cho dropdown
const activeDropdowns = ref({});

const togglePermissionsDropdown = (roleId) => {
    // Đóng tất cả dropdown trước khi mở dropdown mới
    const newState = { ...activeDropdowns.value };
    Object.keys(newState).forEach(key => {
        newState[key] = false;
    });
    newState[roleId] = !activeDropdowns.value[roleId];
    activeDropdowns.value = newState;
};

const editLabel = () => {
    props.listRoles.links.forEach(element => {
        if (element.label == "&laquo; Previous") element.label = "Previous";
        if (element.label == "Next &raquo;") element.label = "Next"
    });
}
editLabel();
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

};

// Handle delete role

</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c4c4c4;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}
</style>