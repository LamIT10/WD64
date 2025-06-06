<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
        <div>
          <h5 class="text-lg text-indigo-700 font-semibold mb-2">Danh sách Kiểm kê</h5>
        </div>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" placeholder="Tìm theo mã, tên sản phẩm..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Waiting route-name="admin.inventory-audit.create" :route-params="{}">
            <i class="fas fa-plus"></i> Tạo phiếu kiểm kê
          </Waiting>
          <Waiting route-name="admin.inventory.import" :route-params="{}">
            <i class="fa fa-sign-in icon-btn"></i> Nhập file
          </Waiting>
          <Waiting route-name="admin.inventory.export" :route-params="{}">
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
              <button @click="activeTab = 'in_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'in_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'in_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Đang có hàng
              </button>
            </li>
            <li class="mr-2">
              <button @click="activeTab = 'low_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'low_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'low_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Sắp hết hàng
              </button>
            </li>
            <li class="mr-2">
              <button @click="activeTab = 'out_of_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'out_of_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'out_of_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Hết hàng
              </button>
            </li>
          </ul>
          <!-- Dropdown thao tác -->
          <div class="relative ml-2" v-if="selectedItems.length > 0" ref="actionDropdownRef">
            <button @click="toggleActionDropdown"
              class="px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-1.5">
              <i class="fa fa-bars icon-btn"></i>
              Thao tác
            </button>
            <div v-if="showActionDropdown"
              class="absolute right-0 z-20 mt-1 w-40 bg-white border border-gray-200 rounded-lg shadow-md py-1">
              <button @click="bulkUpdateStatus('in_stock')"
                class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                <i class="fas fa-check-circle mr-2 text-xs"></i> Đặt có hàng
              </button>
              <button @click="bulkUpdateStatus('out_of_stock')"
                class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                <i class="fas fa-times-circle mr-2 text-xs"></i> Đặt hết hàng
              </button>
              <div class="border-t border-gray-100 my-1"></div>
              <button @click="bulkDelete"
                class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                <i class="fas fa-trash-alt mr-2 text-xs"></i> Xóa
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Table -->
      <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="w-12 px-4 py-3 text-left">
                  <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </th>
                <th v-if="visibleColumns.includes('name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Tên sản phẩm
                </th>
                <th v-if="visibleColumns.includes('item_code')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Mã sản phẩm
                </th>
                <th v-if="visibleColumns.includes('category')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Danh mục
                </th>
                <th v-if="visibleColumns.includes('quantity')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Số lượng
                </th>
                <th v-if="visibleColumns.includes('warehouse')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Kho
                </th>
                <th v-if="visibleColumns.includes('status')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Trạng thái
                </th>
                <th v-if="visibleColumns.includes('last_updated')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Cập nhật cuối
                </th>
              </tr>
            </thead>
            <tbody v-if="props.inventory && props.inventory.data && props.inventory.data.length">
              <tr v-for="item in props.inventory.data" :key="item.id"
                @click="$inertia.visit(route('admin.inventory.show', item.id))"
                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                  <input type="checkbox" :value="item.id" v-model="selectedItems"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </td>
                <td v-if="visibleColumns.includes('name')" class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <img :src="item.image ? `/storage/${item.image}` : 'https://via.placeholder.com/40'"
                      alt="Item Image"
                      class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm">
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                    </div>
                  </div>
                </td>
                <td v-if="visibleColumns.includes('item_code')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                  {{ item.item_code || '-' }}
                </td>
                <td v-if="visibleColumns.includes('category')"
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.category || '-' }}
                </td>
                <td v-if="visibleColumns.includes('quantity')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ item.quantity || 0 }}
                </td>
                <td v-if="visibleColumns.includes('warehouse')"
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.warehouse || '-' }}
                </td>
                <td v-if="visibleColumns.includes('status')" class="px-6 py-4 whitespace-nowrap text-center">
                  <span :class="{
                    'px-2.5 py-0.5 rounded-full text-xs': true,
                    'bg-green-100 text-green-800': item.status === 'in_stock',
                    'bg-yellow-100 text-yellow-800': item.status === 'low_stock',
                    'bg-red-100 text-red-800': item.status === 'out_of_stock'
                  }">
                    {{ statusLabels[item.status] || '-' }}
                  </span>
                </td>
                <td v-if="visibleColumns.includes('last_updated')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ item.last_updated ? new Date(item.last_updated).toLocaleDateString('vi-VN') : '-' }}
                </td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td :colspan="visibleColumns.length + 1" class="text-center text-gray-400 py-8">
                  Không có dữ liệu
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="props.inventory && props.inventory.data && props.inventory.data.length"
          class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
          <div class="text-sm text-gray-600 font-medium">
            Hiển thị
            <span class="font-semibold">{{ props.inventory.from }}</span> đến
            <span class="font-semibold">{{ props.inventory.to }}</span> của
            <span class="font-semibold">{{ props.inventory.total }}</span> kết quả
          </div>
          <div class="flex items-center space-x-2">
            <Link v-if="props.inventory.prev_page_url" :href="addStatusToUrl(props.inventory.prev_page_url)"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </span>
            <span class="text-sm text-gray-600">
              Trang {{ props.inventory.current_page }} / {{ props.inventory.last_page }}
            </span>
            <Link v-if="props.inventory.next_page_url" :href="addStatusToUrl(props.inventory.next_page_url)"
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
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { route } from 'ziggy-js';

