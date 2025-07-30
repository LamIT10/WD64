<template>
    <AppLayout>
        <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
            <!-- Header -->
            <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
                <div>
                    <h5 class="text-lg text-indigo-700 font-semibold mb-2">Danh sách Khách hàng</h5>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input type="text" v-model="search" @keydown.enter="handleSearch"
                            placeholder="Tìm theo tên, số điện thoại, email..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <Waiting route-name="admin.customers.create" :route-params="{}">
                        <i class="fas fa-plus"></i> Khách hàng
                    </Waiting>
                    <div ref="dropdownRef" class="relative">
                        <button @click="toggleDropdown"
                            class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-columns"></i> Cột hiển thị
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
                                Khách hàng đang hoạt động
                            </button>
                        </li>
                        <li class="mr-2">
                            <button @click="activeTab = 'inactive'"
                                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'inactive', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'inactive' }"
                                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                                Khách hàng không hoạt động
                            </button>
                        </li>
                    </ul>
                    <!-- Dropdown thao tác -->
                    <div class="relative ml-2" v-if="selectedCustomers.length > 0" ref="actionDropdownRef">
                        <button @click="toggleActionDropdown"
                            class="px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-1.5">
                            <i class="fa fa-bars icon-btn"></i> Thao tác
                        </button>
                        <div v-if="showActionDropdown"
                            class="absolute right-0 z-20 mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-md py-1">
                            <button @click="bulkDelete"
                                class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                                <i class="fas fa-trash-alt mr-2 text-xs"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Table -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300">
                            <tr>
                                <th class="w-12 px-4 py-3 text-left">
                                    <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                </th>
                                <th v-if="visibleColumns.includes('name')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-1">
                                        <span>Tên</span>
                                    </div>
                                </th>
                                <th v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Số điện thoại
                                </th>
                                <th v-if="visibleColumns.includes('email')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>
                                <th v-if="visibleColumns.includes('remaining_amount')"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Công nợ
                                </th>
                                <th v-if="visibleColumns.includes('rank')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Hạng
                                </th>
                                <th v-if="visibleColumns.includes('status')"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Trạng thái
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="customer in props.customers.data" :key="customer.id"
                                @click="handleClick(customer.id)"
                                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                                    <input type="checkbox" :value="customer.id" v-model="selectedCustomers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                </td>
                                <td v-if="visibleColumns.includes('name')" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img :src="customer.avatar ? `/storage/${customer.avatar}` : '/images/default-ava.png'"
                                            alt="Avatar" class="h-10 w-10 object-cover shadow-sm rounded-md" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{
                                    customer.phone || '-' }}</td>
                                <td v-if="visibleColumns.includes('email')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.email || '-'
                                    }}</td>
                                <td v-if="visibleColumns.includes('remaining_amount')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-red-700 font-semibold text-right">
                                    {{ formatCurrency(customer.remaining_amount) }}
                                </td>
                                <td v-if="visibleColumns.includes('rank')"
                                    class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                                        'bg-gray-200 text-gray-800': customer.rank?.name === 'Sắt',
                                        'bg-amber-200 text-amber-800': customer.rank?.name === 'Đồng',
                                        'bg-slate-200 text-slate-800': customer.rank?.name === 'Bạc',
                                        'bg-yellow-200 text-yellow-800': customer.rank?.name === 'Vàng',
                                        'bg-blue-200 text-blue-800': customer.rank?.name === 'Bạch Kim',
                                        'bg-gray-100 text-gray-700': !customer.rank
                                    }">
                                        {{ customer.rank ? customer.rank.name : '-' }}
                                    </span>
                                </td>
                                <td v-if="visibleColumns.includes('status')"
                                    class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                                        'bg-green-100 text-green-800': customer.status === 'active',
                                        'bg-red-100 text-red-800': customer.status === 'inactive'
                                    }">
                                        {{ statusLabel(customer.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                                    <div class="relative inline-block text-left">
                                        <button @click="toggleActionDropdownSingle(customer.id)"
                                            data-customer-id="customer.id"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 4 15">
                                                <path
                                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                            </svg>
                                        </button>
                                        <div v-if="activeDropdown === customer.id"
                                            class="absolute right-0 z-20 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                            <div class="py-1">
                                                <Link :href="route('admin.customers.show', customer.id)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                <i class="fas fa-eye mr-2 text-blue-600"></i> Chi tiết
                                                </Link>
                                                <Link :href="route('admin.customers.edit', customer.id)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                <i class="fas fa-edit mr-2 text-indigo-600"></i> Sửa
                                                </Link>
                                                <Link :href="route('admin.customers.debt-orders', customer.id)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                                <i class="fa fa-credit-card-alt text-indigo-600 mr-2"
                                                    aria-hidden="true"></i> Công nợ
                                                </Link>
                                                <ConfirmModal :route-name="'admin.customers.destroy'"
                                                    :route-params="{ id: customer.id }" title="Xác nhận xóa khách hàng"
                                                    :message="`Bạn có chắc chắn muốn xóa khách hàng ${customer.name}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xóa`">
                                                    <template #trigger="{ openModal }">
                                                        <button @click="openModal"
                                                            class="text-sm px-3 py-2 bg-white text-red-600">
                                                            <i class="fas fa-trash-alt mr-1"></i> Xóa
                                                        </button>
                                                    </template>
                                                </ConfirmModal>
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
                        Hiển thị <span class="font-semibold">{{ props.customers.from || 1 }}</span> đến <span
                            class="font-semibold">{{
                            props.customers.to || 0 }}</span> của <span class="font-semibold">{{ props.customers.total
                            || 0 }}</span>
                        kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="props.customers.prev_page_url" :href="addStatusToUrl(props.customers.prev_page_url)"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
                        <i class="fas fa-chevron-left"></i>
                        </Link>
                        <span v-else
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="text-sm text-gray-600">
                            Trang {{ props.customers.current_page || 1 }} / {{ props.customers.last_page || 1 }}
                        </span>
                        <Link v-if="props.customers.next_page_url" :href="addStatusToUrl(props.customers.next_page_url)"
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
        <Toast />
    </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import Toast from '../../components/Toast.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    customers: Object,
    status: String,
    search: String,
});

const activeTab = ref(props.status || 'active');
const activeDropdown = ref(null); // Thêm biến cho dropdown hành động

const columnOptions = [
    'name', 'phone', 'email', 'rank', 'status', 'remaining_amount'
];

const columnLabels = {
    name: 'Tên',
    phone: 'Số điện thoại',
    email: 'Email',
    rank: 'Hạng',
    status: 'Trạng thái',
    remaining_amount: 'Công nợ'
};

const visibleColumns = ref(
    localStorage.getItem('customerVisibleColumns')
        ? JSON.parse(localStorage.getItem('customerVisibleColumns'))
        : ['name', 'phone', 'email', 'rank', 'status', 'remaining_amount']
);

const search = ref(props.search || '');
const showColumnDropdown = ref(false);
const dropdownRef = ref(null);
const showActionDropdown = ref(false);
const actionDropdownRef = ref(null);
const selectedCustomers = ref([]);
const selectAll = ref(false);

const statusLabel = (status) => {
    switch (status) {
        case 'active':
            return 'Hợp tác';
        case 'inactive':
            return 'Ngừng hợp tác';
        default:
            return '-';
    }
};

watch(visibleColumns, (newColumns) => {
    localStorage.setItem('customerVisibleColumns', JSON.stringify(newColumns));
}, { deep: true });

const toggleDropdown = () => {
    showColumnDropdown.value = !showColumnDropdown.value;
};

const toggleActionDropdown = () => {
    showActionDropdown.value = !showActionDropdown.value;
};

const toggleActionDropdownSingle = (customerId) => {
    activeDropdown.value = activeDropdown.value === customerId ? null : customerId;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showColumnDropdown.value = false;
    }
    if (actionDropdownRef.value && !actionDropdownRef.value.contains(event.target)) {
        showActionDropdown.value = false;
    }
    if (activeDropdown.value && !event.target.closest(`[data-customer-id="${activeDropdown.value}"]`)) {
        activeDropdown.value = null;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedCustomers.value = props.customers.data.map(customer => customer.id);
    } else {
        selectedCustomers.value = [];
    }
};

