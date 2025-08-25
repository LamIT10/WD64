<template>
  <AppLayout>
    <div class="p-6 min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-100 font-sans">
      <h2 class="text-3xl font-bold mb-8 text-indigo-900 tracking-tight flex items-center gap-3">
        <i class="fas fa-warehouse text-indigo-400 text-2xl"></i> Lịch sử thay đổi kho
      </h2>
      <div class="mb-8 flex flex-col md:flex-row md:items-end gap-4 md:gap-6">
        <div class="flex flex-col gap-2">
          <label class="text-sm font-medium text-gray-700">Từ ngày</label>
          <input type="date" v-model="startDate" class="border border-indigo-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none shadow-sm transition-all" />
        </div>
        <div class="flex flex-col gap-2">
          <label class="text-sm font-medium text-gray-700">Đến ngày</label>
          <input type="date" v-model="endDate" class="border border-indigo-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none shadow-sm transition-all" />
        </div>
        <div class="flex-1 flex flex-col gap-2">
          <label class="text-sm font-medium text-gray-700">Tìm kiếm</label>
          <input type="text" v-model="keyword" placeholder="Tìm sản phẩm, biến thể, mã đơn hàng..." class="border border-indigo-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none shadow-sm transition-all w-full" />
        </div>
        <button @click="fetchHistory" class="bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:from-indigo-600 hover:to-indigo-800 hover:shadow-lg transition-all">Lọc</button>
      </div>
      <div class="overflow-x-auto rounded-2xl shadow-xl border border-indigo-100 bg-white animate-fade-in">
        <table class="min-w-full text-sm border-separate border-spacing-0">
          <thead>
            <tr class="bg-indigo-50 text-indigo-900">
              <th class="px-6 py-4 border-b font-bold text-left rounded-tl-2xl">Ngày</th>
              <th class="px-6 py-4 border-b font-bold text-left">Loại</th>
              <th class="px-6 py-4 border-b font-bold text-left">Mã đơn hàng / Mã phiếu </th>
              <!-- <th class="px-6 py-4 border-b font-bold text-left">Sản phẩm - Biến thể</th>
              <th class="px-6 py-4 border-b font-bold text-right">Số lượng (+/-)</th> -->
              <th class="px-6 py-4 border-b font-bold text-left">Ghi chú</th>
              <th class="px-6 py-4 border-b font-bold text-center rounded-tr-2xl">Chi tiết</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, i) in pagedRows" :key="i" :class="[
              'hover:bg-indigo-50 transition-all duration-200',
              'text-gray-800',
              i % 2 === 0 ? 'bg-white' : 'bg-indigo-50/50'
            ]">
              <td class="px-6 py-3 border-b">{{ formatDate(row.date) }}</td>
              <td class="px-6 py-3 border-b">{{ row.type }}</td>
              <td class="px-6 py-3 border-b font-mono text-xs text-indigo-700">{{ row.code }}</td>
              <!-- <td class="px-6 py-3 border-b">
                <span class="font-semibold">{{ row.product }}</span>
                <span v-if="row.variant"> - <span class="text-gray-500">{{ row.variant }}</span></span>
                <div v-if="row.attributes" class="text-xs text-gray-400">{{ row.attributes }}</div>
              </td>
              <td class="px-6 py-3 border-b text-right font-bold" :class="row.qty < 0 ? 'text-red-600' : 'text-green-700'">{{ row.qty }}</td> -->
              <td class="px-6 py-3 border-b">{{ row.note }}</td>
              <td class="px-6 py-3 border-b text-center">
                <button class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 font-semibold underline underline-offset-4 transition-colors" @click="showDetail(row)">
                  <i class="fas fa-eye"></i> Xem
                </button>
              </td>
            </tr>
            <tr v-if="!filteredRows.length">
              <td colspan="7" class="text-center text-gray-400 py-12">Không có dữ liệu</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="showModal && detail" class="fixed inset-0 flex items-center justify-center z-50" style="backdrop-filter: blur(6px);">
        <div class="absolute inset-0 bg-gradient-to-br from-white/11 via-indigo-100/10 to-indigo-200/50 backdrop-blur-[6px] transition-all" @click="closeModal"></div>
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl p-8 min-w-[600px] max-w-2xl w-full animate-slide-up border border-indigo-100 overflow-y-auto max-h-[80vh]" @click.stop>
          <button class="absolute top-4 right-4 text-gray-400 hover:text-indigo-700 text-3xl font-bold transition-colors" @click="closeModal" title="Đóng">&times;</button>
          <h3 class="text-2xl font-bold mb-6 text-indigo-800 flex items-center gap-3">
            <i class="fas fa-file-alt text-indigo-400 text-xl"></i> Chi tiết đơn hàng
          </h3>
            <template v-if="detail">
            <!-- Nếu là Điều chỉnh kho -->
            <a
              v-if="detail.type === 'Điều chỉnh'"
              :href="`/admin/inventory-audit/${detail.id}`"
              target="_blank"
              class="inline-flex mb-3 items-center gap-1 text-indigo-600 hover:text-indigo-900 font-semibold underline underline-offset-4 transition-colors"
            >
              <i class="fas fa-external-link-alt"></i> Xem phiếu kiểm kho
            </a>
            <!-- Nếu là Nhập kho -->
            <a
              v-else-if="detail.type === 'Nhập kho'"
              :href="`/admin/receiving?code=${detail.code}`"
              target="_blank"
              class="inline-flex mb-3 items-center gap-1 text-indigo-600 hover:text-indigo-900 font-semibold underline underline-offset-4 transition-colors"
            >
              <i class="fas fa-external-link-alt"></i> Xem phiếu nhập kho
            </a>
            <!-- Nếu là Xuất kho -->
            <a
              v-else-if="detail.type === 'Xuất kho'"
              :href="`/admin/shipping?code=${detail.code}`"
              target="_blank"
              class="inline-flex mb-3 items-center gap-1 text-indigo-600 hover:text-indigo-900 font-semibold underline underline-offset-4 transition-colors"
            >
              <i class="fas fa-external-link-alt"></i> Xem phiếu xuất kho
            </a>
            </template>
          <div v-if="detail.items && detail.items.length">
            <table class="min-w-full text-sm border-separate border-spacing-0 mb-4">
              <thead>
                <tr class="bg-indigo-50 text-indigo-900">
                  <th class="px-4 py-3 border-b font-bold text-left rounded-tl-xl">Sản phẩm - Biến thể</th>
                  <th class="px-4 py-3 border-b font-bold text-left">Mã biến thể</th>
                  <th class="px-4 py-3 border-b font-bold text-right">Số lượng (+/-)</th>
                  <th class="px-4 py-3 border-b font-bold text-left rounded-tr-xl">Ghi chú</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in detail.items" :key="item.id" :class="['text-gray-800', 'hover:bg-indigo-50 transition-all duration-200', 'border-b', 'align-top']">
                  <td class="px-4 py-3">
                    <span class="font-semibold">{{ item.product }}</span>
                    <span v-if="item.variant && item.variant !== item.product"> - <span class="text-gray-500">{{ item.variant }}</span></span>
                    <div v-if="item.attributes && item.attributes !== item.variant" class="text-xs text-gray-400 mt-1">({{ item.attributes }})</div>
                  </td>
                  <td class="px-4 py-3 font-mono text-xs text-indigo-700">{{ item.product_variant?.code || item.variant_code || '' }}</td>
                  <td class="px-4 py-3 text-right font-bold" :class="item.qty < 0 ? 'text-red-600' : 'text-green-700'">{{ item.qty ?? 0 }}</td>
                  <td class="px-4 py-3">{{ item.note ?? item.discrepancy_reason ?? '' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="text-gray-500 text-sm text-center py-8">Không có dữ liệu chi tiết.</div>
          <div class="mt-4 text-right">
            <button class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-700 text-white rounded-xl font-semibold shadow-md hover:from-indigo-600 hover:to-indigo-800 hover:shadow-lg transition-all" @click="closeModal">
              Đóng
            </button>
          </div>
        </div>
      </div>
      <!-- Modern Pagination with beautiful design -->
      <div class="flex flex-col items-center gap-4 mt-10">
        <div class="flex items-center justify-center gap-1 p-2 bg-white rounded-2xl shadow-lg border border-indigo-100/50 backdrop-blur-sm">
          <!-- First page button -->
          <button 
            @click="goToPage(1)" 
            :disabled="page.value === 1" 
            class="pagination-nav-btn" 
            title="Trang đầu"
          >
            <i class="fas fa-angle-double-left text-sm"></i>
          </button>
          
          <!-- Page numbers -->
          <div class="flex items-center gap-1 mx-2">
            <template v-for="p in visiblePages" :key="p">
              <div 
                v-if="p === page.value" 
                class="pagination-current"
                aria-current="page"
              >
                {{ p }}
              </div>
              <button
                v-else-if="p !== '...'"
                @click="goToPage(p)"
                class="pagination-number"
                :title="`Trang ${p}`"
              >
                {{ p }}
              </button>
              <span v-else class="pagination-ellipsis">⋯</span>
            </template>
          </div>
          
          <!-- Last page button -->
          <button 
            @click="goToPage(totalPages)" 
            :disabled="page.value === totalPages" 
            class="pagination-nav-btn" 
            title="Trang cuối"
          >
            <i class="fas fa-angle-double-right text-sm"></i>
          </button>
        </div>
        
        <!-- Page info with beautiful styling -->
        <div class="flex items-center gap-4 text-sm">
          <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-full border border-indigo-200/50">
            <i class="fas fa-file-alt text-indigo-500"></i>
            <span class="text-indigo-700 font-medium">
              Trang <span class="font-bold text-indigo-800">{{ page }}</span> 
              / <span class="font-bold text-indigo-800">{{ totalPages }}</span>
            </span>
          </div>
          <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-50 to-emerald-100 rounded-full border border-green-200/50">
            <i class="fas fa-database text-green-500"></i>
            <span class="text-green-700 font-medium">
              Tổng <span class="font-bold text-green-800">{{ totalRows }}</span> bản ghi
            </span>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '../Layouts/AppLayout.vue'
import { computed as vComputed } from 'vue'
const visiblePages = vComputed(() => {
  const total = totalPages.value
  const current = page.value
  console.log('Visible pages:', { total, current })
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }
  const pages = []
  if (current > 4) {
    pages.push(1)
    if (current > 5) pages.push('...')
  }
  for (let i = Math.max(2, current - 2); i <= Math.min(total - 1, current + 2); i++) {
    pages.push(i)
  }
  if (current < total - 3) {
    if (current < total - 4) pages.push('...')
    pages.push(total)
  }
  return pages
})
// Định dạng ngày từ ISO thành dd/MM/yyyy
function formatDate(dateStr) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  if (isNaN(d)) return dateStr
  return d.toLocaleDateString('vi-VN')
}

