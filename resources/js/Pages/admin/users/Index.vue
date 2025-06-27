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
                        <input type="text" placeholder="Tìm theo mã, tên nhân viên..." v-model="search"
                            @keydown.enter="handleSearch"
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <Waiting route-name="admin.users.create" :route-params="{}">
                        <i class="fas fa-plus"></i> Nhân viên
                    </Waiting>
                    <!-- <Waiting route-name="#" :route-params="{}">
                        <i class="fa fa-sign-in icon-btn"></i> Nhập file
                    </Waiting>
                    <Waiting route-name="#" :route-params="{}">
                        <i class="fa fa-file-export icon-btn"></i> Xuất file
                    </Waiting> -->
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
            <div v-if="search" class="mb-2 text-sm text-gray-600 italic">
                Kết quả tìm kiếm cho: "<span class="font-medium text-indigo-600">{{ search }}</span>"
            </div>
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
                    <!-- Dropdown thao tác-->
                    <div class="relative ml-2" v-if="selectedUsers.length > 0" ref="actionDropdownRef">
                        <button @click="toggleActionDropdown"
                            class="px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-1.5">
                            <i class="fa fa-bars icon-btn"></i>
                            Thao tác
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
                                    <i class="fas fa-user-check mr-2 text-xs"></i> Quay lại làm việc
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
                        <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:text-gray-400">
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
                            <tr v-for="(user, index) in props.users.data" :key="user.id" @click="handleClick(user.id)"
                                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                <!-- Checkbox for individual row -->
                                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                                    <input type="checkbox" :value="user.id" v-model="selectedUsers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </td>
                                <td v-if="visibleColumns.includes('name')" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center ">
                                        <img :src="user.avatar ? `/storage/${user.avatar}` : '/images/default-ava.png'"
                                            alt="Avatar" class="h-10 w-10 object-cover shadow-sm rounded-md">
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
                                    <span class="px-2.5 py-0.5 rounded-full text-gray-500">
                                        {{ user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : '-' }}
                                    </span>
                                </td>
                                <td v-if="visibleColumns.includes('address')"
                                    class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ user.address || '-' }}
                                </td>
                                <td v-if="visibleColumns.includes('position')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <p class="rounded-2xl font-medium p-1 bg-gray-100 text-center  m-0.5">
                                        {{ user.position || '-' }}
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
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <div class="text-sm text-gray-600 font-medium">
                        Hiển thị
                        <span class="font-semibold">{{ props.users.from }}</span> đến
                        <span class="font-semibold">{{ props.users.to }}</span> của
                        <span class="font-semibold">{{ props.users.total }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="props.users.prev_page_url" :href="addStatusToUrl(props.users.prev_page_url)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="text-sm text-gray-600">
                            Trang {{ props.users.current_page }} / {{ props.users.last_page }}
                        </span>
                        <Link v-if="props.users.next_page_url" :href="addStatusToUrl(props.users.next_page_url)"
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
import { route } from 'ziggy-js';

// Props
const props = defineProps({
    users: Object,
    status: String,
    search: String,
});
// const search = ref('');
// console.log(props.users);
// Tab control
const activeTab = ref(props.status || 'active');

// Cột hiển thị

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
const visibleColumns = ref(
    localStorage.getItem('visibleColumns')
        ? JSON.parse(localStorage.getItem('visibleColumns'))
        : [
            'name', 'employee_code', 'email', 'phone', 'gender', 'address',
            'position', 'start_date', 'facebook', 'identity_number', 'birthday', 'note'
        ]
);

// Lưu visibleColumns vào localStorage khi thay đổi
watch(visibleColumns, (newColumns) => {
    localStorage.setItem('visibleColumns', JSON.stringify(newColumns));
}, { deep: true });
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

// --- Dropdown hành động ---
const showActionDropdown = ref(false);
const actionDropdownRef = ref(null);

const toggleActionDropdown = () => {
    showActionDropdown.value = !showActionDropdown.value;
};

const handleActionClickOutside = (event) => {
    if (actionDropdownRef.value && !actionDropdownRef.value.contains(event.target)) {
        showActionDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('click', handleActionClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('click', handleActionClickOutside);
});

// --- Checkbox chọn nhân viên ---
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
    if (newVal.length === props.users.data.length && newVal.length > 0) {
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

// Cập nhật trạng thái hàng loạt
const bulkUpdateStatus = (newStatus) => {

    if (!confirm(`Bạn có chắc muốn chuyển ${selectedUsers.value.length} nhân viên sang trạng thái ${newStatus === 'active' ? 'Đang làm việc' : 'Ngừng làm việc'}?`)) {
        return;
    }
    router.post(
        route('admin.users.bulk-update-status'),
        {
            user_ids: selectedUsers.value,
            status: newStatus,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedUsers.value = [];
                selectAll.value = false;
                showActionDropdown.value = false;
            },
        }
    );
};

// Xóa nhân viên hàng loạt
const bulkDelete = () => {

    if (!confirm(`Bạn có chắc muốn xóa ${selectedUsers.value.length} nhân viên? Hành động này không thể hoàn tác!`)) {
        return;
    }
    router.post(
        route('admin.users.bulk-delete'),
        {
            user_ids: selectedUsers.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedUsers.value = [];
                selectAll.value = false;
                showActionDropdown.value = false;
            },
        }
    );
};

// Hàm thêm query parameter 'status' vào URL phân trang
const addStatusToUrl = (url) => {
    const urlObj = new URL(url);
    urlObj.searchParams.set('status', activeTab.value);
    return urlObj.toString();
};

// Xử lý sự kiện click vào hàng
const handleClick = (userId) => {
    // Kiểm tra xem có văn bản nào đang được chọn không
    if (window.getSelection().toString()) {
        // Nếu có, ngừng hành động click (không chuyển hướng)
        return;
    }
    // Nếu không có văn bản đang được chọn, chuyển hướng
    router.visit(route('admin.users.show', userId));
};

// Xử lý sự kiện tìm kiếm
const handleSearch = () => {
    const params = {
        status: activeTab.value,
    };

    if (search.value) {
        params.search = search.value;
    }

    router.get(route('admin.users.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};

// giữ giá trị tìm kiếm khi chuyển tab
watch(activeTab, (newTab) => {
    const params = {
        status: newTab,
    };

    if (search.value) {
        params.search = search.value;
    }

    router.get(route('admin.users.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
});


// reload mất search
const search = ref('');
onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.has('search')) {
        params.delete('search');
        const cleanUrl = params.toString()
            ? `${window.location.pathname}?${params.toString()}`
            : window.location.pathname;
        window.location.replace(cleanUrl); // ← chuyển URL, reload lại ngay
    }
});

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