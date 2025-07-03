<template>
  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Thống kê tồn kho theo loại giấy</h3>
      <select v-model="selectedRange"
              class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        <option value="7days">7 ngày qua</option>
        <option value="30days">30 ngày qua</option>
        <option value="90days">90 ngày qua</option>
      </select>
    </div>

    <!-- <div class="flex items-center gap-4 mb-4">
      <label class="text-sm flex items-center gap-2">
        <input type="checkbox" v-model="hideA10" />
        Ẩn Giấy A10
      </label>
      <label class="text-sm flex items-center gap-2">
        <input type="checkbox" v-model="useLogScale" />
        Dùng log scale
      </label>
    </div> -->

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
  data: Object
})

const selectedRange = ref('90days')
const chartCanvas = ref(null)
const chartInstance = ref(null)
const hideA10 = ref(false)
const useLogScale = ref(false)

const chartData = ref({
  labels: [],
  datasets: []
})

const renderChart = async () => {
  const raw = props.data[selectedRange.value] || []

  // Filter data nếu cần ẩn Giấy A10
  const filtered = hideA10.value
    ? raw.filter(item => item.paper_type !== 'Giấy A10')
    : raw

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

  const canvas = chartCanvas.value
  if (!canvas) return

  const ctx = canvas.getContext('2d')
  chartInstance.value = new Chart(ctx, {
    type: 'bar',
    data: chartData.value,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          type: useLogScale.value ? 'logarithmic' : 'linear',
          beginAtZero: !useLogScale.value,
          title: {
            display: true,
            text: 'Số lượng'
          },
          ticks: {
            callback: function (value) {
              return value.toLocaleString('vi-VN')
            }
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

// Re-render khi thay đổi range, toggle ẩn A10 hoặc log scale
onMounted(renderChart)
watch([selectedRange, hideA10, useLogScale], renderChart)
</script>
