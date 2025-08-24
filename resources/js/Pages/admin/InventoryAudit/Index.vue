<template>
  <AppLayout>
    <ToastClient ref="toastRef" />
    <div class="p-3 min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-50">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
        <div class="flex items-center gap-3 relative">
          <span class="inline-flex items-center justify-center bg-indigo-100 rounded-full p-2 shadow text-indigo-600 animate-bounce-slow">
            <i class="fas fa-user-ninja text-lg"></i>
            <i class="fas fa-search text-xs -ml-2 -mr-1"></i>
          </span>
          <div>
            <h1 class="text-xl font-bold text-indigo-900 mb-0.5 tracking-tight">Lịch sử Kiểm kho</h1>
            <p class="text-xs text-gray-500">Quản lý và theo dõi các phiếu kiểm kho gần đây</p>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <!-- Search -->
          <div class="relative" title="Tìm kiếm phiếu kiểm kho">
            <input type="text" placeholder="Tìm kiếm..."
              class="w-44 md:w-56 px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-200 focus:outline-none text-xs bg-white transition placeholder-gray-400" />
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400 text-xs pointer-events-none">
              <i class="fas fa-search"></i>
            </span>
          </div>

          <!-- Create Button -->
          <Waiting route-name="admin.inventory-audit.create" :route-params="{}">
            <button v-can="'admin.inventory-audit.create'"
              class="flex items-center hover:bg-indigo-700 text-white rounded-lg shadow transition text-xs font-semibold focus:ring-2 focus:ring-indigo-300 focus:outline-none active:scale-95"
              title="Tạo phiếu kiểm kho mới">
              <i class="fas fa-plus text-sm"></i>
              <span class="hidden md:inline">Tạo mới</span>
            </button>
          </Waiting>

          <!-- Export Button -->
          <button @click="exportToExcel" v-can="'admin.inventory-audit.export'"
            class="flex items-center gap-1 px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition text-xs font-semibold focus:ring-2 focus:ring-green-300 focus:outline-none active:scale-95"
            title="Xuất dữ liệu ra Excel">
            <i class="fa fa-file-export text-sm"></i>
            <span class="hidden md:inline">Xuất Excel</span>
          </button>

          <!-- Column Dropdown -->
          <div ref="dropdownRef" class="relative" title="Tùy chỉnh hiển thị các cột">
            <button @click="toggleDropdown"
              class="flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg shadow transition text-xs font-semibold focus:ring-2 focus:ring-gray-300 focus:outline-none active:scale-95"
              title="Tùy chỉnh cột hiển thị">
              <i class="fas fa-columns text-sm"></i>
            </button>
            <transition name="fade">
              <div v-if="showColumnDropdown"
                class="absolute right-0 z-20 mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-lg p-3 animate-fade-in">
                <div class="grid grid-cols-1 gap-y-2">
                  <label v-for="field in columnOptions" :key="field"
                    class="flex items-center space-x-2 text-xs cursor-pointer select-none"
                    :title="`Hiển thị/Ẩn cột ${columnLabels[field]}`">
                    <input type="checkbox" :value="field" v-model="visibleColumns"
                      class="h-4 w-4 accent-indigo-600 rounded" />
                    <span>{{ columnLabels[field] }}</span>
                  </label>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>

      <!-- Bộ lọc cải tiến -->
      <div class="bg-white rounded-lg shadow p-4 mb-3 border border-indigo-100 flex flex-wrap gap-4 items-end">
        <div class="flex flex-col gap-1 w-32">
          <label class="text-xs text-gray-500 font-medium flex items-center gap-1"><i class="fas fa-barcode"></i> Mã phiếu</label>
          <input v-model="filters.code" placeholder="Nhập mã phiếu" class="px-2 py-1 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-indigo-200" />
        </div>
        <div class="flex flex-col gap-1 w-32">
          <label class="text-xs text-gray-500 font-medium flex items-center gap-1"><i class="fas fa-warehouse"></i> Khu vực</label>
          <input v-model="filters.zone" placeholder="Nhập khu vực" class="px-2 py-1 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-indigo-200" />
        </div>
        <div class="flex flex-col gap-1 w-36">
          <label class="text-xs text-gray-500 font-medium flex items-center gap-1"><i class="fas fa-calendar-alt"></i> Ngày kiểm kho</label>
          <input v-model="filters.audit_date" type="date" class="px-2 py-1 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-indigo-200" />
        </div>
        <div class="flex flex-col gap-1 w-36">
          <label class="text-xs text-gray-500 font-medium flex items-center gap-1"><i class="fas fa-info-circle"></i> Trạng thái</label>
          <select v-model="filters.status" class="px-2 py-1 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-indigo-200">
            <option value="">Tất cả trạng thái</option>
            <option value="completed">Không chênh lệch</option>
            <option value="issues">Có chênh lệch</option>
          </select>
        </div>
        <div class="flex flex-col gap-1">
          <label class="text-xs text-white font-medium">&nbsp;</label>
          <button @click="resetFilters" class="px-3 py-1 border border-gray-200 rounded text-xs bg-gray-100 hover:bg-indigo-100 text-gray-700 flex items-center gap-1"><i class="fas fa-times"></i> Xóa lọc</button>
        </div>
      </div>
      <!-- Table -->
      <div class="bg-white rounded-xl shadow overflow-x-auto animate-fade-in border border-indigo-50">
        <table class="min-w-full text-xs border-separate border-spacing-0">
          <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
            <tr>
              <th v-if="visibleColumns.includes('code')"
                class="px-2 py-2 text-left font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Mã phiếu kiểm kho">
                Mã
              </th>
              <th v-if="visibleColumns.includes('location')"
                class="px-2 py-2 text-center font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Khu vực kiểm kho">
                Khu vực
              </th>
              <th v-if="visibleColumns.includes('audit_date')"
                class="px-2 py-2 text-left font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Ngày thực hiện kiểm kho">
                Ngày kiểm
              </th>
              <th v-if="visibleColumns.includes('created_at')"
                class="px-2 py-2 text-center font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Ngày lưu phiếu kiểm kho">
                Ngày lưu
              </th>
              <th v-if="visibleColumns.includes('user_name')"
                class="px-2 py-2 text-left font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Người tạo phiếu kiểm kho">
                Người tạo
              </th>
              <th v-if="visibleColumns.includes('status')"
                class="px-2 py-2 text-center font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Trạng thái kiểm kho">
                Trạng thái
              </th>
              <th v-if="visibleColumns.includes('adjusted')"
                class="px-2 py-2 text-center font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Trạng thái đồng bộ dữ liệu">
                Đồng bộ
              </th>
              <th v-if="visibleColumns.includes('action')"
                class="px-2 py-2 text-center font-bold text-indigo-800 border-b border-indigo-50 uppercase tracking-wide"
                title="Các hành động">
                Hành động
              </th>
            </tr>
          </thead>
          <tbody v-if="props.audits && props.audits.data && props.audits.data.length">
            <tr v-for="item in filteredAudits" :key="item.id"
              class="hover:bg-indigo-50 transition cursor-pointer group"
              @click="$inertia.visit(route('admin.audits.show', item.id))"
              :title="`Xem chi tiết phiếu kiểm kho #${item.code}`">
              <td v-if="visibleColumns.includes('code')"
                class="px-2 py-2 font-bold text-indigo-900 border-b border-indigo-50 align-middle"
                :title="`Mã phiếu: #${item.code}`">
                <span class="inline-flex items-center gap-1">
                  <span
                    class="bg-indigo-600 text-white rounded px-2 py-0.5 text-xs font-bold shadow group-hover:bg-indigo-700 transition">
                    #{{ item.code }}
                  </span>
                </span>
              </td>
              <td v-if="visibleColumns.includes('location')"
                class="px-2 py-2 text-center border-b border-indigo-50 align-middle"
                :title="`Khu vực: ${(item.audited_zones || []).join(', ')}`">
                <div class="flex flex-wrap justify-center gap-0.5">
                  <span v-for="location in item.audited_zones || []" :key="location"
                    class="inline-block bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-700 text-[10px] px-2 py-0.5 rounded-full shadow-sm font-semibold"
                    :title="`Khu vực: ${location}`">
                    {{ location }}
                  </span>
                </div>
              </td>
              <td v-if="visibleColumns.includes('audit_date')" class="px-2 py-2 border-b border-indigo-50 align-middle"
                :title="item.audit_date ? `Ngày kiểm: ${new Date(new Date(item.audit_date).getTime() + 7 * 60 * 60 * 1000).toLocaleString('vi-VN')}` : '-'">
                <span class="inline-flex items-center gap-1">
                  <i class="far fa-calendar-alt text-indigo-400"></i>
                  {{
                    item.audit_date
                      ? new Date(new Date(item.audit_date).getTime() + 7 * 60 * 60 * 1000).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                      })
                      : '-'
                  }}
                </span>
              </td>
              <td v-if="visibleColumns.includes('created_at')"
                class="px-2 py-2 text-center border-b border-indigo-50 align-middle"
                :title="item.created_at ? `Ngày lưu: ${new Date(new Date(item.created_at).getTime() + 7 * 60 * 60 * 1000).toLocaleString('vi-VN')}` : '-'">
                <span class="inline-flex items-center gap-1">
                  <i class="far fa-clock text-indigo-400"></i>
                  {{
                    item.created_at
                      ? new Date(new Date(item.created_at).getTime()).toLocaleString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                      })
                      : '-'
                  }}
                </span>
              </td>
                <td v-if="visibleColumns.includes('user_name')" class="px-2 py-2 border-b border-indigo-50 align-middle"
                :title="item.user?.name ? `Người tạo: ${item.user.name}${item.user.employee_code ? ' - Mã NV: ' + item.user.employee_code : ''}` : 'Không rõ người tạo'">
                <span class="inline-flex items-center gap-1">
                  <i class="fas fa-user text-indigo-400"></i>
                  <span class="font-semibold">{{ item.user?.name || '-' }}</span>
                </span>
                </td>
              <td v-if="visibleColumns.includes('status')"
                class="px-2 py-2 text-center border-b border-indigo-50 align-middle"
                :title="item.status === 'completed' ? 'Không có chênh lệch' : 'Có chênh lệch'">
                <span
                  :class="item.status === 'completed'
                    ? 'inline-flex items-center justify-center w-7 h-7 bg-green-100 text-green-700 rounded-full shadow-sm text-xs'
                    : 'inline-flex items-center justify-center w-7 h-7 bg-red-100 text-red-700 rounded-full shadow-sm text-xs'">
                  <i v-if="item.status === 'completed'" class="fas fa-check-circle"></i>
                  <i v-else class="fas fa-exclamation-triangle"></i>
                </span>
              </td>
              <td v-if="visibleColumns.includes('adjusted')"
                class="px-2 py-2 text-center border-b border-indigo-50 align-middle"
                :title="item.status === 'completed' ? 'Không cần đồng bộ' : (item.is_adjusted === 1 ? 'Đã đồng bộ' : (item.is_adjusted === 2 ? 'Đã từ chối đồng bộ' : 'Chưa đồng bộ'))">
                <span v-if="item.status === 'completed'"></span>
                <span v-else>
                  <span v-if="item.is_adjusted === 1"
                    class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 text-blue-700 rounded-full shadow-sm text-xs"
                    title="Đã đồng bộ">
                    <i class="fas fa-check-circle"></i>
                  </span>
                  <span v-else-if="item.is_adjusted === 2"
                    class="inline-flex items-center justify-center w-7 h-7 bg-red-100 text-red-700 rounded-full shadow-sm text-xs"
                    title="Đã từ chối đồng bộ">
                    <i class="fas fa-times-circle"></i>
                  </span>
                  <span v-else
                    class="inline-flex items-center justify-center w-7 h-7 bg-yellow-100 text-yellow-700 rounded-full shadow-sm text-xs"
                    title="Chưa đồng bộ">
                    <i class="fas fa-exclamation-circle"></i>
                  </span>
                </span>
              </td>
              <td v-if="visibleColumns.includes('action')"
                class="px-2 py-2 text-center border-b border-indigo-50 align-middle"
                title="Xem chi tiết phiếu kiểm kho">
                <Link :href="route('admin.inventory-audit.show', item.id)" @click.stop
                  class="inline-flex items-center justify-center w-7 h-7 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-full transition shadow text-xs"
                  title="Xem chi tiết">
                <i class="fas fa-eye"></i>
                </Link>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td :colspan="visibleColumns.length" class="text-center text-gray-400 py-8 border-b border-indigo-50"
                title="Không có dữ liệu">
                <i class="fas fa-box-open text-2xl mb-1"></i>
                <div class="text-sm font-semibold">Không có dữ liệu</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="props.audits && props.audits.data && props.audits.data.length"
        class="flex flex-col md:flex-row items-center justify-between text-xs text-gray-600 mt-4 gap-1">
        <div title="Thông tin số lượng phiếu kiểm kho đang hiển thị">
          Hiển thị
          <span class="font-bold text-indigo-700">{{ props.audits.from }}</span>
          -
          <span class="font-bold text-indigo-700">{{ props.audits.to }}</span>
          /
          <span class="font-bold text-indigo-700">{{ props.audits.total }}</span>
        </div>
        <div class="flex items-center gap-1">
          <Link v-if="props.audits.prev_page_url" :href="addStatusToUrl(props.audits.prev_page_url)"
            class="px-2 py-1 border rounded-md text-gray-600 bg-white hover:bg-indigo-50 shadow-sm transition"
            title="Trang trước">
          <i class="fas fa-chevron-left"></i>
          </Link>
          <span v-else class="px-2 py-1 border rounded-md text-gray-300 bg-gray-50" title="Trang trước">
            <i class="fas fa-chevron-left"></i>
          </span>
          <span title="Trang hiện tại / Tổng số trang">
            Trang
            <span class="font-bold text-indigo-700">{{ props.audits.current_page }}</span>
            /
            <span class="font-bold text-indigo-700">{{ props.audits.last_page }}</span>
          </span>
          <Link v-if="props.audits.next_page_url" :href="addStatusToUrl(props.audits.next_page_url)"
            class="px-2 py-1 border rounded-md text-gray-600 bg-white hover:bg-indigo-50 shadow-sm transition"
            title="Trang sau">
          <i class="fas fa-chevron-right"></i>
          </Link>
          <span v-else class="px-2 py-1 border rounded-md text-gray-300 bg-gray-50" title="Trang sau">
            <i class="fas fa-chevron-right"></i>
          </span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { route } from 'ziggy-js';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import ToastClient from '../../components/ToastClient.vue';

