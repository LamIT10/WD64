<template>
    <AppLayout>
       
        <div class="bg-white rounded-lg shadow p-6 w-full mx-auto m-2 pb-[80px]">
            <BarLineChartGoodReceipt :pluck_year="data.pluck_year"
                :receipt_count_in_month_in_year="data.receipt_count_in_month_in_year"
                :purchase_in_month_in_year="data.purchase_in_month_in_year" :filter="searchForm"
                @filterByYear="hanldeFilterPurchase" />
            <ReportPurchaseGoodReceipt :purchase_order="data.purchase_orders" :filter="searchForm"
                @filterPurchase="hanldeFilterPurchase" :total_purchase_in_month="data.total_purchase_in_month"
                :total_good_receipt_in_month="data.total_good_receipt_in_month" />
            <SupplierAndProductChart :top5_supplier="data.top_5_suppliers" :top_10_product="data.top_10_product"
                :filter="searchForm" />

        </div>
    </AppLayout>
</template>

<script setup>
import { reactive } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import BarLineChartGoodReceipt from './components/BarLineChartGoodReceipt.vue';
import SupplierAndProductChart from './components/SupplierAndProductChart.vue';
import ReportPurchaseGoodReceipt from './components/ReportPurchaseGoodReceipt.vue';
import { useForm } from '@inertiajs/vue3';
const { data } = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});
const url = new URL(window.location.href);
// Lấy tất cả tham số từ query string
const params = new URLSearchParams(url.search);
const queryData = reactive({
    start_date: "",
    end_date: "",
    year: "",
});
params.forEach((value, key) => {
    if (key == "perPage") {
        formPerpage.perPage = value;

    } else {
        queryData[key] = value;
    }
});

const searchForm = useForm({
    start_date: queryData.start_date,
    end_date: queryData.end_date,
    year: queryData.year,
});
const hanldeFilterPurchase = (params) => {
    console.log(params);
    searchForm.start_date = params.start_date ?? queryData.start_date;
    searchForm.end_date = params.end_date ?? queryData.end_date;
    searchForm.year = params.year ?? queryData.year;
    searchForm.get(route('admin.reports.index'));
}
</script>

<style scoped></style>