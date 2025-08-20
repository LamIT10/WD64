```vue
<template>
    <AppLayout>
        <div class="p-6">
            <!-- Flash messages -->
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Sản phẩm của nhà cung cấp: {{ supplier.name }}
                </h5>
                <div class="flex items-center space-x-3">
                    <button @click="openAddProductModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Thêm biến thể
                    </button>
                    <Link :href="route('admin.suppliers.index')"
                        class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300">
                    Quay lại
                    </Link>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="relative overflow-x-auto rounded-lg shadow-sm">
                    <table class="w-full text-left text-gray-600">
                        <thead class="text-xs text-gray-700 bg-blue-50 border-b border-blue-200">
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
                            <tr v-for="product in products" :key="product.id"
                                class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                                <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ product.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ product.category?.name || "N/A" }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{
                                                product.product_variants
                                                    ?.length || 0
                                            }}
                                            biến thể
                                        </span>
                                        <button v-if="
                                            product.product_variants
                                                ?.length > 0
                                        " class="text-blue-600 hover:text-blue-800 transition-colors duration-150"
                                            @click="showVariants(product)">
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
            <ModalProductVariantDisplay :isOpen="isModalOpen" :variants="selectedProduct.product_variants"
                :selectedProduct="selectedProduct" :supplier="supplier" @close="closeModal" />
            <!-- Modal thêm biến thể -->
            <div v-if="showAddProductModal"
                class="fixed inset-0 backdrop-blur-sm bg-opacity-20 flex items-center justify-center z-50 transition-opacity duration-300">
                <div class="bg-white p-6 rounded-xl w-full max-w-xl relative shadow-lg border border-indigo-50">
                    <h3 class="text-xl font-medium mb-6 text-indigo-900">
                        Thêm biến thể cho nhà cung cấp
                    </h3>
                    <form @submit.prevent="confirmAddProduct" class="space-y-5">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-normal text-indigo-700 mb-1">Biến thể</label>
                                <span class="text-red-500" v-if="formAddVariant.errors.id">{{ formAddVariant.errors.id
                                }}</span>
                                <Multiselect v-model="formAddVariant.id" :options="formattedVariants" :searchable="true"
                                    :placeholder="'Chọn biến thể'" label="displayName" valueProp="id"
                                    :filterResults="customFilter"
                                    class="p-2 border border-indigo-100 rounded-lg bg-indigo-50/30">
                                    <template v-slot:option="{ option }">
                                        {{ option.product.name }}
                                        <span v-for="(att, index) in option.attributes" :key="index">
                                            <span v-if="index === 0">/ {{ att.name }} </span>
                                            <span v-else-if="index === option.attributes.length - 1">-{{ att.name
                                            }}</span>
                                            <span v-else>{{ att.name + " - " }}</span>
                                        </span>
                                    </template>
                                </Multiselect>
                            </div>

                            <!-- <div>
                                <label class="block text-sm font-normal text-indigo-700 mb-1">Số lượng đặt tối
                                    thiểu</label>
                                <span class="text-red-500" v-if="formAddVariant.errors.min_order_quantity">{{
                                    formAddVariant.errors.min_order_quantity }}</span>
                                <input type="number" v-model="formAddVariant.min_order_quantity"
                                    class="w-full p-2.5 text-sm border border-indigo-100 rounded-lg focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 bg-indigo-50/30" />
                            </div> -->
                            <div>
                                <label class="block text-sm font-normal text-indigo-700 mb-1">Giá vốn</label>
                                <span class="text-red-500" v-if="formAddVariant.errors.cost_price">
                                    {{ formAddVariant.errors.cost_price }}
                                </span>
                                <input type="text" :value="formattedCostPrice" @input="handleCostPriceInput"
                                    @keypress="restrictToNumbers" class="w-full p-2.5 text-sm border border-indigo-100 rounded-lg 
               focus:ring-1 focus:ring-indigo-300 focus:border-indigo-300 bg-indigo-50/30"
                                    placeholder="Nhập giá vốn" />
                            </div>

                        </div>

                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" @click="closeAddProductModal"
                                class="px-4 py-2 text-sm text-indigo-700 hover:text-indigo-900 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                Hủy
                            </button>
                            <button type="submit" :disabled="isAdding"
                                class="px-4 py-2 text-sm bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200 flex items-center">
                                <span v-if="isAdding" class="flex items-center">
                                    <svg class="animate-spin mr-2 h-4 w-4 text-indigo-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
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
import { ref, computed } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import ModalProductVariantDisplay from "../../Components/ModalProductVariantDisplay.vue";
import axios from "axios";
import { Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Multiselect from '@vueform/multiselect';


// Props
const { supplier, products, listVariants } = defineProps({
    supplier: Object,
    products: Array,
    listVariants: Array,
});

// Transform listVariants to include displayName for Multiselect
const formattedVariants = computed(() => {
    return listVariants.map((item) => ({
        ...item,
        displayName: `${item.product.name} / ${item.attributes
            .map((att) => att.name)
            .join(" - ")}`,
    }));
});

// Custom filter for Multiselect search
const customFilter = (options, search) => {
    if (!search) return options;
    const searchLower = search.toLowerCase();
    return options.filter((option) => {
        return (
            option.displayName.toLowerCase().includes(searchLower) ||
            option.product.name.toLowerCase().includes(searchLower) ||
            option.attributes.some((attr) =>
                attr.name.toLowerCase().includes(searchLower)
            )
        );
    });
};

// State
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

// Methods
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
    formAddVariant.reset();
};

const formAddVariant = useForm({
    id: "",
    cost_price: 0,
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
                formattedCostPrice.value = 0;
            },
        }
    );
};
// Format số theo kiểu VNĐ
const formatNumber = (value) => {
    return new Intl.NumberFormat("vi-VN").format(value);
};



// Biến hiển thị giá vốn có format
const formattedCostPrice = ref(formatNumber(formAddVariant.cost_price));

// Xử lý nhập
const handleCostPriceInput = (e) => {
    const raw = e.target.value.replace(/[^\d]/g, ''); // bỏ ký tự không phải số
    if (!raw) {
        formAddVariant.cost_price = 0;
        formattedCostPrice.value = '';
        return;
    }
    const numericValue = Number(raw);
    formAddVariant.cost_price = numericValue;
    formattedCostPrice.value = formatNumber(numericValue);
};

// Chỉ cho nhập số
const restrictToNumbers = (e) => {
    const charCode = e.charCode;
    if (charCode < 48 || charCode > 57) {
        e.preventDefault();
    }
};

const showVariants = (product) => {
    selectedProduct.value = {
        ...product,
        product_variants: product.product_variants || [],
    };
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = {};
};
</script>

<style scoped>
/* Scrollbar styles */
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

/* Multiselect styles */
.multiselect {
    --ms-border-color: #e0e7ff;
    --ms-ring-color: #c7d2fe;
    --ms-bg: rgba(224, 231, 255, 0.3);
    --ms-option-bg-selected: #e0e7ff;
    --ms-option-color-selected: #4f46e5;
    --ms-dropdown-bg: #ffffff;
    --ms-border-radius: 0.5rem;
    --ms-py: 0.625rem;
    --ms-px: 0.625rem;
    --ms-font-size: 0.875rem;
    --ms-line-height: 1.25rem;
}

.multiselect.is-active {
    border-color: #c7d2fe;
    box-shadow: 0 0 0 1px #c7d2fe;
}
</style>