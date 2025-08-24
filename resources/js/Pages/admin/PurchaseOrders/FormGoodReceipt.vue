<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-6">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div
                    class="bg-white rounded shadow-sm border border-gray-200 p-6 mb-6 flex items-center justify-between"
                >
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-100 rounded-lg">
                            <i
                                class="fas fa-clipboard-check text-indigo-600 text-xl"
                            ></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">
                                Tạo phiếu nhập kho
                            </h1>
                        </div>
                    </div>
                    <Waiting
                        route-name="admin.purchases.index"
                        :route-params="{}"
                        :color="'bg-gray-100 text-gray-700'"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors"
                    >
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </Waiting>
                </div>

                <!-- Error Messages -->
                <div
                    v-if="errors && Object.keys(errors).length > 0"
                    class="mb-6"
                >
                    <div class="bg-red-50 border border-red-200 rounded p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-red-100 rounded-lg mr-3">
                                <i
                                    class="fas fa-exclamation-triangle text-red-600"
                                ></i>
                            </div>
                            <h4 class="text-red-800 font-semibold">
                                Có lỗi xảy ra khi cập nhật phiếu nhập hàng
                            </h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div
                                v-for="(error, field) in errors"
                                :key="field"
                                class="flex items-start gap-3 bg-white rounded-lg px-4 py-3 border border-red-200"
                            >
                                <i
                                    class="fas fa-exclamation-circle text-red-500 mt-0.5"
                                ></i>
                                <div>
                                    <span class="font-medium text-gray-800"
                                        >{{ getFieldLabel(field) }}:</span
                                    >
                                    <p class="text-red-700 text-sm mt-1">
                                        {{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left: Product List -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-white rounded shadow-sm border border-gray-200 p-6"
                        >
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-boxes text-blue-600"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        Danh sách hàng hóa
                                    </h2>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200"
                                        >
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Mã hàng
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Tên hàng
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Đơn vị
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                SL Đặt
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                SL Nhận
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Đơn giá
                                            </th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Thành tiền
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in purchaseOrder.items"
                                            :key="item.id"
                                            class="hover:bg-gray-50 transition-colors"
                                        >
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="p-2 bg-indigo-100 rounded-lg mr-3"
                                                    >
                                                        <i
                                                            class="fas fa-barcode text-indigo-600 text-sm"
                                                        ></i>
                                                    </div>
                                                    <span
                                                        class="font-mono text-sm text-gray-900"
                                                        >{{
                                                            item.product_variant
                                                                .code
                                                        }}</span
                                                    >
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div>
                                                    <p
                                                        class="font-medium text-gray-900"
                                                    >
                                                        {{
                                                            item.product_variant
                                                                .product.name
                                                        }}
                                                    </p>
                                                    <div
                                                        class="flex flex-wrap gap-1 mt-1"
                                                    >
                                                        <span
                                                            v-for="(
                                                                attribute,
                                                                attrIndex
                                                            ) in item
                                                                .product_variant
                                                                .attributes"
                                                            :key="attrIndex"
                                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                                        >
                                                            {{
                                                                attribute.name
                                                            }}
                                                            {{
                                                                attribute.value
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                                >
                                                    {{ item.unit.name }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span
                                                    v-if="
                                                        item.quantity_received ==
                                                        0
                                                    "
                                                    class="font-semibold text-gray-900"
                                                >
                                                    {{ item.quantity_ordered }}
                                                </span>
                                                <span
                                                    v-else
                                                    class="font-semibold text-blue-600"
                                                >
                                                    {{
                                                        Math.max(
                                                            item.quantity_ordered -
                                                                item.quantity_received,
                                                            0
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div
                                                    class="max-w-[100px] mx-auto"
                                                >
                                                    <input
                                                        @input="
                                                            handleQuantityChange(
                                                                item
                                                            )
                                                        "
                                                        v-model="
                                                            receiptForm[item.id]
                                                        "
                                                        type="number"
                                                        min="0"
                                                        :class="[
                                                            'w-full text-center border rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                                            errors?.[
                                                                `items.${index}.quantity_received`
                                                            ]
                                                                ? 'border-red-300 bg-red-50 text-red-600'
                                                                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200 text-indigo-600',
                                                        ]"
                                                        placeholder="0"
                                                    />
                                                    <div
                                                        v-if="
                                                            errors?.[
                                                                `items.${index}.quantity_received`
                                                            ]
                                                        "
                                                        class="text-red-500 text-xs mt-1"
                                                    >
                                                        {{
                                                            errors[
                                                                `items.${index}.quantity_received`
                                                            ]
                                                        }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span
                                                    class="font-semibold text-gray-900"
                                                >
                                                    {{
                                                        formatCurrencyVND(
                                                            item.unit_price
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <span
                                                    class="font-bold text-lg text-indigo-600"
                                                >
                                                    {{
                                                        formatCurrencyVND(
                                                            item.subtotal
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Supplier Info -->
                            <div
                                class="bg-white rounded shadow-sm border border-gray-200 p-6"
                            >
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="p-2 bg-indigo-100 rounded-lg">
                                        <i
                                            class="fas fa-building text-indigo-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            Nhà cung cấp
                                        </h3>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-4 border border-indigo-100"
                                >
                                    <p
                                        class="font-semibold text-indigo-700 text-lg"
                                    >
                                        {{ purchaseOrder.supplier.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Financial Summary -->
                            <div
                                class="bg-white rounded shadow-sm border border-gray-200 p-6"
                            >
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="p-2 bg-indigo-100 rounded-lg">
                                        <i
                                            class="fas fa-calculator text-indigo-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            Chi tiết thanh toán
                                        </h3>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div
                                        class="flex justify-between items-center p-3 bg-gray-50 rounded-lg"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >Tổng giá trị hàng nhận:</span
                                        >
                                        <span class="font-bold text-gray-900"
                                            >{{
                                                totalAmount.toLocaleString()
                                            }}
                                            đ</span
                                        >
                                    </div>
                                    <div>
                                        <label
                                            class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                        >
                                            <i
                                                class="fas fa-money-bill-wave text-green-500"
                                            ></i>
                                            Tiền trả NCC
                                        </label>
                                        <input
                                            type="number"
                                            v-model="realQuantity"
                                            :max="totalAmount"
                                            placeholder="Nhập số tiền thanh toán"
                                            :class="[
                                                'w-full px-3 py-2 border rounded-lg text-right font-semibold transition-colors',
                                                errors.payment
                                                    ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200 text-red-600'
                                                    : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200 text-indigo-600',
                                            ]"
                                        />
                                        <div
                                            v-if="errors.payment"
                                            class="text-red-500 text-xs mt-1 flex items-center gap-1"
                                        >
                                            <i
                                                class="fas fa-exclamation-circle"
                                            ></i>
                                            {{ errors.payment }}
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-200"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >Công nợ nhà cung cấp:</span
                                        >
                                        <span class="font-bold text-orange-600"
                                            >{{
                                                debtAmount.toLocaleString()
                                            }}
                                            đ</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div
                                class="bg-white rounded shadow-sm border border-gray-200 p-6"
                            >
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="p-2 bg-gray-100 rounded-lg">
                                        <i
                                            class="fas fa-sticky-note text-gray-600"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            Ghi chú
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Thông tin bổ sung
                                        </p>
                                    </div>
                                </div>
                                <textarea
                                    rows="3"
                                    placeholder="Nhập ghi chú cho phiếu nhập hàng..."
                                    v-model="form.note"
                                    :class="[
                                        'w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 transition-colors resize-none',
                                        errors?.note
                                            ? 'border-red-300 bg-red-50 focus:ring-red-200 focus:border-red-500'
                                            : 'border-gray-300 focus:ring-indigo-200 focus:border-indigo-500',
                                    ]"
                                ></textarea>
                                <div
                                    v-if="errors?.note"
                                    class="mt-2 text-red-500 text-xs flex items-center gap-1"
                                >
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ errors.note }}
                                </div>
                                <div
                                    v-if="showReceiveTypeSelector"
                                    class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg"
                                >
                                    <div class="flex items-center gap-2 mb-2">
                                        <i
                                            class="fas fa-exclamation-triangle text-amber-600"
                                        ></i>
                                        <span
                                            class="text-sm font-medium text-amber-800"
                                            >Thông báo</span
                                        >
                                    </div>
                                    <p class="text-sm text-amber-700">
                                        Số lượng hàng nhập chưa đủ. Bạn có thể
                                        chọn nhập một phần.
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button
                                    @click="submitGoodReceipt('full')"
                                    class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-3 hover:from-indigo-700 hover:to-indigo-700"
                                >
                                    <i class="fas fa-check-circle text-lg"></i>
                                    <span>Xác nhận kết thúc đơn</span>
                                </button>
                                <button
                                    v-if="showReceiveTypeSelector"
                                    @click="submitGoodReceipt('partial')"
                                    class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-3 hover:from-amber-600 hover:to-orange-600"
                                >
                                    <i class="fas fa-clock text-lg"></i>
                                    <span>Nhập một phần</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Modal -->
                <div
                    v-if="showConfirmModal"
                    class="fixed inset-0 z-50 flex items-center justify-center"
                    style="background-color: rgba(0, 0, 0, 0.5)"
                >
                    <div
                        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all"
                    >
                        <div class="p-8">
                            <div
                                class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full"
                            >
                                <i
                                    class="fas fa-exclamation-triangle text-2xl text-amber-600"
                                ></i>
                            </div>
                            <div class="text-center mb-6">
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-2"
                                >
                                    {{ confirmModalData.title }}
                                </h3>
                                <p class="text-amber-600 text-sm">
                                    Vui lòng kiểm tra kỹ thông tin trước khi tạo
                                    phiếu
                                </p>
                            </div>
                            <div class="mb-6">
                                <p class="text-gray-600 text-center mb-4">
                                    {{
                                        confirmModalData.message?.split("\n")[0]
                                    }}
                                </p>
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded p-4"
                                >
                                    <h4
                                        class="font-semibold text-blue-800 mb-3 text-center flex items-center justify-center gap-2"
                                    >
                                        <i class="fas fa-calculator"></i>
                                        Xác nhận thông tin thanh toán
                                    </h4>
                                    <div class="space-y-2">
                                        <div
                                            class="flex justify-between items-center py-2 px-3 bg-white rounded-lg"
                                        >
                                            <span
                                                class="text-gray-700 font-medium"
                                                >Số tiền phải trả:</span
                                            >
                                            <span class="font-bold text-red-600"
                                                >{{
                                                    totalAmount.toLocaleString(
                                                        "vi-VN"
                                                    )
                                                }}
                                                VND</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center py-2 px-3 bg-white rounded-lg"
                                        >
                                            <span
                                                class="text-gray-700 font-medium"
                                                >Số tiền đã trả:</span
                                            >
                                            <span
                                                class="font-bold text-green-600"
                                                >{{
                                                    realQuantity.toLocaleString(
                                                        "vi-VN"
                                                    )
                                                }}
                                                VND</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between items-center py-2 px-3 bg-white rounded-lg border-t-2 border-gray-200"
                                        >
                                            <span
                                                class="text-gray-700 font-semibold"
                                                >Nợ nhà cung cấp:</span
                                            >
                                            <span
                                                class="font-bold text-orange-600"
                                                >{{
                                                    debtAmount.toLocaleString(
                                                        "vi-VN"
                                                    )
                                                }}
                                                VND</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button
                                    @click="closeConfirmModal"
                                    class="flex-1 px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all"
                                >
                                    <i class="fas fa-times mr-2"></i>
                                    {{
                                        confirmModalData.cancelText || "Hủy bỏ"
                                    }}
                                </button>
                                <button
                                    @click="handleConfirm"
                                    class="flex-1 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all shadow-md"
                                >
                                    <i class="fas fa-check mr-2"></i>
                                    {{
                                        confirmModalData.confirmText ||
                                        "Xác nhận"
                                    }}
                                </button>
                            </div>
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
const { purchaseOrder, errors } = defineProps({
    purchaseOrder: {
        type: Object,
        required: true,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});
console.log(purchaseOrder);

const realQuantity = ref(0);
const totalAmount = ref(0);
const debtAmount = ref(0);
const receiptForm = ref({});
const showConfirmModal = ref(false);
const confirmModalData = ref({});
const pendingReceiveType = ref(null);

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
    let received = Number(receiptForm.value[item.id]);

    if (received < 0) {
        receiptForm.value[item.id] = 0;
        received = 0;
    }

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

const getFieldLabel = (field) => {
    const labels = {
        note: "Ghi chú",
        payment: "Tiền trả NCC",
        receive_type: "Loại nhận hàng",
        items: "Danh sách sản phẩm",
        total_amount: "Tổng tiền",
        purchase_order_id: "Mã đơn nhập",
    };

    // Handle nested field names like "items.0.quantity_received"
    if (field.includes("items.") && field.includes(".quantity_received")) {
        return "Số lượng nhận hàng";
    }
    if (field.includes("items.") && field.includes(".unit_price")) {
        return "Đơn giá";
    }
    if (field.includes("items.") && field.includes(".subtotal")) {
        return "Thành tiền";
    }

    return labels[field] || field;
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
        showConfirmModal.value = true;
        pendingReceiveType.value = receiveType;
        confirmModalData.value = {
            title: "XÁC NHẬN KẾT THÚC ĐƠN",
            message:
                "Bạn có chắc chắn muốn xác nhận hoàn thành và kết thúc đơn nhập này?",
            confirmText: "Xác nhận",
            cancelText: "Hủy bỏ",
        };
        return;
    }

    if (receiveType == "partial") {
        showConfirmModal.value = true;
        pendingReceiveType.value = receiveType;
        confirmModalData.value = {
            title: "NHẬP MỘT PHẦN",
            message:
                "Bạn có chắc chắn muốn tạo phiếu nhập cho một phần hàng hóa?",
            confirmText: "Xác nhận",
            cancelText: "Hủy bỏ",
        };
        return;
    }
};

const handleConfirm = () => {
    processSubmit(pendingReceiveType.value);
    closeConfirmModal();
};

const closeConfirmModal = () => {
    showConfirmModal.value = false;
    pendingReceiveType.value = null;
    confirmModalData.value = {};
};

const processSubmit = (receiveType) => {
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
