<template>
  <div class="w-full mb-6">
    <div class="mb-4 flex justify-between items-center">
      <h2 class="text-xl font-semibold">{{ title }}</h2>
      <select v-model="selectedPeriod" class="border p-2 rounded">
        <option value="week">Tuần này</option>
        <option value="month">Tháng này</option>
        <option value="year">Năm này</option>
      </select>
    </div>

    <Bar :data="chartData" :options="chartOptions" class="max-h-[400px]" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const props = defineProps({
  title: String,
  rawData: Object, // { week: [...], month: [...], year: [...] }
  type: String // 'product' hoặc 'customer'
})

const selectedPeriod = ref('month')

const chartData = computed(() => {
  const source = props.rawData?.[selectedPeriod.value] || []

  const labels = source.map(item =>
    props.type === 'customer' ? item.customer_name : item.full_variant_name
  )
  const data = source.map(item =>
    props.type === 'customer' ? Number(item.total_spent || 0) : Number(item.total_quantity || 0)
  )

  return {
    labels,
    datasets: [
      {
        label: props.type === 'customer' ? 'Tổng chi tiêu (VNĐ)' : 'Số lượng',
        data,
        backgroundColor: props.type === 'customer' ? '#10b981' : '#3b82f6'
      }
    ]
  }
})

const chartOptions = {
  indexAxis: 'y',
  responsive: true,
  plugins: {
    legend: { display: false },
    tooltip: {
  callbacks: {
    label: (ctx) => {
      const item = props.rawData?.[selectedPeriod.value]?.[ctx.dataIndex]
      if (!item) return ctx.raw
      if (props.type === 'customer') {
        return `${ctx.label}: ₫${(ctx.raw / 1_000_000).toFixed(1)}M`
      } else {
        return `${ctx.label}: ${ctx.raw} ${item.base_unit_name || ''}`
      }
    }
  }
}

  },
  scales: {
    x: {
      beginAtZero: true,
      ticks: { precision: 0 }
    }
  }
}
</script>
