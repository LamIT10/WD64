<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 py-6">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div
                    class="bg-white rounded shadow border border-gray-200 p-6 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <i
                                    class="fas fa-edit text-indigo-600 text-xl"
                                ></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-semibold text-gray-900">
                                    Cập nhật đơn nhập hàng
                                </h1>
                            </div>
                        </div>
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{}"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            <i class="fas fa-arrow-left"></i>
                            <span>Quay lại</span>
                        </Waiting>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Right Column: Product List -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-white rounded shadow border border-gray-200 p-6"
                        >
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-emerald-100 rounded-lg">
                                    <i class="fas fa-box text-emerald-600"></i>
                                </div>
                                <div>
                                    <h2
                                        class="text-lg font-semibold text-gray-900"
                                    >
                                        Danh sách sản phẩm
                                    </h2>
                                </div>
                            </div>

                            <p
                                v-if="form.errors.items"
                                class="text-red-500 text-sm mb-4 p-3 bg-red-50 rounded-lg border border-red-200"
                            >
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ form.errors.items }}
                            </p>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200"
                                        >
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Sản phẩm
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Số lượng
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Đơn vị
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                            >
                                                Giá nhập (VND)
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
                                            ) in purchase.items"
                                            :key="index"
                                            class="hover:bg-gray-50 transition-colors"
                                        >
                                            <td class="px-4 py-4">
                                                <div
                                                    class="flex items-start space-x-3"
                                                >
                                                    <div
                                                        class="p-2 bg-blue-100 rounded-lg"
                                                    >
                                                        <i
                                                            class="fas fa-cube text-blue-600"
                                                        ></i>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="font-medium text-gray-900"
                                                        >
                                                            {{
                                                                item
                                                                    .product_variant
                                                                    .product
                                                                    .name
                                                            }}
                                                        </p>
                                                        <div
                                                            class="flex flex-wrap gap-1 mt-1"
                                                        >
                                                            <span
                                                                v-for="(
                                                                    attribute, i
                                                                ) in item
                                                                    .product_variant
                                                                    .attributes"
                                                                :key="i"
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                                                            >
                                                                {{
                                                                    attribute.name
                                                                }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div
                                                    class="max-w-[80px] mx-auto"
                                                >
                                                    <input
                                                        type="number"
                                                        v-model.number="
                                                            item.quantity_ordered
                                                        "
                                                        @input="
                                                            updateItem(index)
                                                        "
                                                        :class="[
                                                            'w-full text-center border rounded-lg px-2 py-1 text-sm transition-colors',
                                                            form.errors[
                                                                `items.${index}.quantity_ordered`
                                                            ]
                                                                ? 'border-red-300 bg-red-50'
                                                                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200',
                                                        ]"
                                                        min="0"
                                                    />
                                                    <p
                                                        v-if="
                                                            form.errors[
                                                                `items.${index}.quantity_ordered`
                                                            ]
                                                        "
                                                        class="text-red-500 text-xs mt-1"
                                                    >
                                                        {{
                                                            form.errors[
                                                                `items.${index}.quantity_ordered`
                                                            ]
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <select
                                                    v-model="item.unit_id"
                                                    @change="
                                                        updateUnitPrice(index)
                                                    "
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-1 text-sm focus:border-indigo-500 focus:ring-indigo-200"
                                                >
                                                    <option
                                                        v-for="unit in getAvailableUnits(
                                                            item
                                                        )"
                                                        :key="unit.id"
                                                        :value="unit.id"
                                                    >
                                                        {{ unit.name }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="space-y-2">
                                                    <input
                                                        type="text"
                                                        :value="
                                                            Number(
                                                                item.unit_price ||
                                                                    0
                                                            ).toLocaleString(
                                                                'vi-VN'
                                                            )
                                                        "
                                                        @input="
                                                            (e) =>
                                                                moneyInput(
                                                                    item,
                                                                    'unit_price',
                                                                    () =>
                                                                        updateOriginalPrice(
                                                                            index
                                                                        ),
                                                                    e
                                                                )
                                                        "
                                                        @focus="
                                                            (e) =>
                                                                moneyFocus(
                                                                    item,
                                                                    'unit_price',
                                                                    e
                                                                )
                                                        "
                                                        @blur="
                                                            (e) =>
                                                                moneyBlur(
                                                                    item,
                                                                    'unit_price',
                                                                    e
                                                                )
                                                        "
                                                        :class="[
                                                            'w-full text-right border rounded-lg px-3 py-1 text-sm transition-colors',
                                                            form.errors[
                                                                `items.${index}.unit_price`
                                                            ]
                                                                ? 'border-red-300 bg-red-50'
                                                                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200',
                                                        ]"
                                                    />
                                                    <div
                                                        v-if="
                                                            item.hasUnitChanged &&
                                                            item.unit_id !==
                                                                item
                                                                    .product_variant
                                                                    .product
                                                                    .default_unit_id
                                                        "
                                                        class="text-xs"
                                                    >
                                                        <span
                                                            class="text-gray-500"
                                                            >Giá quy đổi:</span
                                                        >
                                                        <span
                                                            class="font-semibold text-emerald-600 ml-1"
                                                            >{{
                                                                formatCurrency(
                                                                    item.converted_price
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <p
                                                        v-if="
                                                            form.errors[
                                                                `items.${index}.unit_price`
                                                            ]
                                                        "
                                                        class="text-red-500 text-xs"
                                                    >
                                                        {{
                                                            form.errors[
                                                                `items.${index}.unit_price`
                                                            ]
                                                        }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <span
                                                    class="font-semibold text-lg text-gray-900"
                                                >
                                                    {{
                                                        formatCurrency(
                                                            item.quantity_ordered *
                                                                item.converted_price
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="!purchase.items.length">
                                            <td
                                                colspan="5"
                                                class="px-4 py-8 text-center"
                                            >
                                                <div
                                                    class="flex flex-col items-center space-y-3"
                                                >
                                                    <div
                                                        class="p-4 bg-gray-100 rounded-full"
                                                    >
                                                        <i
                                                            class="fas fa-box-open text-2xl text-gray-400"
                                                        ></i>
                                                    </div>
                                                    <p class="text-gray-500">
                                                        Chưa có sản phẩm trong
                                                        đơn
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Left Column: Order Information -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white rounded shadow border border-gray-200 p-6"
                        >
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i
                                        class="fas fa-info-circle text-blue-600"
                                    ></i>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Thông tin đơn hàng
                                </h2>
                            </div>

                            <div class="space-y-4">
                                <!-- Supplier -->
                                <div
                                    class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-100"
                                >
                                    <label
                                        class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                    >
                                        <i
                                            class="fas fa-building text-indigo-500"
                                        ></i>
                                        Nhà cung cấp
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ purchase.supplier.name }}
                                    </p>
                                </div>

                                <!-- Expected Date -->
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                    >
                                        <i
                                            class="fas fa-calendar-alt text-green-500"
                                        ></i>
                                        Ngày giao dự kiến
                                    </label>
                                    <input
                                        type="date"
                                        v-model="purchase.order_date"
                                        :class="[
                                            'w-full px-3 py-2 border rounded-lg text-sm transition-colors',
                                            form.errors.order_date
                                                ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-200'
                                                : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-200',
                                        ]"
                                    />
                                    <p
                                        v-if="form.errors.order_date"
                                        class="text-red-500 text-xs mt-1"
                                    >
                                        {{ form.errors.order_date }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                    >
                                        <i
                                            class="fas fa-flag text-yellow-500"
                                        ></i>
                                        Trạng thái
                                    </label>
                                    <div
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200"
                                    >
                                        <i class="fas fa-clock mr-2"></i>
                                        Chờ duyệt
                                    </div>
                                </div>

                                <!-- User -->
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                    >
                                        <i
                                            class="fas fa-user text-purple-500"
                                        ></i>
                                        Người tạo đơn
                                    </label>
                                    <select
                                        v-model="purchase.user_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-200"
                                    >
                                        <option
                                            v-for="user in users"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Created Date -->
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-700 flex items-center gap-2 mb-2"
                                    >
                                        <i
                                            class="fas fa-clock text-gray-500"
                                        ></i>
                                        Ngày tạo đơn
                                    </label>
                                    <p class="text-gray-600 text-sm">
                                        {{ formatDate(purchase.created_at) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Summary -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div
                                    class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-4 border border-emerald-200"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >Tổng giá trị đơn</span
                                        >
                                        <span
                                            class="text-lg font-bold text-emerald-600"
                                            >{{
                                                formatCurrency(totalAmount)
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span class="text-xs text-gray-600"
                                            >Số lượng sản phẩm</span
                                        >
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >{{ purchase.items.length }} mặt
                                            hàng</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <button
                                @click="savePurchase"
                                class="w-full mt-6 bg-indigo-600 text-white font-medium py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import { route } from "ziggy-js";
import Waiting from "../../components/Waiting.vue";

const { purchase, users, products } = defineProps({
    purchase: { default: () => ({ supplier: {}, items: [] }) },
    users: { default: () => [] },
    products: { default: () => [] },
});

const calculateConvertedPrice = (item) => {
    const product = item.product_variant.product;
    const unitPrice = Number(item.unit_price) || 0;

    // Nếu chưa thay đổi đơn vị (lần đầu load), giữ nguyên giá gốc
    if (!item.hasUnitChanged) {
        return unitPrice;
    }

    if (item.unit_id === product.default_unit_id) {
        return unitPrice;
    }
    const conversion = product.unit_conversions.find(
        (conv) => conv.to_unit_id === item.unit_id
    );
    const factor = Number(conversion?.conversion_factor) || 1;
    return conversion ? unitPrice * factor : unitPrice;
};

watch(
    () => purchase.order_date,
    (val) => {
        if (val && val.includes("T")) {
            purchase.order_date = val.split("T")[0];
        }
    },
    { immediate: true }
);

onMounted(() => {
    if (purchase.order_date) {
        purchase.order_date = purchase.order_date.split("T")[0];
    }
    purchase.items.forEach((item) => {
        const product = item.product_variant.product;
        const isDefaultUnit = item.unit_id === product.default_unit_id;
        // Ensure unit_price is a number
        item.unit_price = Number(item.unit_price) || 0;

        // Đánh dấu là chưa thay đổi đơn vị (lần đầu load)
        item.hasUnitChanged = false;

        if (isDefaultUnit) {
            item.original_unit_price = item.unit_price;
            item.converted_price = item.unit_price;
        } else {
            // Lần đầu load: giữ nguyên giá gốc, không tính theo factor
            item.original_unit_price = item.unit_price;
            item.converted_price = item.unit_price;
        }

        item.subtotal =
            (Number(item.quantity_ordered) || 0) *
            (Number(item.converted_price) || 0);
    });
    // Update total amount on mount
    nextTick(() => {
        totalAmount.value = purchase.items.reduce(
            (sum, item) =>
                sum +
                (Number(item.quantity_ordered) || 0) *
                    (Number(item.converted_price) || 0),
            0
        );
    });
});

watch(
    () => purchase.items,
    (items) => {
        items.forEach((item) => {
            const product = item.product_variant.product;
            const isDefaultUnit = item.unit_id === product.default_unit_id;
            item.unit_price = Number(item.unit_price) || 0;
            if (isDefaultUnit) {
                item.original_unit_price = item.unit_price;
            } else {
                const conversion = product.unit_conversions.find(
                    (conv) => conv.to_unit_id === item.unit_id
                );
                const factor = Number(conversion?.conversion_factor) || 1;
                item.original_unit_price = item.unit_price / factor;
            }
            item.converted_price = calculateConvertedPrice(item);
            item.subtotal =
                (Number(item.quantity_ordered) || 0) *
                (Number(item.converted_price) || 0);
        });
    },
    { immediate: true, deep: true }
);

const totalAmount = ref(0);

// Tự động cập nhật tổng tiền khi items thay đổi
watch(
    () => purchase.items.map((i) => [i.quantity_ordered, i.converted_price]),
    () => {
        totalAmount.value = purchase.items.reduce(
            (sum, item) =>
                sum +
                (Number(item.quantity_ordered) || 0) *
                    (Number(item.converted_price) || 0),
            0
        );
    },
    { deep: true, immediate: true }
);

const formatCurrency = (value) => {
    value = Number(value) || 0;
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
};
//Format money

function moneyInput(obj, key, cb, e) {
    const raw = e.target.value.replace(/[^\d]/g, ""); // chỉ giữ số
    obj[key] = Number(raw) || 0; // bind số thuần
    e.target.value = obj[key].toLocaleString("vi-VN"); // format ngay
    if (typeof cb === "function") cb(); // gọi logic cũ
}
const getVariantName = (item) => {
    const baseName = item?.product_variant?.product?.name ?? "";
    const attrs = Array.isArray(item?.product_variant?.attributes)
        ? item.product_variant.attributes.map((a) => a?.name).filter(Boolean)
        : [];
    return [baseName, ...attrs].filter(Boolean).join(" - ").trim();
};
function moneyFocus(obj, key, e) {
    e.target.value = obj[key] ? String(obj[key]) : "";
}
function moneyBlur(obj, key, e) {
    e.target.value = (obj[key] || 0).toLocaleString("vi-VN");
}

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const formatDateForSubmission = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
};

const getAvailableUnits = (item) => {
    const product = item.product_variant.product;

    const defaultUnit = {
        id: product.default_unit_id,
        name: product.unit_default.name,
    };

    const conversionUnits = product.unit_conversions.map((conv) => ({
        id: conv.to_unit_id,
        name: conv.to_unit.name,
    }));

    return [
        defaultUnit,
        ...conversionUnits.filter(
            (u, index, self) => self.findIndex((x) => x.id === u.id) === index
        ),
    ];
};

const updateItem = (index) => {
    const item = purchase.items[index];
    item.unit_price = Number(item.unit_price) || 0;
    item.converted_price = calculateConvertedPrice(item);
    item.subtotal =
        (Number(item.quantity_ordered) || 0) *
        (Number(item.converted_price) || 0);
    // Update total amount
    nextTick(() => {
        totalAmount.value = purchase.items.reduce(
            (sum, item) =>
                sum +
                (Number(item.quantity_ordered) || 0) *
                    (Number(item.converted_price) || 0),
            0
        );
    });
};

const updateOriginalPrice = (index) => {
    const item = purchase.items[index];
    item.unit_price = Number(item.unit_price) || 0;
    item.original_unit_price = item.unit_price;
    item.converted_price = calculateConvertedPrice(item);
    item.subtotal =
        (Number(item.quantity_ordered) || 0) *
        (Number(item.converted_price) || 0);
    nextTick(() => {
        totalAmount.value = purchase.items.reduce(
            (sum, item) =>
                sum +
                (Number(item.quantity_ordered) || 0) *
                    (Number(item.converted_price) || 0),
            0
        );
    });
};

const updateUnitPrice = (index) => {
    const item = purchase.items[index];
    const product = item.product_variant.product;

    // Đánh dấu là đã thay đổi đơn vị
    item.hasUnitChanged = true;

    // Giữ nguyên unit_price user nhập, chỉ tính lại converted_price
    item.unit_price = Number(item.unit_price) || 0;

    if (item.unit_id === product.default_unit_id) {
        // Nếu là đơn vị gốc, giá cơ bản = unit_price
        item.original_unit_price = item.unit_price;
    } else {
        // Nếu là đơn vị quy đổi, tính giá cơ bản từ unit_price hiện tại
        const conversion = product.unit_conversions.find(
            (conv) => conv.to_unit_id === item.unit_id
        );
        const factor = Number(conversion?.conversion_factor) || 1;
        // Giá cơ bản = giá hiện tại / hệ số quy đổi
        item.original_unit_price = item.unit_price / factor;
    }

    item.converted_price = calculateConvertedPrice(item);
    item.subtotal =
        (Number(item.quantity_ordered) || 0) *
        (Number(item.converted_price) || 0);
    nextTick(() => {
        totalAmount.value = purchase.items.reduce(
            (sum, item) =>
                sum +
                (Number(item.quantity_ordered) || 0) *
                    (Number(item.converted_price) || 0),
            0
        );
    });
};

const form = useForm({
    order_date: purchase.order_date ?? null,
    user_id: purchase.user_id ?? null,
    total_amount: 0,
    items: [],
});

const savePurchase = () => {
    // Recalculate converted_price and subtotal for each item before saving
    purchase.items.forEach((item) => {
        item.unit_price = Number(item.unit_price) || 0;
        item.converted_price = calculateConvertedPrice(item);
        item.subtotal =
            (Number(item.quantity_ordered) || 0) *
            (Number(item.converted_price) || 0);
    });
    form.order_date = purchase.order_date || null;
    form.user_id = purchase.user_id || null;
    form.total_amount = purchase.items.reduce(
        (sum, item) =>
            sum +
            (Number(item.quantity_ordered) || 0) *
                (Number(item.converted_price) || 0),
        0
    );
    form.items = purchase.items.map((item) => {
        const qty = Number(item.quantity_ordered) || 0;
        const price = Number(item.converted_price) || 0;

        // Tính factor dựa trên đơn vị được chọn
        const product = item.product_variant.product;
        let factor = 1;

        if (item.unit_id !== product.default_unit_id) {
            const conversion = product.unit_conversions.find(
                (conv) => conv.to_unit_id === item.unit_id
            );
            factor = Number(conversion?.conversion_factor) || 1;
        }

        return {
            id: item.id,
            variant_id: item.product_variant.id,
            quantity_ordered: qty,
            unit_id: item.unit_id,
            unit_price: price,
            subtotal: qty * price,
            supplier_id: item.supplier_id,
            variant_name: getVariantName(item),
            factor: factor,
        };
    });

    form.put(route("admin.purchases.update", purchase.id), {
        onError: () => {
            console.log(form.errors);
        },
    });
};
</script>
