<template>
  <AppLayout>
    <div class="bg-white rounded-lg shadow p-6 w-full mx-auto m-2 pb-[80px]">
      <ExportChart 
  :export_data_by_month="exportData" 
  :export_value_by_month="exportValue" 
  :pluck_year="years"
  @filterByYear="handleFilter"
/>

    <ExportStatusPieChart 
  :export_status_summary="statusData" 
  :pluck_month="[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]"
  @filterByMonthOnly="handleMonthFilter"
/>

      <HorizontalBar
  title="Top 10 sản phẩm bán chạy"
  :rawData="top10.top_10_product_variants"
  type="product"
/>

<HorizontalBar
  title="Top 10 khách hàng chi tiêu nhiều nhất"
  :rawData="top10.top_10_customers"
  type="customer"
/>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import AppLayout from '../Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

import ExportChart from './components/ExportChart.vue'
import ExportStatusPieChart from './components/ExportStatusPieChart.vue'
import HorizontalBar from './components/HorizontalBar.vue'

// Nhận dữ liệu props từ server
const props = defineProps({
  top10: Object,
    exportData: Object,
  exportValue: Object,
  years: Array,
  exportStatusSummary: Object, 
  selectedMonth: Number 
})
const statusData = ref(props.exportStatusSummary)
// Thời gian lọc: tuần/tháng/năm...
const selectedTime = ref('week')

// Dữ liệu Top 10
const top10Products = props.top10.top_10_product_variants || {}
const top10Customers = props.top10.top_10_customers || {}

// Hàm xử lý lọc theo năm
const handleFilter = (filter) => {
  router.get(route('admin.reports.export'), { year: filter.year }, {
    preserveScroll: true,
    preserveState: true,
    only: ['exportData', 'exportValue'] // chỉ reload các phần cần
  })
}
// Hàm xử lý lọc theo tháng
const handleMonthFilter = (month) => {
  router.get(route('admin.reports.export'), { month }, {
    preserveScroll: true,
    preserveState: true,
    only: ['exportStatusSummary', 'selectedMonth'], // chỉ reload phần Pie chart
    onSuccess: () => {
      statusData.value = props.exportStatusSummary
    }
  })
}
</script>
