<template>
    <AppLayout>
        <div class="p-6">
            <!-- Thông báo flash -->
            <div v-if="$page.props.flash.success" class="p-4 mb-4 bg-green-100 text-green-700 rounded-md">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error" class="p-4 mb-4 bg-red-100 text-red-700 rounded-md">
                {{ $page.props.flash.error }}
            </div>

            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Sản phẩm của nhà cung cấp: {{ supplier.name }}
                </h5>
                <div class="flex items-center space-x-3">
                    <button @click="openAddProductModal"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                        Thêm sản phẩm
                    </button>
                    <a :href="route('admin.suppliers.index')"
                        class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300">
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="relative overflow-x-auto shadow-md">
                        <table class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300">
                                <tr>
                                    <th scope="col" class="px-4 py-2">Tên sản phẩm</th>
                                    <th scope="col" class="px-4 py-2">Danh mục</th>
                                    <th scope="col" class="px-4 py-2">Biến thể</th>
                                    <th scope="col" class="px-4 py-2">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products.data" :key="product.id"
                                    class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                        {{ product.name }}
                                    </th>
                                    <td class="px-4 py-2">
                                        {{ product.category?.name || 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-700">
                                                {{ product.product_variants?.length || 0 }} biến thể
                                            </span>
                                            <button v-if="product.product_variants?.length > 0"
                                                class="text-indigo-600 hover:text-indigo-800 text-xs"
                                                @click="showVariants(product)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <Waiting route-name="admin.products.edit" :route-params="{ id: product.id }"
                                            :color="'text-blue-700'">
                                            <i class="fas fa-edit"></i> Sửa
                                        </Waiting>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-2 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ products.from || 0 }}</span> đến
                        <span class="font-medium">{{ products.to || 0 }}</span> của
                        <span class="font-medium">{{ products.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex justify-end space-x-1 mt-4">
                        <button v-for="link in products.links" :key="link.label" v-html="link.label"
                            :disabled="!link.url" @click="$inertia.visit(link.url)" :class="[
                                'px-3 py-1 rounded-md text-sm',
                                link.active
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100',
                                !link.url && 'opacity-50 cursor-not-allowed',
                            ]"></button>
                    </div>
                </div>
            </div>

            <!-- Modal Biến thể Sản phẩm -->
            <ModalProductVariant :isOpen="isModalOpen" :variants="selectedProduct.product_variants"
                :selectedProduct="selectedProduct" @close="closeModal" />

            <!-- Modal Thêm Sản Phẩm -->
            <div v-if="showAddProductModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-xl relative">
                    <h3 class="text-lg font-semibold mb-4">Thêm sản phẩm cho nhà cung cấp</h3>
                    <form @submit.prevent="confirmAddProduct" class="space-y-6">
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-indigo-700">Chọn sản phẩm</label>
                            <input type="text" v-model="searchQuery" list="productSuggestions"
                                @input="debouncedSearchProducts"
                                class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="Nhập tên sản phẩm...">
                            <div v-if="isSearching" class="absolute right-3 top-10">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                </svg>
                            </div>
                            <datalist id="productSuggestions">
                                <option v-for="product in searchResults" :key="product.id" :value="product.name"
                                    :data-id="product.id">
                                </option>
                            </datalist>
                        </div>

                        <div v-if="selectedProductVariants.length" class="space-y-4">
                            <label class="block text-sm font-medium text-indigo-700">Chọn và cấu hình biến thể</label>
                            <div v-for="variant in selectedProductVariants" :key="variant.id"
                                class="p-4 border border-gray-200 rounded-md">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" :value="variant.id" v-model="selectedVariantIds"
                                        class="mr-2">
                                    <span>{{ variant.attributes.map(attr => attr.value).join(', ') }}</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Giá mua (VND)</label>
                                        <input v-model.number="variantPrices[variant.id].cost_price" type="number"
                                            step="0.01" placeholder="Nhập giá mua"
                                            class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Giá bán (VND)</label>
                                        <input v-model.number="variantPrices[variant.id].sale_price" type="number"
                                            step="0.01" placeholder="Nhập giá bán"
                                            class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Số lượng tối thiểu</label>
                                        <input v-model.number="variantPrices[variant.id].min_order_quantity"
                                            type="number" placeholder="Nhập số lượng"
                                            class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="errorMessage" class="text-red-500 text-sm">
                            {{ errorMessage }}
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="closeAddProductModal"
                                class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300">
                                Hủy
                            </button>
                            <button type="submit" :disabled="isAdding"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 disabled:opacity-50">
                                <span v-if="isAdding">Đang thêm...</span>
                                <span v-else>Thêm</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import debounce from 'lodash/debounce'; // Giả định lodash đã được cài
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import ModalProductVariant from '../../components/ModalProductVariant.vue';

// Props
const { supplier, products } = defineProps({
    supplier: Object,
    products: Object,
});

// State
const showAddProductModal = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const selectedProductVariants = ref([]);
const selectedVariantIds = ref([]);
const variantPrices = ref({}); // Lưu giá và số lượng cho từng variant
const isSearching = ref(false);
const isAdding = ref(false);
const errorMessage = ref('');
const isModalOpen = ref(false);
const selectedProduct = ref({});

// Methods
const showVariants = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = {};
};