// Modal chi tiết
const detail = ref(null)
const showModal = ref(false)
const closeModal = () => {
  showModal.value = false
  detail.value = null
}

const rows = ref([])
const startDate = ref()
const endDate = ref('')
const keyword = ref('')


// Pagination state (from backend)
const page = ref(1)
const perPage = ref(20)
const totalPages = ref(1)
const totalRows = ref(0)

// Filtered rows (search by keyword)
const filteredRows = computed(() => {
  if (!keyword.value) return rows.value
  return rows.value.filter(
    r =>
      (r.product || '').toLowerCase().includes(keyword.value.toLowerCase()) ||
      (r.variant || '').toLowerCase().includes(keyword.value.toLowerCase()) ||
      (r.code || '').toLowerCase().includes(keyword.value.toLowerCase())
  )
})

const pagedRows = computed(() => filteredRows.value)

const fetchHistory = async () => {
  let url = '/api/inventory/history?'
  url += `start_date=${startDate.value || '2025-01-01'}&`
  if (endDate.value) url += `end_date=${endDate.value}&`
  url += `page=${page.value}`
  const res = await fetch(url)
  const json = await res.json()
  rows.value = json.data || []
  totalPages.value = json.last_page || 1
  totalRows.value = json.total || 0
  perPage.value = json.per_page || 20
  // Always sync page.value with backend current_page
  if (json.current_page && page.value !== json.current_page) {
    page.value = json.current_page
  }
  console.log('Fetched inventory history:', rows.value, 'Current page:', page.value);
}

