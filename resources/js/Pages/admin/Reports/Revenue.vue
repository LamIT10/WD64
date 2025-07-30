<template>
    <AppLayout>
        <div class="p-4 shadow rounded bg-white m-5 flex justify-between items-center">
            <h5 class="text-lg text-indigo-700 font-semibold">
                <i class="fa-solid fa-chart-line mr-2 text-xl"></i> Báo cáo doanh thu & lợi nhuận
            </h5>
        </div>
        <div class="m-5 p-6 bg-white">
            <!-- Bộ lọc -->
            <form @submit.prevent="fetchRevenue">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Từ ngày</label
                        >
                        <input
                            type="date"
                            v-model="form.start_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Đến ngày</label
                        >
                        <input
                            type="date"
                            v-model="form.end_date"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Tìm kiếm sản phẩm</label
                        >
                        <input
                            type="text"
                            v-model="form.product_search"
                            placeholder="Nhập tên hoặc mã sản phẩm"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        />
                    </div>
                    <div class="mt-6 text-right">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700"
                        >
                            <i
                                class="fa-solid fa-magnifying-glass-plus text-xl mr-2"
                            ></i>
                            <span v-if="!form.processing">Xem báo cáo</span>
                            <span v-else>Đang xử lý...</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tổng doanh thu, vốn, lãi gộp -->
            <div class="flex gap-16 my-6">
                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm mb-2">Tổng doanh thu</span>
                    <span class="text-xl font-bold text-indigo-700">{{ formatCurrency(meta.total_revenue) }}</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm mb-2">Tổng tiền vốn</span>
                    <span class="text-xl font-bold text-amber-700">{{ formatCurrency(meta.total_cost) }}</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-gray-500 text-sm mb-2">Tổng lãi gộp</span>
                    <span class="text-xl font-bold"
                        :class="meta.total_profit > 0 ? 'text-green-700' : meta.total_profit < 0 ? 'text-red-700' : 'text-gray-700'">
                        {{ formatCurrency(meta.total_profit) }}
                    </span>
                </div>
            </div>

            <!-- Bảng kết quả -->
            <div class="mt-8 overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-indigo-100 rounded-lg">
                    <thead class="bg-indigo-50 text-indigo-800 font-medium">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Mã sản phẩm</th>
                            <th class="px-6 py-3">Tên sản phẩm</th>
                            <th class="px-6 py-3">Đơn vị</th>
                            <th class="px-6 py-3 text-end">Doanh số</th>
                            <th class="px-6 py-3 text-end">Tiền vốn</th>
                            <th class="px-6 py-3 bg-green-500 text-white text-end">Lãi gộp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-100">
                        <tr
                            v-for="(item, idx) in revenues"
                            :key="item.variant_id"
                            class="transition-all"
                            :class="{
                                'hover:bg-green-50': item.profit > 0,
                                'hover:bg-red-50': item.profit < 0,
                                'hover:bg-gray-100': item.profit === 0,
                            }"
                        >
                            <td class="px-6 py-3">{{ idx + 1 + ((meta.pagination.current_page - 1) * meta.pagination.per_page) }}</td>
                            <td class="px-6 py-3">{{ item.product_code }}</td>
                            <td class="px-6 py-3">{{ item.variant_name }}</td>
                            <td class="px-6 py-3">{{ item.unit }}</td>
                            <td class="px-6 py-3 font-semibold text-indigo-700 text-end">{{ formatCurrency(item.revenue) }}</td>
                            <td class="px-6 py-3 font-semibold text-amber-700 text-end">{{ formatCurrency(item.cost) }}</td>
                            <td class="px-6 py-3 font-semibold text-end"
                                :class="{
                                    'text-green-700 bg-green-50': item.profit > 0,
                                    'text-red-700 bg-red-50': item.profit < 0,
                                    'text-gray-700 bg-gray-100': item.profit === 0,
                                }"
                            >
                                {{ formatCurrency(item.profit) }}
                            </td>
                        </tr>
                        <tr v-if="revenues.length === 0">
                            <td colspan="7" class="px-6 py-3 text-center text-gray-400">Không có dữ liệu.</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Phân trang -->
                <div class="mt-6 flex justify-end">
                    <button
                        v-for="pageNum in meta.pagination.last_page"
                        :key="pageNum"
                        class="mx-1 px-3 py-1 rounded border"
                        :class="pageNum === meta.pagination.current_page ? 'bg-indigo-600 text-white' : 'bg-white text-indigo-700 border-indigo-200'"
                        @click="goToPage(pageNum)"
                    >
                        {{ pageNum }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";

const page = usePage();
const revenues = ref(page.props.revenues || []);
const meta = ref(page.props.meta || {
    total_revenue: 0,
    total_cost: 0,
    total_profit: 0,
    pagination: { current_page: 1, last_page: 1, per_page: 5, total: 0 }
});

const form = useForm({
    start_date: page.props.filters?.start_date || "",
    end_date: page.props.filters?.end_date || "",
    product_search: page.props.filters?.product_search || "",
    page: meta.value.pagination.current_page || 1,
});

watch(() => page.props, (newProps) => {
    revenues.value = newProps.revenues || [];
    meta.value = newProps.meta || meta.value;
    form.page = meta.value.pagination.current_page || 1;
});

function fetchRevenue() {
    form.page = 1;
    form.get(route("admin.reports.revenue"), {
        preserveState: true,
        preserveScroll: true,
    });
}

function goToPage(pageNum) {
    if (pageNum === meta.value.pagination.current_page) return;
    form.page = pageNum;
    form.get(route("admin.reports.revenue"), {
        preserveState: true,
        preserveScroll: true,
    });
}

function formatCurrency(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<style lang="scss" scoped></style>
