<template>
  <AppLayout>
    <div class="p-4 min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-100">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-indigo-900 mb-1 tracking-tight">{{ reportTitle }}</h1>
          <p class="text-sm text-gray-600">Thống kê tồn kho, nhập xuất vật tư</p>
        </div>
        <div class="flex flex-wrap items-end gap-3">
          <div class="flex flex-wrap gap-3 items-end">
            <div>
              <label class="block text-sm font-semibold text-indigo-700 mb-1" for="keyword-input">
                Tìm kiếm
              </label>
              <div class="relative">
                <input id="keyword-input" type="text" v-model="filter.keyword" placeholder="Tìm mã/tên ..."
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
                  <option v-for="y in yearOptions" :key="y" :value="y" :disabled="y > currentYear">{{ y }}</option>
                </select>
              </div>

              <!-- Select Month or Full Year -->
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Chọn tháng hoặc toàn năm</label>
                <select v-model="tempMonth" class="w-full px-4 py-2 border border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-400 text-sm">
                  <option value="all" :disabled="tempYear > currentYear || (tempYear == currentYear && currentMonth < 12)">Toàn bộ năm</option>
                  <option v-for="m in 12" :key="m" :value="String(m).padStart(2, '0')"
                    :disabled="tempYear > currentYear || (tempYear == currentYear && m > currentMonth)">
                    Tháng {{ String(m).padStart(2, '0') }}
                  </option>
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
        <table class="min-w-full text-sm border border-indigo-300 rounded-xl bg-white">
          <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 sticky top-0 z-10">
            <tr class="">
              <th v-for="header in [
                'Mã VT', 'Tên vật tư', 'ĐVT',
                'Tồn đầu SL', 'Tồn đầu TT',
                'Nhập SL', 'Nhập TT',
                'Xuất SL', 'Xuất TT',
                'Điều chỉnh',
                'Tồn cuối SL', 'Tồn cuối TT',
                'Đơn giá xuất BQGQ']"
                :key="header"
                class="px-3 py-3 text-xs font-bold text-indigo-800 uppercase tracking-wider border border-indigo-300 text-center bg-indigo-50">
                {{ header }}
              </th>
            </tr>
          </thead>
          <tbody v-if="filteredData.length && !loading">
            <tr v-for="item in filteredData" :key="item.item_code" class="hover:bg-indigo-50 transition cursor-pointer group">
              <td class="px-3 py-3 border border-indigo-200 text-left">{{ item.item_code }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-left">
                {{ item.item_name }}
                <br>
                <span class="text-xs text-gray-500 font-normal italic">{{ item.attributes }}</span>
              </td>
              <td class="px-3 py-3 border border-indigo-200 text-center">{{ item.unit }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.opening_qty) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.opening_value) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.received_qty) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.received_value) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.shipped_qty) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.shipped_value) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right text-red-700 font-semibold">{{ format(item.increase_qty) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.closing_qty) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(item.closing_value) }}</td>
              <td class="px-3 py-3 border border-indigo-200 text-right">{{ format(importBQGQ(item)) }}</td>
            </tr>
          </tbody>
          <tbody v-else-if="!loading">
            <tr>
              <td colspan="13" class="text-center text-gray-500 py-12 border border-indigo-200">
                <i class="fas fa-box-open text-4xl mb-2 text-indigo-400"></i>
                <div class="text-base font-semibold text-indigo-600">Không có dữ liệu</div>
              </td>
            </tr>
          </tbody>
          <tfoot class="bg-indigo-100 font-semibold text-indigo-800 sticky bottom-0">
            <tr>
              <td colspan="3" class="px-3 py-3 border border-indigo-300 text-right text-xs">Tổng cộng:</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('opening_qty') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('opening_value') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('received_qty') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('received_value') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('shipped_qty') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('shipped_value') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs text-green-700">{{ total('increase_qty') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('closing_qty') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs">{{ total('closing_value') }}</td>
              <td class="px-3 py-3 border border-indigo-300 text-right text-xs"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div v-else class="text-center text-gray-500 py-20 text-lg font-semibold bg-white rounded-xl shadow-lg border border-indigo-100">
        <i class="fas fa-calendar-alt text-4xl mb-2 text-indigo-400"></i>
        <div>Vui lòng chọn thời gian 1 tháng hoặc 1 năm.</div>
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
const now = new Date()
const currentYear = now.getFullYear()
const currentMonth = now.getMonth() + 1 // JS month is 0-based
const yearOptions = ref(Array.from({ length: 3 }, (_, i) => currentYear - i))

