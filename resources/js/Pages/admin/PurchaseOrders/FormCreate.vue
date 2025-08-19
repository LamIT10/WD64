<script setup>
import { ref, computed, nextTick } from "vue";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import AppLayout from "../Layouts/AppLayout.vue";
import { route } from "ziggy-js";
import axios from "axios";
import Waiting from "../../components/Waiting.vue";
import { useForm } from "@inertiajs/vue3";

// Props
const { products } = defineProps(["products"]);

// Computed
const selectOptions = computed(() =>
    products.map((p) => ({
        label: p.name,
        value: p.id,
    }))
);
//Fomat money input
function handleMoneyInput(obj, key, cb, e) {
  const raw = e.target.value.replace(/[^\d]/g, '');
  obj[key] = Number(raw) || 0;                       
  e.target.value = obj[key].toLocaleString('vi-VN');  
  if (typeof cb === 'function') cb(obj);
}

function handleMoneyFocus(obj, key, e) {
  e.target.value = obj[key] ? String(obj[key]) : '';
}

function handleMoneyBlur(obj, key, e) {
  e.target.value = (obj[key] || 0).toLocaleString('vi-VN');
}
// Refs
const variantOfProducts = ref([]);
const selectedProduct = ref(null);
const showModal = ref(false);
const variantOptions = ref([]);
const checkedVariants = ref([]);
const selectedVariants = ref([]);
const unitDefault = ref(null);
const unitOptionsMap = ref({});
const unitConversions = ref([]);
const showConfirmModal = ref(false);
const preparedOrders = ref([]);
const finalItems = ref([]);

const selectedVariantIds = computed(() =>
    finalItems.value.map((item) => item.variants)
);

// Fetch product variants
async function onProductSelect(productId) {
    if (!productId) return;
    try {
        const url = route("admin.purchases.getVariants", { id: productId });
        const { data } = await axios.get(url);

        variantOfProducts.value = data;
        unitDefault.value = data.unit_default;
        unitConversions.value = data.unit_conversions;

        const toUnits = data.unit_conversions.map((conv) => ({
            id: conv.to_unit.id,
            name: conv.to_unit.name,
        }));

        const allUnitOptions = [
            unitDefault.value,
            ...toUnits.filter(
                (u, index, self) =>
                    self.findIndex((x) => x.id === u.id) === index &&
                    u.id !== unitDefault.value.id
            ),
        ];

        variantOptions.value = data.product_variants.map((v) => {
            unitOptionsMap.value[v.id] = allUnitOptions;
            return {
                id: v.id,
                label: `${data.name} - ${v.attributes
                    .map((attr) => attr.name)
                    .join(" - ")}`,
                value: v.id,
                suppliers: v.suppliers,
                isDisabled: selectedVariantIds.value.includes(v.id),
            };
        });

        checkedVariants.value = [
            ...variantOptions.value.filter((v) => !v.isDisabled),
        ];
        showModal.value = true;
    } catch (error) {
        console.error("Lỗi lấy biến thể:", error);
    }
}

// Toggle all variants
function toggleAllVariants(state) {
    checkedVariants.value = state
        ? [...variantOptions.value.filter((v) => !v.isDisabled)]
        : [];
}

// Confirm selected variants
function confirmVariants() {
    if (!checkedVariants.value.length) return;

    selectedVariants.value = checkedVariants.value.map((v) => {
        const defaultUnit = unitDefault.value;
        const unitOpts = unitOptionsMap.value[v.id] || [defaultUnit];
        const processedVendors = v.suppliers.map((s) => ({
            id: s.id,
            name: s.name,
            cost_price: s.pivot.cost_price || 0,
            sale_price: s.pivot.sale_price || 0,
            min_order_quantity: s.pivot.min_order_quantity || 1,
        }));

        return {
            name: v.label,
            vendors: processedVendors,
            vendorSelected: null,
            quantity: 1,
            variantId: v.id,
            unit: defaultUnit.name,
            unitSelected: defaultUnit.id,
            unitOptions: unitOpts,
            price: 0,
            total: 0,
            conversionFactor: 1,
        };
    });

    showModal.value = false;
}

