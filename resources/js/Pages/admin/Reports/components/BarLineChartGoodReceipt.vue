<template>
    <div class="w-full">
        <select v-model="filterYear.year" @change="handleFilterByYear"
            class="px-4 py-2 text-sm border border-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-700 shadow-sm">
            <option v-for="year in pluck_year" :key="year" :value="year">
                {{ year }}
            </option>
        </select>

        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>

<script setup>
import { Bar } from 'vue-chartjs'
const year = purchase_in_month_in_year.year;
const { receipt_count_in_month_in_year, purchase_in_month_in_year, pluck_year } = defineProps({
    receipt_count_in_month_in_year: {
        type: Object,
        default: {}
    },
    purchase_in_month_in_year: {
        type: Object,
        default: {}
    },
    pluck_year: {
        type: Array,
        default: []
    }
})
const filterYear = reactive({
    year: year
})
const emit = defineEmits(['filterByYear']);
const handleFilterByYear = () => {
    emit('filterByYear', filterYear);
}
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
import { reactive } from 'vue';

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

// Fake data 12 tháng
const chartData = {
    labels: [
        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4',
        'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
        'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ],
    datasets: [
        {
            type: 'bar',
            label: 'Đơn nhập / tháng',
            data: [
                purchase_in_month_in_year.data[0].count,
                purchase_in_month_in_year.data[1].count,
                purchase_in_month_in_year.data[2].count,
                purchase_in_month_in_year.data[3].count,
                purchase_in_month_in_year.data[4].count,
                purchase_in_month_in_year.data[5].count,
                purchase_in_month_in_year.data[6].count,
                purchase_in_month_in_year.data[7].count,
                purchase_in_month_in_year.data[8].count,
                purchase_in_month_in_year.data[9].count,
                purchase_in_month_in_year.data[10].count,
                purchase_in_month_in_year.data[11].count,
            ],
            backgroundColor: '#6366f1',
            yAxisID: 'y1',
            order: 2,
        },
        {
            type: 'line',
            label: 'Tổng giá trị nhập',
            data: [
                receipt_count_in_month_in_year.data[0].total,
                receipt_count_in_month_in_year.data[1].total,
                receipt_count_in_month_in_year.data[2].total,
                receipt_count_in_month_in_year.data[3].total,
                receipt_count_in_month_in_year.data[4].total,
                receipt_count_in_month_in_year.data[5].total,
                receipt_count_in_month_in_year.data[6].total,
                receipt_count_in_month_in_year.data[7].total,
                receipt_count_in_month_in_year.data[8].total,
                receipt_count_in_month_in_year.data[9].total,
                receipt_count_in_month_in_year.data[10].total,
                receipt_count_in_month_in_year.data[11].total,
            ],
            borderColor: '#f97316',
            borderWidth: 2,
            tension: 0.3,
            fill: false,
            pointBackgroundColor: '#f97316',
            yAxisID: 'y2',
            order: 1,

        }
    ]
}

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                font: {
                    size: 12
                }
            }
        },
        title: {
            display: true,
            text: 'Biểu đồ nhập hàng 12 tháng năm ' + purchase_in_month_in_year.year,
            font: {
                size: 16
            }
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
                precision: 0 // giữ số nguyên
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
                drawOnChartArea: false // không vẽ grid của y2 đè lên y1
            },
            ticks: {
                callback: (value) => value.toLocaleString() + '₫'
            }
        }
    }
}
</script>

<style scoped>
div {
    height: 400px;
}
</style>