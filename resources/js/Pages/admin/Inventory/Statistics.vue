<template>
  <AppLayout>
    <div class="p-4 min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-100">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-indigo-900 mb-1 tracking-tight">{{ reportTitle }}</h1>
          <p class="text-sm text-gray-600">Thống kê tồn kho, nhập xuất vật tư</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <div class="flex flex-wrap gap-3 items-end">
            <div>
              <label class="block text-sm font-semibold text-indigo-700 mb-1" for="keyword-input">
                Tìm kiếm
              </label>
              <div class="relative">
                <input id="keyword-input" type="text" v-model="filter.keyword" placeholder="Tìm mã/tên vật tư..."
                  class="w-48 md:w-64 pl-10 pr-4 py-2.5 border border-indigo-200 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:outline-none text-sm bg-white transition placeholder-gray-400"
                  :disabled="!selectedPeriod" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-indigo-500 pointer-events-none">
                  <i class="fas fa-search text-base"></i>
                </span>
              </div>
            </div>
          </div>
          <button @click="exportExcel"
            class="flex items-center gap-1.5 px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-md transition text-sm font-semibold focus:ring-2 focus:ring-green-400 focus:outline-none active:scale-95 hover:shadow-lg">
            <i class="fa fa-file-export text-base"></i>
            <span class="hidden md:inline">Xuất Excel</span>
          </button>
          <button @click="printReport"
            class="flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition text-sm font-semibold focus:ring-2 focus:ring-indigo-400 focus:outline-none active:scale-95 hover:shadow-lg">
            <i class="fas fa-print text-base"></i>
            <span class="hidden md:inline">In báo cáo</span>
          </button>
        </div>
      </div>

      <!-- Period Selector -->
      <div class="relative font-sans mb-6 max-w-md">
        <!-- Label -->
        <label class="block text-sm font-bold text-gray-800 mb-2 tracking-wide">
          Khoảng thời gian
        </label>

        <!-- Button to open calendar modal -->
        <button
          @click="isModalOpen = true"
          class="w-full min-w-[280px] px-4 py-3 text-sm bg-gradient-to-r from-white to-indigo-50 border border-gray-200 rounded-xl shadow-sm hover:border-indigo-300 transition-all duration-200 ease-in-out flex items-center justify-between text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        >
          <span class="flex items-center gap-2">
            <i class="fas fa-calendar-alt text-indigo-500"></i>
            {{ displayPeriod || 'Chọn 1 tháng hoặc 1 năm' }}
          </span>
          <svg class="h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <!-- Calendar Modal -->
        <transition name="fade">
          <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="closeModal">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto p-6 relative animate-slide-up">
              <!-- Close Button -->
              <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
              </button>

              <!-- Modal Header -->
              <h3 class="text-lg font-bold text-indigo-900 mb-4">Chọn 1 tháng hoặc 1 năm</h3>

              <!-- Select Year -->
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Chọn năm</label>
                <select v-model="tempYear" class="w-full px-4 py-2 border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-400 text-sm">
                  <option v-for="y in yearOptions" :key="y" :value="y">{{ y }}</option>
                </select>
              </div>

              <!-- Select Month or Full Year -->
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Chọn tháng hoặc toàn năm</label>
                <select v-model="tempMonth" class="w-full px-4 py-2 border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-400 text-sm">
                  <option value="all">Toàn bộ năm</option>
                  <option v-for="m in 12" :key="m" :value="String(m).padStart(2, '0')">Tháng {{ String(m).padStart(2, '0') }}</option>
                </select>
              </div>

              <!-- Footer Buttons -->
              <div class="flex justify-end gap-3 mt-6">
                <button @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl text-sm font-semibold transition">Hủy</button>
                <button @click="applySelection" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition" :disabled="!tempYear">Áp dụng</button>
              </div>
            </div>
          </div>
        </transition>
      </div>

      <!-- Table -->
      <div v-if="selectedPeriod" class="bg-white rounded-xl shadow-lg overflow-x-auto animate-fade-in border border-indigo-100 relative">
        <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 z-10">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
        <table class="min-w-full text-sm border-separate border-spacing-0">
          <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 sticky top-0 z-10">
            <tr>
              <th class="px-3 py-3 text-left font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Mã VT</th>
              <th class="px-3 py-3 text-left font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Tên vật tư</th>
              <th class="px-3 py-3 text-center font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">ĐVT</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Tồn đầu SL</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Tồn đầu TT</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Nhập SL</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Nhập TT</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Xuất SL</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Xuất TT</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Tồn cuối SL</th>
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Tồn cuối TT</th>
              <!-- <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Đơn giá nhập BQGQ</th> -->
              <th class="px-3 py-3 text-right font-bold text-indigo-800 border-b border-indigo-100 uppercase tracking-wider text-xs">Đơn giá xuất BQGQ</th>
            </tr>
          </thead>
          <tbody v-if="filteredData.length && !loading">
            <tr v-for="item in filteredData" :key="item.item_code" class="hover:bg-indigo-50 transition cursor-pointer group">
              <td class="px-3 py-3 border-b border-indigo-100">{{ item.item_code }}</td>
              <td class="px-3 py-3 border-b border-indigo-100">{{ item.item_name }}</td>
              <td class="px-3 py-3 text-center border-b border-indigo-100">{{ item.unit }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.opening_qty) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.opening_value) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.received_qty) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.received_value) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.shipped_qty) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.shipped_value) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.closing_qty) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(item.closing_value) }}</td>
              <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(importBQGQ(item)) }}</td>
              <!-- <td class="px-3 py-3 text-right border-b border-indigo-100">{{ format(exportBQGQ(item)) }}</td> -->
            </tr>
          </tbody>
          <tbody v-else-if="!loading">
            <tr>
              <td colspan="12" class="text-center text-gray-500 py-12 border-b border-indigo-100">
                <i class="fas fa-box-open text-4xl mb-2 text-indigo-400"></i>
                <div class="text-base font-semibold text-indigo-600">Không có dữ liệu</div>
              </td>
            </tr>
          </tbody>
          <tfoot class="bg-indigo-100 font-semibold text-indigo-800 sticky bottom-0">
            <tr>
              <td colspan="3" class="px-3 py-3 border-t border-indigo-100 text-right text-xs">Tổng cộng:</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('opening_qty') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('opening_value') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('received_qty') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('received_value') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('shipped_qty') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ total('shipped_value') }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ totalClosingQty }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ totalClosingValue }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs"></td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs"></td>
            </tr>
            <!-- <tr class="bg-indigo-200">
              <td colspan="4" class="px-3 py-3 border-t border-indigo-100 text-right text-xs font-bold">Tổng theo BQGQ (Tồn cuối TT):</td>
              <td colspan="8" class="px-3 py-3 border-t border-indigo-100 text-right text-xs">{{ totalClosingValueBQGQ }}</td>
              <td class="px-3 py-3 border-t border-indigo-100 text-right text-xs"></td>
            </tr> -->
          </tfoot>
        </table>
      </div>
      <div v-else class="text-center text-gray-500 py-20 text-lg font-semibold bg-white rounded-xl shadow-lg border border-indigo-100">
        <i class="fas fa-calendar-alt text-4xl mb-2 text-indigo-400"></i>
        <div>Vui lòng chọn 1 tháng hoặc 1 năm để xem báo cáo tồn kho.</div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'
