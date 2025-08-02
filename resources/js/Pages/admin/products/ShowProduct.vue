<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <!-- Header -->
            <div class="flex justify-between items-center bg-white p-4 rounded shadow mb-6">
                <div>
                    <h1 class="text-xl font-bold text-indigo-700">Chi tiết sản phẩm</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="font-semibold">Mã sản phẩm:</span> {{ product.code || 'N/A' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <Waiting route-name="admin.products.index" :route-params="{}"
                        class="btn bg-indigo-100 text-indigo-700">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </Waiting>
                    <button @click="printBarcode"
                        class="btn bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded flex items-center gap-2">
                        <i class="fas fa-barcode"></i>
                        In mã vạch
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Cột trái: Thông tin chính -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Thông tin sản phẩm -->
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Thông tin cơ bản</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 font-medium">Tên sản phẩm</p>
                                <p class="font-semibold mt-1">{{ product.name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium">Danh mục</p>
                                <p class="font-semibold mt-1">{{ product.category?.name || 'Không có' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium">Tồn kho tối thiểu</p>
                                <p class="font-semibold mt-1">{{ formatCurrency(product.min_stock) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium">Đơn vị cơ bản</p>
                                <p class="font-semibold mt-1">
                                    {{ product.default_unit?.name }} ({{ product.default_unit?.symbol }})
                                </p>
                            </div>
                            <!-- <div>
                                <p class="text-gray-500 font-medium">Ngày sản xuất</p>
                                <p class="font-semibold mt-1">
                                    {{ product.production_date ? formatDate(product.production_date) : '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium">Ngày hết hạn</p>
                                <p class="font-semibold mt-1">
                                    {{ product.expiration_date ? formatDate(product.expiration_date) : '—' }}
                                </p>
                            </div> -->
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-500 font-medium">Mô tả</p>
                            <p class="text-gray-700 mt-1 whitespace-pre-line">{{ product.description }}</p>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="bg-white p-6 rounded shadow" v-if="product.images?.length">
                        <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Hình ảnh sản phẩm</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            <img v-for="(img, i) in product.images" :key="i" :src="`/storage/${img.path}`"
                                class="w-full h-32 object-cover rounded border" />
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Thông tin phụ -->
                <div class="space-y-6">
                    <!-- Tồn kho -->
                    <div class="bg-white p-6 rounded shadow">
                        <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Tình trạng tồn kho</h2>
                        <p class="text-sm text-gray-500">Tổng số lượng</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ formatCurrency(totalQuantity) }} {{ product.default_unit?.symbol || '' }}
                        </p>
                        <p class="mt-3 text-sm text-green-600 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Còn hàng
                        </p>
                    </div>

                    <!-- Nhà cung cấp -->
                    <div class="bg-white p-6 rounded shadow" v-if="product.supplier_variants?.length">
                        <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Nhà cung cấp</h2>
                        <div v-for="(sv, i) in product.supplier_variants" :key="i" class="flex items-center mb-3">
                            <div class="h-10 w-10 bg-indigo-100 rounded-full flex justify-center items-center">
                                <i class="fas fa-store text-indigo-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium">{{ sv.supplier?.name }}</p>
                                <p class="text-sm text-gray-500">{{ sv.supplier?.phone || '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vị trí kho -->
                    <div class="bg-white p-6 rounded shadow" v-if="product.inventory_locations?.length">
                        <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Vị trí kho</h2>
                        <div v-for="(loc, i) in product.inventory_locations" :key="i" class="flex items-center mb-3">
                            <div class="ml-3">
                                <p class="font-medium">Khu Vực: {{ loc.zone?.name }}</p>
                                <p class="text-sm text-gray-500">Ghi chú: {{ loc.custom_location_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin bán hàng -->
            <div class="mt-6 bg-white p-6 rounded shadow" v-if="product.product_variants?.length">
                <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Thông tin bán hàng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Giá bán</p>
                        <p class="mt-1 text-lg font-bold text-indigo-600">
                            {{ formatCurrency(product.product_variants[0]?.sale_price) }}đ
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Mã vạch</p>
                        <p class="mt-1 text-sm font-medium">{{ product.product_variants[0]?.barcode || '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Trạng thái tồn kho</p>
                        <p class="mt-1 text-sm font-medium text-green-600">
                            <i class="fas fa-check-circle mr-1"></i> Còn hàng
                        </p>
                    </div>
                </div>
            </div>
            <!-- Biến thể sản phẩm -->
            <!-- Biến thể sản phẩm -->
            <div class="mt-6 bg-white p-6 rounded shadow" v-if="product.product_variants?.length">
                <h2 class="text-md font-semibold text-indigo-700 mb-4 border-b pb-2">Danh sách biến thể</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã Biến thể</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mã vạch</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giá bán</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thuộc tính</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tồn kho</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vị trí</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(variant, i) in product.product_variants" :key="variant.id"
                                class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ variant.code || '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ variant.barcode || '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                    {{ variant.sale_price ? parseFloat(variant.sale_price).toLocaleString('vi-VN') : '—'
                                    }} ₫
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    <div class="flex flex-wrap gap-1">
                                        <span v-for="(attr, j) in variant.attributes" :key="j"
                                            class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded">
                                            {{ attr.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium"
                                    :class="{ 'text-green-600': variant.inventory?.[0]?.quantity_on_hand > 0, 'text-black-500': !variant.inventory?.[0]?.quantity_on_hand }">
                                    {{ formatCurrency(variant.inventory.quantity_on_hand) ?? 0 }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    <span v-if="variant.inventory_locations?.length">
                                        {{ variant.inventory_locations[0].zone?.name || '' }}
                                        <span v-if="variant.inventory_locations[0].custom_location_name">
                                            - {{ variant.inventory_locations[0].custom_location_name }}
                                        </span>
                                    </span>
                                    <span v-else>—</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { computed } from 'vue';

const props = defineProps({ product: Object });

const totalQuantity = computed(() =>
    props.product?.product_variants?.reduce((sum, pv) => sum + (pv.inventory?.quantity_on_hand || 0), 0)
);

// const formatDate = (dateStr) => {
//     const d = new Date(dateStr);
//     return d.toLocaleDateString('vi-VN');
// };
const printBarcode = () => {
    const url = route('admin.products.print_barcode', { product_id: props.product.id });
    window.open(url, '_blank');
};

const formatCurrency = (value) => {
    if (!value) return '0 ₫';
    return Number(value).toLocaleString('vi-VN');
};
</script>

<style scoped></style>
