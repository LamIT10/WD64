<template>
    <AppLayout>
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
            <!-- Header -->
            <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
                <div>
                    <h5 class="text-lg text-indigo-700 font-semibold mb-2">
                        Danh sách Nhân viên
                    </h5>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input type="text" placeholder="Tìm theo mã, tên nhân viên..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <Waiting route-name="admin.users.create" :route-params="{}">
                        <i class="fas fa-plus"></i> Nhân viên
                    </Waiting>
                    <Waiting route-name="admin.users.create" :route-params="{}">
                        <i class="fa fa-sign-in icon-btn"></i> Nhập file
                    </Waiting>
                    <Waiting route-name="admin.users.create" :route-params="{}">
                        <i class="fa fa-file-export icon-btn"></i> Xuất file
                    </Waiting>
                    <div ref="dropdownRef" class="relative">
                        <button @click="toggleDropdown"
                            class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-columns"></i>
                            Cột hiển thị
                        </button>

                        <div v-if="showColumnDropdown"
                            class="absolute right-0 z-20 mt-2 w-80 bg-white border border-indigo-100 rounded-xl shadow-lg p-4">
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                                <label v-for="field in columnOptions" :key="field"
                                    class="flex items-center space-x-2 text-sm text-gray-800">
                                    <input type="checkbox" :value="field" v-model="visibleColumns"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                                    <span class="truncate">{{ columnLabels[field] }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
 <div class="mb-4 border-b border-gray-200">
    <div class="flex items-center">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <button @click="activeTab = 'active'"
                    :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'active', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'active' }"
                    class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                    Nhân viên đang làm việc
                </button>
            </li>
            <li class="mr-2">
                <button @click="activeTab = 'inactive'"
                    :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'inactive', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'inactive' }"
                    class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                    Nhân viên đã nghỉ việc
                </button>
            </li>
        </ul>
        
        <div class="relative ml-2" v-if="selectedUsers.length > 0" ref="actionDropdownRef">
            <button @click="toggleActionDropdown"
                class="px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-1.5">
                <i class="fa fa-bars icon-btn"></i>
                Hành động
            </button>
            <div v-if="showActionDropdown"
                class="absolute right-0 z-20 mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-md py-1">
                <template v-if="activeTab === 'active'">
                    <button @click="bulkUpdateStatus('inactive')"
                        class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-user-slash mr-2 text-xs"></i> Ngừng làm việc
                    </button>
                </template>
                <template v-else>
                    <button @click="bulkUpdateStatus('active')"
                        class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <i class="fas fa-user-check mr-2 text-xs"></i> Kích hoạt lại
                    </button>
                    <div class="border-t border-gray-100 my-1"></div>
                    <button @click="bulkDelete"
                        class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                        <i class="fas fa-trash-alt mr-2 text-xs"></i> Xóa
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>
            <!-- User Table -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- Checkbox column for selecting all -->
                                <th class="w-12 px-4 py-3 text-left">
                                    <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </th>

                                <th v-if="visibleColumns.includes('name')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-1">
                                        <span>Họ tên</span>
                                    </div>
                                </th>
                                <th v-if="visibleColumns.includes('employee_code')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Mã NV
                                </th>
                                <th v-if="visibleColumns.includes('email')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>
                                <th v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Điện thoại
                                </th>
                                <th v-if="visibleColumns.includes('gender')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Giới tính
                                </th>
                                <th v-if="visibleColumns.includes('address')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Địa chỉ
                                </th>
                                <th v-if="visibleColumns.includes('position')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Chức vụ
                                </th>
                                <th v-if="visibleColumns.includes('facebook')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Facebook
                                </th>
                                <th v-if="visibleColumns.includes('identity_number')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    CMND/CCCD
                                </th>
                                <th v-if="visibleColumns.includes('start_date')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Ngày bắt đầu
                                </th>
                                <th v-if="visibleColumns.includes('birthday')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Sinh nhật
                                </th>
                                <th v-if="visibleColumns.includes('note')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Ghi chú
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(user, index) in props.users.data" :key="user.id"
                                @click="$inertia.visit(route('admin.users.show', user.id))"
                                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                <!-- Checkbox for individual row -->
                                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                                    <input type="checkbox" :value="user.id" v-model="selectedUsers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </td>

                                <td v-if="visibleColumns.includes('name')" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img :src="user.avatar ? `/storage/${user.avatar}` : 'https://cdn-media.sforum.vn/storage/app/media/ctv_seo3/meme-meo-cuoi-51.jpg'"
                                            alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td v-if="visibleColumns.includes('employee_code')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                                    {{ user.employee_code || '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('email')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ user.email }}
                                </td>

                                <td v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ user.phone || '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('gender')"
                                    class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-0.5 rounded-full  text-gray-500 ">
                                        {{ user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : '-' }}
                                    </span>
                                </td>

                                <td v-if="visibleColumns.includes('address')"
                                    class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ user.address || '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('position')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <p class="rounded-2xl bg-blue-500 p-1 text-center m-0.5 text-white" v-for="role in user.roles">
                                        {{ role.name }}
                                    </p>
                                </td>

                                <td v-if="visibleColumns.includes('facebook')"
                                    class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ user.facebook || '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('identity_number')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ user.identity_number || '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('start_date')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ user.start_date ? new Date(user.start_date).toLocaleDateString('vi-VN') : '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('birthday')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ user.birthday ? new Date(user.birthday).toLocaleDateString('vi-VN') : '-' }}
                                </td>

                                <td v-if="visibleColumns.includes('note')"
                                    class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ user.note || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <div class="text-sm text-gray-600 font-medium">
                        Hiển thị
                        <span class="font-semibold">{{ users.from }}</span> đến
                        <span class="font-semibold">{{ users.to }}</span> của
                        <span class="font-semibold">{{ users.total }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="users.prev_page_url" :href="addStatusToUrl(users.prev_page_url)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="text-sm text-gray-600">
                            Trang {{ users.current_page }} / {{ users.last_page }}
                        </span>
                        <Link v-if="users.next_page_url" :href="addStatusToUrl(users.next_page_url)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <i class="fas fa-chevron-right"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';

// Props
const props = defineProps({
    users: Object,
    status: String,
});
console.log(props.users);
// Tab control
const activeTab = ref(props.status || 'active');

// Cột hiển thị
const visibleColumns = ref([
    'name', 'employee_code', 'email', 'phone', 'gender', 'address', 'position', 'start_date', 'facebook', 'identity_number', 'birthday', 'note'
]);

const columnOptions = [
    'name', 'employee_code', 'email', 'phone', 'gender', 'address', 'position', 'start_date', 'facebook', 'identity_number', 'birthday', 'note'
];

const columnLabels = {
    name: 'Họ tên',
    employee_code: 'Mã nhân viên',
    email: 'Email',
    phone: 'Điện thoại',
    gender: 'Giới tính',
    address: 'Địa chỉ',
    position: 'Chức vụ',
    start_date: 'Ngày bắt đầu',
    facebook: 'Facebook',
    note: 'Ghi chú',
    identity_number: 'Số CMND/CCCD',
    birthday: 'Ngày sinh'
};

// --- Dropdown hiển thị cột ---
const showColumnDropdown = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    showColumnDropdown.value = !showColumnDropdown.value;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showColumnDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

// --- Checkbox chọn người dùng ---
const selectedUsers = ref([]);
const selectAll = ref(false);

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedUsers.value = props.users.data.map(user => user.id);
    } else {
        selectedUsers.value = [];
    }
};

watch(selectedUsers, (newVal) => {
    if (newVal.length === props.users.data.length) {
        selectAll.value = true;
    } else {
        selectAll.value = false;
    }
});

watch(activeTab, () => {
    selectedUsers.value = [];
    selectAll.value = false;
});

// Khi chuyển tab, gửi yêu cầu đến server với query parameter 'status'
watch(activeTab, (newTab) => {
    router.get(
        route('admin.users.index'),
        { status: newTab },
        { preserveState: true, preserveScroll: true }
    );
});

// Đồng bộ activeTab với props.status sau mỗi lần server trả về dữ liệu
watch(() => props.status, (newStatus) => {
    activeTab.value = newStatus || 'active';
});

// Hàm thêm query parameter 'status' vào URL phân trang
const addStatusToUrl = (url) => {
    const urlObj = new URL(url);
    urlObj.searchParams.set('status', activeTab.value);
    return urlObj.toString();
};
</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #a0aec0;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

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
</style>