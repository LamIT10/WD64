<template>
    <AppLayout>
        <div class="p-6 bg-gray-100 min-h-screen">
            <!-- Header -->
            <div class="bg-white shadow rounded p-4 mb-6 flex justify-between items-center">
            <h5 class="text-xl text-indigo-700 font-semibold">
                Danh sách phiếu nhập kho
            </h5>
            <div class="ml-auto">
                <a
                :href="route('admin.receiving.export')"
                class="inline-block px-4 py-2 text-sm font-semibold bg-green-600 text-white rounded hover:bg-green-700 transition"
                >
                <i class="fa-solid fa-file-excel mr-2"></i> Xuất Excel
                </a>
            </div>
            </div>
            <!-- Filter Section -->
            <form
                class="bg-white shadow rounded p-6 mb-6 flex flex-wrap gap-6 items-end"
                @submit.prevent="submitFilter"
            >
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mã phiếu nhập</label>
                    <input
                        v-model="form.code"
                        type="text"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="Nhập mã phiếu"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mã đơn nhập</label>
                    <input
                        v-model="form.purchase_order_code"
                        type="text"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="Nhập mã đơn nhập"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input
                        v-model="form.date_from"
                        type="date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input
                        v-model="form.date_to"
                        type="date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition font-medium"
                    >
                        Lọc
                    </button>
                    <button
                        type="button"
                        @click="resetFilter"
                        class="border border-gray-300 text-gray-700 px-3 py-2 rounded hover:bg-gray-100 transition font-medium"
                    >
                        <i class="fa-solid text-xl fa-rotate-left"></i>
                    </button>
                </div>
            </form>

            <!-- Table Section -->
            <div class="bg-white shadow rounded overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-500">
                        <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input
                                            id="checkbox-all-search"
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                        />
                                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-2">Mã phiếu nhập</th>
                                <th scope="col" class="px-4 py-2">Mã đơn nhập</th>
                                <th scope="col" class="px-4 py-2">Nhà cung cấp</th>
                                <th scope="col" class="px-4 py-2">Ngày nhận hàng</th>
                                <th scope="col" class="px-4 py-2">Người tạo phiếu</th>
                                <th scope="col" class="px-4 py-2 text-end">Đã thanh toán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="goodReceipt in listGoodReceipts.data"
                                :key="goodReceipt.id"
                                @click="openModal(goodReceipt)"
                                class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
                            >
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input
                                            :id="'checkbox-table-search-' + goodReceipt.id"
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                        />
                                        <label
                                            :for="'checkbox-table-search-' + goodReceipt.id"
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </td>
                                <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ goodReceipt.code }}
                                </th>
                                <td class="px-4 py-2">
                                    {{ goodReceipt.purchase_order?.code || '' }}
                                </td>
                                <td class="px-4 py-2 text-indigo-700 font-semibold">
                                    {{ goodReceipt.purchase_order?.supplier?.name || '' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ formatDate(goodReceipt.receipt_date) }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ goodReceipt.create_by?.name || '' }}
                                </td>
                                <td class="px-4 py-2 text-blue-800 font-semibold flex items-center justify-end">
                                    {{ formatCurrencyVND(goodReceipt.total_amount) }}
                                    <i class="fa-solid fa-tag text-lg ml-2"></i>
                                </td>
                                <td>
                                    <button @click="printPhieu()">In phiếu</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-end" v-if="listGoodReceipts.links">
                <nav class="inline-flex rounded-md shadow-sm gap-1" aria-label="Pagination">
                    <button
                        v-for="(link, i) in listGoodReceipts.links"
                        :key="i"
                        :disabled="!link.url"
                        @click="goToPage(link.url)"
                        v-html="link.label"
                        class="px-4 py-2 text-sm rounded-md transition"
                        :class="{
                            'bg-indigo-600 text-white': link.active,
                            'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100': !link.active,
                            'opacity-50 cursor-not-allowed': !link.url,
                        }"
                    ></button>
                </nav>
            </div>

            <!-- Modal -->
            <div v-if="isModalOpen" class="fixed inset-0 overflow-y-auto z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>
                    <div class="relative bg-white rounded shadow-xl max-w-4xl w-full">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-gray-900">
                                    Phiếu nhập {{ selectedGoodReceipt.code }}
                                </h3>
                                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <table class="w-full border border-gray-200 rounded text-sm">
                                <tbody>
                                    <tr class="border-b border-gray-200">
                                        <td class="bg-gray-50 font-medium text-gray-700 px-4 py-3 w-1/3">
                                            🏢 Nhà cung cấp
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ selectedGoodReceipt.purchase_order.supplier.name }}
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="bg-gray-50 font-medium text-gray-700 px-4 py-3 w-1/3">
                                            Ngày nhận hàng
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ formatDate(selectedGoodReceipt.receipt_date) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-3">Chi tiết phiếu nhập</h4>
                                <div class="border border-gray-200 rounded overflow-hidden">
                                    <table class="w-full">
                                        <thead class="bg-indigo-500 text-white">
                                            <tr>
                                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                    Sản phẩm
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                    Số lượng
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                    Đơn vị
                                                </th>
                                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">
                                                    Thành tiền
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="item in selectedGoodReceipt.items" :key="item.id">
                                                <td class="px-6 py-3 text-sm text-gray-600">
                                                    {{ item.product_variant.product.name }} -
                                                    <span
                                                        v-for="(attribute, index) in item.product_variant.attributes"
                                                        :key="index"
                                                    >
                                                        {{ attribute.name }} {{ attribute.value }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-3 text-center text-sm text-gray-600">
                                                    {{ item.quantity_received }}
                                                </td>
                                                <td class="px-6 py-3 text-center text-sm text-gray-600">
                                                    {{ item.unit.name }}
                                                </td>
                                                <td class="px-6 py-3 text-right text-sm text-gray-600">
                                                    {{ formatCurrencyVND(item.subtotal) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <div class="bg-gray-50 p-4 rounded w-64">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 font-medium">Tổng giá trị phiếu nhập:</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ formatCurrencyVND(selectedGoodReceipt.total_amount) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    listGoodReceipts: Object,
    filters: Object,
});

const form = useForm({
    code: props.filters?.code || "",
    purchase_order_code: props.filters?.purchase_order_code || "",
    date_from: props.filters?.date_from || "",
    date_to: props.filters?.date_to || "",
});

const isModalOpen = ref(false);
const selectedGoodReceipt = ref({ items: [] });

function openModal(goodReceipt) {
    isModalOpen.value = true;
    selectedGoodReceipt.value = goodReceipt;
}
function closeModal() {
    isModalOpen.value = false;
}
function submitFilter() {
    form.get(route("admin.receiving.index"), {
        preserveState: true,
        preserveScroll: true,
    });
}
function resetFilter() {
    form.code = "";
    form.purchase_order_code = "";
    form.date_from = "";
    form.date_to = "";
    submitFilter();
}
function goToPage(url) {
    form.get(url, {
        preserveState: true,
        preserveScroll: true,
    });
}
function formatCurrencyVND(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
const printPhieu = () =>{
    window.print();
}
</script>
<style lang="css" scoped>
/* Modal overlay */
.fixed.inset-0 {
    background-color: rgba(0, 0, 0, 0.125);
}

/* Smooth transitions for hover effects */
button, input, .hover\:bg-gray-50:hover, .hover\:bg-gray-100:hover {
    transition: background-color 0.2s ease, color 0.2s ease;
}

/* Ensure table cells have proper spacing */
table {
    border-collapse: collapse;
}

/* Enhance modal table styling */
table tbody tr td {
    vertical-align: middle;
}

/* Add subtle shadow to buttons */
button {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style>