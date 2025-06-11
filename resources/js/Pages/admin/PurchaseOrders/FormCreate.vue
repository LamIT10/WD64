<template>
    <AppLayout>
        <div class="">
            <div class="container mx-auto p-4 sm:p-6">
                <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Tạo mới đơn nhập kho
                </h5>
                <Waiting
                    route-name="admin.suppliers.index"
                    :route-params="{}"
                    :color="'bg-indigo-50 text-indigo-700'"
                >
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

                <!-- Form nhập sản phẩm -->
                <div
                    class="bg-white shadow rounded p-6 mb-8 border border-gray-100"
                >
                    <!-- Ô chọn sản phẩm -->
                    <div class="mb-6">
                        <label
                            for="product"
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Sản phẩm</label
                        >
                        <select
                            v-model="selectedProduct"
                            id="product"
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                        >
                            <option value="">Chọn sản phẩm</option>
                            <option
                                v-for="product in products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Biến thể và nhà cung cấp -->
                    <div v-if="selectedProduct && variants.length" class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-3"
                            >Biến thể và Nhà cung cấp</label
                        >
                        <div id="variant-options" class="space-y-4">
                            <div
                                v-for="(variant, index) in variants"
                                :key="variant.id"
                                class="border border-gray-200 rounded-lg p-4 flex justify-between items-center"
                            >
                                <div class="flex items-center">
                                    <input
                                        type="radio"
                                        v-model="selectedVariant"
                                        :value="variant.id"
                                        class="mr-3"
                                    />
                                    <span class="font-medium text-gray-900">{{
                                        variant.name
                                    }}</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <select
                                        v-model="selectedSuppliers[variant.id]"
                                        :name="'supplier-' + variant.id"
                                        class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">
                                            Chọn nhà cung cấp
                                        </option>
                                        <option
                                            v-for="supplier in variant.suppliers"
                                            :key="supplier.id"
                                            :value="supplier.id"
                                        >
                                            {{ supplier.name }} (Tồn kho:
                                            {{ supplier.stock }})
                                        </option>
                                    </select>
                                    <button
                                        @click="removeVariant(index)"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Số lượng, giá, thành tiền -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label
                                for="quantity"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Số lượng</label
                            >
                            <input
                                v-model.number="quantity"
                                type="number"
                                id="quantity"
                                min="1"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nhập số lượng"
                            />
                        </div>
                        <div>
                            <label
                                for="price"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Giá (VND)</label
                            >
                            <input
                                v-model.number="price"
                                type="number"
                                id="price"
                                min="0"
                                step="1000"
                                class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nhập giá"
                            />
                        </div>
                        <div>
                            <label
                                for="total"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Thành tiền (VND)</label
                            >
                            <input
                                v-model="total"
                                readonly
                                class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-900"
                                value="0"
                            />
                        </div>
                    </div>

                    <!-- Nút thêm sản phẩm -->
                    <div>
                        <button
                            @click="addProduct"
                            :disabled="!isFormValid"
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            Thêm sản phẩm
                        </button>
                    </div>
                </div>

                <!-- Danh sách sản phẩm đã chọn -->
                <div
                    class="bg-white shadow rounded p-4"
                >
                    <h5 class="underline-custom text-lg text-indigo-700 font-semibold">
                    Danh sách sản phẩm đã chọn
                </h5>
                    <div id="selected-products" class="space-y-4">
                        <div
                            v-for="(product, index) in selectedProducts"
                            :key="index"
                            class="border border-gray-200 rounded-lg p-4 flex justify-between items-center bg-gray-50"
                        >
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ product.name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Biến thể: {{ product.variantName }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Nhà cung cấp: {{ product.supplierName }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Số lượng: {{ product.quantity }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Giá:
                                    {{
                                        Number(product.price).toLocaleString()
                                    }}
                                    VND
                                </p>
                                <p class="text-sm text-gray-600">
                                    Thành tiền:
                                    {{
                                        (
                                            product.quantity * product.price
                                        ).toLocaleString()
                                    }}
                                    VND
                                </p>
                            </div>
                            <button
                                @click="removeSelectedProduct(index)"
                                class="text-red-600 hover:text-red-800"
                            >
                                Xóa
                            </button>
                        </div>
                        <p
                            v-if="!selectedProducts.length"
                            id="no-products"
                            class="text-gray-500 text-center"
                        >
                            Chưa có sản phẩm nào được thêm.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";

// Dữ liệu tĩnh
const products = [
    { id: "1", name: "Sản phẩm A" },
    { id: "2", name: "Sản phẩm B" },
    { id: "3", name: "Sản phẩm C" },
];
const productVariants = {
    1: [
        {
            id: "1-1",
            name: "Màu đỏ",
            suppliers: [
                { id: "s1", name: "Nhà cung cấp 1", stock: 50 },
                { id: "s2", name: "Nhà cung cấp 2", stock: 30 },
            ],
        },
        {
            id: "1-2",
            name: "Màu xanh",
            suppliers: [
                { id: "s2", name: "Nhà cung cấp 2", stock: 40 },
                { id: "s3", name: "Nhà cung cấp 3", stock: 20 },
            ],
        },
    ],
    2: [
        {
            id: "2-1",
            name: "Size S",
            suppliers: [
                { id: "s1", name: "Nhà cung cấp 1", stock: 60 },
                { id: "s3", name: "Nhà cung cấp 3", stock: 25 },
            ],
        },
        {
            id: "2-2",
            name: "Size M",
            suppliers: [{ id: "s2", name: "Nhà cung cấp 2", stock: 50 }],
        },
    ],
    3: [
        {
            id: "3-1",
            name: "Loại A",
            suppliers: [{ id: "s3", name: "Nhà cung cấp 3", stock: 15 }],
        },
        {
            id: "3-2",
            name: "Loại B",
            suppliers: [
                { id: "s1", name: "Nhà cung cấp 1", stock: 35 },
                { id: "s2", name: "Nhà cung cấp 2", stock: 45 },
            ],
        },
    ],
};

// Dữ liệu reactive
const selectedProduct = ref("");
const selectedVariant = ref("");
const selectedSuppliers = ref({});
const quantity = ref(0);
const price = ref(0);
const total = ref("0");
const selectedProducts = ref([]);

// Computed properties
const variants = computed(() =>
    selectedProduct.value ? productVariants[selectedProduct.value] : []
);
const isFormValid = computed(() => {
    const hasVariant = variants.value.length > 0;
    return (
        selectedProduct.value &&
        quantity.value > 0 &&
        price.value > 0 &&
        (!hasVariant ||
            (selectedVariant.value &&
                selectedSuppliers.value[selectedVariant.value]))
    );
});

// Watchers
watch(selectedProduct, () => {
    selectedVariant.value = "";
    selectedSuppliers.value = {};
});
watch([quantity, price], () => {
    const totalValue = quantity.value * price.value;
    total.value = totalValue.toLocaleString();
});

// Methods
const removeVariant = (index) => {
    variants.value.splice(index, 1);
    if (!variants.value.length) {
        selectedVariant.value = "";
        selectedSuppliers.value = {};
    }
};

const addProduct = () => {
    const product = products.find((p) => p.id === selectedProduct.value);
    const variant = variants.value.find((v) => v.id === selectedVariant.value);
    const supplier = variant.suppliers.find(
        (s) => s.id === selectedSuppliers.value[selectedVariant.value]
    );
    selectedProducts.value.push({
        name: product.name,
        variantName: variant.name,
        supplierName: supplier.name,
        quantity: quantity.value,
        price: price.value,
    });
    resetForm();
};

const removeSelectedProduct = (index) => {
    selectedProducts.value.splice(index, 1);
};

const resetForm = () => {
    selectedProduct.value = "";
    selectedVariant.value = "";
    selectedSuppliers.value = {};
    quantity.value = 0;
    price.value = 0;
    total.value = "0";
};
</script>

<style scoped>
h5.underline-custom {
  display: inline-block; /* để giới hạn theo nội dung chữ */
  position: relative;
}

h5.underline-custom::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -6px;
  height: 2px;
  width: 50%;
  background: linear-gradient(45deg, #2010ff, #ae00ff);
}
</style>
