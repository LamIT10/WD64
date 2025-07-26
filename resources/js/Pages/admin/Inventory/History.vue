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
          <input type="text" v-model="keyword" placeholder="Tìm sản phẩm, biến thể, mã chứng từ..." class="border border-indigo-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none shadow-sm transition-all w-full" />
        </div>
        <button @click="fetchHistory" class="bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md hover:from-indigo-600 hover:to-indigo-800 hover:shadow-lg transition-all">Lọc</button>
      </div>
      <div class="overflow-x-auto rounded-2xl shadow-xl border border-indigo-100 bg-white animate-fade-in">
        <table class="min-w-full text-sm border-separate border-spacing-0">
          <thead>
            <tr class="bg-indigo-50 text-indigo-900">
              <th class="px-6 py-4 border-b font-bold text-left rounded-tl-2xl">Ngày</th>
              <th class="px-6 py-4 border-b font-bold text-left">Loại</th>
              <th class="px-6 py-4 border-b font-bold text-left">Mã chứng từ</th>
              <!-- <th class="px-6 py-4 border-b font-bold text-left">Sản phẩm - Biến thể</th>
              <th class="px-6 py-4 border-b font-bold text-right">Số lượng (+/-)</th> -->
              <th class="px-6 py-4 border-b font-bold text-left">Ghi chú</th>
              <th class="px-6 py-4 border-b font-bold text-center rounded-tr-2xl">Chi tiết</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, i) in pagedRows" :key="i" :class="[
              'hover:bg-indigo-50 transition-all duration-200',
              row.qty < 0 ? 'text-red-600' : 'text-gray-800',
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
            <i class="fas fa-file-alt text-indigo-400 text-xl"></i> Chi tiết đon hàng
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
                <tr v-for="item in detail.items" :key="item.id" :class="[item.qty < 0 ? 'text-red-600' : 'text-gray-800', 'hover:bg-indigo-50 transition-all duration-200', 'border-b', 'align-top']">
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
      <div v-if="totalPages > 1" class="flex justify-center items-center gap-3 mt-6">
        <button @click="page.value = 1" :disabled="page.value === 1" class="px-3 py-1.5 bg-white text-indigo-700 border border-indigo-200 rounded-lg shadow-sm hover:bg-indigo-100 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all" title="Trang đầu">&lt;&lt;</button>
        <button @click="page.value--" :disabled="page.value === 1" class="px-3 py-1.5 bg-white text-indigo-700 border border-indigo-200 rounded-lg shadow-sm hover:bg-indigo-100 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all">&lt;</button>
        <span class="text-indigo-900 font-medium">Trang {{ page }} / {{ totalPages }}</span>
        <button @click="page.value++" :disabled="page.value === totalPages" class="px-3 py-1.5 bg-white text-indigo-700 border border-indigo-200 rounded-lg shadow-sm hover:bg-indigo-100 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all">&gt;</button>
        <button @click="page.value = totalPages" :disabled="page.value === totalPages" class="px-3 py-1.5 bg-white text-indigo-700 border border-indigo-200 rounded-lg shadow-sm hover:bg-indigo-100 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed transition-all" title="Trang cuối">&gt;&gt;</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '../Layouts/AppLayout.vue'

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

// Phân trang client
const page = ref(1)
const perPage = 50
// Gộp các đơn hàng trùng code+type thành 1 dòng duy nhất (lấy ngày lớn nhất, note đầu tiên)
const groupedRows = computed(() => {
  const map = new Map();
  for (const r of rows.value) {
    const key = r.code + '|' + r.type;
    if (!map.has(key)) {
      map.set(key, { ...r });
    } else {
      // Nếu có nhiều dòng cùng code+type, lấy ngày lớn nhất
      const exist = map.get(key);
      if (new Date(r.date) > new Date(exist.date)) {
        map.set(key, { ...r });
      }
    }
  }
  return Array.from(map.values());
});
const filteredRows = computed(() => {
  if (!keyword.value) return groupedRows.value;
  return groupedRows.value.filter(
    r =>
      (r.product || '').toLowerCase().includes(keyword.value.toLowerCase()) ||
      (r.variant || '').toLowerCase().includes(keyword.value.toLowerCase()) ||
      (r.code || '').toLowerCase().includes(keyword.value.toLowerCase())
  );
});
const pagedRows = computed(() => {
  const start = (page.value - 1) * perPage;
  return filteredRows.value.slice(start, start + perPage);
});
const totalPages = computed(() => Math.ceil(filteredRows.value.length / perPage));

const fetchHistory = async () => {
  let url = '/api/inventory/history?'
  // Nếu chưa chọn startDate thì mặc định là 2025-01-01
  url += `start_date=${startDate.value || '2025-01-01'}&`
  if (endDate.value) url += `end_date=${endDate.value}&`
  const res = await fetch(url)
  const json = await res.json()
  rows.value = json.data || []
  page.value = 1 // reset về trang đầu khi lọc
  console.log('Fetched inventory history:', rows.value);
}

const showDetail = async (row) => {
  if (!row.id) return;
  showModal.value = false;
  detail.value = null;
  // Lấy tất cả các dòng cùng mã chứng từ (code) và type
  const items = rows.value.filter(r => r.code === row.code && r.type === row.type);
  // Map lại các trường cho từng item
  const mappedItems = items.map(item => {
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

onMounted(fetchHistory)

</script>
<style scoped>
table { border-collapse: collapse; }
th, td { border: 1px solid #e5e7eb; }
</style>