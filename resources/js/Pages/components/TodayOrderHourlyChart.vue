<template>
  <div class="chart-container">
    <h3 class="chart-title">{{ title }}</h3>
    <canvas ref="chartRef"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref, watch, onBeforeUnmount } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  title: {
    type: String,
    default: 'Thống kê đơn theo giờ',
  },
  data: {
    type: Array,
    default: () => [],
  },
  color: {
    type: String,
    default: '#6366f1', // Mặc định màu indigo
  },
});

const chartRef = ref(null);
let chartInstance = null;

const drawChart = () => {
  if (chartInstance) {
    chartInstance.destroy();
  }

  const labels = Array.from({ length: 24 }, (_, i) => `${i}:00`);
  const mapped = Object.fromEntries(props.data.map(d => [d.hour, d.total_orders]));
  const dataset = labels.map((_, hour) => mapped[hour] || 0);

  const ctx = chartRef.value.getContext('2d');

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: 'Số đơn',
          data: dataset,
          backgroundColor: props.color,
          borderRadius: 6,
          barThickness: 14,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          mode: 'index',
          intersect: false,
        },
        title: {
          display: false,
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
          },
          title: {
            display: true,
            text: 'Số đơn',
          },
        },
        x: {
          title: {
            display: true,
            text: 'Giờ trong ngày',
          },
        },
      },
    },
  });
};

onMounted(drawChart);
watch(() => props.data, drawChart, { deep: true });

onBeforeUnmount(() => {
  if (chartInstance) {
    chartInstance.destroy();
  }
});
</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 320px;
  position: relative;
  background-color: #fff;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
}

.chart-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 1rem;
}
</style>
