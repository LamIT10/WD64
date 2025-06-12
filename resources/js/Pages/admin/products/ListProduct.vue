<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header với các bộ lọc -->
            <div class="p-3 bg-white mb-4 flex justify-between items-center rounded-lg shadow-sm">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Sản phẩm
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Filter Dropdown -->
                    <select
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả danh mục</option>
                        <option value="1">Điện thoại</option>
                        <option value="2">Laptop</option>
                        <option value="3">Phụ kiện</option>
                    </select>

                    <!-- Stock Status Filter -->
                    <select
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả trạng thái</option>
                        <option value="low_stock">Sắp hết hàng</option>
                        <option value="out_of_stock">Hết hàng</option>
                        <option value="expiring">Sắp hết hạn</option>
                    </select>

                    <!-- Search bar -->
                    <div class="relative">
                        <input type="text" placeholder="Tìm kiếm theo tên, mã..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <Waiting route-name="admin.products.create" :route-params="{}"
                        :color="'bg-indigo-600 hover:bg-indigo-700 text-white'">
                        <i class="fas fa-plus mr-1"></i> Thêm mới
                    </Waiting>
                </div>
            </div>

            <!-- Container cho bảng với thanh cuộn ngang -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto scrollbar-custom">
                    <table class="w-full text-left text-gray-500 min-w-max">
                        <thead class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-200">
                            <tr>
                                <th scope="col" class="p-4 sticky left-0 bg-indigo-50 z-10">
                                    <div class="flex items-center">
                                        <input id="checkbox-all-search" type="checkbox"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500" />
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 sticky left-12 bg-indigo-50 z-10">
                                    <div class="flex items-center space-x-1 table-cell-nowrap">
                                        <i class="fas fa-box text-indigo-500"></i>
                                        <span>Sản phẩm</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-barcode text-indigo-500"></i>
                                        <span>Mã SP</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-dollar-sign text-green-500"></i>
                                        <span>Giá nhập/Giá bán</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-warehouse text-blue-500"></i>
                                        <span>Tồn kho </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-chart-line text-yellow-500"></i>
                                        <span>Trạng thái</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-sitemap text-purple-500"></i>
                                        <span>Biến thể</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-calendar text-red-500"></i>
                                        <span>Ngày sản xuất</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-calendar-times text-red-500"></i>
                                        <span>Ngày hết hạn</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-boxes text-blue-500"></i>
                                        <span>Vị trí</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-tools text-gray-500"></i>
                                        <span>Hành động</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in products.data" :key="index"
                                class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="w-4 p-4 sticky left-0 bg-white z-10">
                                    <div class="flex items-center">
                                        <input :id="'checkbox-' + product.id" type="checkbox"
                                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500" />
                                    </div>
                                </td>
                                <td class="px-4 py-3 sticky left-12 bg-white z-10">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <img :src="product.images.length ? product.images[0].url : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTt3BNhyW0blq30vL0Hd72oIJfwYSUjj6_hzg&s'"
                                                class="w-12 h-12 object-cover rounded-lg border border-gray-200"
                                                :alt="product.name" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-medium text-gray-900 truncate" :title="product.name">
                                                {{ product.name }}
                                            </div>
                                            <div class="text-sm text-gray-500 truncate" :title="product.category?.name">
                                                {{ product.category?.name || 'Không có danh mục' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Mã sản phẩm -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 font-mono">
                                        {{ product.code || 'N/A' }}
                                    </span>
                                </td>

                                <!-- Giá nhập/Giá bán -->
                                <!-- Giá nhập/Giá bán -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-600">
                                            N: {{ formatCurrency(getMinCostPrice(product)) }} - {{
                                                formatCurrency(getMaxCostPrice(product)) }}
                                        </div>
                                        <div class="text-sm font-medium text-green-600">
                                            B: {{ formatCurrency(getMinSalePrice(product)) }} - {{
                                                formatCurrency(getMaxSalePrice(product)) }}
                                        </div>
                                    </div>
                                </td>


                                <!-- Tồn kho -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <div class="space-y-1">
                                        <div class="text-sm font-medium"
                                            :class="getStockStatusClass(product).textClass">
                                            Số lượng: {{ getTotalStock(product) }} {{ getFormUnit(product).fromUnit }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ≈ {{ convertToBoxes(product) }} {{ getFormUnit(product).toUnit }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            SL tối thiểu: {{ product.min_stock }}
                                        </div>
                                    </div>
                                </td>
                                <!-- Trạng thái -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getStockStatusClass(product).bgClass">
                                        <span class="w-2 h-2 rounded-full mr-1.5"
                                            :class="getStockStatusClass(product).dotClass"></span>
                                        {{ getStockStatusText(product) }}
                                    </span>
                                </td>

                                <!-- Biến thể -->
                                <td class="px-4 py-3 table-cell-nowrap">
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

                                <!-- Ngày sản xuất -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ formatDate(product.production_date) }}
                                    </div>
                                </td>

                                <!-- Ngày hết hạn -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ formatDate(product.expiration_date) }}
                                    </div>
                                    <div class="text-xs" :class="getExpirationStatus(product).textClass">
                                        {{ getExpirationStatus(product).text }}
                                    </div>
                                </td>

                                <!-- Vị trí -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <span class="text-sm">
                                        {{ getLocation(product) }}
                                    </span>
                                </td>

                                <!-- Hành động -->
                                <td class="px-4 py-3 table-cell-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <button
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button
                                            class="inline-flex items-center p-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors"
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button
                                            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors"
                                            title="Xóa">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50">
                    <div class="text-sm text-gray-600">
                        Hiển thị <span class="font-medium text-gray-900">{{ products.from }}</span> đến
                        <span class="font-medium text-gray-900">{{ products.to }}</span> của
                        <span class="font-medium text-gray-900">{{ products.total }}</span> kết quả
                    </div>
                    <div class="flex items-center space-x-1">
                        <button v-for="link in products.links" :key="link.label" @click="goToPage(link.url)"
                            :disabled="!link.url" class="px-3 py-2 text-sm font-medium rounded-md" :class="{
                                'bg-indigo-600 text-white shadow-sm': link.active,
                                'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50': !link.active,
                                'opacity-50 cursor-not-allowed': !link.url
                            }" v-html="link.label"></button>
                    </div>
                </div>
            </div>
            <ModalProductVariant :isOpen="isModalOpen" :variants="selectedProduct.product_variants" :selectedProduct="selectedProduct"
                @close="closeModal" />
        </div>
    </AppLayout>
