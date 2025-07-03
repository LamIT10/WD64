<template>
    <AppLayout>
        <div class="p-6">
            <!-- Flash messages -->

            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Sản phẩm của nhà cung cấp: {{ supplier.name }}
                </h5>
                <div class="flex items-center space-x-3">
                    <button
                        @click="openAddProductModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700"
                    >
                        Thêm biến thể
                    </button>
                    <Link
                        :href="route('admin.suppliers.index')"
                        class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300"
                    >
                        Quay lại
                    </Link>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-left text-gray-600">
                        <thead
                            class="text-xs text-gray-700 bg-blue-50 border-b border-blue-200"
                        >
                            <tr>
                                <th class="px-6 py-3 font-semibold">
                                    Tên sản phẩm
                                </th>
                                <th class="px-6 py-3 font-semibold">
                                    Danh mục
                                </th>
                                <th class="px-6 py-3 font-semibold">
                                    Biến thể
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150"
                            >
                                <th
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ product.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ product.category?.name || "N/A" }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{
                                                product.product_variants
                                                    ?.length || 0
                                            }}
                                            biến thể
                                        </span>
                                        <button
                                            v-if="
                                                product.product_variants
                                                    ?.length > 0
                                            "
                                            class="text-blue-600 hover:text-blue-800 transition-colors duration-150"
                                            @click="showVariants(product)"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal xem biến thể -->
            <ModalProductVariantDisplay
                :isOpen="isModalOpen"
                :variants="selectedProduct.product_variants"
                :selectedProduct="selectedProduct"
                @close="closeModal"
            />
            <!-- Modal thêm biến thể -->
            <div
                v-if="showAddProductModal"
                class="fixed inset-0 backdrop-blur-sm bg-opacity-20 flex items-center justify-center z-50 transition-opacity duration-300"
            >
                <div
                    class="bg-white p-6 rounded-xl w-full max-w-xl relative shadow-lg border border-indigo-50"
                >
                    <h3 class="text-xl font-medium mb-6 text-indigo-900">
                        Thêm biến thể cho nhà cung cấp
                    </h3>
                    <form @submit.prevent="confirmAddProduct" class="space-y-5">
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-normal text-indigo-700 mb-1"
                                    >Biến thể</label
                                >
                                <span
                                    class="text-red-500"
                                    v-if="formAddVariant.errors.id"
                                    >{{ formAddVariant.errors.id }}</span
                                >
                                <select
                                    v-model="formAddVariant.id"
                                    class="w-full p-2.5 text-sm border border-indigo-100 rounded-lg focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 bg-indigo-50/30"
                                >
                                    <option
                                        v-for="item in listVariants"
                                        :value="item.id"
                                    >
                                        {{ item.product.name + " / " }}
                                        <span
                                            v-for="(
                                                att, index
                                            ) in item.attributes"
                                            :key="index"
                                        >
                                            <span v-if="index === 0">
                                                {{ att.name + " - " }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    index ===
                                                    item.attributes.length - 1
                                                "
                                                >{{ att.name + " / " }}</span
                                            >
                                            <span v-else>{{
                                                att.name + " - "
                                            }}</span>
                                        </span>
                                        <span
                                            v-for="(
                                                att, index
                                            ) in item.attributes"
                                        >
                                            <span v-if="index === 0">
                                                {{ att.attribute.name + " - " }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    index ===
                                                    item.attributes.length - 1
                                                "
                                                >{{ att.attribute.name }}</span
                                            >
                                            <span v-else>{{
                                                att.attribute.name + " - "
                                            }}</span>
                                        </span>
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-normal text-indigo-700 mb-1"
                                    >Số lượng đặt tối thiểu</label
                                >
                                <span
                                    class="text-red-500"
                                    v-if="
                                        formAddVariant.errors.min_order_quantity
                                    "
                                    >{{
                                        formAddVariant.errors.min_order_quantity
                                    }}</span
                                >
                                <input
                                    type="number"
                                    v-model="formAddVariant.min_order_quantity"
                                    class="w-full p-2.5 text-sm border border-indigo-100 rounded-lg focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 bg-indigo-50/30"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-normal text-indigo-700 mb-1"
                                    >Giá vốn</label
                                >
                                <span
                                    class="text-red-500"
                                    v-if="formAddVariant.errors.cost_price"
                                    >{{
                                        formAddVariant.errors.cost_price
                                    }}</span
                                >
                                <input
                                    type="number"
                                    v-model="formAddVariant.cost_price"
                                    class="w-full p-2.5 text-sm border border-indigo-100 rounded-lg focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 bg-indigo-50/30"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-2">
                            <button
                                type="button"
                                @click="closeAddProductModal"
                                class="px-4 py-2 text-sm text-indigo-700 hover:text-indigo-900 rounded-lg hover:bg-indigo-50 transition-colors duration-200"
                            >
                                Hủy
                            </button>
                            <button
                                type="submit"
                                :disabled="isAdding"
                                class="px-4 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center"
                            >
                                <span v-if="isAdding" class="flex items-center">
                                    <svg
                                        class="animate-spin mr-2 h-4 w-4 text-indigo-600"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    Đang xử lý...
                                </span>
                                <span v-else>Thêm biến thể</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import ModalProductVariantDisplay from "../../Components/ModalProductVariantDisplay.vue";
import axios from "axios";
import { Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { formatNumber } from "chart.js/helpers";

const { supplier, products, listVariants } = defineProps({
    supplier: Object,
    products: Array,
    listVariants: Object,
});
console.log(listVariants);
const showAddProductModal = ref(false);
const searchQuery = ref("");
const searchResults = ref([]);
const selectedProductVariants = ref([]);
const selectedVariantIds = ref([]);
const isSearching = ref(false);
const isAdding = ref(false);
const errorMessage = ref("");
const selectedProductId = ref(null);

const isModalOpen = ref(false);
const selectedProduct = ref({});

const openAddProductModal = () => {
    showAddProductModal.value = true;
    resetForm();
};

const closeAddProductModal = () => {
    showAddProductModal.value = false;
    resetForm();
};

const resetForm = () => {
    searchQuery.value = "";
    searchResults.value = [];
    selectedProductVariants.value = [];
    selectedVariantIds.value = [];
    selectedProductId.value = null;
    errorMessage.value = "";
};

const formAddVariant = useForm({
    id: "",
    cost_price: "",
    min_order_quantity: "",
});

const confirmAddProduct = () => {
    formAddVariant.post(
        route("admin.suppliers.products.store", {
            supplierId: supplier.id,
        }),
        {
            onSuccess: () => {
                showAddProductModal.value = false;
                formAddVariant.reset();
            },
        }
    );
};

// ✅ Đã sửa để luôn gọi API lấy product_variants có attributes đầy đủ
const showVariants = (product) => {
    selectedProduct.value = {
        ...product,
        product_variants: product || {},
    };

    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = {};
};
</script>

<style scoped>
::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c4c4c4;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}
</style>
