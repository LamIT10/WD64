<script setup>
import { ref, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import AppLayout from "../Layouts/AppLayout.vue";
import { route } from "ziggy-js";
import axios from "axios";
const { products } = defineProps(["products"]);
const selectOptions = computed(() =>
    products.map((p) => ({
        label: p.name,
        value: p.id,
    }))
);
const variantOfProducts = ref([]);

const selectedProduct = ref(null);
const showModal = ref(false);
const variantOptions = ref([]);
const checkedVariants = ref([]);
const selectedVariants = ref([]);

function onProductSelect(productId) {
    const url = route("admin.purchases.getVariants", { id: productId });
    axios
        .get(url)
        .then((response) => {
            variantOfProducts.value = response.data;

            variantOptions.value = variantOfProducts.value.product_variants.map(
                (v) => ({
                    id: v.id,
                    label: `${variantOfProducts.value.name} - ${v.attributes
                        .map((attr) => attr.name)
                        .join(" - ")}`,
                    value: v.id,
                })
            );

            checkedVariants.value = [...variantOptions.value];
            showModal.value = true;
        })
        .catch((error) => {
            console.error("Lỗi lấy biến thể:", error);
        });
}

function toggleAllVariants(state) {
    checkedVariants.value = state ? [...variantOptions.value] : [];
}

function confirmVariants() {
    checkedVariants.value.forEach((v) => {
        console.log(v.id);
    });

    if (checkedVariants.value.length === 0) return;

    selectedVariants.value = checkedVariants.value.map((v) => ({
        name: v.label,
        vendor: "",
        quantity: 1,
        unit: "Cái",
        price: 0,
        total: 0,
    }));

    showModal.value = false;
}

function calculateTotal(variant) {
    variant.total = variant.quantity * variant.price;
}

const finalItems = ref([]);
function addToList() {
    finalItems.value.push(
        ...selectedVariants.value.filter(
            (item) => item.vendor && item.quantity > 0 && item.price >= 0
        )
    );
    selectedVariants.value = [];
}

function removeItem(index) {
    finalItems.value.splice(index, 1);
}

const grandTotal = computed(() => {
    return finalItems.value.reduce((sum, item) => sum + item.total, 0);
});
</script>

<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-50 p-6">
            <div class="mx-auto bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    Tạo yêu cầu nhập hàng
                </h2>

                <!-- Product Selection -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Chọn sản phẩm</label
                    >
                    <Multiselect
                        v-model="selectedProduct"
                        :options="selectOptions"
                        placeholder="Tìm kiếm hoặc chọn sản phẩm"
                        label="label"
                        track-by="value"
                        :searchable="true"
                        :close-on-select="true"
                        :multiple="false"
                        @select="onProductSelect"
                        class="focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                <!-- Selected Variants Table -->
                <div class="mb-12">
                    <div
                        class="overflow-x-auto border border-gray-200 rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Biến thể
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nhà cung cấp
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Số lượng
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Đơn vị
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Giá tiền (VND)
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Thành tiền (VND)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-if="!selectedVariants.length"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        colspan="6"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
                                    >
                                        Chưa có biến thể nào được chọn
                                    </td>
                                </tr>
                                <tr
                                    v-for="(variant, i) in selectedVariants"
                                    :key="i"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ variant.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select
                                            v-model="variant.vendor"
                                            class="block w-full pl-3 pr-10 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                        >
                                            <option disabled value="">
                                                Chọn nhà cung cấp
                                            </option>
                                            <option>Nhà cung cấp A</option>
                                            <option>Nhà cung cấp B</option>
                                            <option>Nhà cung cấp C</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input
                                            type="number"
                                            v-model.number="variant.quantity"
                                            @input="calculateTotal(variant)"
                                            min="1"
                                            class="block w-full pl-3 pr-10 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                        />
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ variant.unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input
                                            type="number"
                                            v-model.number="variant.price"
                                            @input="calculateTotal(variant)"
                                            min="0"
                                            step="1000"
                                            class="block w-full pl-3 pr-10 py-2 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                        />
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"
                                    >
                                        {{ variant.total.toLocaleString() }}
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
                                    !v.vendor || v.quantity <= 0 || v.price < 0
                            )
                        "
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Thêm vào danh sách
                    </button>
                </div>

                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center hihi"
                    @click.self="showModal = false"
                >
                    <div
                        class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4"
                    >
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Chọn biến thể cho sản phẩm
                            </h3>
                            <div class="flex justify-between mb-4">
                                <button
                                    @click="toggleAllVariants(true)"
                                    class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none"
                                >
                                    Chọn tất cả
                                </button>
                                <button
                                    @click="toggleAllVariants(false)"
                                    class="text-sm text-red-600 hover:text-red-800 focus:outline-none"
                                >
                                    Bỏ chọn tất cả
                                </button>
                            </div>
                            <div
                                class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-2"
                            >
                                <div
                                    v-for="v in variantOptions"
                                    :key="v.id"
                                    class="flex items-center mb-2 last:mb-0"
                                >
                                    <input
                                        type="checkbox"
                                        :id="`variant-${v.id}`"
                                        :value="v"
                                        v-model="checkedVariants"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 rounded"
                                    />
                                    <label
                                        :for="`variant-${v.id}`"
                                        class="ml-2 block text-sm text-gray-700"
                                    >
                                        {{ v.label }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 px-6 py-3 flex justify-end gap-3 rounded-lg"
                        >
                            <button
                                @click="showModal = false"
                                class="px-4 py-2 bg-white text-gray-700 rounded-md border hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Hủy bỏ
                            </button>
                            <button
                                @click="confirmVariants"
                                :disabled="checkedVariants.length === 0"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Xác nhận
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-auto bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    Danh sách sản phẩm đã chọn
                </h3>
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Biến thể
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Nhà cung cấp
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Số lượng
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Đơn vị
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Giá tiền
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Thành tiền
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                ></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-if="!finalItems.length"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    colspan="7"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
                                >
                                    Chưa có sản phẩm nào được thêm
                                </td>
                            </tr>
                            <tr
                                v-for="(item, i) in finalItems"
                                :key="i"
                                class="hover:bg-gray-50"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ item.name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ item.vendor }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ item.quantity }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ item.unit }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ item.price.toLocaleString() }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium"
                                >
                                    {{ item.total.toLocaleString() }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                >
                                    <button
                                        @click="removeItem(i)"
                                        class="text-red-600 hover:text-red-900 focus:outline-none"
                                    >
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-lg font-medium text-gray-900">
                        Tổng cộng:
                        <span class="font-semibold"
                            >{{ grandTotal.toLocaleString() }} VND</span
                        >
                    </div>
                    <button
                        class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        Tạo yêu cầu nhập hàng
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.multiselect {
    border-radius: 0.375rem;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    border: 1px solid #d1d5db;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    outline: none;
}

.multiselect:focus,
.multiselect:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.multiselect-search {
    font-size: 0.875rem;
}

.multiselect-placeholder {
    color: #9ca3af;
}

.multiselect-single-label {
    color: #374151;
}

.multiselect-option {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    cursor: pointer;
}

.multiselect-option.is-selected {
    background-color: #eff6ff;
    color: #1e3a8a;
}

.multiselect-option.is-pointed {
    background-color: #f3f4f6;
}
.hihi {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