</template>

<script setup>
import ModalProductVariant from '../../components/ModalProductVariant.vue';
import Waiting from '../../components/Waiting.vue';
import AppLayout from '../Layouts/AppLayout.vue'
import { defineProps, ref } from 'vue';

const props = defineProps({
    products: Object
});
const formatCurrency = (value) => {
    if (!value) return '0 ₫';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
};

const getTotalStock = (product) => {
    if (!product.product_variants) return 0;
    return product.product_variants.reduce((total, variant) => {
        return total + (variant.inventory?.reduce((sum, inv) => sum + inv.quantity_on_hand, 0) || 0);
    }, 0);
};
const convertToBoxes = (product) => {
    const totalSheets = getTotalStock(product);

    if (!product.unit_conversions || product.unit_conversions.length === 0) return 'N/A';

    const conversion = product.unit_conversions.find(unitConversion =>
        unitConversion.conversion_factor)

    if (!conversion) return 'N/A';

    const boxes = totalSheets / parseFloat(conversion.conversion_factor);
    return Math.round(boxes * 100) / 100;
};
const getFormUnit = (product) => {
    if (!product.unit_conversions || product.unit_conversions.length === 0) return { fromUnit: null, toUnit: null };

    const fromUnit = product.unit_conversions.find(conversion => conversion.from_unit_id);

    if (!fromUnit) return { fromUnit: null, toUnit: null };

    const textFormUnit = fromUnit.from_unit?.name ?? null;
    const textToUnit = fromUnit.to_unit?.name ?? null;

    return { fromUnit: textFormUnit, toUnit: textToUnit };
}
const getMinCostPrice = (product) => {
    if (!product.product_variants || product.product_variants.length === 0) return 'N/A';

    let minCost = Infinity;
    product.product_variants.forEach(variant => {
        variant.supplier_variants.forEach(supplierVariant => {
            const costPrice = parseFloat(supplierVariant.cost_price);
            if (costPrice < minCost) {
                minCost = costPrice;
            }
        });
    });

    return minCost === Infinity ? 'N/A' : minCost;
};

