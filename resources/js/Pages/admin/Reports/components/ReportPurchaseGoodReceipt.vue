<template>

    <!-- Tiêu đề -->
    <h2 v-if="!filters.sub_from_date || !filter.sub_to_date  " class="text-xl font-semibold mb-4">
        Thống kê đơn nhập trong tháng {{ month }}
    </h2>

    <!-- Bộ lọc ngày -->
    <div class="flex items-center space-x-6">
        <!-- Từ ngày -->
        <div class="flex items-center space-x-2">
            <label for="start-date" class="text-xs text-indigo-600">Từ ngày</label>
            <input type="text" id="start-date"
                class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                v-model="filters.sub_from_date" v-date-picker @change="handleFilterPurchase" />
        </div>

        <!-- Đến ngày -->
        <div class="flex items-center space-x-2">
            <label for="end-date" class="text-xs text-indigo-600">Đến ngày</label>
            <input type="text" id="end-date"
                class="px-2 py-1 text-xs border border-indigo-200 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-400 text-indigo-700 date-picker"
                v-model="filters.sub_to_date" v-date-picker @change="handleFilterPurchase" />
        </div>
    </div>

    <div class="w-full flex justify-center">
        <canvas ref="pieChart" style="max-width: 500px; width: 500px; max-height: 500px; height: 500px;"></canvas>
    </div>

</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import Chart from 'chart.js/auto';
const formatDateForSubmit = (dateString) => {
  if (!dateString) return '';
  const [day, month, year] = dateString.split('/');
  return `${year}-${month}-${day}`;
};
const formatDateForDisplay = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return '';
  return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
};
const { purchase_order, filter } = defineProps({
    purchase_order: {
        type: Object,
        default: () => ({}),
    },
    filter : {
        type: Object,
        default: () => ({}),
    },
});
const filters = reactive({
    sub_from_date: formatDateForDisplay(filter.start_date) || '',
    sub_to_date: formatDateForDisplay(filter.end_date) || '',
});
const filterDateEmit = reactive({
    start_date: formatDateForSubmit(filters.sub_from_date),
    end_date: formatDateForSubmit(filters.sub_to_date),
});
const emit = defineEmits(['filterPurchase']);

const handleFilterPurchase = () => {
    filterDateEmit.start_date = formatDateForSubmit(filters.sub_from_date);
    filterDateEmit.end_date = formatDateForSubmit(filters.sub_to_date);
    emit('filterPurchase', filterDateEmit);
}
const date = new Date();
const month = date.getMonth() + 1;
// Dữ liệu mẫu cho biểu đồ
const chartData = ref({
    labels: ['Chờ duyệt', 'Đã duyệt', 'Nhập một phần', 'Đã hoàn thành', 'Từ chối'],
    datasets: [{
        data: [purchase_order.count_pending_purchase, purchase_order.count_accepted_purchase, purchase_order.count_parital_import_purchase, purchase_order.count_completed_purchase, purchase_order.count_closed_purchase],
        backgroundColor: [
            '#FFA500',
            '#007BFF',
            '#FFC107',
            '#28A745',
            '#ee3a1f',
        ],
        hoverBackgroundColor: [
            '#FFA500',
            '#007BFF',
            '#FFC107',
            '#28A745',
            '#ee3a1f',
        ]
    }]
});

const pieChart = ref(null);
let chartInstance = null;

// Hàm khởi tạo biểu đồ
const initChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }

    const ctx = pieChart.value.getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'pie',
        data: chartData.value,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
};

// Khởi tạo biểu đồ khi component được mount
onMounted(() => {
    initChart();
});

</script>

<style lang="css" scoped>
/* Thêm style nếu cần */
input[type="range"] {
    -webkit-appearance: none;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
}
</style>