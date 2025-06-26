<template>
    <AppLayout>
        <div class="p-6">
            <!-- Flash messages -->
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
                        Thêm biến thể
                    </button>
                    <a :href="route('admin.suppliers.index')"
                        class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300">
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="relative overflow-x-auto shadow-md">
                    <table class="w-full text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300">
                            <tr>
                                <th class="px-4 py-2">Tên sản phẩm</th>
                                <th class="px-4 py-2">Danh mục</th>
                                <th class="px-4 py-2">Biến thể</th>
                                <!-- <th class="px-4 py-2">Hành động</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id"
                                class="bg-white border-b hover:bg-gray-50">
                                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                                    {{ product.name }}
                                </th>
                                <td class="px-4 py-2">{{ product.category?.name || 'N/A' }}</td>
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
                                <!-- <td class="px-4 py-2">
                                    <a :href="route('admin.products.edit', product.id)"
                                        class="text-blue-700 hover:text-blue-900">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal xem biến thể -->
            <ModalProductVariantDisplay :isOpen="isModalOpen" :variants="selectedProduct.product_variants"
                :selectedProduct="selectedProduct" @close="closeModal" />
            <!-- Modal thêm biến thể -->
            <div v-if="showAddProductModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-xl relative">
                    <h3 class="text-lg font-semibold mb-4">Thêm biến thể cho nhà cung cấp</h3>
                    <form @submit.prevent="confirmAddProduct" class="space-y-6">
                        <div class="mb-4 relative">
                            <!-- <label class="block text-sm font-medium text-indigo-700">Tìm sản phẩm</label>
                            <input type="text" v-model="searchQuery" @input="searchProducts"
                                class="w-full mt-1 border border-gray-300 rounded-md p-2"
                                placeholder="Nhập tên sản phẩm..." />
                            <ul v-if="searchResults.length && searchQuery"
                                class="absolute z-10 bg-white border border-gray-300 mt-1 w-full max-h-48 overflow-y-auto rounded shadow">
                                <li v-for="item in searchResults" :key="item.id"
                                    @click="selectProduct(item)"
                                    class="px-3 py-2 cursor-pointer hover:bg-indigo-100">
                                    {{ item.name }}
                                </li>
                            </ul>
                            <div v-if="isSearching" class="absolute right-3 top-10">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                </svg>
                            </div> -->

                            <div class="mt-2">
                                <label for="">Biến thể</label>
                                <select v-model="formAddVariant.id" name=""
                                    class="w-full p-2 border border-gray-400 rounded" id="">
                                    <option v-for="item in listVariants" :value="item.id">{{ item.product.name + " / "
                                        }}
                                        <span v-for="(att, index) in item.attributes" :key="index">
                                            <span v-if="index === 0"> {{ att.name + " - " }} </span>
                                            <span v-else-if="index === item.attributes.length - 1">{{ att.name + " / "
                                                }}
                                            </span>
                                            <span v-else>{{ att.name + " - " }}</span>
                                        </span>

                                        <span v-for="(att, index) in item.attributes">
                                            <span v-if="index === 0"> {{ att.attribute.name + " - " }} </span>
                                            <span v-else-if="index === item.attributes.length - 1">{{ att.attribute.name
                                                }}
                                            </span>
                                            <span v-else>{{ att.attribute.name + " - " }}</span>
                                        </span>

                                    </option>
                                </select>
                            </div>
                            <div class="mt-2">
                                <label for="">Số lượng</label>
                                <input type="number" v-model="formAddVariant.min_order_quantity"
                                    class="w-full p-2 border border-gray-400 rounded">

                            </div>
                            <div class="mt-2">
                                <label for="">Giá</label>

                                <input type="number" v-model="formAddVariant.cost_price"
                                    class="w-full p-2 border border-gray-400 rounded">
                            </div>
                        </div>
                        <!-- 
                        <div v-if="selectedProductVariants.length" class="space-y-4">
                            <label class="block text-sm font-medium text-indigo-700">Chọn biến thể</label>
                            <div v-for="variant in selectedProductVariants" :key="variant.id"
                                class="p-4 border border-gray-200 rounded-md">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" :value="variant.id" v-model="selectedVariantIds" class="mr-2">
                                    <span>{{ variant.attributes.map(attr => attr.value).join(', ') }}</span>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div v-if="errorMessage" class="text-red-500 text-sm">
                            {{ errorMessage }}
                        </div> -->

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="closeAddProductModal"
                                class="px-4 py-2 bg-gray-200 rounded-md text-sm hover:bg-gray-300">Hủy</button>
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
import { ref } from 'vue';
import AppLayout from "../Layouts/AppLayout.vue";
import ModalProductVariantDisplay from '../../Components/ModalProductVariantDisplay.vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const { supplier, products, listVariants } = defineProps({ supplier: Object, products: Array, listVariants: Object });
console.log(listVariants);
const showAddProductModal = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const selectedProductVariants = ref([]);
const selectedVariantIds = ref([]);
const isSearching = ref(false);
const isAdding = ref(false);
const errorMessage = ref('');
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
    searchQuery.value = '';
    searchResults.value = [];
    selectedProductVariants.value = [];
    selectedVariantIds.value = [];
    selectedProductId.value = null;
    errorMessage.value = '';
};

const formAddVariant = useForm({
    id: "",
    cost_price: "",
    min_order_quantity: ""
})

const confirmAddProduct = () => {
    formAddVariant.post(route('admin.suppliers.products.store', {
        supplierId: supplier.id
    }), {
        onSuccess: () => {
            showAddProductModal.value = false;
            formAddVariant.reset();
        }
    });
};



// ✅ Đã sửa để luôn gọi API lấy product_variants có attributes đầy đủ
const showVariants = async (product) => {



    selectedProduct.value = {
        ...product,
        product_variants: product || {}
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