const getMaxCostPrice = (product) => {
    if (!product.product_variants || product.product_variants.length === 0) return 'N/A';

    let maxCost = -Infinity;
    product.product_variants.forEach(variant => {
        variant.supplier_variants.forEach(supplierVariant => {
            const costPrice = parseFloat(supplierVariant.cost_price);
            if (costPrice > maxCost) {
                maxCost = costPrice;
            }
        });
    });

    return maxCost === -Infinity ? 'N/A' : maxCost;
};

const getMinSalePrice = (product) => {
    if (!product.product_variants || product.product_variants.length === 0) return 'N/A';

    const minSale = Math.min(...product.product_variants.map(variant => parseFloat(variant.sale_price)));
    return isNaN(minSale) ? 'N/A' : minSale;
};

const getMaxSalePrice = (product) => {
    if (!product.product_variants || product.product_variants.length === 0) return 'N/A';

    const maxSale = Math.max(...product.product_variants.map(variant => parseFloat(variant.sale_price)));
    return isNaN(maxSale) ? 'N/A' : maxSale;
};


const getStockStatusClass = (product) => {
    const totalStock = getTotalStock(product);
    const minStock = product.min_stock || 0;

    if (totalStock === 0) {
        return {
            textClass: 'text-red-600',
            bgClass: 'bg-red-100 text-red-700',
            dotClass: 'bg-red-400'
        };
    } else if (totalStock <= minStock) {
        return {
            textClass: 'text-yellow-600',
            bgClass: 'bg-yellow-100 text-yellow-700',
            dotClass: 'bg-yellow-400'
        };
    } else {
        return {
            textClass: 'text-green-600',
            bgClass: 'bg-green-100 text-green-700',
            dotClass: 'bg-green-400'
        };
    }
};

const getStockStatusText = (product) => {
    const totalStock = getTotalStock(product);
    const minStock = product.min_stock || 0;

    if (totalStock === 0) return 'Hết hàng';
    if (totalStock <= minStock) return 'Sắp hết hàng';
    return 'Bình thường';
};

const getSuppliers = (product) => {
    if (!product.product_variants) return [];

    const suppliers = new Set();
    product.product_variants.forEach(variant => {
        variant.supplier_variants?.forEach(sv => {
            if (sv.supplier?.name) {
                suppliers.add(sv.supplier.name);
            }
        });
    });

    return Array.from(suppliers);
};

const getLocation = (product) => {
    if (!product.product_variants || product.product_variants.length === 0) return 'N/A';

    const locations = new Set();
    product.product_variants.forEach(variant => {
        variant.inventory_locations?.forEach(location => {
            if (location.zone?.name) {
                locations.add(location.zone.name);
            }
        });
    });

    return Array.from(locations).join(', ') || 'N/A';
};

const getExpirationStatus = (product) => {
    if (!product.expiration_date) return { text: 'N/A', textClass: 'text-gray-400' };

    const expirationDate = new Date(product.expiration_date);
    const today = new Date();
    const diffTime = expirationDate.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays <= 0) {
        return { text: 'Đã hết hạn', textClass: 'text-red-500 font-medium' };
    } else if (diffDays <= 30) {
        return { text: `Còn ${diffDays} ngày`, textClass: 'text-yellow-500 font-medium' };
    } else {
        return { text: 'Còn hạn', textClass: 'text-green-500' };
    }
};

const isModalOpen = ref(false);
const selectedProduct = ref({});

const showVariants = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const goToPage = (url) => {
    if (!url) return;

    const page = new URL(url).searchParams.get('page');
    Inertia.get(route('admin.products.index'), { page }, {
        preserveState: true,
        replace: true
    });
};
</script>

<style scoped>
.scrollbar-custom::-webkit-scrollbar {
    height: 8px;
}

.scrollbar-custom::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.scrollbar-custom::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.scrollbar-custom::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Đảm bảo các ô trong bảng không bị xuống dòng */
.table-cell-nowrap {
    white-space: nowrap;
}
</style>