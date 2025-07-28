<template>

    <!-- Tiêu đề -->
    <div class="me-30 ms-30">


        <!-- Bộ lọc ngày -->
        <div class="flex flex-wrap items-center gap-4 mb-8 px-6">
            <!-- Từ ngày -->
            <div class="flex items-center space-x-2">
                <label for="start-date" class="text-sm text-gray-700 font-medium">Từ ngày</label>
                <input type="text" id="start-date"
                    class="px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-800 date-picker"
                    v-model="filters.sub_from_date" v-date-picker @change="handleFilterPurchase" />
            </div>

            <!-- Đến ngày -->
            <div class="flex items-center space-x-2">
                <label for="end-date" class="text-sm text-gray-700 font-medium">Đến ngày</label>
                <input type="text" id="end-date"
                    class="px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-800 date-picker"
                    v-model="filters.sub_to_date" v-date-picker @change="handleFilterPurchase" />
            </div>

            <!-- Reset Button -->
            <button
                class="flex items-center gap-1 px-3 py-1.5 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-all"
                @click="resetDateFilter">
                <i class="fas fa-rotate-left"></i>
                <span>Đặt lại</span>
            </button>
        </div>
        <div class="m-5 mb-20 mt-10 border border-gray-200 rounded-lg p-6 bg-white shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">

                <!-- Tổng đơn đặt hàng -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-indigo-600 text-2xl mb-2">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <p class="text-sm text-gray-600">Tổng đơn đặt hàng</p>
                    <p class="text-xl font-bold text-indigo-700">{{ total_purchase_in_month.total_purchase }}</p>
                </div>

                <!-- Tổng phiếu nhập -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-blue-600 text-2xl mb-2">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <p class="text-sm text-gray-600">Tổng phiếu nhập theo đơn</p>
                    <p class="text-xl font-bold text-blue-700">{{ total_good_receipt_in_month.total_receipt }}</p>
                </div>

                <!-- Tỷ lệ chuyển đổi -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-purple-600 text-2xl mb-2">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <p class="text-sm text-gray-600">Tỷ lệ chuyển đổi</p>
                    <p class="text-xl font-bold text-purple-700">
                        {{ConvertRate() }}%</p>
                </div>

                <!-- Giá trị đặt hàng -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-green-600 text-2xl mb-2">
                        <i class="fas fa-cart-arrow-down"></i>
                    </div>
                    <p class="text-sm text-gray-600">Giá trị đặt hàng</p>
                    <p class="text-xl font-bold text-green-700">{{ formatNumber(total_purchase_in_month.total_amount) }}
                        ₫</p>
                </div>

                <!-- Giá trị nhập kho -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-emerald-600 text-2xl mb-2">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <p class="text-sm text-gray-600">Giá trị nhập kho</p>
                    <p class="text-xl font-bold text-emerald-700">{{
                        formatNumber(total_good_receipt_in_month.total_amount) }} ₫</p>
                </div>

                <!-- Chênh lệch -->
                <div class="flex flex-col items-center text-center">
                    <div class="text-red-600 text-2xl mb-2">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <p class="text-sm text-gray-600">Chênh lệch</p>
                    <p class="text-xl font-bold text-red-700">{{ formatNumber(total_purchase_in_month.total_amount -
                        total_good_receipt_in_month.total_amount) }} ₫</p>
                </div>

            </div>
        </div>


        <h2 v-if="filter.start_date != '' || filter.end_date != ''" class="text-xl font-semibold mb-4">
            Thống kê đơn nhập từ ngày {{ formatDateForDisplay(filter.start_date) }} đến ngày {{
                formatDateForDisplay(filter.end_date) }}
        </h2>
        <h2 v-else class="text-xl font-semibold mb-4">
            Thống kê đơn nhập trong tháng {{ month }}
        </h2>
        <div class="w-full flex justify-center">
            <canvas ref="pieChart" style="max-width: 500px; width: 500px; max-height: 500px; height: 500px;"></canvas>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import Chart from 'chart.js/auto';
import { formatNumber } from "chart.js/helpers";

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
const { purchase_order, filter, total_purchase_in_month, total_good_receipt_in_month } = defineProps({
    purchase_order: {
        type: Object,
        default: () => ({}),
    },
    filter: {
        type: Object,
        default: () => ({}),
    },
    total_purchase_in_month: {
        type: Object,
        default: () => ({}),
    },
    total_good_receipt_in_month: {
        type: Object,
        default: () => ({}),
    }
});
const ConvertRate = () => {
    const value = formatNumber(((total_good_receipt_in_month.total_receipt != null ?
        total_good_receipt_in_month.total_receipt : 0) / (total_purchase_in_month.total_purchase !=
            null ? total_purchase_in_month.total_purchase : 0) * 100));

    if(isNaN(value)) return 0;
    return value;
}
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
const resetDateFilter = () => {
    filterDateEmit.start_date = "";
    filterDateEmit.end_date = "";
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
console.log(total_purchase_in_month,
    total_good_receipt_in_month);
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