watch(selectedCustomers, (newVal) => {
    if (newVal.length === props.customers.data.length && newVal.length > 0) {
        selectAll.value = true;
    } else {
        selectAll.value = false;
    }
});

watch(activeTab, () => {
    selectedCustomers.value = [];
    selectAll.value = false;
});

watch(activeTab, (newTab) => {
    const params = { status: newTab };
    if (search.value) {
        params.search = search.value;
    }
    router.get(route('admin.customers.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
});

watch(() => props.status, (newStatus) => {
    activeTab.value = newStatus || 'active';
});

const bulkDelete = () => {
    if (!confirm(`Bạn có chắc muốn xóa ${selectedCustomers.value.length} khách hàng? Hành động này không thể hoàn tác!`)) {
        return;
    }
    router.post(
        route('admin.customers.bulk-delete'),
        { customer_ids: selectedCustomers.value },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedCustomers.value = [];
                selectAll.value = false;
                showActionDropdown.value = false;
                const toast = document.querySelector('toast');
                if (toast && toast.showLocalToast) {
                    toast.showLocalToast('Xóa hàng loạt thành công!', 'success');
                }
            },
            onError: (errors) => {
                console.error('Lỗi từ backend:', errors);
                const toast = document.querySelector('toast');
                if (toast && toast.showLocalToast) {
                    toast.showLocalToast('Lỗi khi xóa hàng loạt: ' + (errors.message || 'Vui lòng thử lại!'), 'error');
                }
            },
        }
    );
};

const addStatusToUrl = (url) => {
    const urlObj = new URL(url);
    urlObj.searchParams.set('status', activeTab.value);
    if (search.value) {
        urlObj.searchParams.set('search', search.value);
    }
    return urlObj.toString();
};

const handleClick = (customerId) => {
    if (window.getSelection().toString()) {
        return;
    }
    router.visit(route('admin.customers.show', customerId));
};

const handleSearch = () => {
    const params = { status: activeTab.value };
    if (search.value) {
        params.search = search.value;
    }
    router.get(route('admin.customers.index'), params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const deleteCustomer = (customerId) => {
    if (!confirm('Bạn có chắc muốn xóa khách hàng này? Hành động này không thể hoàn tác!')) {
        return;
    }
    router.delete(route('admin.customers.destroy', customerId), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const toast = document.querySelector('toast');
            if (toast && toast.showLocalToast) {
                toast.showLocalToast('Xóa khách hàng thành công!', 'success');
            }
        },
        onError: (errors) => {
            console.error('Lỗi khi xóa khách hàng:', errors);
            const toast = document.querySelector('toast');
            if (toast && toast.showLocalToast) {
                toast.showLocalToast('Lỗi khi xóa khách hàng: ' + (errors.message || 'Vui lòng thử lại!'), 'error');
            }
        },
    });
};
const formatCurrency = (value) => {
    if (value === null || value === undefined || isNaN(value)) return '0 ₫';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
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