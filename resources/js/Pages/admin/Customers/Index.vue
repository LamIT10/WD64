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
                        <input type="text" v-model="searchQuery" @input="handleSearch"
                            placeholder="Tìm theo tên, số điện thoại..."
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
                        <button @click="toggleColumnDropdown"
                            class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-columns"></i>
                            Cột hiển thị
                        </button>
                        <div v-if="showColumnDropdown"
                            class="absolute right-0 z-20 mt-2 w-80 bg-white border border-indigo-100 rounded-xl shadow-lg p-6">
                            <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                                <label v-for="field in columnOptions" :key="field"
                                    class="flex items-center space-x-2 text-sm text-gray-700">
                                    <input type="checkbox" :value="field" v-model="visibleColumns"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                                    <span>{{ columnLabels[field] }}</span>
                                </label>
                            </div>
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
                                <th class="w-12 px-4 py-3 text-left">
                                    <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                </th>
                                <th v-if="visibleColumns.includes('name')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Tên
                                </th>
                                <th v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    SĐT
                                </th>
                                <th v-if="visibleColumns.includes('email')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>
                                <th v-if="visibleColumns.includes('current_debt')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Công nợ
                                </th>
                                <th v-if="visibleColumns.includes('total_spent')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Tổng chi tiêu
                                </th>
                                <th v-if="visibleColumns.includes('max_debt_limit')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Giới hạn công nợ
                                </th>
                                <th v-if="visibleColumns.includes('rank')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Hạng
                                </th>
                                <th v-if="visibleColumns.includes('status')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Trạng thái
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="customer in customers.data || []" :key="customer.id"
                                @click="$inertia.visit(route('admin.customers.show', customer.id))"
                                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                                    <input type="checkbox" :value="customer.id" v-model="selectedCustomers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                </td>
                                <td v-if="visibleColumns.includes('name')" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img :src="customer.avatar ? `/storage/${customer.avatar}` : 'https://cdn-media.sforum.vn/storage/app/media/ctv_seo3/meme-meo-cuoi-51.jpg'"
                                            alt="Avatar"
                                            class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td v-if="visibleColumns.includes('phone')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ customer.phone || '-' }}
                                </td>
                                <td v-if="visibleColumns.includes('email')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ customer.email || '-' }}
                                </td>
                                <td v-if="visibleColumns.includes('current_debt')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatNumber(customer.current_debt) }}
                                </td>
                                <td v-if="visibleColumns.includes('total_spent')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatNumber(customer.total_spent) }}
                                </td>
                                <td v-if="visibleColumns.includes('max_debt_limit')"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatNumber(customer.max_debt_limit) }}
                                </td>
                                <td v-if="visibleColumns.includes('rank')" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full" :class="{
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
                                <td v-if="visibleColumns.includes('status')" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded-full" :class="{
                                        'bg-green-100 text-green-800': customer.status === 'active',
                                        'bg-red-100 text-red-800': customer.status === 'inactive',
                                        'bg-yellow-100 text-yellow-800': customer.status === 'debt_exceeded'
                                    }">
                                        {{ statusLabel(customer.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                                    <div class="relative inline-block text-left">
                                        <button @click="toggleActionDropdownSingle(customer.id)"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <i class="fas fa-ellipsis-v"></i>
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
                                                <ConfirmModal :route-name="'admin.customers.destroy'" :route-params="{
                                                    id: customer.id,
                                                }" title="Xác nhận xóa khách hàng"
                                                    :message="`Bạn có chắc chắn muốn xóa khách hàng ${customer.name}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xóa`">
                                                    <template #trigger="{
                                                        openModal,
                                                    }">
                                                        <button @click="openModal" class="text-sm px-3 py-2 bg-white text-red-600">
                                                            <i class="fas fa-trash-alt mr-1"></i>
                                                            Xóa
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
                        Hiển thị
                        <span class="font-semibold">{{ customers.from || 1 }}</span> đến
                        <span class="font-semibold">{{ customers.to || 0 }}</span> của
                        <span class="font-semibold">{{ customers.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-2">
                        <Link v-if="customers.prev_page_url" :href="addSearchToUrl(customers.prev_page_url)"
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
                        <Link v-if="customers.next_page_url" :href="addSearchToUrl(customers.next_page_url)"
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

const { customers } = defineProps({
    customers: Object,
});

const searchQuery = ref('');
let searchTimeout = null;
const showColumnDropdown = ref(false);
const dropdownRef = ref(null);
const activeDropdown = ref(null);
const selectedCustomers = ref([]);
const selectAll = ref(false);

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
    status: 'Trạng thái',
};
const visibleColumns = ref(
    localStorage.getItem('customerVisibleColumns')
        ? JSON.parse(localStorage.getItem('customerVisibleColumns'))
        : ['name', 'phone', 'email', 'current_debt', 'total_spent', 'max_debt_limit', 'rank', 'status']
);

const formatNumber = (value) => {
    if (value === null || value === undefined || isNaN(Number(value))) {
        return '0';
    }
    return Number(value).toLocaleString('vi-VN', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
};

const statusLabel = (status) => {
    switch (status) {
        case 'active':
            return 'Hoạt động';
        case 'inactive':
            return 'Không hoạt động';
        case 'debt_exceeded':
            return 'Vượt công nợ';
        default:
            return '-';
    }
};

watch(visibleColumns, (newColumns) => {
    localStorage.setItem('customerVisibleColumns', JSON.stringify(newColumns));
}, { deep: true });

const toggleColumnDropdown = () => {
    showColumnDropdown.value = !showColumnDropdown.value;
};

const toggleActionDropdownSingle = (id) => {
    activeDropdown.value = activeDropdown.value === id ? null : id;
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showColumnDropdown.value = false;
    }
    if (!event.target.closest('.action-dropdown')) {
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
        selectedCustomers.value = customers.data.map(customer => customer.id);
    } else {
        selectedCustomers.value = [];
    }
};

watch(selectedCustomers, (newVal) => {
    if (newVal.length === customers.data.length && newVal.length > 0) {
        selectAll.value = true;
    } else {
        selectAll.value = false;
    }
});

const handleSearch = () => {
    console.log('Search query:', searchQuery.value); // Debug
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('admin.customers.index'),
            { search: searchQuery.value },
            { preserveState: true, preserveScroll: true }
        );
    }, 500); // Chờ 500ms trước khi gửi request
};

