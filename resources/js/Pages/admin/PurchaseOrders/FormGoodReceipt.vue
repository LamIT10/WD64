<template>
    <AppLayout>
        <div class="p-5">
            <div
                class="flex w-full h-full bg-white rounded-lg overflow-hidden border border-gray-100"
            >
                <div class="w-4/5 p-5 border-r border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold ml-2 text-gray-800">
                            Danh sách hàng hoá
                        </h2>
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{}"
                            :color="'bg-indigo-50 text-indigo-700'"
                        >
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </Waiting>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="relative overflow-x-auto shadow-md">
                            <table
                                class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400 overflow-visible"
                            >
                                <thead
                                    class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-4 py-2"
                                            rowspan="2"
                                        >
                                            Mã hàng
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-2"
                                            rowspan="2"
                                        >
                                            Tên hàng
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-2"
                                            rowspan="2"
                                        >
                                            Đơn vị
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-1 text-center"
                                            colspan="2"
                                        >
                                            Số lượng
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-2"
                                            rowspan="2"
                                        >
                                            Đơn giá
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 py-2"
                                            rowspan="2"
                                        >
                                            Thành tiền
                                        </th>
                                    </tr>
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-2 py-1 text-center"
                                        >
                                            Đặt
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-2 py-1 text-center"
                                        >
                                            Thực nhận
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in purchaseOrder.items"
                                        :key="item.id"
                                    >
                                        <td class="px-4 py-2">
                                            {{ item.product_variant.code }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{
                                                item.product_variant.product
                                                    .name
                                            }}
                                            -
                                            <span
                                                v-for="(
                                                    attribute, index
                                                ) in item.product_variant
                                                    .attributes"
                                                :key="index"
                                            >
                                                {{ attribute.name }}
                                                {{ attribute.value }}
                                            </span>
                                        </td>
                                        <td class="px-2">
                                            {{ item.unit.name }}
                                        </td>
                                        <td
                                            class="text-center"
                                            v-if="item.quantity_received == 0"
                                        >
                                            {{ item.quantity_ordered }}
                                        </td>
                                        <td
                                            v-else
                                            class="text-center text-blue-800"
                                        >
                                            {{
                                                Math.max(
                                                    item.quantity_ordered -
                                                        item.quantity_received,
                                                    0
                                                )
                                            }}
                                        </td>
                                        <td class="text-center">
                                            <input
                                                @input="
                                                    handleQuantityChange(item)
                                                "
                                                v-model="receiptForm[item.id]"
                                                type="number"
                                                min="0"
                                                class="w-24 text-blue-800 text-xl text-center bg-transparent focus:outline-none focus:ring-0 border-none"
                                                placeholder="0"
                                            />
                                        </td>
                                        <td class="text-center">
                                            {{
                                                formatCurrencyVND(
                                                    item.unit_price
                                                )
                                            }}
                                        </td>
                                        <td
                                            class="text-center font-semibold text-red-700"
                                        >
                                            {{
                                                formatCurrencyVND(item.subtotal)
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: ORDER INFO -->
                <div class="w-2/5 p-5 bg-gray-50">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center"
                                >
                                    <svg
                                        class="h-5 w-5 text-indigo-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">
                                        hihi
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        20/06/2025 22:16
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full"
                        >
                            Phiếu in chờ kí
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div
                            class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
                        >
                            <div class="flex items-center space-x-2 mb-3">
                                <svg
                                    class="h-5 w-5 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <h3 class="font-semibold text-indigo-700">
                                    {{ purchaseOrder.supplier.name }}
                                </h3>
                            </div>

                            <div class="space-y-2 text-sm">
                                <!-- Tổng tiền hàng tính từ thực nhận -->
                                <div class="flex justify-between">
                                    <span class="text-gray-500"
                                        >Tổng giá trị hàng nhận:</span
                                    >
                                    <span class="font-medium">{{
                                        totalAmount.toLocaleString()
                                    }}</span>
                                </div>

                                <!-- Tiền trả NCC -->
                                <div
                                    class="flex justify-between pt-2 border-t border-gray-100 mt-2"
                                >
                                    <span class="text-gray-700 font-semibold"
                                        >Tiền trả NCC:</span
                                    >
                                    <input
                                        type="number"
                                        v-model="realQuantity"
                                        :max="totalAmount"
                                        placeholder="0"
                                        class="w-40 px-2 py-1 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all text-right"
                                    />
                                </div>

                                <!-- Tính vào công nợ -->
                                <div class="flex justify-between">
                                    <span class="text-gray-500"
                                        >Tính vào công nợ:</span
                                    >
                                    <span class="font-medium text-red-600">
                                        {{ debtAmount.toLocaleString() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
                        >
                            <h3
                                class="flex items-center space-x-2 text-sm font-semibold text-gray-700 mb-2"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                                <span>Ghi chú</span>
                            </h3>
                            <textarea
                                rows="2"
                                placeholder="Nhập ghi chú..."
                                v-model="form.note"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all"
                            ></textarea>
                            <div
                                v-if="showReceiveTypeSelector"
                                class="mt-4 p-3 bg-orange-50 border border-orange-300 rounded"
                            >
                                <p
                                    class="text-sm font-medium text-center text-orange-800 mb-2"
                                >
                                    <i
                                        class="fa-solid fa-triangle-exclamation"
                                    ></i>
                                    Số lượng hàng nhập chưa đủ
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button
                                @click="submitGoodReceipt('full')"
                                class="w-full py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2"
                            >
                                <svg
                                    class="h-5 w-5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span>Xác nhận kết thúc đơn</span>
                            </button>
                            <button
                                v-if="showReceiveTypeSelector"
                                @click="submitGoodReceipt('partial')"
                                class="w-full py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2"
                            >
                                <svg
                                    class="h-5 w-5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span>Nhập một phần</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { watch } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import { ref } from "vue";
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import Waiting from "../../components/Waiting.vue";

const today = new Date().toLocaleDateString("vi-VN");
const { purchaseOrder } = defineProps({
    purchaseOrder: {
        type: Object,
        required: true,
    },
});
console.log(purchaseOrder);

const realQuantity = ref(0);
const totalAmount = ref(0);
const debtAmount = ref(0);
const receiptForm = ref({});

const calculateTotals = () => {
    totalAmount.value = purchaseOrder.items.reduce((sum, item) => {
        const received = Number(receiptForm.value[item.id]) || 0;
        return sum + received * item.unit_price;
    }, 0);

    if (realQuantity.value > totalAmount.value) {
        realQuantity.value = totalAmount.value;
    }

    debtAmount.value = totalAmount.value - realQuantity.value;
};

const handleQuantityChange = (item) => {
    const received = Number(receiptForm.value[item.id]);

    if (received > item.quantity_ordered * 2) {
        receiptForm.value[item.id] = item.quantity_ordered;
    }

    item.subtotal = item.unit_price * (receiptForm.value[item.id] || 0);

    calculateTotals();
    checkReceiveType();
};

watch(realQuantity, () => {
    calculateTotals();
});

onMounted(() => {
    purchaseOrder.items.forEach((item) => {
        receiptForm.value[item.id] = 0;
    });
    calculateTotals();
});
const showReceiveTypeSelector = ref(false);
const checkReceiveType = () => {
    const hasPartial = purchaseOrder.items.some(
        (item) =>
            Number(receiptForm.value[item.id] || 0) <
            Number(item.quantity_ordered - item.quantity_received)
    );
    showReceiveTypeSelector.value = hasPartial;
};

const form = useForm({
    note: "",
    total_amount: totalAmount.value,
    items: [],
    purchase_order_id: purchaseOrder.id,
    receive_type: "full",
    payment: 0,
});
const submitGoodReceipt = (receiveType) => {
    if (receiveType == "full") {
        if (
            !confirm(
                "Bạn có chắc chắn muốn xác nhận hoàn thành và kết thúc đơn nhập này ?"
            )
        )
            return;
    }
    form.receive_type = receiveType;
    form.total_amount = totalAmount.value;
    form.payment = realQuantity.value;

    form.items = purchaseOrder.items
        .filter((item) => receiptForm.value[item.id] > 0)
        .map((item) => ({
            product_variant_id: item.product_variant.id,
            unit_id: item.unit.id,
            unit_default: item.product_variant.product.default_unit_id,
            product_id: item.product_variant.product_id,
            quantity_ordered: item.quantity_ordered - item.quantity_received, // Số lượng còn lại chưa nhận
            quantity_received: receiptForm.value[item.id],
            unit_price: item.unit_price,
            subtotal: item.unit_price * receiptForm.value[item.id],
        }));

    form.post(route("admin.receiving.store", purchaseOrder.id), {
        onError: (errors) => {
            console.error(errors);
        },
    });
};
function formatCurrencyVND(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}
</script>

<style scoped>
td {
    border: 1px solid #e5e7eb;
    vertical-align: middle;
}
th {
    border: 1px solid #c9c3ff;
    vertical-align: middle;
}
</style>