// Calculate conversion factor
function getConversionFactor(fromUnitId, toUnitId) {
    if (fromUnitId === toUnitId) return 1;
    const conversion = unitConversions.value.find(
        (conv) =>
            conv.from_unit_id === fromUnitId && conv.to_unit_id === toUnitId
    );
    return conversion ? parseFloat(conversion.conversion_factor) : 1;
}

// Calculate total for variant
function calculateTotal(variant) {
    const conversionFactor = getConversionFactor(
        unitDefault.value?.id,
        variant.unitSelected
    );
    variant.conversionFactor = conversionFactor;
    variant.convertedPrice = variant.price * conversionFactor;
    variant.total = variant.quantity * variant.convertedPrice;
}

// Update price when vendor is selected
function updatePriceOnVendorChange(variant) {
    if (variant.vendorSelected) {
        const selectedVendor = variant.vendors.find(v => v.id === variant.vendorSelected);
        if (selectedVendor && selectedVendor.cost_price > 0) {
            variant.price = Number(selectedVendor.cost_price);
        } else {
            variant.price = 0; // Reset to 0 if no cost_price
        }
        calculateTotal(variant);
    }
}

// Get supplier name
function getSupplierName(supplierId) {
    const item = finalItems.value.find((i) => i.vendorSelected === supplierId);
    if (!item || !item.vendors) return "Không rõ";
    const supplier = item.vendors.find((v) => v.id === supplierId);
    return supplier?.name || "Không rõ";
}

// Add variants to final list
function addToList() {
    const convertedItems = selectedVariants.value
        .filter(
            (item) =>
                item.vendorSelected && item.quantity > 0 && item.price >= 0
        )
        .map((item) => {
            const factor = item.conversionFactor || 1;
            const priceBase = item.price; // Giá gốc trước khi nhân với factor
            const convertedPrice = item.price * factor;
            
            return {
                name: item.name,
                vendors: item.vendors,
                vendorSelected: item.vendorSelected,
                quantity: item.quantity,
                variants: item.variantId,
                unitId: item.unitSelected,
                unit:
                    item.unitOptions.find((u) => u.id === item.unitSelected)
                        ?.name || "",
                unitSelected: item.unitSelected,
                conversionFactor: factor,
                factor: factor,
                price: convertedPrice,
                priceBase: priceBase,
                total: item.total,
            };
        });

    finalItems.value.push(...convertedItems);
    selectedVariants.value = [];
    // Update variant options to disable newly added variants
    variantOptions.value = variantOptions.value.map((v) => ({
        ...v,
        isDisabled: selectedVariantIds.value.includes(v.id),
    }));
}

// Remove item from list
function removeItem(index) {
    const removedVariantId = finalItems.value[index].variants;
    finalItems.value.splice(index, 1);
    // Re-enable the variant in options
    variantOptions.value = variantOptions.value.map((v) => ({
        ...v,
        isDisabled:
            v.id === removedVariantId
                ? false
                : selectedVariantIds.value.includes(v.id),
    }));
}

// Update quantity and price for final items
function updateItem(index, field, value) {
    finalItems.value[index][field] = value;
    const item = finalItems.value[index];
    
    // Nếu thay đổi giá, cập nhật priceBase
    if (field === 'price') {
        const factor = item.factor || 1;
        item.priceBase = value / factor;
    }
    
    item.total = item.quantity * item.price;
}

// Calculate grand total
const grandTotal = computed(() =>
    finalItems.value.reduce((sum, item) => sum + item.total, 0)
);