const filter = ref({
  periods: [],
  months: [],
  keyword: ''
})

const data = ref(page.props.data || [])
const loading = ref(false)

// Modal refs
const isModalOpen = ref(false)
const tempYear = ref(currentYear)
const tempMonth = ref(String(currentMonth).padStart(2, '0'))

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
    console.log(data.value);
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
      tempMonth.value = String(currentMonth).padStart(2, '0')
    }
  }
})
// Đảm bảo tempMonth luôn hợp lệ khi đổi năm
watch(tempYear, (newYear) => {
  if (newYear > currentYear) {
    tempMonth.value = String(currentMonth).padStart(2, '0')
  } else if (newYear == currentYear && Number(tempMonth.value) > currentMonth) {
    tempMonth.value = String(currentMonth).padStart(2, '0')
  } else if (newYear < currentYear && tempMonth.value === 'all') {
    // giữ nguyên
  } else if (newYear < currentYear && Number(tempMonth.value) > 12) {
    tempMonth.value = '12'
  }
})
const importBQGQ = (item) => {
  const totalQty = (Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0)
  const totalVal = (Number(item.opening_value) || 0) + (Number(item.received_value) || 0)
  return totalQty > 0 ? totalVal / totalQty : 0
}

const exportBQGQ = (item) => {
  const shipQty = Number(item.shipped_qty)
  const shipVal = Number(item.shipped_value)
  return shipQty > 0 ? shipVal / shipQty : 0
}

