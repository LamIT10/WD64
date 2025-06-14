<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
        <div>
          <h5 class="text-lg text-indigo-700 font-semibold mb-2">Lịch sử Kiểm kho</h5>
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
          <input ref="importInput" type="file" accept=".xlsx,.xls" style="display: none" @change="handleImportExcel" />
          <button @click="$refs.importInput.click()"
            class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2">
            <i class="fa fa-sign-in icon-btn"></i> Nhập file
          </button>
          <button @click="exportToExcel"
            class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2">
            <i class="fa fa-file-export icon-btn"></i> Xuất file
          </button>
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
          <!-- <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
              <button @click="activeTab = 'in_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'in_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'in_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Tất cả
              </button>
            </li>
            <li class="mr-2">
              <button @click="activeTab = 'low_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'low_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'low_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Có chênh lệch
              </button>
            </li>
            <li class="mr-2">
              <button @click="activeTab = 'out_of_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'out_of_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'out_of_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Không chênh lệch
              </button>
            </li>
             <li class="mr-2">
              <button @click="activeTab = 'out_of_stock'"
                :class="{ 'text-indigo-600 border-indigo-600': activeTab === 'out_of_stock', 'text-gray-500 hover:text-gray-600 hover:border-gray-300': activeTab !== 'out_of_stock' }"
                class="inline-block p-3 border-b-2 rounded-t-lg text-sm font-medium">
                Không chênh lệch
              </button>
            </li>
          </ul> -->
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
              <button @click="bulkDelete" class="w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                <i class="fas fa-trash-alt mr-2 text-xs"></i> Xóa
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- audits Table -->
      <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <!-- <th class="w-12 px-4 py-3 text-left">
                  <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </th> -->
                <th v-if="visibleColumns.includes('id')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  #ID
                </th>
                <th v-if="visibleColumns.includes('location')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Khu vực
                </th>
                <th v-if="visibleColumns.includes('audit_date')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Ngày kiểm
                </th>
                <th v-if="visibleColumns.includes('craeted_at')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Ngày lưu
                </th>
                <th v-if="visibleColumns.includes('user_name')"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Người tạo
                </th>
                <th v-if="visibleColumns.includes('status')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Trạng thái
                </th>
                <th v-if="visibleColumns.includes('adjusted')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Đồng bộ
                </th>
                <th v-if="visibleColumns.includes('action')"
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Hành động
                </th>
              </tr>
            </thead>
            <tbody v-if="props.audits && props.audits.data && props.audits.data.length">
              <tr v-for="item in props.audits.data" :key="item.id"
                @click="$inertia.visit(route('admin.audits.show', item.id))"
                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                <!-- {{ item }} -->
                <!-- <td class="px-4 py-4 whitespace-nowrap" @click.stop>
                  <input type="checkbox" :value="item.id" v-model="selectedItems"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </td> -->
                <td v-if="visibleColumns.includes('id')" class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ item.id }}</div>
                    </div>
                  </div>
                </td>
                <td v-if="visibleColumns.includes('location')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                  <div class="flex flex-wrap gap-1 justify-center">
                    <span v-for="zone in item.audited_locations || []" :key="zone"
                      class="inline-block bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded-full border border-indigo-200">
                      {{ zone }}
                    </span>
                  </div>
                </td>
                <td v-if="visibleColumns.includes('audit_date')"
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.audit_date ? new Date(new Date(item.audit_date).getTime() + 7 * 60 * 60 *
                    1000).toLocaleString('vi-VN', {
                      day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit',
                  minute: '2-digit' }) : '-' }}
                </td>
                <td v-if="visibleColumns.includes('created_at')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ item.created_at ? new Date(new Date(item.created_at).getTime() + 7 * 60 * 60 *
                    1000).toLocaleString('vi-VN', {
                      day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit',
                  minute: '2-digit' }) : '-' }}
                </td>
                <td v-if="visibleColumns.includes('user_name')"
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ item.user?.name }} - {{ item.user?.id }}
                </td>
                <td v-if="visibleColumns.includes('status')" class="px-6 py-4 whitespace-nowrap text-center">
                  <span :class="{
                    'px-2.5 py-0.5 rounded-full text-xs': true,
                    'bg-green-100 text-green-800': item.status === 'completed',
                    'bg-red-100 text-red-800': item.status === 'issues'
                  }">
                    {{ item.status === 'completed' ? 'Ko chênh lệch' : 'Có chênh lệch' }}
                  </span>
                </td>
                <td v-if="visibleColumns.includes('adjusted')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  <span v-if="item.status === 'completed'">--</span>
                  <span v-else>{{ item.is_adjusted ? 'Đã đồng bộ' : 'Chưa đồng bộ' }}</span>
                </td>
                <td v-if="visibleColumns.includes('action')"
                  class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                    <Link :href="route('admin.inventory-audit.show', item.id)" @click.stop title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                    </Link>
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
        <div v-if="props.audits && props.audits.data && props.audits.data.length"
          class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
          <div class="text-sm text-gray-600 font-medium">
            Hiển thị
            <span class="font-semibold">{{ props.audits.from }}</span> đến
            <span class="font-semibold">{{ props.audits.to }}</span> của
            <span class="font-semibold">{{ props.audits.total }}</span> kết quả
          </div>
          <div class="flex items-center space-x-2">
            <Link v-if="props.audits.prev_page_url" :href="addStatusToUrl(props.audits.prev_page_url)"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
            <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </span>
            <span class="text-sm text-gray-600">
              Trang {{ props.audits.current_page }} / {{ props.audits.last_page }}
            </span>
            <Link v-if="props.audits.next_page_url" :href="addStatusToUrl(props.audits.next_page_url)"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-right"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
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
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';