// Props
const props = defineProps({
  inventory: Object,
  status: String,
});

// Tab control
const activeTab = ref(props.status || 'in_stock');

// Column options and labels
const columnOptions = ['name', 'item_code', 'category', 'quantity', 'warehouse', 'status', 'last_updated'];

const columnLabels = {
  name: 'Tên sản phẩm',
  item_code: 'Mã sản phẩm',
  category: 'Danh mục',
  quantity: 'Số lượng',
  warehouse: 'Kho',
  status: 'Trạng thái',
  last_updated: 'Cập nhật cuối',
};

const statusLabels = {
  in_stock: 'Có hàng',
  low_stock: 'Sắp hết',
  out_of_stock: 'Hết hàng',
};

const visibleColumns = ref(
  localStorage.getItem('inventoryVisibleColumns')
    ? JSON.parse(localStorage.getItem('inventoryVisibleColumns'))
    : ['name', 'item_code', 'category', 'quantity', 'warehouse', 'status', 'last_updated']
);

// Save visibleColumns to localStorage
watch(visibleColumns, (newColumns) => {
  localStorage.setItem('inventoryVisibleColumns', JSON.stringify(newColumns));
}, { deep: true });

// Column dropdown
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

// Action dropdown
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

// Checkbox selection
const selectedItems = ref([]);
const selectAll = ref(false);

const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedItems.value = props.inventory?.data.map(item => item.id) || [];
  } else {
    selectedItems.value = [];
  }
};

watch(selectedItems, (newVal) => {
  selectAll.value = props.inventory?.data && newVal.length === props.inventory.data.length && newVal.length > 0;
});

watch(activeTab, () => {
  selectedItems.value = [];
  selectAll.value = false;
  router.get(
    route('admin.inventory.index'),
    { status: activeTab.value },
    { preserveState: true, preserveScroll: true }
  );
});

// Sync activeTab with props.status
watch(() => props.status, (newStatus) => {
  activeTab.value = newStatus || 'in_stock';
});

// Bulk update status
const bulkUpdateStatus = (newStatus) => {
  if (!confirm(`Bạn có chắc muốn cập nhật trạng thái cho ${selectedItems.value.length} sản phẩm thành "${statusLabels[newStatus]}"?`)) {
    return;
  }
  router.post(
    route('admin.inventory.bulk-update-status'),
    { item_ids: selectedItems.value, status: newStatus },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        selectedItems.value = [];
        selectAll.value = false;
        showActionDropdown.value = false;
      },
    }
  );
};

// Bulk delete
const bulkDelete = () => {
  if (!confirm(`Bạn có chắc muốn xóa ${selectedItems.value.length} sản phẩm? Hành động này không thể hoàn tác!`)) {
    return;
  }
  router.post(
    route('admin.inventory.bulk-delete'),
    { item_ids: selectedItems.value },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        selectedItems.value = [];
        selectAll.value = false;
        showActionDropdown.value = false;
      },
    }
  );
};

// Add status to pagination URL
const addStatusToUrl = (url) => {
  const urlObj = new URL(url);
  urlObj.searchParams.set('status', activeTab.value);
  return urlObj.toString();
};

// Show server flash messages
const page = usePage();
watch(() => page.props.flash, (flash) => {
  if (flash.success) {
    alert(flash.success); // Replace with toast notification if preferred
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