const exportExcel = () => {
  if (!filteredData.value.length) {
    alert('Không có dữ liệu để xuất Excel!')
    return
  }

  // Tạo workbook và worksheet
  const wb = XLSX.utils.book_new()
  const ws = {}

  // Thiết lập tiêu đề báo cáo
  const reportTitle = 'BÁO CÁO TỒN KHO'
  const periodTitle = `Kỳ báo cáo: ${displayPeriod.value}`

  // Style chung (giữ nguyên để đẹp lộng lẫy, khoa học)
  const titleStyle = {
    font: { name: 'Arial', sz: 18, bold: true, color: { rgb: 'FFFFFF' } },
    fill: { fgColor: { rgb: '4472C4' } }, // Xanh dương đậm, lộng lẫy
    alignment: { horizontal: 'center', vertical: 'center' },
    border: { 
      top: { style: 'thick', color: { rgb: '000000' } }, 
      bottom: { style: 'thick', color: { rgb: '000000' } }, 
      left: { style: 'thick', color: { rgb: '000000' } }, 
      right: { style: 'thick', color: { rgb: '000000' } } 
    }
  }

  const periodStyle = {
    font: { name: 'Arial', sz: 14, italic: true, color: { rgb: '000000' } },
    fill: { fgColor: { rgb: 'DDEBF7' } }, // Xanh nhạt
    alignment: { horizontal: 'center', vertical: 'center' },
    border: { 
      bottom: { style: 'thick', color: { rgb: '000000' } },
      top: { style: 'thin', color: { rgb: '000000' } },
      left: { style: 'thin', color: { rgb: '000000' } },
      right: { style: 'thin', color: { rgb: '000000' } }
    }
  }

  const headerStyle = {
    font: { name: 'Arial', sz: 12, bold: true, color: { rgb: '000000' } },
    fill: { fgColor: { rgb: 'BFBFBF' } }, // Xám nhạt
    alignment: { horizontal: 'center', vertical: 'center', wrapText: true },
    border: { 
      top: { style: 'thick', color: { rgb: '000000' } }, 
      bottom: { style: 'thick', color: { rgb: '000000' } }, 
      left: { style: 'thin', color: { rgb: '000000' } }, 
      right: { style: 'dashed', color: { rgb: '000000' } }  // Dashed để phân cách khoa học
    }
  }

  const dataStyleTextEven = {  // Nền xám nhạt cho row chẵn
    font: { name: 'Arial', sz: 11 },
    fill: { fgColor: { rgb: 'F2F2F2' } }, // Xám rất nhạt, xen kẽ
    alignment: { horizontal: 'left', vertical: 'center', wrapText: true },
    border: { 
      top: { style: 'thin', color: { rgb: '000000' } }, 
      bottom: { style: 'thin', color: { rgb: '000000' } }, 
      left: { style: 'thin', color: { rgb: '000000' } }, 
      right: { style: 'thin', color: { rgb: '000000' } } 
    }
  }

  const dataStyleTextOdd = { ...dataStyleTextEven, fill: { fgColor: { rgb: 'FFFFFF' } } }; // Trắng cho row lẻ

  const dataStyleNumEven = { ...dataStyleTextEven, alignment: { horizontal: 'right', vertical: 'center' }, numFmt: '#,##0' };
  const dataStyleNumOdd = { ...dataStyleTextOdd, alignment: { horizontal: 'right', vertical: 'center' }, numFmt: '#,##0' };

  const dataStyleValueEven = { ...dataStyleNumEven, numFmt: '#,##0.00' };
  const dataStyleValueOdd = { ...dataStyleNumOdd, numFmt: '#,##0.00' };

  const dataStyleAdjustmentEven = { ...dataStyleNumEven, fill: { fgColor: { rgb: 'FFE4E1' } }, font: { name: 'Arial', sz: 11, color: { rgb: 'FF0000' } } }; // Hồng nhạt + đỏ
  const dataStyleAdjustmentOdd = { ...dataStyleAdjustmentEven, fill: { fgColor: { rgb: 'FFFFFF' } } }; // Xen kẽ

  const totalStyle = {
    font: { name: 'Arial', sz: 12, bold: true, color: { rgb: '000000' } },
    fill: { fgColor: { rgb: 'FFF2CC' } }, // Vàng nhạt
    alignment: { horizontal: 'right', vertical: 'center' },
    border: { 
      top: { style: 'thick', color: { rgb: '000000' } }, 
      bottom: { style: 'thick', color: { rgb: '000000' } }, 
      left: { style: 'thin', color: { rgb: '000000' } }, 
      right: { style: 'dashed', color: { rgb: '000000' } } 
    },
    numFmt: '#,##0.00'
  }

  const totalStyleAdjustment = { ...totalStyle, font: { ...totalStyle.font, color: { rgb: '008000' } }, numFmt: '#,##0' };

  const footerStyle = {
    font: { name: 'Arial', sz: 10, italic: true, color: { rgb: '808080' } },
    fill: { fgColor: { rgb: 'EDEDED' } }, // Xám nhạt
    alignment: { horizontal: 'center', vertical: 'center' },
    border: { top: { style: 'thin', color: { rgb: '000000' } } }
  };

  // Thiết lập tiêu đề với style
  ws['A1'] = { v: reportTitle, t: 's', s: titleStyle }
  ws['A2'] = { v: periodTitle, t: 's', s: periodStyle }

  // Header của bảng (bắt đầu từ dòng 4, row 3 zero-based)
  const headers = [
    'Mã VT', 'Tên vật tư', 'ĐVT',
    'Tồn đầu SL', 'Tồn đầu TT',
    'Nhập SL', 'Nhập TT',
    'Xuất SL', 'Xuất TT',
    'Điều chỉnh',
    'Tồn cuối SL', 'Tồn cuối TT',
    'Đơn giá xuất BQGQ'
  ]

  headers.forEach((header, index) => {
    const cellRef = XLSX.utils.encode_cell({ r: 3, c: index })
    ws[cellRef] = { v: header, t: 's', s: headerStyle }
  })

  // Dữ liệu rows (bắt đầu từ row 5, sử dụng cả giá trị tính toán (v) và công thức (f))
  const rows = filteredData.value.map((item, rowIndex) => {
    const isEven = rowIndex % 2 === 0; // Xen kẽ nền
    const textStyle = isEven ? dataStyleTextEven : dataStyleTextOdd;
    const numStyle = isEven ? dataStyleNumEven : dataStyleNumOdd;
    const valueStyle = isEven ? dataStyleValueEven : dataStyleValueOdd;
    const adjStyle = isEven ? dataStyleAdjustmentEven : dataStyleAdjustmentOdd;

    const rowNum = rowIndex + 5;

    // Tính toán giá trị trước bằng JS cho các cột công thức
    const closingQty = (Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0) - (Number(item.shipped_qty) || 0) + (Number(item.increase_qty) || 0);
    const closingValue = Number(item.closing_value) || 0; // Sử dụng giá trị thực tế từ dữ liệu
    const unitPriceBQGQ = ((Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0)) > 0 ? 
      ((Number(item.opening_value) || 0) + (Number(item.received_value) || 0)) / ((Number(item.opening_qty) || 0) + (Number(item.received_qty) || 0)) : 0;

    return [
      { v: item.item_code, t: 's', s: textStyle },
      { v: item.item_name, t: 's', s: textStyle },
      { v: item.unit, t: 's', s: textStyle },
      { v: Number(item.opening_qty) || 0, t: 'n', s: numStyle }, // Giá trị tĩnh
      { v: Number(item.opening_value) || 0, t: 'n', s: valueStyle },
      { v: Number(item.received_qty) || 0, t: 'n', s: numStyle },
      { v: Number(item.received_value) || 0, t: 'n', s: valueStyle },
      { v: Number(item.shipped_qty) || 0, t: 'n', s: numStyle },
      { v: Number(item.shipped_value) || 0, t: 'n', s: valueStyle },
      { v: Number(item.increase_qty) || 0, t: 'n', s: adjStyle },
      { v: closingQty, t: 'n', f: `=D${rowNum}+F${rowNum}-H${rowNum}+J${rowNum}`, s: numStyle }, // Giá trị + công thức (xóa khoảng trắng để Sheets parse tốt hơn)
      { v: closingValue, t: 'n', s: valueStyle },
      { v: unitPriceBQGQ, t: 'n', f: `=IF((D${rowNum}+F${rowNum})=0,0,(E${rowNum}+G${rowNum})/(D${rowNum}+F${rowNum}))`, s: valueStyle } // Công thức mới: (Tồn đầu TT + Nhập TT) / (Tồn đầu SL + Nhập SL)
    ]
  });

  // Thêm dữ liệu vào worksheet
  rows.forEach((row, rowIndex) => {
    row.forEach((cell, colIndex) => {
      const cellRef = XLSX.utils.encode_cell({ r: rowIndex + 4, c: colIndex })
      ws[cellRef] = cell
    })
  })

  // Tính toán dòng tổng cộng (sử dụng cả giá trị tính toán và công thức SUM động)
  const lastDataRow = rows.length + 4; // Row cuối của data (zero-based) +1 cho 1-based
  const totalRow = lastDataRow + 1; // Dòng total (zero-based: lastDataRow +1)

  // Tính tổng bằng JS cho giá trị hiển thị
  const totalOpeningQty = filteredData.value.reduce((acc, item) => acc + (Number(item.opening_qty) || 0), 0);
  const totalOpeningValue = filteredData.value.reduce((acc, item) => acc + (Number(item.opening_value) || 0), 0);
  const totalReceivedQty = filteredData.value.reduce((acc, item) => acc + (Number(item.received_qty) || 0), 0);
  const totalReceivedValue = filteredData.value.reduce((acc, item) => acc + (Number(item.received_value) || 0), 0);
  const totalShippedQty = filteredData.value.reduce((acc, item) => acc + (Number(item.shipped_qty) || 0), 0);
  const totalShippedValue = filteredData.value.reduce((acc, item) => acc + (Number(item.shipped_value) || 0), 0);
  const totalIncreaseQty = filteredData.value.reduce((acc, item) => acc + (Number(item.increase_qty) || 0), 0);
  const totalClosingQty = filteredData.value.reduce((acc, item) => acc + (Number(item.closing_qty) || 0), 0);
  const totalClosingValue = filteredData.value.reduce((acc, item) => acc + (Number(item.closing_value) || 0), 0);

  const totals = [
    { v: 'Tổng cộng:', t: 's', s: { ...totalStyle, alignment: { horizontal: 'left' } } },
    { v: '', t: 's', s: totalStyle },
    { v: '', t: 's', s: totalStyle },
    { v: totalOpeningQty, t: 'n', f: `=SUM(D5:D${lastDataRow})`, s: { ...totalStyle, numFmt: '#,##0' } }, // Giá trị + công thức
    { v: totalOpeningValue, t: 'n', f: `=SUM(E5:E${lastDataRow})`, s: totalStyle },
    { v: totalReceivedQty, t: 'n', f: `=SUM(F5:F${lastDataRow})`, s: { ...totalStyle, numFmt: '#,##0' } },
    { v: totalReceivedValue, t: 'n', f: `=SUM(G5:G${lastDataRow})`, s: totalStyle },
    { v: totalShippedQty, t: 'n', f: `=SUM(H5:H${lastDataRow})`, s: { ...totalStyle, numFmt: '#,##0' } },
    { v: totalShippedValue, t: 'n', f: `=SUM(I5:I${lastDataRow})`, s: totalStyle },
    { v: totalIncreaseQty, t: 'n', f: `=SUM(J5:J${lastDataRow})`, s: totalStyleAdjustment },
    { v: totalClosingQty, t: 'n', f: `=SUM(K5:K${lastDataRow})`, s: { ...totalStyle, numFmt: '#,##0' } },
    { v: totalClosingValue, t: 'n', f: `=SUM(L5:L${lastDataRow})`, s: totalStyle },
    { v: '', t: 's', s: totalStyle }
  ];

  totals.forEach((cell, colIndex) => {
    const cellRef = XLSX.utils.encode_cell({ r: totalRow, c: colIndex })
    ws[cellRef] = cell
  });

  // Thêm footer (dòng dưới total)
  const footerRow = totalRow + 1;
  ws[XLSX.utils.encode_cell({ r: footerRow, c: 0 })] = { v: 'Báo cáo được tạo bởi hệ thống - Ngày: ' + new Date().toLocaleDateString(), t: 's', s: footerStyle };

  // Merge cells
  ws['!merges'] = [
    { s: { r: 0, c: 0 }, e: { r: 0, c: 12 } }, // Tiêu đề chính
    { s: { r: 1, c: 0 }, e: { r: 1, c: 12 } },  // Kỳ báo cáo
    { s: { r: totalRow, c: 0 }, e: { r: totalRow, c: 2 } }, // Merge 'Tổng cộng:'
    { s: { r: footerRow, c: 0 }, e: { r: footerRow, c: 12 } } // Merge footer
  ];

  // Thiết lập range
  const range = { s: { r: 0, c: 0 }, e: { r: footerRow, c: 12 } };
  ws['!ref'] = XLSX.utils.encode_range(range);

  // Độ rộng cột và chiều cao hàng (tăng để lộng lẫy)
  ws['!cols'] = [
    { wch: 15 }, { wch: 35 }, { wch: 10 },
    { wch: 15 }, { wch: 18 },
    { wch: 15 }, { wch: 18 },
    { wch: 15 }, { wch: 18 },
    { wch: 15 },
    { wch: 15 }, { wch: 18 },
    { wch: 20 }
  ];
  ws['!rows'] = [
    { hpt: 35 }, // Tiêu đề
    { hpt: 25 }, // Kỳ
    { hpt: 15 }, // Khoảng trống
    { hpt: 30 }, // Header
    // Data rows tự động, total 25pt
    ...Array(rows.length).fill({ hpt: 20 }),
    { hpt: 25 }, // Total
    { hpt: 20 }  // Footer
  ];

  // Freeze panes
  ws['!freeze'] = { xSplit: 3, ySplit: 4 }; // Freeze 3 cột đầu và header

  // Thêm worksheet và xuất file với tùy chọn tối ưu cho Sheets
  XLSX.utils.book_append_sheet(wb, ws, 'BaoCaoTonKho')
  const fileName = `BaoCaoTonKho_${displayPeriod.value.replace(/ /g, '_').replace(/\//g, '_')}.xlsx`

  // Xuất với tùy chọn để tương thích tốt hơn với Google Sheets
  XLSX.writeFile(wb, fileName, {
    bookType: 'xlsx', // Định dạng XLSX chuẩn
    bookSST: true, // Shared String Table để tối ưu chuỗi và công thức
    compression: true, // Nén file để import nhanh
    cellStyles: true, // Giữ style (màu, border) khi import vào Sheets
    Props: { Title: 'Bao Cao Ton Kho' } // Thêm metadata
  });

  console.log('File exported:', fileName); // Debug để kiểm tra
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