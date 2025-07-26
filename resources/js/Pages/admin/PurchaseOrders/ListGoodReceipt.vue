<template>
    <AppLayout>
        <div class="p-6">
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách phiếu nhập kho
                </h5>
                <div class="flex items-center space-x-3">
                    <form class="relative">
                        <button type="submit" class="">Tìm kiếm</button>
                        <input
                            type="text"
                            name="search"
                            placeholder="Tìm kiếm phiếu nhập kho..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        />
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="relative overflow-x-auto shadow-md">
                        <table
                            class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400 overflow-visible"
                        >
                            <thead
                                class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-all-search"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                            />
                                            <label
                                                for="checkbox-all-search"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Mã phiếu nhập
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Mã đơn nhập
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Nhà cung cấp
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Ngày nhận hàng
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Người tạo phiếu
                                    </th>
                                    <th scope="col" class="px-4 py-2 text-end">
                                        Đã thanh toán
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="goodReceipt in listGoodReceipts"
                                    :key="goodReceipt.id"
                                    @click="openModal(goodReceipt)"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-table-search-1"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-table-search-1"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </td>
                                    <th
                                        scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ goodReceipt.code }}
                                    </th>
                                    <td class="px-4 py-2">
                                        {{ goodReceipt.purchase_order.code }}
                                    </td>
                                    <td
                                        class="px-4 py-2 text-indigo-600 font-semibold"
                                    >
                                        {{
                                            goodReceipt.purchase_order.supplier
                                                .name
                                        }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ formatDate(goodReceipt.receipt_date) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ goodReceipt.create_by.name }}
                                    </td>
                                    <td
                                        class="px-4 py-2 text-blue-800 font-semibold flex items-center justify-end"
                                    >
                                        {{
                                            formatCurrencyVND(
                                                goodReceipt.total_amount
                                            )
                                        }}
                                        <i
                                            class="fa-solid fa-tag text-lg ml-2"
                                        ></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-if="isModalOpen" class="fixed inset-0 overflow-y-auto z-50">
                <div
                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <div
                        class="fixed inset-0 z-40 transition-opacity"
                        aria-hidden="true"
                    >
                        <div
                            class="absolute inset-0 hihi"
                            @click="closeModal"
                        ></div>
                    </div>

                    <div
                        class="inline-block relative z-50 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full"
                    >
                        <div class="bg-white px-4 p-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                >
                                    <div
                                        class="flex justify-between p-3 items-center"
                                    >
                                        <h3
                                            class="text-lg leading-6 font-medium text-gray-900"
                                        >
                                            Phiếu nhập
                                            {{ selectedGoodReceipt.code }}
                                        </h3>
                                        <button
                                            @click="closeModal"
                                            class="text-gray-400 hover:text-gray-500"
                                        >
                                            <span class="sr-only">Close</span>
                                            <svg
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                    <table
                                        class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm"
                                    >
                                        <tbody>
                                            <!-- Nhà cung cấp -->
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                >
                                                    🏢 Nhà cung cấp
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-gray-900"
                                                >
                                                    {{
                                                        selectedGoodReceipt
                                                            .purchase_order
                                                            .supplier.name
                                                    }}
                                                </td>
                                            </tr>
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                >
                                                    Ngày nhận hàng
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-gray-900"
                                                >
                                                    {{
                                                        formatDate(selectedGoodReceipt.receipt_date)
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-6">
                                        <h4
                                            class="font-medium text-gray-900 mb-2"
                                        >
                                            Chi tiết phiếu nhập
                                        </h4>
                                        <div
                                            class="border border-gray-200 overflow-hidden"
                                        >
                                            <table
                                                class="min-w-full divide-y divide-gray-200"
                                            >
                                                <thead
                                                    class="bg-indigo-400 text-white"
                                                >
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-center border border-white text-xs font-medium uppercase tracking-wider"
                                                        >
                                                            Sản phẩm
                                                        </th>
                                                        <th
                                                            class="px-6 py-3 text-center border border-white text-xs font-medium uppercase tracking-wider"
                                                        >
                                                            Số lượng
                                                        </th>
                                                        <th
                                                            class="px-6 py-3 text-center border border-white text-xs font-medium uppercase tracking-wider"
                                                        >
                                                            Đơn vị
                                                        </th>
                                                        <th
                                                            class="px-6 py-3 text-center border border-white text-xs font-medium uppercase tracking-wider"
                                                        >
                                                            Thành tiền
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white divide-y divide-gray-200"
                                                >
                                                    <tr
                                                        v-for="item in selectedGoodReceipt.items"
                                                        :key="item.id"
                                                    >
                                                        <td
                                                            class="px-3 py-2 whitespace-nowrap text-sm text-gray-500"
                                                        >
                                                            {{
                                                                item
                                                                    .product_variant
                                                                    .product
                                                                    .name
                                                            }}
                                                            -
                                                            <span
                                                                v-for="(
                                                                    attribute,
                                                                    index
                                                                ) in item
                                                                    .product_variant
                                                                    .attributes"
                                                                :key="index"
                                                            >
                                                                {{
                                                                    attribute.name
                                                                }}
                                                                {{
                                                                    attribute.value
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-500"
                                                        >
                                                            {{
                                                                item.quantity_received
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-500"
                                                        >
                                                            {{ item.unit.name }}
                                                        </td>
                                                        <td
                                                            class="px-3 py-2 text-right whitespace-nowrap text-sm text-gray-500"
                                                        >
                                                            {{
                                                                formatCurrencyVND(
                                                                    item.subtotal
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <div
                                            class="bg-gray-50 p-4 rounded-lg w-64"
                                        >
                                            <div
                                                class="flex justify-between mb-2"
                                            >
                                                <span class="text-gray-600"
                                                    >Tổng giá trị phiếu
                                                    nhập:</span
                                                >
                                                <span class="font-medium">{{
                                                    formatCurrencyVND(
                                                        selectedGoodReceipt.total_amount
                                                    )
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const { listGoodReceipts } = defineProps({
    listGoodReceipts: {
        type: Array,
        default: () => [],
    },
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
</script>
<style lang="css" scoped>
.hihi {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
