<template>
    <AppLayout>
        <div
            class="p-4 shadow rounded bg-white m-5 flex justify-between items-center"
        >
            <h5 class="text-lg text-indigo-700 font-semibold">
                <i class="fa-solid fa-truck-ramp-box mr-2 text-xl"></i> Xem danh sách sản phẩm gợi ý nhập kho
            </h5>
        </div>
        <div class="m-5 p-6 bg-white">
            <!-- Bộ lọc -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div>
                    <label
                        for="history_weeks"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Thời gian thống kê (tuần)
                    </label>
                    <select
                        v-model="historyWeeks"
                        id="history_weeks"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="4">4 tuần</option>
                        <option value="8">8 tuần</option>
                        <option value="12">12 tuần</option>
                        <option value="custom">Tùy chọn</option>
                    </select>

                    <transition name="fade">
                        <input
                            v-if="historyWeeks === 'custom'"
                            type="number"
                            min="1"
                            max="52"
                            v-model.number="customWeeks"
                            placeholder="Nhập số tuần"
                            class="mt-2 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </transition>
                </div>

                <!-- Số ngày dự trữ -->
                <div>
                    <label
                        for="reserve_days"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Số ngày dự trữ
                    </label>
                    <input
                        type="number"
                        id="reserve_days"
                        min="1"
                        max="30"
                        v-model.number="reserveDays"
                        placeholder="VD: 5"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <p class="text-gray-500 mt-1 ml-1">
                        <i>Số ngày sắp tới bạn cần đảm bảo có đủ hàng để bán</i>
                    </p>
                </div>

                <!-- Tìm kiếm sản phẩm -->
                <div>
                    <label
                        for="product_search"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Tìm kiếm sản phẩm
                    </label>
                    <input
                        type="text"
                        id="product_search"
                        v-model="productSearch"
                        placeholder="Nhập tên hoặc mã sản phẩm"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>
                <!-- Nút hành động -->
                <div class="mt-6 text-right">
                    <button
                        type="button"
                        @click="fetchSuggestions"
                        :disabled="loading"
                        class="inline-flex items-center px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all"
                    >
                        <i
                            class="fa-solid fa-magnifying-glass-plus text-xl mr-2"
                        ></i>
                        <span v-if="!loading">Gợi ý nhập hàng</span>
                        <span v-else>Đang xử lý...</span>
                    </button>
                </div>
            </div>

            <!-- Bảng kết quả gợi ý -->
            <div class="mt-8 overflow-x-auto">
                <table
                    class="min-w-full text-sm text-left border border-indigo-100 rounded-lg"
                >
                    <thead class="bg-indigo-50 text-indigo-800 font-medium">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Mã sản phẩm</th>
                            <th class="px-6 py-3">Tên sản phẩm</th>
                            <th class="px-6 py-3">Tồn kho</th>
                            <th class="px-6 py-3">Bán TB/ngày</th>
                            <th class="px-6 py-3">Dự trữ (ngày)</th>
                            <th class="px-6 py-3">Đơn vị</th>
                            <th class="px-6 py-3 bg-amber-500 text-white">
                                Gợi ý nhập
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-100">
                        <tr
                            v-for="(item, idx) in suggestions"
                            :key="item.variant_id"
                            class="hover:bg-amber-50 transition-all"
                        >
                            <td class="px-6 py-3">{{ idx + 1 }}</td>
                            <td class="px-6 py-3">{{ item.product_code }}</td>
                            <td class="px-6 py-3">{{ item.variant_name }}</td>
                            <td class="px-6 py-3">{{ item.current_stock }}</td>
                            <td class="px-6 py-3">
                                {{ item.avg_sold_per_day }}
                            </td>
                            <td class="px-6 py-3">{{ item.reserve_days }}</td>
                            <td class="px-6 py-3">{{ item.unit }}</td>
                            <td
                                class="px-4 py-3 font-semibold text-lg text-center"
                                :class="
                                    item.suggest_qty > 0
                                        ? 'text-amber-600 bg-amber-50'
                                        : 'text-gray-700 bg-gray-50'
                                "
                            >
                                <span
                                    :class="
                                        item.suggest_qty > 0
                                            ? 'px-5 py-1 bg-amber-500 rounded text-white'
                                            : 'py-1 px-5 bg-gray-300 rounded text-black'
                                    "
                                >
                                    {{ item.suggest_qty }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="suggestions.length === 0">
                            <td
                                colspan="8"
                                class="px-6 py-3 text-center text-gray-400"
                            >
                                Không có dữ liệu gợi ý.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";

const page = usePage();
const suggestions = ref(page.props.suggestions || []);
const loading = ref(false);

const historyWeeks = ref("12");
const customWeeks = ref(12);
const reserveDays = ref(5);
const productSearch = ref("");

const form = useForm({
    history_weeks: historyWeeks.value,
    reserve_days: reserveDays.value,
    product_search: productSearch.value,
});

function fetchSuggestions() {
    loading.value = true;
    form.history_weeks =
        historyWeeks.value === "custom"
            ? customWeeks.value
            : Number(historyWeeks.value);
    form.reserve_days = reserveDays.value;
    form.product_search = productSearch.value;

    form.get(route("admin.reports.suggest"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            suggestions.value = page.props.suggestions || [];
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        },
    });
}
</script>

<style scoped></style>