// Props
const props = defineProps({
  audits: Object,
  status: String,
});
console.log('DEBUG audits.data:', props.audits?.data);

if (props.audits?.data && Array.isArray(props.audits.data)) {
  props.audits.data.forEach((item, idx) => {
    console.log(`Audit #${idx}:`, {
      id: item.id,
      code: item.code,
      audited_locations: item.audited_locations,
      audit_date: item.audit_date,
      created_at: item.created_at,
      user_name: item.user_name,
      status: item.status,
      is_adjusted: item.is_adjusted,
    });
  });
}

// Tab control
const activeTab = ref(props.status || 'in_stock');

// Column options and labels
const columnOptions = [
  'code',
  'location',
  'audit_date',
  'created_at',
  'user_name',
  'status',
  'adjusted',
  'action',
];

const columnLabels = {
  code: 'Mã phiếu',
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
    // , 'adjusted'
    : ['id', 'location', 'audit_date', 'created_at', 'user_name', 'status', 'action', 'adjusted']
);

// Save visibleColumns to localStorage
watch(visibleColumns, (newColumns) => {
  localStorage.setItem('auditsVisibleColumns', JSON.stringify(newColumns));
}, { deep: true });

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
const addStatusToUrl = (url) => {
  // Giữ nguyên các tham số khác, chỉ thêm hoặc cập nhật status nếu cần
  const u = new URL(url, window.location.origin);
  if (activeTab.value && activeTab.value !== 'all') {
    u.searchParams.set('status', activeTab.value);
  }
  return u.pathname + u.search;
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

const toastRef = ref();
function toastSuccess(message) {
    toastRef.value?.triggerToast(message, 'success');
}
function toastError(message) {
    toastRef.value?.triggerToast(message, 'error');
}

// Bộ lọc client-side
const filters = ref({
  code: '',
  zone: '',
  status: '',
  audit_date: ''
});

const resetFilters = () => {
  filters.value = { code: '', zone: '', status: '', audit_date: '' };
};

const filteredAudits = computed(() => {
  if (!props.audits?.data) return [];
  return props.audits.data.filter(item => {
    // Lọc mã phiếu
    if (filters.value.code && !(item.code || '').toLowerCase().includes(filters.value.code.toLowerCase())) return false;
    // Lọc khu vực (theo audited_zones)
    if (filters.value.zone && !(item.audited_zones || []).join(',').toLowerCase().includes(filters.value.zone.toLowerCase())) return false;
    // Lọc trạng thái
    if (filters.value.status && item.status !== filters.value.status) return false;
    // Lọc ngày kiểm kho
    if (filters.value.audit_date && item.audit_date !== filters.value.audit_date) return false;
    return true;
  });
});

const exportToExcel = async () => {
  try {
    // Gọi API để lấy dữ liệu Excel
    const response = await fetch(`/api/inventory-audit/export-excel?status=${activeTab.value}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (!response.ok) {
      throw new Error('Lỗi khi xuất Excel');
    }

    const result = await response.json();
    
    if (result.success && result.data) {
      // Tạo file Excel từ dữ liệu nhận được
      const ws = XLSX.utils.json_to_sheet(result.data);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'KiemKe');
      const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
      // Download file
      saveAs(new Blob([wbout], { type: 'application/octet-stream' }), result.filename);
      toastSuccess('Đã tải file Excel thành công.');
    } else {
      toastError('Lỗi khi xuất Excel');
    }
  } catch (error) {
    console.error('Export error:', error);
    toastError('Lỗi khi xuất Excel: ' + error.message);
  }
};


</script>
<style lang="css" scoped>
@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}
.animate-bounce-slow {
  animation: bounce-slow 1.4s infinite;
}
@keyframes carry-move {
  0% { transform: translateX(0); }
  20% { transform: translateX(-10px); }
  50% { transform: translateX(10px); }
  80% { transform: translateX(-10px); }
  100% { transform: translateX(0); }
}
.animate-carry-move {
  animation: carry-move 2.2s infinite linear;
}
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