const bulkDelete = () => {
    if (!confirm(`Bạn có chắc muốn xóa ${selectedCustomers.value.length} khách hàng? Hành động này không thể hoàn tác!`)) {
        return;
    }
    router.post(
        route('admin.customers.bulk-delete'),
        {
            customer_ids: selectedCustomers.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                selectedCustomers.value = [];
                selectAll.value = false;
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

const handleDelete = (id) => {
    if (!confirm('Bạn có chắc muốn xóa khách hàng này không?')) {
        return;
    }
    router.delete(route('admin.customers.destroy', id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            activeDropdown.value = null;
            const toast = document.querySelector('toast');
            if (toast && toast.showLocalToast) {
                toast.showLocalToast('Xóa khách hàng thành công!', 'success');
            }
        },
        onError: (errors) => {
            console.error('Lỗi từ backend:', errors);
            const toast = document.querySelector('toast');
            if (toast && toast.showLocalToast) {
                toast.showLocalToast('Lỗi khi xóa khách hàng: ' + (errors.message || 'Vui lòng thử lại!'), 'error');
            }
        },
    });
};

const addSearchToUrl = (url) => {
    const urlObj = new URL(url);
    if (searchQuery.value) {
        urlObj.searchParams.set('search', searchQuery.value);
    }
    return urlObj.toString();
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