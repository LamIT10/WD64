<template>
    <AppLayout>
        <div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-6 md:p-8">


            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
                <h5 class="text-xl font-bold text-indigo-800 tracking-tight">Thông tin chi tiết phiếu Kiểm Kho</h5>
            </div>

            <!-- Thông tin tổng quan phiếu kiểm kho -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-gray-100">
                <div class="mb-2 font-semibold text-indigo-700">Khu vực kiểm kho:</div>
                <div class="flex flex-wrap gap-3 mb-4">
                    <span v-for="zone in audit.audited_zones" :key="zone"
                        class="px-4 py-2 rounded-lg bg-indigo-100 text-indigo-700 font-medium">{{ zone }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700">
                    <div>
                        <span class="font-medium">ID phiếu:</span>
                        <span
                            class="px-2 py-1 rounded bg-gray-100 text-indigo-800 font-semibold ml-1">{{ audit.id }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Người tạo:</span>
                        <span
                            class="px-2 py-1 rounded bg-gray-100 text-indigo-800 font-semibold ml-1">{{ audit.user?.name || '-' }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Ngày kiểm kho:</span>
                        <span
                            class="px-2 py-1 rounded bg-gray-100 text-indigo-800 font-semibold ml-1">{{ audit.audit_date }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Ngày lưu kho:</span>
                        <span
                            class="px-2 py-1 rounded bg-gray-100 text-indigo-800 font-semibold ml-1">{{ audit.created_at }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Trạng thái:</span>
                        <span v-if="audit.status === 'completed'"
                            class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold ml-1">Không chênh lệch
                        </span>
                        <span v-else
                            class="px-2 py-1 rounded bg-red-100 text-red-700 font-semibold ml-1">Có chênh lệch</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-medium">Ghi chú:</span>
                        <span
                            class="px-2 py-1 rounded bg-gray-50 text-gray-700 font-normal ml-1">{{ audit.notes || '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Danh sách Sản phẩm cần kiểm kê -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex justify-between mb-4">
                    <div>
                        <h6 class="text-lg font-semibold text-indigo-800 mb-4">Danh sách Sản phẩm</h6>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Khu vực</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Mã hàng</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Tên hàng</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Đơn vị</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Tồn kho</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Thực tế</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    SL chênh</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                    Ghi chú chênh</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr v-for="(product, index) in filteredProducts" :key="product.id"
                                class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ index + 1 }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.zone }}</td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">{{ product.code }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ product.name_product }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.unit }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.quantity_on_hand }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ auditData.items[index].actual_quantity }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    {{ auditData.items[index].actual_quantity - product.quantity_on_hand || 0 }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ auditData.items[index].discrepancy_reason }}
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
import { reactive, ref, computed } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const audit = page.props.audit;
const auditData = ref({
    items: audit.items.map(item => ({
        actual_quantity: item.actual_quantity,
        discrepancy_reason: item.discrepancy_reason,
    }))
});
const filteredProducts = computed(() => audit.items.map((item, idx) => ({
    id: item.id,
    zone: item.zone,
    code: item.product_variant?.product?.code || '',
    name_product: item.product_variant?.product?.name || '',
    unit: item.unit || item.product_variant?.product?.unit || '',
    quantity_on_hand: item.expected_quantity,
})));
</script>