// Prepare purchase orders
function preparePurchaseOrders() {
    const ordersMap = {};

    for (const item of finalItems.value) {
        const supplierId = item.vendorSelected;
        if (!ordersMap[supplierId]) {
            ordersMap[supplierId] = {
                supplier_id: supplierId,
                supplier_name: getSupplierName(supplierId),
                items: [],
                subtotal: 0,
                expected_date: null,
            };
        }

        ordersMap[supplierId].items.push({
            variant_id: item.variants,
            name: item.name,
            quantity: item.quantity,
            unit_id: item.unitId,
            unit: item.unit,
            price: item.price,
            price_base: item.priceBase || item.price, // Giá cơ bản
            factor: item.factor || 1, // Hệ số quy đổi
            total: item.total,
        });

        ordersMap[supplierId].subtotal += item.total;
    }

    return Object.values(ordersMap);
}

// Open confirm modal
function openConfirmModal() {
    if (!finalItems.value.length) return;
    preparedOrders.value = preparePurchaseOrders();
    showConfirmModal.value = true;
}

// Submit confirmed orders
const form = useForm({
    orders: preparedOrders.value,
});
function submitConfirmedOrders() {
    form.orders = preparedOrders.value;
    form.post(route("admin.purchases.store"), {
        onError: () => {
            console.error(form.errors);
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100 p-6">
            <!-- Header -->
            <div
                class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow-sm"
            >
                <h1 class="text-xl font-semibold text-indigo-800">
                    Tạo yêu cầu nhập hàng
                </h1>
                <Waiting
                    route-name="admin.purchases.index"
                    :route-params="{}"
                    class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-md hover:bg-indigo-100"
                >
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </Waiting>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <!-- Product Selection -->
                <div class="mb-6">
                    <Multiselect
                        v-model="selectedProduct"
                        :options="selectOptions"
                        label="label"
                        value-prop="value"
                        searchable
                        placeholder="Tìm kiếm hoặc chọn sản phẩm"
                        @update:modelValue="onProductSelect"
                    />
                </div>

                <!-- Selected Variants -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-indigo-800 mb-4">
                        <i class="fa-solid fa-calendar-check text0xl mr-2"></i>
                        Biến thể đã chọn
                    </h2>
                    <div class="border border-indigo-100">
                        <table class="min-w-full">
                            <thead
                                class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        Sản phẩm
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        Nhà cung cấp
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        Số lượng
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        Đơn vị
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left">
                                        Giá nhập (VND)
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 text-right"
                                    >
                                        Thành tiền
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-if="!selectedVariants.length">
                                    <td colspan="6" class="p-2">
                                        <div
                                            v-if="!selectedVariants.length"
                                            class="bg-gray-50 rounded-lg p-8 text-center"
                                        >
                                            <i
                                                class="fas fa-box-open text-3xl text-gray-300 mb-3"
                                            ></i>
                                            <p class="text-gray-500">
                                                Tìm kiếm sản phẩm để tiến hành
                                                nhập hàng
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="(variant) in selectedVariants"
                                    :key="`sv-${variant.variantId}`"
                                    class="hover:bg-gray-50 transition"
                                >
                                    <td
                                        class="px-4 py-3 text-sm text-gray-900 align-top"
                                    >
                                        {{ variant.name }}
                                    </td>

                                    <td class="px-4 py-3 align-top w-100">
                                        <Multiselect
                                            v-if="variant.vendors.length"
                                            v-model="variant.vendorSelected"
                                            :options="
                                                variant.vendors.map((v) => ({
                                                    label: v.name,
                                                    value: v.id,
                                                }))
                                            "
                                            label="label"
                                            value-prop="value"
                                            placeholder="Chọn nhà cung cấp"
                                            :searchable="true"
                                            :z-index="3000"
                                            :close-on-select="true"
                                            :can-clear="false"
                                            class="w-full"
                                            @update:modelValue="updatePriceOnVendorChange(variant)"
                                        ></Multiselect>
                                        <span
                                            v-else
                                            class="text-sm text-red-600 italic"
                                        >
                                            Hiện chưa có nhà cung cấp
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 align-top w-24">
                                        <input
                                            type="number"
                                            v-model.number="variant.quantity"
                                            @input="calculateTotal(variant)"
                                            min="1"
                                            class="w-full bg-transparent border-b border-gray-300 text-sm px-1 py-1 focus:outline-none focus:border-indigo-500"
                                        />
                                    </td>

                                    <td class="px-4 py-3 align-top w-48">
                                        <select
                                            v-model="variant.unitSelected"
                                            @change="calculateTotal(variant)"
                                            class="w-full bg-transparent border-b border-gray-300 text-sm px-1 py-1 focus:outline-none focus:border-indigo-500"
                                        >
                                            <option
                                                v-for="u in variant.unitOptions"
                                                :key="u.id"
                                                :value="u.id"
                                            >
                                                {{ u.name }}
                                            </option>
                                        </select>
                                    </td>

                                    <td class="px-4 py-3 align-top w-32">
                                        <div class="flex flex-col gap-1">
                                           <input
                                                 type="text"
                                                :value="variant.price?.toLocaleString('vi-VN')"
                                                 @input="e => handleMoneyInput(variant, 'price', () => calculateTotal(variant), e)"
                                                 @focus="e => handleMoneyFocus(variant, 'price', e)"
                                                  @blur="e => handleMoneyBlur(variant, 'price', e)"
                                                  :placeholder="variant.price === 0 ? 'Nhập giá' : ''"
                                                  class="w-full bg-transparent border-b border-gray-300 text-sm px-1 py-1 focus:outline-none focus:border-indigo-500"
                                                    />
                                            <span
                                                class="text-xs text-gray-600 pl-1 mt-1"
                                            >
                                                Giá quy đổi:
                                                <span
                                                    :class="{
                                                        'text-green-600 font-semibold':
                                                            variant.conversionFactor >
                                                            1,
                                                    }"
                                                >
                                                    {{
                                                        (
                                                            variant.price *
                                                            variant.conversionFactor
                                                        ).toLocaleString()
                                                    }}
                                                </span>
                                            </span>
                                        </div>
                                    </td>

                                    <td
                                        class="px-4 py-3 text-sm font-medium text-gray-900 align-top text-right"
                                    >
                                        {{ variant.total.toLocaleString() }}đ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button
                        v-if="selectedVariants.length"
                        @click="addToList"
                        :disabled="
                            selectedVariants.some(
                                (v) =>
                                    !v.vendorSelected ||
                                    v.quantity <= 0 ||
                                    v.price < 0
                            )
                        "
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Thêm vào danh sách
                    </button>
                </div>

                <!-- Variant Selection Modal -->
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center hihi"
                    @click.self="showModal = false"
                >
                    <div
                        class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Chọn biến thể sản phẩm
                        </h3>
                        <div class="flex justify-between mb-4">
                            <button
                                @click="toggleAllVariants(true)"
                                class="text-sm text-indigo-600 hover:text-indigo-800"
                            >
                                Chọn tất cả
                            </button>
                            <button
                                @click="toggleAllVariants(false)"
                                class="text-sm text-red-600 hover:text-red-800"
                            >
                                Bỏ chọn tất cả
                            </button>
                        </div>
                        <div
                            class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4"
                        >
                            <div
                                v-for="v in variantOptions"
                                :key="v.id"
                                class="flex items-center mb-2"
                            >
                                <input
                                    type="checkbox"
                                    :id="`variant-${v.id}`"
                                    :value="v"
                                    v-model="checkedVariants"
                                    :disabled="v.isDisabled"
                                    class="h-4 w-4 text-indigo-600 rounded disabled:opacity-50 focus:ring-indigo-500"
                                />
                                <label
                                    :for="`variant-${v.id}`"
                                    class="ml-2 text-sm text-gray-700"
                                    :class="{ 'opacity-50': v.isDisabled }"
                                >
                                    {{ v.label }}
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-4">
                            <button
                                @click="showModal = false"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Hủy
                            </button>
                            <button
                                @click="confirmVariants"
                                :disabled="checkedVariants.length === 0"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Xác nhận
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Items List -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-indigo-800 mb-4">
                    <i class="fa-solid fa-check text-xl mr-2"></i> Danh sách sản
                    phẩm đã chọn
                </h2>
                <div class="overflow-x-auto border border-indigo-100">
                    <table class="min-w-full">
                        <thead
                            class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left">
                                    Sản phẩm
                                </th>
                                <th scope="col" class="px-4 py-3 text-left">
                                    Nhà cung cấp
                                </th>
                                <th scope="col" class="px-4 py-3 text-left">
                                    Số lượng
                                </th>
                                <th scope="col" class="px-4 py-3 text-left">
                                    Đơn vị
                                </th>
                                <th scope="col" class="px-4 py-3 text-left">
                                    Giá nhập (VND)
                                </th>
                                <th scope="col" class="px-4 py-3 text-right">
                                    Thành tiền
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-right"
                                ></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr v-if="!finalItems.length">
                                <td colspan="7" class="p-2">
                                    <div
                                        v-if="!finalItems.length"
                                        class="bg-gray-50 rounded-lg p-8 text-center"
                                    >
                                        <i
                                            class="fas fa-clipboard-list text-3xl text-gray-300 mb-3"
                                        ></i>
                                        <p class="text-gray-500">
                                            Chưa có sản phẩm nào được thêm
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                v-for="(item, i) in finalItems"
                                :key="i"
                                class="hover:bg-gray-50 transition"
                            >
                                <td
                                    class="px-4 py-3 text-sm text-gray-900 align-top"
                                >
                                    {{ item.name }}
                                </td>

                                <td
                                    class="px-4 py-3 text-sm text-gray-700 align-top"
                                >
                                    {{
                                        item.vendors.find(
                                            (v) => v.id === item.vendorSelected
                                        )?.name || "N/A"
                                    }}
                                </td>

                                <td class="px-4 py-3 align-top w-24">
                                    <input
                                        type="number"
                                        v-model.number="item.quantity"
                                        @input="
                                            updateItem(
                                                i,
                                                'quantity',
                                                item.quantity
                                            )
                                        "
                                        min="1"
                                        class="w-full text-center bg-transparent border-b border-gray-300 text-sm px-1 py-1 focus:outline-none focus:border-indigo-500"
                                    />
                                </td>

                                <td
                                    class="px-4 py-3 text-sm text-gray-700 align-top"
                                >
                                    {{ item.unit }}
                                </td>

                                <td class="px-4 py-3 align-top w-32">
                                    <input
                                        type="text"
                                        :value="item.price?.toLocaleString('vi-VN')"
                                        @input="e => handleMoneyInput(item, 'price', () => updateItem(i, 'price', item.price), e)"
                                        @focus="e => handleMoneyFocus(item, 'price', e)"
                                        @blur="e => handleMoneyBlur(item, 'price', e)"
                                        class="w-full bg-transparent border-b border-gray-300 text-sm px-1 py-1 text-right focus:outline-none focus:border-indigo-500"
                                        />
                                </td>

                                <td
                                    class="px-4 py-3 text-sm font-medium text-gray-900 align-top text-right"
                                >
                                    {{ item.total.toLocaleString() }}đ
                                </td>

                                <td class="px-3 py-3 text-center align-top">
                                    <button
                                        @click="removeItem(i)"
                                        class="text-red-600 hover:text-red-800 text-xl"
                                    >
                                        <i class="fa-solid fa-delete-left"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-lg font-medium text-gray-900">
                        Tổng giá trị:
                        <span class="text-indigo-700 font-semibold"
                            >{{ grandTotal.toLocaleString() }} VND</span
                        >
                    </div>
                    <button
                        @click="openConfirmModal"
                        :disabled="finalItems.length === 0"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Xem trước đơn nhập
                    </button>
                </div>

                <!-- Confirm Order Modal -->
                <div
                    v-if="showConfirmModal"
                    class="fixed inset-0 z-50 flex items-center justify-center hihi"
                    @click.self="showConfirmModal = false"
                >
                    <div
                        class="bg-white rounded-lg shadow-lg w-[80%] max-h-[90vh] p-6 overflow-y-auto"
                    >
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            Xác nhận đơn nhập hàng ({{ preparedOrders.length }}
                            đơn)
                        </h2>
                        <div
                            v-for="(order, index) in preparedOrders"
                            :key="order.supplier_id"
                            class="mb-6 p-4 bg-gray-50 border border-indigo-200 rounded-lg"
                        >
                            <div class="flex justify-between items-center mb-4">
                                <span
                                    class="text-sm font-semibold text-indigo-700 ml-2"
                                    ><i
                                        class="fa-solid fa-truck text-xl mr-2"
                                    ></i>
                                    {{ order.supplier_name }}</span
                                >
                                <div class="flex items-center gap-4">
                                    <label class="text-sm text-orange-600"
                                        >Ngày dự kiến:</label
                                    >
                                    <div>
                                        <input
                                            type="date"
                                            v-model="order.expected_date"
                                            class="border border-gray-300 rounded-md p-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            :class="
                                                form.errors[
                                                    `orders.${index}.expected_date`
                                                ]
                                                    ? 'border-red-500'
                                                    : ''
                                            "
                                        />
                                        <p
                                            v-if="
                                                form.errors[
                                                    `orders.${index}.expected_date`
                                                ]
                                            "
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{
                                                form.errors[
                                                    `orders.${index}.expected_date`
                                                ]
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-indigo-50 text-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                            >
                                                Sản phẩm
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                            >
                                                Số lượng
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                            >
                                                Đơn vị
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                            >
                                                Giá nhập (VND)
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider"
                                            >
                                                Thành tiền
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-100"
                                    >
                                        <tr
                                            v-for="item in order.items"
                                            :key="item.variant_id"
                                            class="hover:bg-gray-50 transition"
                                        >
                                            <td
                                                class="px-4 py-4 text-sm text-gray-900"
                                            >
                                                {{ item.name }}
                                            </td>
                                            <td
                                                class="px-4 py-4 text-sm text-gray-700"
                                            >
                                                {{ item.quantity }}
                                            </td>
                                            <td
                                                class="px-4 py-4 text-sm text-gray-700"
                                            >
                                                {{ item.unit }}
                                            </td>
                                            <td
                                                class="px-4 py-4 text-sm text-gray-700"
                                            >
                                                {{
                                                    item.price.toLocaleString()
                                                }}đ
                                            </td>
                                            <td
                                                class="px-4 py-4 text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    item.total.toLocaleString()
                                                }}đ
                                            </td>
                                        </tr>
                                        <tr
                                            class="border-t-2 border-t-indigo-100"
                                        >
                                            <td colspan="3"></td>
                                            <td
                                                class="px-4 py-4 text-sm font-semibold bg-indigo-50 text-gray-700 text-right"
                                            >
                                                Tổng cộng:
                                            </td>
                                            <td
                                                class="px-4 py-4 text-sm bg-indigo-50 font-semibold text-gray-900"
                                            >
                                                {{
                                                    order.subtotal.toLocaleString()
                                                }}đ
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-4">
                            <button
                                @click="showConfirmModal = false"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200"
                            >
                                Hủy
                            </button>
                            <button
                                @click="submitConfirmedOrders"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                <i class="fa-solid fa-paper-plane mr-2"></i> Xác
                                nhận & Gửi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.hihi {
    background-color: rgba(97, 97, 97, 0.652);
}
</style>