import * as XLSX from 'xlsx'

const page = usePage()
const currentYear = new Date().getFullYear()
const yearOptions = ref(Array.from({ length: 5 }, (_, i) => currentYear - i)) // 5 năm gần nhất

const filter = ref({
  periods: [], // Chỉ chứa 1 giá trị: 'YYYY-MM' hoặc 'YYYY-all'
  months: [],
  keyword: ''
})

const data = ref(page.props.data || [])
const loading = ref(false)

// Modal refs
const isModalOpen = ref(false)
const tempYear = ref(currentYear)
const tempMonth = ref('all')

// Computed for display
const selectedPeriod = computed(() => filter.value.periods[0] || null)

const displayPeriod = computed(() => {
  if (!selectedPeriod.value) return ''
  if (selectedPeriod.value.endsWith('-all')) {
    return `Năm ${selectedPeriod.value.slice(0, -4)}`
  }
  const [year, month] = selectedPeriod.value.split('-')
  return `Tháng ${month}/${year}`
})

const reportTitle = computed(() => {
  return `Báo cáo tồn kho ${displayPeriod.value.toLowerCase()}`
})

// Filtered data and totals (giữ nguyên)
const filteredData = computed(() => {
  return data.value.filter(item => {
    const matchesKeyword =
      (item.item_code || '').toLowerCase().includes(filter.value.keyword.toLowerCase()) ||
      (item.item_name || '').toLowerCase().includes(filter.value.keyword.toLowerCase())
    return matchesKeyword
  })
})

const format = (val) => new Intl.NumberFormat('vi-VN').format(val ?? 0)
const total = (key) => format(filteredData.value.reduce((acc, item) => acc + (Number(item[key]) || 0), 0))

const totalClosingQty = computed(() =>
  format(filteredData.value.reduce((acc, item) =>
    acc + ((Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0) - (Number(item.shipped_qty) || 0)), 0))
)

const totalClosingValue = computed(() =>
  format(filteredData.value.reduce((acc, item) => {
    const closingQty = (Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0) - (Number(item.shipped_qty) || 0)
    return acc + (closingQty * (Number(item.unit_price) || 0)) // Sử dụng unit_price cho closing_value
  }, 0))
)

// Thống kê BQGQ: Tổng tồn cuối TT theo đơn giá BQGQ (tương tự totalClosingValue, nhưng tách riêng để nhấn mạnh)
const totalClosingValueBQGQ = computed(() => totalClosingValue.value)

// Đóng modal không áp dụng
const closeModal = () => {
  isModalOpen.value = false
}

// Áp dụng lựa chọn
const applySelection = () => {
  if (!tempYear.value) return
  const period = tempMonth.value === 'all' ? `${tempYear.value}-all` : `${tempYear.value}-${tempMonth.value}`
  filter.value.periods = [period]
  isModalOpen.value = false
  onPeriodChange()
}