const showDetail = (row) => {
  if (!row.id) return;
  showModal.value = false;
  detail.value = null;
  // Use the items array provided by the backend for this order
  const mappedItems = (row.items || []).map(item => {
    let product = item.product_variant?.product?.name || item.product || '';
    let variant = '';
    if (item.product_variant?.name && item.product_variant?.name !== product) {
      variant = item.product_variant.name;
    } else if (item.variant && item.variant !== product) {
      variant = item.variant;
    }
    let attributes = item.attributes ?? item.product_variant?.attributes ?? '';
    let qty = item.qty ?? item.quantity_received ?? item.quantity_shipped ?? item.actual_quantity ?? 0;
    let product_code = item.product_variant?.product?.code || item.product_code || '';
    let variant_code = item.product_variant?.code || item.variant_code || '';
    let stock_after = item.stock_after ?? item.stock ?? 0;
    return { ...item, product, variant, attributes, qty, product_code, variant_code, stock_after };
  });
  detail.value = { ...row, items: mappedItems };
  showModal.value = true;
}



// Chuyển trang an toàn
function goToPage(p) {
  if (p < 1) p = 1
  if (p > totalPages.value) p = totalPages.value
  if (p !== page.value) {
    page.value = p
    fetchHistory()
  }
}

onMounted(() => {
  fetchHistory()
})

