<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div
                class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow-sm"
            >
                <h1 class="text-xl font-semibold text-indigo-800">
                    Cập nhật đơn nhập hàng
                </h1>
                <Waiting
                    route-name="admin.purchases.index"
                    :route-params="{}"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </Waiting>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <!-- Purchase Details -->
                <table
                    class="w-full border border-gray-300 rounded-lg text-sm mb-6"
                >
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3 w-1/3"
                            >
                                🏢 Nhà cung cấp
                            </td>
                            <td class="px-4 py-3 text-gray-900">
                                {{ purchase.supplier.name }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3 w-1/3"
                            >
                                Ngày giao dự kiến
                            </td>
                            <td class="px-4 py-3">
                                <input
                                    type="date"
                                    v-model="purchase.order_date"
                                    class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                />
                                <p
                                    v-if="form.errors[`order_date`]"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors[`order_date`] }}
                                </p>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                📌 Trạng thái
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-block px-3 py-1 rounded-xl text-sm font-medium text-yellow-600 bg-yellow-100 border border-yellow-300"
                                >
                                    Chờ duyệt
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                Người tạo đơn
                            </td>
                            <td class="px-4 py-3">
                                <select
                                    v-model="purchase.user_id"
                                    class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                >
                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                Ngày tạo đơn
                            </td>
                            <td class="px-4 py-3">
                                {{ formatDate(purchase.created_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Existing Items -->
                <div class="overflow-x-auto mt-10">
                    <h2 class="text-lg font-semibold text-indigo-600 mb-6">
                        <i class="fa-solid fa-check mr-2"></i> Danh sách sản
                        phẩm trong đơn
                    </h2>
                    <p
                        v-if="form.errors.items"
                        class="text-red-500 text-xs mb-4"
                    >
                        {{ form.errors.items }}
                    </p>
                    <table class="w-full border-collapse border-b text-sm">
                        <thead class="bg-indigo-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">Sản phẩm</th>
                                <th class="px-4 py-3 text-center">Số lượng</th>
                                <th class="px-4 py-3 text-center">Đơn vị</th>
                                <th class="px-4 py-3 text-center">Giá nhập (VND)</th>
                                <th class="px-4 py-3 text-right">Thành tiền</th>
                                <th class="px-4 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in purchase.items"
                                :key="index"
                                class="border-b hover:bg-gray-50"
                            >
                                <td class="px-4 py-3">
                                    {{ item.product_variant.product.name }}
                                    <span
                                        v-for="(attribute, i) in item
                                            .product_variant.attributes"
                                        :key="i"
                                        class="ml-2 text-indigo-600 font-bold"
                                    >
                                        {{ attribute.name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input
                                        type="number"
                                        v-model.number="item.quantity_ordered"
                                        @input="updateItem(index)"
                                        class="w-20 text-center border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:outline-none focus:ring-indigo-500"
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
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <select
                                        v-model="item.unit_id"
                                        @change="updateUnitPrice(index)"
                                        class="w-full border border-gray-300 rounded px-3 py-1 text-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                <td class="px-4 py-3 text-center">
                                    <div
                                        class="flex flex-col items-center gap-1"
                                    >
                                      <input
                                            type="text"
                                            :value="Number(item.unit_price || 0).toLocaleString('vi-VN')"
                                            @input="e => moneyInput(item, 'unit_price', () => updateOriginalPrice(index), e)"
                                            @focus="e => moneyFocus(item, 'unit_price', e)"
                                            @blur="e => moneyBlur(item, 'unit_price', e)"
                                            class="w-40 text-right border border-gray-300 rounded px-3 py-1 text-sm focus:ring-2 focus:outline-none focus:ring-indigo-500"
                                            />
                                        <span
                                            v-if="
                                                item.unit_id !==
                                                item.product_variant.product
                                                    .default_unit_id
                                            "
                                            class="text-gray-600 pl-1 mt-1"
                                        >
                                            Giá quy đổi:
                                            <span
                                                class="text-green-600 font-semibold"
                                            >
                                                {{
                                                    formatCurrency(
                                                        item.converted_price
                                                    )
                                                }}
                                            </span>
                                        </span>
                                        <p
                                            v-if="
                                                form.errors[
                                                    `items.${index}.unit_price`
                                                ]
                                            "
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{
                                                form.errors[
                                                    `items.${index}.unit_price`
                                                ]
                                            }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-medium">
                                    {{
                                        formatCurrency(
                                            item.quantity_ordered *
                                                item.converted_price
                                        )
                                    }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button
                                        @click="removeItem(index)"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!purchase.items.length">
                                <td
                                    colspan="6"
                                    class="p-4 text-center text-gray-500"
                                >
                                    Chưa có sản phẩm trong đơn
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total and Save -->
                <div class="flex justify-end mt-6">
                    <div class="text-right space-y-2">
                        <p class="text-base font-semibold text-gray-800">
                            Tổng tiền đơn: {{ formatCurrency(totalAmount) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Số lượng sản phẩm: {{ purchase.items.length }}
                        </p>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button
                        class="px-6 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                        @click="savePurchase"
                    >
                        Lưu thay đổi
                    </button>
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
        if (isDefaultUnit) {
            item.original_unit_price = item.unit_price;
        } else {
            const conversion = product.unit_conversions.find(
                (conv) => conv.to_unit_id === item.unit_id
            );
            const factor = Number(conversion?.conversion_factor) || 1;
            item.original_unit_price = item.unit_price / factor;
        }
        // Converted price is always a number
        item.converted_price = calculateConvertedPrice(item);
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
  const raw = e.target.value.replace(/[^\d]/g, '');   // chỉ giữ số
  obj[key] = Number(raw) || 0;                        // bind số thuần
  e.target.value = obj[key].toLocaleString('vi-VN');  // format ngay
  if (typeof cb === 'function') cb();                 // gọi logic cũ
}
function moneyFocus(obj, key, e) {
  e.target.value = obj[key] ? String(obj[key]) : '';
}
function moneyBlur(obj, key, e) {
  e.target.value = (obj[key] || 0).toLocaleString('vi-VN');
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
    item.unit_price = Number(item.unit_price) || 0;
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

const removeItem = (index) => {
    purchase.items.splice(index, 1);
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
        return {
            id: item.id,
            variant_id: item.product_variant.id,
            quantity_ordered: qty,
            unit_id: item.unit_id,
            unit_price: price,
            subtotal: qty * price,
            supplier_id: item.supplier_id,
        };
    });

    form.put(route("admin.purchases.update", purchase.id), {
        onError: () => {
            console.log(form.errors);
        },
    });
};
</script>
