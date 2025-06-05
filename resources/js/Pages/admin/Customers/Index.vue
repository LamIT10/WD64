<template>
    <AppLayout>
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
            <!-- Header -->
            <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
                <div>
                    <h5 class="text-lg text-indigo-700 font-semibold mb-2">
                        Danh sách Khách hàng
                    </h5>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input type="text" placeholder="Tìm theo tên, số điện thoại..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <Waiting route-name="admin.customers.create" :route-params="{}">
                        <i class="fas fa-plus"></i> Khách hàng
                    </Waiting>
                    <Waiting route-name="admin.customers.import" :route-params="{}">
                        <i class="fa fa-sign-in icon-btn"></i> Nhập file
                    </Waiting>
                    <Waiting route-name="admin.customers.export" :route-params="{}">
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
                                Khách hàng hoạt động
                            </button>
                        </li>
                        <li class="mr-2">
                            <button @click="activeTab = 'inactive'"
                                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'inactive', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'inactive' }"
                                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                                Khách hàng không hoạt động
                            </button>
                        </li>
                        <li class="mr-2">
                            <button @click="activeTab = 'blocked'"
                                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'blocked', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'blocked' }"
                                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                                Khách hàng bị khóa
                            </button>
                        </li>
                    </ul>
                    <div class="relative ml-2" v-if="selectedCustomers.length > 0" ref="actionDropdownRef">
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
                                    <i class="fas fa-user-slash mr-2 text-xs"></i> Đặt không hoạt động
                                </button>
                                <button @click="bulkUpdateStatus('blocked')"
                                    class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-lock mr-2 text-xs"></i> Khóa
                                </button>
                            </template>
                            <template v-else-if="activeTab === 'inactive'">
                                <button @click="bulkUpdateStatus('active')"
                                    class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-user-check mr-2 text-xs"></i> Kích hoạt
                                </button>
                                <button @click="bulkUpdateStatus('blocked')"
                                    class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-lock mr-2 text-xs"></i> Khóa
                                </button>
                            </template>
                            <template v-else>
                                <button @click="bulkUpdateStatus('active')"
                                    class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-user-check mr-2 text-xs"></i> Kích hoạt
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

            <!-- Customer Table -->
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
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tên
                                </th>
                                <th v-if="visibleColumns.includes('phone')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    SĐT
                                </th>
                                <th v-if="visibleColumns.includes('email')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Email
                                </th>
                                <th v-if="visibleColumns.includes('current_debt')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Công nợ
                                </th>
                                <th v-if="visibleColumns.includes('total_spent')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tổng chi tiêu
                                </th>
                                <th v-if="visibleColumns.includes('max_debt_limit')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Giới hạn công nợ
                                </th>
                                <th v-if="visibleColumns.includes('rank')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Hạng
                                </th>
                                <th v-if="visibleColumns.includes('status')"
                                    class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(customer, index) in customers.data || []" :key="customer.id"
                                @click="$inertia.visit(route('admin.customers.show', customer.id))"
                                class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-gray-50 transition-colors duration-200">
                                <!-- Checkbox for individual row -->
                                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                                    <input type="checkbox" :value="customer.id" v-model="selectedCustomers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </td>
                                <td v-if="visibleColumns.includes('name')" class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <img :src="customer.avatar ? `/storage/${customer.avatar}` : 'https://via.placeholder.com/40'"
                                            alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border border-indigo-200">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ customer.name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td v-if="visibleColumns.includes('phone')"
                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                    {{ customer.phone || 'Chưa cập nhật' }}
                                </td>
                                <td v-if="visibleColumns.includes('email')"
                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                    {{ customer.email || 'Chưa cập nhật' }}
                                </td>
                                <td v-if="visibleColumns.includes('current_debt')"
                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                    {{ customer.current_debt || 0 }}
                                </td>
                                <td v-if="visibleColumns.includes('total_spent')"
                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                    {{ customer.total_spent || 0 }}
                                </td>
                                <td v-if="visibleColumns.includes('max_debt_limit')"
                                    class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                    {{ customer.max_debt_limit || 0 }}
                                </td>
                                <td v-if="visibleColumns.includes('rank')" class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm"
                                        :class="{
                                            'bg-gray-200 text-gray-800': customer.rank?.name === 'Sắt',
                                            'bg-amber-200 text-amber-800': customer.rank?.name === 'Đồng',
                                            'bg-slate-200 text-slate-800': customer.rank?.name === 'Bạc',
                                            'bg-yellow-200 text-yellow-800': customer.rank?.name === 'Vàng',
                                            'bg-blue-100 text-blue-800': customer.rank?.name === 'Bạch Kim',
                                            'bg-gray-100 text-gray-700': !customer.rank
                                        }">
                                        {{ customer.rank ? customer.rank.name : 'Chưa có hạng' }}
                                    </span>
                                </td>
                                <td v-if="visibleColumns.includes('status')" class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': customer.status === 'active',
                                            'bg-red-100 text-red-800': customer.status === 'inactive',
                                            'bg-gray-100 text-gray-800': customer.status === 'blocked'
                                        }">
                                        {{ customer.status === 'active' ? 'Hoạt động' : customer.status === 'inactive' ? 'Không hoạt động' : 'Bị khóa' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium"
                                    @click.stop>
                                    <div class="relative inline-block text-left">
                                        <button class="text-gray-400 hover:text-gray-600 focus:outline-none"
                                            @click="toggleDropdown(customer.id)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div v-if="activeDropdown === customer.id"
                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                            <div class="py-1">
                                                <Link :href="route('admin.customers.show', customer.id)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    <i class="fas fa-eye mr-2 text-blue-600"></i> Chi tiết
                                                </Link>
                                                <Link :href="route('admin.customers.edit', customer.id)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    <i class="fas fa-edit mr-2 text-indigo-600"></i> Sửa
                                                </Link>
                                                <button @click="handleDelete(customer.id)"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                    <i class="fas fa-trash mr-2 text-red-500"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <div class="text-sm text-gray-600 font-medium">
                        Hiển thị
                        <span class="font-semibold">{{ customers.from || 1 }}</span> đến
                        <span class="font-semibold">{{ customers.to || 0 }}</span> của
                        <span class="font-semibold">{{ customers.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="customers.prev_page_url" :href="addStatusToUrl(customers.prev_page_url)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="text-sm text-gray-600">
                            Trang {{ customers.current_page || 1 }} / {{ customers.last_page || 1 }}
                        </span>
                        <Link v-if="customers.next_page_url" :href="addStatusToUrl(customers.next_page_url)"
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
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';

defineProps({
    customers: Object,
    status: String,
});

const activeTab = ref('active');

const visibleColumns = ref([
    'name', 'phone', 'email', 'current_debt', 'total_spent', 'max_debt_limit', 'rank', 'status'
]);

const columnOptions = [
    'name', 'phone', 'email', 'current_debt', 'total_spent', 'max_debt_limit', 'rank', 'status'
];

const columnLabels = {
    name: 'Tên',
    phone: 'Số điện thoại',
    email: 'Email',
    current_debt: 'Công nợ',
    total_spent: 'Tổng chi tiêu',
    max_debt_limit: 'Giới hạn công nợ',
    rank: 'Hạng',
    status: 'Trạng thái'
};

// Dropdown hiển thị cột
const showColumnDropdown = ref(false);
const dropdownRef = ref(null);


const toggleDropdown = (id) => {
    activeDropdown.value = activeDropdown.value === id ? null : id;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showColumnDropdown.value = false;
    }
    if (actionDropdownRef.value && !actionDropdownRef.value.contains(event.target)) {
        showActionDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Checkbox chọn khách hàng
const selectedCustomers = ref([]);
const selectAll = ref(false);

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedCustomers.value = customers.data.map(customer => customer.id);
    } else {
        selectedCustomers.value = [];
    }
};

watch(selectedCustomers, (newVal) => {
    if (newVal.length === customers.data.length) {
        selectAll.value = true;
    } else {
        selectAll.value = false;
    }
});

watch(activeTab, () => {
    selectedCustomers.value = [];
    selectAll.value = false;
});

// Khi chuyển tab, gửi yêu cầu đến server với query parameter 'status'
watch(activeTab, (newTab) => {
    selectedCustomers.value = [];
    selectAll.value = false;

    router.get(
        route('admin.customers.index'),
        { status: newTab },
        { preserveState: true, preserveScroll: true }
    );
});

// Đồng bộ activeTab với status sau mỗi lần server trả về dữ liệu
watch(() => status, (newStatus) => {
    activeTab.value = newStatus || 'active';
});

// Hàm thêm query parameter 'status' vào URL phân trang
const addStatusToUrl = (url) => {
    const urlObj = new URL(url);
    urlObj.searchParams.set('status', activeTab.value);
    return urlObj.toString();
};

// Dropdown hành động
const activeDropdown = ref(null);
const showActionDropdown = ref(false);
const actionDropdownRef = ref(null);

const toggleActionDropdown = () => {
    showActionDropdown.value = !showActionDropdown.value;
};

// Xử lý hành động hàng loạt
const bulkUpdateStatus = (status) => {
    if (confirm(`Bạn có chắc muốn thay đổi trạng thái của ${selectedCustomers.value.length} khách hàng thành ${status === 'active' ? 'Hoạt động' : status === 'inactive' ? 'Không hoạt động' : 'Bị khóa'}?`)) {
        useForm({
            ids: selectedCustomers.value,
            status: status
        }).put(route('admin.customers.bulk-update'), {
            onSuccess: () => {
                selectedCustomers.value = [];
                selectAll.value = false;
                showActionDropdown.value = false;
            }
        });
    }
};

const bulkDelete = () => {
    if (confirm(`Bạn có chắc muốn xóa ${selectedCustomers.value.length} khách hàng?`)) {
        useForm({
            ids: selectedCustomers.value
        }).delete(route('admin.customers.bulk-destroy'), {
            onSuccess: () => {
                selectedCustomers.value = [];
                selectAll.value = false;
                showActionDropdown.value = false;
            }
        });
    }
};

// Xử lý xóa từng khách hàng
const handleDelete = (id) => {
    if (confirm('Bạn có chắc muốn khóa hoặc xóa khách hàng này không?')) {
        useForm({}).delete(route('admin.customers.destroy', id), {
            onSuccess: () => {},
        });
    }
};
</script>

<style scoped>
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
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>