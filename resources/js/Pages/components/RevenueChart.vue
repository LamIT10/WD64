<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-semibold text-gray-800">Doanh thu thuần</h3>
      <select v-model="selectedRange" class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        <option value="daily">Hàng ngày</option>
        <option value="weekly">Hàng tuần</option>
        <option value="monthly">Hàng tháng</option>
        <option value="quarterly">Hàng quý</option>
      </select>
    </div>

    <div class="h-80">
      <canvas ref="chartCanvas"></canvas>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-4 text-center">
      <div class="bg-blue-50 p-3 rounded-lg">
        <p class="text-sm text-blue-600"> {{ selectedRangeLabel }} Này</p>
        <p class="font-bold text-blue-800">{{ formatCurrency(currentRevenue) }}</p>
      </div>
      <div class="bg-green-50 p-3 rounded-lg">
        <p class="text-sm text-green-600">{{ selectedRangeLabel }} Trước</p>
        <p class="font-bold text-green-800">{{ formatCurrency(lastRevenue) }}</p>
      </div>
      <div class="bg-purple-50 p-3 rounded-lg">
        <p class="text-sm text-purple-600">Thay đổi</p>
        <p class="font-bold text-purple-800">{{ percentChange }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({
  data: Object
})

const selectedRange = ref('weekly')
const chartCanvas = ref(null)
const chartInstance = ref(null)

const selectedRangeLabel = computed(() => {
  switch (selectedRange.value) {
    case 'daily':
      return 'Ngày';
    case 'weekly':
      return 'Tuần';
    case 'monthly':
      return 'Tháng';
    case 'quarterly':
      return 'Quý';
    default:
      return '';
  }
});

const chartRaw = computed(() => props.data?.[selectedRange.value] || {
  labels: [],
  values: [],
  current: 0,
  previous: 0,
  percent_change: 0
})

const currentRevenue = computed(() => chartRaw.value.current || 0)
const lastRevenue = computed(() => chartRaw.value.previous || 0)

const percentChange = computed(() => {
  const c = currentRevenue.value
  const l = lastRevenue.value
  if (!l) return '0%'
  const change = ((c - l) / l) * 100
  return `${change > 0 ? '+' : ''}${change.toFixed(1)}%`
})

const formatCurrency = (val) => {
  const number = Number(val) || 0;
  return number.toLocaleString('vi-VN', {
    style: 'currency',
    currency: 'VND',
    minimumFractionDigits: 0
  });
};
const renderChart = () => {
  if (chartInstance.value) chartInstance.value.destroy()

  const ctx = chartCanvas.value.getContext('2d')
  chartInstance.value = new Chart(ctx, {
    type: chartRaw.value.labels.length <= 3 ? 'bar' : 'line',
    data: {
      labels: chartRaw.value.labels,
      datasets: [{
        label: 'Doanh thu (₫)',
        data: chartRaw.value.values,
        backgroundColor: 'rgba(99, 102, 241, 0.3)',
        borderColor: '#6366f1',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'top' },
        tooltip: { mode: 'index', intersect: false },
        title: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: val => '₫' + (val / 1_000_000).toFixed(1) + 'M'
          }
        }
      }
    }
  })
}

onMounted(renderChart)
watch(() => selectedRange.value, renderChart)
watch(() => props.data, renderChart, { deep: true })
</script>

<style scoped>
canvas {
  width: 100% !important;
  height: 100% !important;
}
</style>
