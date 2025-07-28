<template>
    <div class="w-full max-w-screen-xl mx-auto px-4 space-y-10 mt-20 mb-20">
        <!-- Top 5 nhà cung cấp -->
        <div class="h-[400px]">
            <h3 v-if="filter.start_date != '' || filter.end_date != ''" class="text-base font-semibold mb-4">
                Top 5 nhà cung cấp có giá trị nhập cao nhất từ ngày {{ formatDateForDisplay(filter.start_date) }} đến
                ngày {{
                    formatDateForDisplay(filter.end_date) }}
            </h3>

            <h3 v-else class="text-base font-semibold mb-4">Top 5 nhà cung cấp có giá trị nhập cao nhất</h3>
            <Bar :data="supplierChartData" :options="supplierChartOptions" />
        </div>

        <!-- Top 10 sản phẩm -->
        <div class="h-[500px] mt-20">
            <h3 v-if="filter.start_date != '' || filter.end_date != ''" class="text-base font-semibold mb-4">
                Top 10 sản phẩm được nhập nhiều nhất từ ngày {{ formatDateForDisplay(filter.start_date) }} đến
                ngày {{
                    formatDateForDisplay(filter.end_date) }}
            </h3>
            <h3 v-else class="text-base font-semibold mb-4">Top 10 sản phẩm được nhập nhiều nhất</h3>
            <Bar :data="productChartData" :options="productChartOptions" />
        </div>
    </div>
</template>

<script setup>
import { Bar } from 'vue-chartjs'
import { defineProps } from 'vue'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    BarElement
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement)

const { top5_supplier, top_10_product, filter } = defineProps({
    top5_supplier: { type: Object, default: {} },
    top_10_product: { type: Object, default: {} },
    filter: { type: Object, default: {} }
})

// Biểu đồ nhà cung cấp (đơn vị: VNĐ)
const supplierChartData = {
    labels: top5_supplier.map(i => i?.supplier_name || ''),
    datasets: [{
        label: 'Giá trị nhập (VNĐ)',
        data: top5_supplier.map(i => i?.total_paid || 0),
        backgroundColor: '#3b82f6'
    }]
}
const formatDateForDisplay = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
};
const supplierChartOptions = {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                label: (context) => {
                    return context.raw.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })
                }
            }
        }
    },
    scales: {
        x: {
            beginAtZero: true,
            ticks: {
                callback: (value) => value.toLocaleString('vi-VN')
            }
        }
    }
}

// Biểu đồ sản phẩm (đơn vị: sản phẩm)
const productChartData = {
    labels: top_10_product.map(i => i?.product_variant_name || ''),
    datasets: [{
        label: 'Số lượng nhập',
        data: top_10_product.map(i => i?.total_quantity || 0),
        backgroundColor: '#f97316'
    }]
}

const productChartOptions = {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                label: (context) => context.raw + ' sản phẩm'
            }
        }
    },
    scales: {
        x: {
            beginAtZero: true,
            ticks: {
                callback: (value) => value.toLocaleString('vi-VN')
            }
        }
    }
}
</script>