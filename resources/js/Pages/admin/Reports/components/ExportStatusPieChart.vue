<template>
  <div class="w-full">
    <div class="mb-4 flex justify-between items-center">
      <h2 class="text-xl font-semibold">
        Tỉ lệ đơn xuất theo trạng thái - Tháng {{ selectedMonth }}
      </h2>
      <select v-model="selectedMonth" @change="filterByMonth" class="border p-2 rounded">
        <option v-for="m in pluck_month" :key="m" :value="m">Tháng {{ m }}</option>
      </select>
    </div>

    <!-- Biểu đồ -->
    <div class="mx-auto" style="max-width: 500px;">
      <Pie :data="chartData" :options="reactiveChartOptions" :width="400" :height="400" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement)

// Props và emit
const props = defineProps({
  export_status_summary: {
    type: Object,
    required: true
  },
  pluck_month: {
    type: Array,
    default: () => []
  }
})
const emit = defineEmits(['filterByMonthOnly'])

// Chọn tháng mặc định là tháng hiện tại
const currentMonth = new Date().getMonth() + 1
const selectedMonth = ref(currentMonth)

// Khi đổi tháng → gọi filter
const filterByMonth = () => {
  emit('filterByMonthOnly', selectedMonth.value)
}

// Khi mounted → gửi emit để load lần đầu
filterByMonth()

// Xử lý dữ liệu biểu đồ
const chartData = computed(() => {
  const raw = props.export_status_summary?.data || {}

  const statusMap = {
    pending: { label: 'Đang chờ duyệt', color: '#fbbf24' },
    shipped: { label: 'Đang giao', color: '#3b82f6' },
    completed: { label: 'Đã hoàn thành', color: '#10b981' },
    cancelled: { label: 'Từ chối', color: '#ef4444' },
    returning: { label: 'Đang hoàn hàng', color: '#fb923c' },
    returned: { label: 'Đã hoàn hàng', color: '#6366f1' }
  }

  const labels = []
  const data = []
  const colors = []

  for (const key in statusMap) {
    const value = raw[key] || 0
    if (value > 0) {
      labels.push(statusMap[key].label)
      data.push(value)
      colors.push(statusMap[key].color)
    }
  }

  return {
    labels,
    datasets: [
      {
        data,
        backgroundColor: colors
      }
    ]
  }
})

// Chart Options reactive
const reactiveChartOptions = computed(() => ({
  responsive: true,
  plugins: {
    legend: {
      position: 'top'
    },
    tooltip: {
      callbacks: {
        label: function (context) {
          const total = context.dataset.data.reduce((a, b) => a + b, 0)
          const value = context.raw
          const percent = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%'
          return `${context.label}: ${value} (${percent})`
        }
      }
    },
    title: {
      display: true,
      text: `Tỉ lệ đơn xuất theo trạng thái - Tháng ${selectedMonth.value}`
    }
  }
}))
</script>

<style scoped>
</style>
