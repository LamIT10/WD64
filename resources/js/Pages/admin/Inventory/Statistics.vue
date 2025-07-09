<template>
  <AppLayout>
    <div class="p-6 bg-gray-50 min-h-screen">
      <!-- Bộ lọc -->
      <div class="bg-white p-4 rounded-xl shadow flex flex-col sm:flex-row gap-4 mb-6 items-end">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-gray-600 mb-1">Tháng thống kê</label>
          <input
            type="month"
            v-model="filter.month"
            @change="onMonthChange"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition"
          />
        </div>
        <div class="flex-1">
          <label class="block text-xs font-semibold text-gray-600 mb-1">Tìm mã/tên vật tư</label>
          <input
            type="text"
            v-model="filter.keyword"
            placeholder="Tìm kiếm..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition"
            :disabled="!filter.month"
          />
        </div>
        <div class="flex gap-2">
          <button @click="exportExcel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Xuất Excel
          </button>
          <button @click="printReport" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            In báo cáo
          </button>
        </div>
      </div>

      <!-- Bảng dữ liệu -->
      <div v-if="filter.month" class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-xs border-separate border-spacing-0">
          <thead class="bg-blue-50 text-gray-700 sticky top-0 z-10">
            <tr>
              <th class="p-3 border-b font-semibold text-left">Mã VT</th>
              <th class="p-3 border-b font-semibold text-left">Tên vật tư</th>
              <th class="p-3 border-b font-semibold text-center">ĐVT</th>
              <th class="p-3 border-b font-semibold text-right">Tồn đầu SL</th>
              <th class="p-3 border-b font-semibold text-right">Tồn đầu TT</th>
              <th class="p-3 border-b font-semibold text-right">Nhập SL</th>
              <th class="p-3 border-b font-semibold text-right">Nhập TT</th>
              <th class="p-3 border-b font-semibold text-right">Xuất SL</th>
              <th class="p-3 border-b font-semibold text-right">Xuất TT</th>
              <th class="p-3 border-b font-semibold text-right">Tồn cuối SL</th>
              <th class="p-3 border-b font-semibold text-right">Tồn cuối TT</th>
              <th class="p-3 border-b font-semibold text-right">Đơn giá BQGQ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in filteredData" :key="item.item_code" class="hover:bg-blue-50 transition">
              <td class="p-2 border-b">{{ item.item_code }}</td>
              <td class="p-2 border-b">{{ item.item_name }}</td>
              <td class="p-2 border-b text-center">{{ item.unit }}</td>
              <td class="p-2 border-b text-right">{{ format(item.opening_qty) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.opening_value) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.received_qty) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.received_value) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.shipped_qty) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.shipped_value) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.closing_qty) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.closing_value) }}</td>
              <td class="p-2 border-b text-right">{{ format(item.unit_price) }}</td>
            </tr>
          </tbody>
          <tfoot class="bg-blue-100 font-semibold text-gray-800">
            <tr>
              <td colspan="3" class="p-2 border-t text-right">Tổng cộng:</td>
              <td class="p-2 border-t text-right">{{ total('opening_qty') }}</td>
              <td class="p-2 border-t text-right">{{ total('opening_value') }}</td>
              <td class="p-2 border-t text-right">{{ total('received_qty') }}</td>
              <td class="p-2 border-t text-right">{{ total('received_value') }}</td>
              <td class="p-2 border-t text-right">{{ total('shipped_qty') }}</td>
              <td class="p-2 border-t text-right">{{ total('shipped_value') }}</td>
              <td class="p-2 border-t text-right">{{ total('closing_qty') }}</td>
              <td class="p-2 border-t text-right">{{ total('closing_value') }}</td>
              <td class="p-2 border-t text-right">-</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div v-else class="text-center text-gray-400 py-16 text-lg font-semibold">
        Vui lòng chọn tháng để xem báo cáo tồn kho.
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'

const page = usePage()

const filter = ref({
  month: page.props.month || '',
  keyword: ''
})

const data = ref(page.props.data || [])

const filteredData = computed(() => {
  return data.value.filter(item => {
    const matchesKeyword =
      (item.item_code || '').toLowerCase().includes(filter.value.keyword.toLowerCase()) ||
      (item.item_name || '').toLowerCase().includes(filter.value.keyword.toLowerCase())
    return matchesKeyword
  })
})

const format = (val) => new Intl.NumberFormat('vi-VN').format(val ?? 0)
const total = (key) => format(filteredData.value.reduce((acc, item) => acc + (item[key] ?? 0), 0))

const exportExcel = () => { alert('Xuất Excel (chức năng giả lập)') }
const printReport = () => { window.print() }

const onMonthChange = async () => {
  if (!filter.value.month) return;
  const res = await fetch(`/api/inventory/statistics?month=${filter.value.month}`)
  const json = await res.json()
  data.value = json.data || []
  console.log('Updated data:', data.value);
}
</script>

<style scoped>
/* Có thể bỏ phần min-width nếu dùng Tailwind responsive tốt */
</style>