const openAddProductModal = () => {
    showAddProductModal.value = true;
    resetForm();
};

const closeAddProductModal = () => {
    showAddProductModal.value = false;
    errorMessage.value = '';
};

const resetForm = () => {
    searchQuery.value = '';
    searchResults.value = [];
    selectedProductVariants.value = [];
    selectedVariantIds.value = [];
    variantPrices.value = {};
    errorMessage.value = '';
};

const searchProducts = () => {
    if (searchQuery.value.length > 2) {
        isSearching.value = true;
        $inertia.get(route('admin.products.search'), { search: searchQuery.value }, {
            preserveState: true,
            only: ['products'],
            onSuccess: (page) => {
                searchResults.value = page.props.products || [];
                isSearching.value = false;
            },
            onError: (errors) => {
                errorMessage.value = 'Lỗi khi tìm kiếm sản phẩm: ' + (errors.message || 'Không xác định');
                isSearching.value = false;
            },
        });
    } else {
        searchResults.value = [];
        selectedProductVariants.value = [];
        selectedVariantIds.value = [];
        variantPrices.value = {};
        isSearching.value = false;
    }
};

// Debounce search to reduce API calls
const debouncedSearchProducts = debounce(searchProducts, 300);

watch(searchQuery, (newValue) => {
    const selectedOption = searchResults.value.find(product => product.name === newValue);
    if (selectedOption) {
        loadVariants(selectedOption.id);
    } else {
        selectedProductVariants.value = [];
        selectedVariantIds.value = [];
        variantPrices.value = {};
    }
});

const loadVariants = (productId) => {
    if (productId) {
        isSearching.value = true;
        $inertia.get(route('admin.products.variants', productId), {}, {
            preserveState: true,
            only: ['variants'],
            onSuccess: (page) => {
                selectedProductVariants.value = page.props.variants || [];
                selectedVariantIds.value = [];
                variantPrices.value = selectedProductVariants.value.reduce((acc, variant) => {
                    acc[variant.id] = { cost_price: null, sale_price: null, min_order_quantity: null };
                    return acc;
                }, {});
                isSearching.value = false;
            },
            onError: (errors) => {
                errorMessage.value = 'Lỗi khi tải biến thể sản phẩm: ' + (errors.message || 'Không xác định');
                isSearching.value = false;
            },
        });
    }
};

const confirmAddProduct = () => {
    if (selectedVariantIds.value.length === 0) {
        errorMessage.value = 'Vui lòng chọn ít nhất một biến thể';
        return;
    }

    const invalidVariants = selectedVariantIds.value.filter(variantId => {
        const prices = variantPrices.value[variantId];
        return !prices.cost_price || !prices.sale_price || !prices.min_order_quantity || prices.min_order_quantity < 0;
    });

    if (invalidVariants.length > 0) {
        errorMessage.value = 'Vui lòng nhập đầy đủ và hợp lệ giá mua, giá bán, và số lượng tối thiểu cho các biến thể đã chọn.';
        return;
    }

    if (confirm('Bạn có chắc chắn muốn thêm các sản phẩm này cho nhà cung cấp?')) {
        addProductToSupplier();
    }
};

const addProductToSupplier = () => {
    isAdding.value = true;
    const data = {
        variant_ids: selectedVariantIds.value,
        variant_details: selectedVariantIds.value.map(variantId => ({
            id: variantId,
            cost_price: variantPrices.value[variantId].cost_price,
            sale_price: variantPrices.value[variantId].sale_price,
            min_order_quantity: variantPrices.value[variantId].min_order_quantity,
        })),
    };
    $inertia.post(route('admin.suppliers.addProduct', supplier.id), data, {
        preserveState: true,
        onSuccess: () => {
            closeAddProductModal();
            isAdding.value = false;
            $page.props.flash.success = 'Thêm sản phẩm thành công!';
        },
        onError: (errors) => {
            errorMessage.value = 'Lỗi khi thêm sản phẩm: ' + (errors.variant_ids?.[0] || errors.message || 'Không xác định');
            isAdding.value = false;
        },
    });
};
</script>

<style lang="css" scoped>
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