// Fetch data khi thay đổi period
const onPeriodChange = async () => {
  const period = selectedPeriod.value
  if (!period) {
    data.value = []
    return
  }

  // Xây dựng months
  filter.value.months = []
  if (period.endsWith('-all')) {
    const year = period.slice(0, -4)
    for (let m = 1; m <= 12; m++) {
      filter.value.months.push(`${year}-${String(m).padStart(2, '0')}`)
    }
  } else {
    filter.value.months = [period]
  }

  loading.value = true
  try {
    const res = await fetch(`/api/inventory/statistics?months=${filter.value.months.join(',')}`)
    const json = await res.json()
    data.value = json.data || []
  } catch (error) {
    console.error('Error fetching data:', error)
  } finally {
    loading.value = false
  }
}

// Khởi tạo temp khi mở modal
watch(isModalOpen, (val) => {
  if (val) {
    // Reset temp to current selected or default
    if (selectedPeriod.value) {
      if (selectedPeriod.value.endsWith('-all')) {
        tempMonth.value = 'all'
        tempYear.value = parseInt(selectedPeriod.value.slice(0, -4))
      } else {
        const [year, month] = selectedPeriod.value.split('-')
        tempYear.value = parseInt(year)
        tempMonth.value = month
      }
    } else {
      tempYear.value = currentYear
      tempMonth.value = 'all'
    }
  }
})

const importBQGQ = (item) => {
  const totalQty = Number(item.opening_qty) + Number(item.received_qty)
  const totalVal = Number(item.opening_value) + Number(item.received_value)
  return totalQty > 0 ? totalVal / totalQty : 0
}

const exportBQGQ = (item) => {
  const shipQty = Number(item.shipped_qty)
  const shipVal = Number(item.shipped_value)
  return shipQty > 0 ? shipVal / shipQty : 0
}

// Chức năng xuất Excel
const exportExcel = () => {
  if (!filteredData.value.length) {
    alert('Không có dữ liệu để xuất Excel!')
    return
  }

  // Header của bảng
  const headers = [
    'Mã VT',
    'Tên vật tư',
    'ĐVT',
    'Tồn đầu SL',
    'Tồn đầu TT',
    'Nhập SL',
    'Nhập TT',
    'Xuất SL',
    'Xuất TT',
    'Tồn cuối SL',
    'Tồn cuối TT',
    'Đơn giá xuất BQGQ' // Hoặc thêm 'Đơn giá nhập BQGQ' nếu cần
  ]

  // Dữ liệu rows
  const rows = filteredData.value.map(item => [
    item.item_code,
    item.item_name,
    item.unit,
    Number(item.opening_qty) || 0,
    Number(item.opening_value) || 0,
    Number(item.received_qty) || 0,
    Number(item.received_value) || 0,
    Number(item.shipped_qty) || 0,
    Number(item.shipped_value) || 0,
    Number(item.closing_qty) || 0,
    Number(item.closing_value) || 0,
    importBQGQ(item) // Sử dụng importBQGQ như trong template (cột cuối là Đơn giá xuất BQGQ, nhưng hàm là import - có thể điều chỉnh nếu cần)
    // exportBQGQ(item) // Nếu cần thêm cột
  ])

  // Thêm dòng tổng cộng
  rows.push([
    'Tổng cộng:', '', '',
    filteredData.value.reduce((acc, item) => acc + (Number(item.opening_qty) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + (Number(item.opening_value) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + (Number(item.received_qty) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + (Number(item.received_value) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + (Number(item.shipped_qty) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + (Number(item.shipped_value) || 0), 0),
    filteredData.value.reduce((acc, item) => acc + ((Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0) - (Number(item.shipped_qty) || 0)), 0),
    filteredData.value.reduce((acc, item) => {
      const closingQty = (Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0) - (Number(item.shipped_qty) || 0)
      return acc + (closingQty * (Number(item.unit_price) || 0))
    }, 0),
    '' // Không tổng cho đơn giá
  ])

  // Thêm dòng tổng BQGQ nếu cần (hiện đang comment trong template)
  // rows.push([
  //   'Tổng theo BQGQ (Tồn cuối TT):', '', '', '', '', '', '', '', '', '', totalClosingValueBQGQ.value, ''
  // ])

  // Tạo worksheet
  const ws = XLSX.utils.aoa_to_sheet([headers, ...rows])

  // Tạo workbook và thêm sheet
  const wb = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(wb, ws, 'BaoCaoTonKho')

  // Tên file dựa trên displayPeriod
  const fileName = `BaoCaoTonKho_${displayPeriod.value.replace(/ /g, '_').replace(/\//g, '_')}.xlsx`

  // Xuất file
  XLSX.writeFile(wb, fileName)
}

const printReport = () => { window.print() }
</script>
<style scoped>
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

/* Custom scroll for select */
select::-webkit-scrollbar {
  width: 6px;
}

select::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

select::-webkit-scrollbar-thumb {
  background: #a5b4fc;
  border-radius: 10px;
}

select::-webkit-scrollbar-thumb:hover {
  background: #818cf8;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.animate-slide-up {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
</style>