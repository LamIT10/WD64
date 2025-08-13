<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Thống kê tồn kho theo loại giấy</h3>
    </div>

    <div class="h-80 bg-gray-50 rounded-lg relative">
      <canvas ref="chartCanvas" class="w-full h-full"></canvas>
      <p v-if="chartData.labels.length === 0"
         class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-gray-400">
        Không có dữ liệu
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  data: {
    type: Array,
    default: () => []
  }
})

const chartCanvas = ref(null)
const chartInstance = ref(null)

const chartData = ref({
  labels: [],
  datasets: []
})

const renderChart = async () => {
  const filtered = props.data || []

  const labels = filtered.map(item => item.paper_type)
  const data = filtered.map(item =>
    parseFloat(String(item.total_quantity).replace(/,/g, '').trim())
  )

  chartData.value = {
    labels,
    datasets: [{
      label: 'Số lượng tồn',
      data,
      backgroundColor: '#6366f1'
    }]
  }

  await nextTick()

  if (chartInstance.value) {
    chartInstance.value.destroy()
  }

  const ctx = chartCanvas.value?.getContext('2d')
  if (!ctx) return

  chartInstance.value = new Chart(ctx, {
    type: 'bar',
    data: chartData.value,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Số lượng'
          },
          ticks: {
            callback: (value) => value.toLocaleString('vi-VN')
          }
        },
        x: {
          title: {
            display: true,
            text: 'Loại giấy'
          }
        }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const index = ctx.dataIndex
              const unit = filtered[index]?.unit || ''
              const value = parseFloat(ctx.raw).toLocaleString('vi-VN')
              return `Số lượng tồn: ${value} ${unit}`
            }
          }
        }
      }
    }
  })
}

onMounted(renderChart)
watch(() => props.data, renderChart, { deep: true })
</script>