// Reset về trang 1 khi lọc/search
import { watch } from 'vue'
watch([startDate, endDate, keyword], () => {
  page.value = 1
  fetchHistory()
})

</script>
<style scoped>
table { border-collapse: collapse; }
th, td { border: 1px solid #e5e7eb; }

/* Modern pagination styling */
.pagination-nav-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  border: 1px solid #e0e7ff;
  background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
  color: #6366f1;
  font-weight: 500;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.06), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  outline: none;
  position: relative;
  overflow: hidden;
}

.pagination-nav-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
  transition: left 0.5s;
}

.pagination-nav-btn:hover:not(:disabled)::before {
  left: 100%;
}

.pagination-nav-btn:hover:not(:disabled) {
  background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);
  color: #4338ca;
  border-color: #6366f1;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

.pagination-nav-btn:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

.pagination-nav-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
  background: linear-gradient(145deg, #f3f4f6 0%, #e5e7eb 100%);
  color: #9ca3af;
  border-color: #d1d5db;
  transform: none;
  box-shadow: none;
}

.pagination-number {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 8px;
  border-radius: 10px;
  border: 1px solid transparent;
  background: transparent;
  color: #6b7280;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  outline: none;
  position: relative;
}

.pagination-number:hover {
  background: linear-gradient(145deg, #f0f4ff 0%, #e0e7ff 100%);
  color: #4338ca;
  border-color: #c7d2fe;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px -2px rgba(99, 102, 241, 0.15);
}

.pagination-current {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 8px;
  border-radius: 10px;
  background: linear-gradient(145deg, #6366f1 0%, #4f46e5 100%);
  color: #ffffff;
  font-weight: 700;
  font-size: 14px;
  border: 1px solid #4f46e5;
  box-shadow: 
    0 4px 12px -2px rgba(99, 102, 241, 0.4),
    0 2px 4px -1px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  position: relative;
  cursor: default;
}

.pagination-current::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 10px;
  background: linear-gradient(145deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
  pointer-events: none;
}

.pagination-ellipsis {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 24px;
  height: 36px;
  color: #9ca3af;
  font-size: 16px;
  font-weight: 600;
  letter-spacing: 2px;
}

/* Animation for page transitions */
@keyframes pageTransition {
  0% { opacity: 0; transform: scale(0.95); }
  100% { opacity: 1; transform: scale(1); }
}

.pagination-current {
  animation: pageTransition 0.2s ease-out;
}

/* Responsive design */
@media (max-width: 640px) {
  .pagination-nav-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
  }
  
  .pagination-number,
  .pagination-current {
    min-width: 32px;
    height: 32px;
    font-size: 13px;
    border-radius: 8px;
  }
}

/* Beautiful gradient animation for active state */
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.pagination-current {
  background: linear-gradient(-45deg, #6366f1, #8b5cf6, #06b6d4, #10b981);
  background-size: 400% 400%;
  animation: gradientShift 3s ease infinite, pageTransition 0.2s ease-out;
}
</style>
