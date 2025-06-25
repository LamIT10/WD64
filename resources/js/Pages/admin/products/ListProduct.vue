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
                                            <img :src="getFirstImageUrl(product)"
                                                class="w-12 h-12 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                                :alt="product.name"
                                                @click="product.images?.length && openImageGallery(product.images)"
                                                loading="lazy" />
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
                                        <Link :href="route('admin.products.show', product.id)"
                                            class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors"
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye text-sm"></i>
                                        </Link>
                                        <Link :href="route('admin.products.edit', product.id)"
                                            class="inline-flex items-center p-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors"
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit text-sm"></i>

                                        </Link>
                                        <ConfirmModal :route-name="'admin.products.destroy'" :route-params="{
                                            id: product.id,
                                        }" title="Xác nhận xóa sản phẩm"
                                            :message="`Bạn có chắc chắn muốn xóa nhà cung cấp ${product.name}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xoá`">
                                            <template #trigger="{
                                                openModal,
                                            }">
                                                <button @click="
                                                    openModal
                                                "
                                                    class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </template>
                                        </ConfirmModal>
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
            <ModalProductVariant :isOpen="isModalOpen" :variants="selectedProduct.product_variants"
                :selectedProduct="selectedProduct" @close="closeModal" />
            <ImageGalleryModal :isOpen="isImageModalOpen" :images="imageList" @close="closeImageGallery" />
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ConfirmModal from '../../components/ConfirmModal.vue';
import ModalProductVariant from '../../components/ModalProductVariant.vue';
import Waiting from '../../components/Waiting.vue';
import AppLayout from '../Layouts/AppLayout.vue'
import { defineProps, ref } from 'vue';
import ImageGalleryModal from '../../components/ImageGalleryModal.vue';

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
const isImageModalOpen = ref(false); // để mở/đóng modal
const imageList = ref([]); // danh sách ảnh của sản phẩm được click
const getFirstImageUrl = (product) => {
    const url = product.images?.[0]?.url;
    return url ? `/storage/${url}` : 'data:image/jpeg;base64,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';
};
const showVariants = (product) => {
    selectedProduct.value = product;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const openImageGallery = (images) => {
    imageList.value = images;
    isImageModalOpen.value = true;
};

const closeImageGallery = () => {
    isImageModalOpen.value = false;
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