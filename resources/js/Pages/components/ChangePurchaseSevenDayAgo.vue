<template>
    <div class="chart-container">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup>
import { Chart, registerables } from 'chart.js';
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';

// Đăng ký các thành phần Chart.js
Chart.register(...registerables);

// Định nghĩa props
const props = defineProps({
    showRefreshButton: {
        type: Boolean,
        default: true,
    },
    chartTitle: {
        type: String,
        default: 'Biểu đồ thay đổi trong 7 ngày',
    },
    lineColor: {
        type: String,
        default: 'rgb(75, 192, 192)',
    },
    purchaseChangeInSevenDay: {
        type: Object,
        default: () => {},
    },
});

// Khai báo refs
const chartInstance = ref(null);
const chartCanvas = ref(null);
const chartData = ref([]);

// Lấy 7 ngày gần nhất (từ 14/06/2025 đến 20/06/2025)
const getLast7Days = () => {
    const days = [];
    const currentDate = new Date(); 
    const timestamp = currentDate. getTime()
    for (let i = 6; i >= 0; i--) {
        const date = new Date(timestamp);
        date.setDate(date.getDate() - i);
        days.push(date.toISOString().split('T')[0]); // Định dạng YYYY-MM-DD
    }
    return days;
};

// Xử lý dữ liệu từ props
const processChartData = () => {
    const days = getLast7Days();
    // Khởi tạo mảng total_orders với giá trị 0 cho tất cả 7 ngày
    const totalOrders = days.map(() => 0);

    // Ánh xạ dữ liệu từ props vào các ngày tương ứng
    props.purchaseChangeInSevenDay.forEach((item) => {
        const index = days.indexOf(item.date);
        if (index !== -1) {
            totalOrders[index] = item.total_orders;
        }
    });

    chartData.value = totalOrders;
};

// Render biểu đồ
const renderChart = () => {
    if (chartInstance.value) {
        chartInstance.value.destroy();
    }

    const ctx = chartCanvas.value.getContext('2d');
    chartInstance.value = new Chart(ctx, {
        type: 'line',
        data: {
            labels: getLast7Days().map((date) =>
                new Date(date).toLocaleDateString('vi-VN')
            ),
            datasets: [
                {
                    label: 'Số lượng đơn hàng',
                    data: chartData.value,
                    fill: false,
                    borderColor: props.lineColor,
                    backgroundColor: props.lineColor,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: props.lineColor,
                    pointHoverRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: props.chartTitle,
                    font: {
                        size: 16,
                    },
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                    },
                    title: {
                        display: true,
                        text: 'Số lượng đơn hàng',
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Ngày',
                    },
                },
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false,
            },
        },
    });
};



// Khởi tạo khi mounted
onMounted(() => {
    processChartData();
    renderChart();
});

// Theo dõi thay đổi props.purchaseChangeInSevenDay
watch(
    () => props.purchaseChangeInSevenDay,
    () => {
        processChartData();
        renderChart();
    },
    { deep: true }
);

// Hủy biểu đồ trước khi unmount
onBeforeUnmount(() => {
    if (chartInstance.value) {
        chartInstance.value.destroy();
    }
});
</script>

<style scoped>
.chart-container {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    height: 400px;
}

.refresh-btn {
    position: absolute;
    right: 10px;
    top: 10px;
    padding: 5px 10px;
    background-color: #4bc0c0;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    z-index: 10;
}

.refresh-btn:hover {
    background-color: #3aa8a8;
}
</style>