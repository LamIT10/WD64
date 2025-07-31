<template>
  <div class="mb-30 me-15 ms-15 mt-15">
    <select
      v-model="filterYear.year"
      @change="handleFilterByYear"
      class="px-4 py-2 text-sm border border-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-700 shadow-sm"
    >
      <option v-for="year in pluck_year" :key="year" :value="year">
        {{ year }}
      </option>
    </select>

    <Bar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  LineController,
  BarController
} from 'chart.js'

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  PointElement,
  LineElement,
  LineController,
  BarController
)

// Props
const props = defineProps({
  export_data_by_month: {
    type: Object,
    required: true
  },
  export_value_by_month: {
    type: Object,
    required: true
  },
  pluck_year: {
    type: Array,
    default: () => []
  }
})

const currentYear = new Date().getFullYear()
const year = props.export_data_by_month?.year ?? currentYear

const filterYear = reactive({
  year: year
})

const emit = defineEmits(['filterByYear'])

const handleFilterByYear = () => {
  emit('filterByYear', filterYear)
}

// Sắp xếp theo tháng
const sortedExportData = computed(() => {
  const sorted = Array(12).fill(0)
  props.export_data_by_month?.data?.forEach(item => {
    const monthIndex = item.month - 1
    if (monthIndex >= 0 && monthIndex < 12) {
      sorted[monthIndex] = item.count
    }
  })
  return sorted
})

const sortedExportValue = computed(() => {
  const sorted = Array(12).fill(0)
  props.export_value_by_month?.data?.forEach(item => {
    const monthIndex = item.month - 1
    if (monthIndex >= 0 && monthIndex < 12) {
      sorted[monthIndex] = item.total
    }
  })
  return sorted
})

// Chart Data
const chartData = computed(() => ({
  labels: [
    'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4',
    'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
    'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
  ],
  datasets: [
    {
      type: 'bar',
      label: 'Đơn xuất hoàn thành / tháng',
      data: sortedExportData.value,
      backgroundColor: '#10b981',
      yAxisID: 'y1',
      order: 2
    },
    {
      type: 'line',
      label: 'Tổng giá trị xuất',
      data: sortedExportValue.value,
      borderColor: '#3b82f6',
      borderWidth: 2,
      tension: 0.3,
      fill: false,
      pointBackgroundColor: '#3b82f6',
      yAxisID: 'y2',
      order: 1
    }
  ]
}))

// Chart Options
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top'
    },
    title: {
      display: true,
      text: `Biểu đồ xuất hàng 12 tháng năm ${filterYear.year}`
    }
  },
  scales: {
    y1: {
      type: 'linear',
      position: 'left',
      title: {
        display: true,
        text: 'Số đơn'
      },
      beginAtZero: true,
      ticks: {
        precision: 0
      }
    },
    y2: {
      type: 'linear',
      position: 'right',
      title: {
        display: true,
        text: 'Giá trị (VNĐ)'
      },
      beginAtZero: true,
      grid: {
        drawOnChartArea: false
      },
      ticks: {
        callback: (value) => value.toLocaleString('vi-VN') + '₫'
      }
    }
  }
}))
</script>

<style scoped>
div {
  height: 400px;
}
</style>