// Props
const props = defineProps({
  audits: Object,
  status: String,
});

// Tab control
const activeTab = ref(props.status || 'in_stock');

// Column options and labels
const columnOptions = [
  'id',
  'location',
  'audit_date',
  'created_at',
  'user_name',
  'status',
  'adjusted',
  'action',
];

const columnLabels = {
  id: '#ID',
  location: 'Khu vực',
  audit_date: 'Ngày kiểm',
  created_at: 'Ngày lưu',
  user_name: 'Người tạo',
  status: 'Trạng thái',
  adjusted: 'Đồng bộ',
  action: 'Hành động',
};

const statusLabels = {
  all: 'Tất cả',
  difference: 'Có chênh lệch',
  no_difference: 'Không chênh lệch',
};

const visibleColumns = ref(
  localStorage.getItem('auditsVisibleColumns')
    ? JSON.parse(localStorage.getItem('auditsVisibleColumns'))
    : ['id', 'location', 'audit_date', 'created_at', 'user_name', 'status', 'adjusted', 'action']
);

// Save visibleColumns to localStorage
watch(visibleColumns, (newColumns) => {
  localStorage.setItem('auditsVisibleColumns', JSON.stringify(newColumns));
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
    selectedItems.value = props.audits?.data.map(item => item.id) || [];
  } else {
    selectedItems.value = [];
  }
};

watch(selectedItems, (newVal) => {
  selectAll.value = props.audits?.data && newVal.length === props.audits.data.length && newVal.length > 0;
});

watch(activeTab, () => {
  selectedItems.value = [];
  selectAll.value = false;
  router.get(
    route('admin.inventory-audit.index'),
    { status: activeTab.value },
    { preserveState: true, preserveScroll: true }
  );
});

// Sync activeTab with props.status
watch(() => props.status, (newStatus) => {
  activeTab.value = newStatus;
});

// Show server flash messages
const page = usePage();
watch(() => page.props.flash, (flash) => {
  if (flash.success) {
    alert(flash.success); // Replace with toast notification if preferred
  }
});

const exportToExcel = () => {
  // Lấy dữ liệu hiện tại (props.audits.data)
  const data = (props.audits?.data || []).map(item => ({
    'ID': item.id,
    'Khu vực': (item.audited_locations || []).join(', '),
    'Ngày kiểm': item.audit_date,
    'Ngày lưu': item.created_at,
    'Người tạo': item.user?.name,
    'Trạng thái': item.status === 'completed' ? 'Ko chênh lệch' : 'Có chênh lệch',
    'Đồng bộ': item.status === 'completed' ? '--' : (item.is_adjusted ? 'Đã đồng bộ' : 'Chưa đồng bộ'),
  }));

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'KiemKe');
  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
  // Lấy ngày hiện tại theo định dạng yyyy-mm-dd
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  const dateStr = `${yyyy}-${mm}-${dd}`;
  saveAs(new Blob([wbout], { type: 'application/octet-stream' }), `kiemkho-${dateStr}.xlsx`);
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