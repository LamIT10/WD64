<template>
    <Transition name="modal">
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50 modal-overlay">

            <Transition name="modal-content" appear>
                <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-6xl max-h-[90vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50">
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-700">
                                <i class="fas fa-boxes mr-2"></i>
                                Biến thể sản phẩm: {{ productName }}
                            </h3>
                        </div>
                        <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="flex-1 overflow-auto">
                        <div class="p-4">
                            <!-- Variants Table -->
                            <div class="overflow-x-auto scrollbar-custom">
                                <table class="w-full text-left text-sm text-gray-700">
                                    <thead class="text-xs text-gray-700 bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 z-10">Mã vạch
                                            </th>
                                            <th scope="col" class="px-2 py-3">Nhà cung cấp</th>
                                            <th scope="col" class="px-2 py-3">Thuộc tính</th>
                                            <th scope="col" class="px-2 py-3 text-right">Giá nhập</th>
                                            <th scope="col" class="px-2 py-3 text-right">Giá bán</th>
                                            <th scope="col" class="px-2 py-3 text-right">Tồn kho</th>
                                            <th scope="col" class="px-2 py-3">Vị trí kho</th>
                                            <th scope="col" class="px-2 py-3">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="(variant, index) in variants" :key="index" class="hover:bg-gray-50">

                                            <!-- Barcode -->
                                            <td class="px-3 py-4 z-10 font-mono">
                                                {{ variant.barcode || 'N/A' }}
                                            </td>

                                            <!-- Suppliers -->
                                            <td class="px-3 py-4">
                                                <div class="space-y-1">
                                                    <div v-for="supplier in variant.supplier_variants"
                                                        :key="supplier.id" class="text-sm">
                                                        NCC {{ supplier.supplier_id }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Attributes -->
                                            <td class="px-3 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    <span v-for="attribute in variant.attributes" :key="attribute.id"
                                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                        {{ attribute.name }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Cost Price -->
                                            <td class="px-3 py-4 text-right">
                                                <div class="space-y-1">
                                                    <div v-for="supplier in variant.supplier_variants"
                                                        :key="supplier.id" class="text-sm">
                                                        {{ formatCurrency(supplier.cost_price) }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Sale Price -->
                                            <td class="px-3 py-4 text-right">
                                                <div class="space-y-1">
                                                    <!-- Lấy giá bán từ Product_Variants -->
                                                    <div class="text-sm font-medium text-green-600">
                                                        {{ formatCurrency(variant.sale_price) }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Stock -->
                                            <td class="px-3 py-4 text-right">
                                                <div class="font-medium"
                                                    :class="getVariantStockClass(variant).textClass">
                                                    {{ getVariantStock(variant) }}
                                                </div>
                                                <div v-if="variant.inventory?.length > 1"
                                                    class="text-xs text-gray-500 mt-1">
                                                    {{ getStockBreakdown(variant) }}
                                                </div>
                                            </td>

                                            <!-- Locations -->
                                            <td class="px-3 py-4">
                                                <div class="space-y-1">
                                                    <div v-for="location in variant.inventory_locations"
                                                        :key="location.id" class="text-sm">
                                                        <span class="font-medium">{{ location.zone?.name }}</span>: {{
                                                            location.custom_location_name }}
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Status -->
                                            <td class="px-3 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                    :class="getVariantStockClass(variant).bgClass">
                                                    <span class="w-2 h-2 rounded-full mr-1.5"
                                                        :class="getVariantStockClass(variant).dotClass"></span>
                                                    {{ getVariantStockStatus(variant) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>

</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    isOpen: Boolean,
    variants: Array,
    selectedProduct: Object
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

const productName = computed(() => props.selectedProduct?.name || '');

const formatCurrency = (value) => {
    if (!value) return '0 ₫';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
};

const getVariantStock = (variant) => {
    if (!variant.inventory) return 0;
    return variant.inventory.reduce((total, inv) => total + inv.quantity_on_hand, 0);
};

const getStockBreakdown = (variant) => {
    return variant.inventory.map(inv => {
        const location = variant.inventory_locations.find(loc => loc.id === inv.warehouse_zone_id);
        return `${location?.custom_location_name || 'N/A'}: ${inv.quantity_on_hand}`;
    }).join(', ');
};

const getVariantStockStatus = (variant) => {
    const stock = getVariantStock(variant);
    return stock === 0 ? 'Hết hàng' : 'Còn hàng';
};

const getVariantStockClass = (variant) => {
    const stock = getVariantStock(variant);

    if (stock === 0) {
        return {
            textClass: 'text-red-600',
            bgClass: 'bg-red-100 text-red-700',
            dotClass: 'bg-red-400'
        };
    } else {
        return {
            textClass: 'text-green-600',
            bgClass: 'bg-green-100 text-green-700',
            dotClass: 'bg-green-400'
        };
    }
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

/* Sticky columns */
.sticky {
    position: sticky;
    z-index: 10;
}

.sticky.left-0 {
    left: 0;
}

.sticky.left-12 {
    left: 48px;
    /* 12 * 4px */
}

/* Animation cho overlay */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* Animation cho nội dung modal */
.modal-content-enter-active {
    animation: modal-enter 0.3s ease-out both;
}

.modal-content-leave-active {
    animation: modal-leave 0.2s ease-in both;
}

@keyframes modal-enter {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes modal-leave {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    to {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
}

/* Giữ nguyên các style khác như trước */
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

.sticky {
    position: sticky;
    z-index: 10;
}

.sticky.left-0 {
    left: 0;
}

.sticky.left-12 {
    left: 48px;
}

.modal-overlay {
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}
</style>