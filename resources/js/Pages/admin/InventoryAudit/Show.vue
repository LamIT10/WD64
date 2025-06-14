<template>
    <AppLayout>
        <div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-6 md:p-8">


            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
                <h5 class="text-xl font-bold text-indigo-800 tracking-tight">Thông tin chi tiết phiếu Kiểm Kho</h5>
            </div>

            <!-- Thời gian tạo phiếu -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-gray-100 text-center">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày kiểm kho: {{ audit.audit_date }}</label>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày lưu kho: {{ audit.created_at }}</label>

                </div>
            </div>

            <!-- Form Phiếu Kiểm Kê -->
            <form @submit.prevent="submitForm">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex justify-center">
                            <div class="my-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Khu vực kiểm
                                    kho</label>
                                <div class="space-y-2 max-w-[22rem] mx-auto">
                                    <div v-for="(row, rowIndex) in grid" :key="rowIndex"
                                        class="flex flex-row items-center justify-center space-x-2">
                                        <div @click="selectRow(rowIndex)"
                                            class="flex items-center justify-center bg-gradient-to-r from-indigo-200 via-indigo-100 to-white rounded-lg shadow border border-indigo-300 min-w-[6rem] min-h-[2.5rem] text-indigo-800 font-semibold text-base tracking-wide transition-all duration-200 hover:shadow-lg hover:from-indigo-300 hover:to-indigo-100 cursor-pointer">
                                            Kho {{ rowIndex }}
                                        </div>
                                        <div v-for="(cell, colIndex) in row" :key="colIndex"
                                            class="p-2 text-center text-sm rounded-lg transition-all duration-200 min-w-[2.5rem] min-h-[2.5rem] flex items-center justify-center"
                                            :class="{
                                                'bg-indigo-600 text-white font-medium cursor-pointer': cell.exist && selectedCell.includes(cell.label),
                                                'bg-indigo-100 text-gray-800 cursor-pointer': cell.exist,
                                                'bg-gray-200 text-gray-500 cursor-not-allowed': !cell.id || !cell.exist
                                            }">
                                            {{ cell.label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                        <textarea v-model="audit.notes" rows="4"
                            class="block w-full border border-gray-200 rounded-lg py-2 px-3 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-sm resize-y transition-all duration-200"
                            placeholder="Nhập ghi chú nếu có"></textarea>
                    </div>
                </div>

                <!-- Danh sách Sản phẩm cần kiểm kê -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <!-- Nhập liệu từ file Excel -->
                    <div class="flex justify-between mb-4">
                        <div>
                            <h6 class="text-lg font-semibold text-indigo-800 mb-4">Danh sách Sản phẩm</h6>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div>
                                <button @click="exportSampleExcel"
                                    class="px-4 py-2 border border-green-200 bg-green-50 text-green-700 rounded-lg text-sm font-medium hover:bg-green-100 hover:border-green-300 transition-all duration-200 flex items-center gap-2 mr-2 active:scale-95 focus:outline-none focus:ring-2 focus:ring-green-300"
                                    type="button">
                                    <i class="fa fa-download icon-btn"></i> Tải file
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Vị trí
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Mã
                                        hàng</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Tên
                                        hàng</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Đơn
                                        vị</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Tồn
                                        kho</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Thực
                                        tế</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        SL
                                        chênh</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">
                                        Ghi chú
                                        chênh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="(product, index) in filteredProducts" :key="product.id"
                                    class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ index + 1 }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{
                                        product.custom_location_name }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">{{ product.code
                                        }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ product.name_product }}
                                        <template
                                            v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span v-for="(value, key) in product.variant_attributes" :key="key"
                                                    class="mr-2">
                                                    <span class="font-medium">{{ value.attribute }}:</span> {{
                                                    value.value }}
                                                </span>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.unit }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.quantity_on_hand
                                        }}</td>
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
            </form>
        </div>
    </AppLayout>
</template>
<script setup>
import { reactive, ref, watch, computed } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

// Thư viện để đọc file Excel (cần cài đặt: npm install xlsx)
import * as XLSX from 'xlsx';

const page = usePage();
const audit = page.props.audit;
const grid = page.props.grid || [];

// Gán dữ liệu vào các input (readonly)
const auditData = ref({
    warehouse_zone_id: null,
    audit_date: audit.audit_date,
    notes: audit.notes,
    items: audit.items.map(item => ({
        product_variant_id: item.product_variant_id,
        expected_quantity: item.expected_quantity,
        actual_quantity: item.actual_quantity,
        discrepancy_reason: item.discrepancy_reason,
        custom_location_name: item.product_variant?.location_name || '',
        code: item.product_variant?.product?.code || '',
        name_product: item.product_variant?.product?.name || '',
        unit: item.product_variant?.product?.unit || '',
        quantity_on_hand: item.expected_quantity,
        variant_attributes: {},
        id: item.id,
    }))
});

// Đổ dữ liệu vào products để bảng hiển thị đúng
const products = reactive(auditData.value.items);

// selectedCell: highlight các vị trí đã kiểm kê
const selectedCell = ref([...audit.audited_locations]);

const filter = ref('all');

const today = new Date().toISOString().split('T')[0];

const canSubmit = computed(() =>
    auditData.value.items.length > 0 &&
    auditData.value.items.every(item =>
        item.actual_quantity !== '' &&
        item.actual_quantity !== null &&
        item.actual_quantity !== undefined &&
        !isNaN(Number(item.actual_quantity)) &&
        Number(item.actual_quantity) >= 0
    )
);

const selectZone = (label) => {
    if (!selectedCell.value.includes(label)) {
        selectedCell.value = [...selectedCell.value, label];
    } else {
        selectedCell.value = selectedCell.value.filter(item => item !== label);
    }
};

selectedCell.value = [...selectedCell.value,...audit.audited_locations] ;

const importInput = ref(null);

const filteredProducts = computed(() => products);

const exportSampleExcel = () => {
    const sampleData = [
        ['Mã hàng', 'Vị trí', 'Tên hàng', 'ĐVT', 'Tồn kho', 'Thực tế', 'Ghi chú chênh'],
        ...filteredProducts.value.map(product => [
            product.code,
            product.custom_location_name,
            product.name_product,
            product.unit,
            product.expected_quantity,
            product.expected_quantity,
            product.discrepancy_reason
        ])
    ];
    const ws = XLSX.utils.aoa_to_sheet(sampleData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'MauKiemKho');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const dateStr = `${yyyy}-${mm}-${dd}`;
    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
    import('file-saver').then(({ saveAs }) => {
        saveAs(new Blob([wbout], { type: 'application/octet-stream' }), `mau-kiem-kho-${dateStr}.xlsx`);
    });
};
</script>