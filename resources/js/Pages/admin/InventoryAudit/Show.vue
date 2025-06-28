<template>
    <div class="no-print">
        <AppLayout>
            <div class=" no-print bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-4 md:p-8">
                <!-- Header Card -->
                <div
                    class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100 flex items-center justify-between">
                    <h5 class="text-lg font-bold text-indigo-800">Thông tin chi tiết phiếu Kiểm Kho</h5>
                    <span class="text-xs text-gray-400">Mã phiếu : {{ audit.id }}</span>
                </div>
                <!-- Thông tin tổng quan phiếu kiểm kho -->
                <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100">
                    <div class="flex flex-wrap gap-2 mb-2 items-center">
                        <span class="font-medium text-gray-700">Khu vực:</span>
                        <span v-for="zone in audit.audited_zones" :key="zone"
                            class="px-3 py-1 rounded bg-indigo-100 text-indigo-700 font-medium">{{ zone }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700 mt-2">
                        <div><span class="font-medium">Người tạo:</span> <span class="ml-1">{{ audit.user?.name || '-'
                        }}</span></div>
                        <div>
                            <span class="font-medium">Ngày kiểm kho:</span>
                            <span class="ml-1">
                                {{ audit.audit_date ? (new Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-medium">Ngày kiểm kho:</span>
                            <span class="ml-1">
                                {{ audit.created_at ? (new Date(audit.created_at).toLocaleDateString('vi-VN')) : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-medium">Trạng thái:</span>
                            <span v-if="audit.status === 'completed'" class="ml-1 text-green-700 font-semibold">Không
                                chênh
                                lệch</span>
                            <span v-else class="ml-1 text-red-700 font-semibold">Có chênh lệch</span>
                        </div>
                        {{ console.log('audit.is_adjusted:', audit.is_adjusted) }}
                        {{ console.log('audit.is_adjusted:', audit.adjusted) }}
                        <div>
                            <span class="font-medium">Đồng bộ:</span>
                            <span v-if="audit.is_adjusted == 1" class="ml-1 text-green-700 font-semibold">Đã đồng
                                bộ</span>
                            <span v-else class="ml-1 text-red-700 font-semibold">Chưa đồng bộ</span>
                        </div>
                        <div v-if="audit.is_adjusted != 1" class="flex items-center gap-2">
                            <span class="font-medium">Đồng bộ dữ liệu với kho hàng ? </span>
                            <div v-if="audit.is_adjusted == 0">
                                <button
                                    class="px-3 mx-2 py-1 rounded bg-green-600 hover:bg-green-700 text-white font-semibold transition"
                                    @click="handleSync">
                                    Đồng bộ
                                </button>
                                <button
                                    class="px-3 mx-2 py-1 rounded bg-red-600 hover:bg-red-700 text-white font-semibold transition"
                                    @click="handleReject">
                                    Từ chối
                                </button>
                            </div>
                            <div v-else>
                                <span class="text-red-600">Đã từ chối</span>
                            </div>
                        </div>
                        <div class="md:col-span-2"><span class="font-medium">Ghi chú:</span> <span class="ml-1">{{
                            audit.notes || '-' }}</span></div>
                    </div>
                </div>
                <!-- Danh sách Sản phẩm cần kiểm kê -->
                <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <h6 class="text-base font-semibold text-indigo-800">Danh sách Sản phẩm</h6>
                        <button
                            class="px-3 mx-2 py-1 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold transition"
                            @click="handlePrint">
                            In phiếu
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">#</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">Khu vực</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">Mã hàng</th>
                                    <th class="px-3 py-2 text-left font-semibold text-indigo-700">Tên hàng</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">Đơn vị</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">Tồn kho</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">Thực tế</th>
                                    <th class="px-3 py-2 text-center font-semibold text-indigo-700">SL chênh</th>
                                    <th class="px-3 py-2 text-left font-semibold text-indigo-700">Ghi chú chênh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr v-for="(product, index) in filteredProducts" :key="product.id"
                                    class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-center">{{ index + 1 }}</td>
                                    <td class="px-3 py-2 text-center">{{ product.zone }}</td>
                                    <td class="px-3 py-2 text-center font-medium">{{ product.code }}</td>
                                    <td class="px-3 py-2">
                                        {{ product.name_product }}
                                        <template
                                            v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span v-for="(value, key) in product.variant_attributes" :key="key"
                                                    class="mr-2">
                                                    <span class="font-medium">{{ value.attribute.name }}:</span> {{
                                                        value.name }}
                                                </span>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-3 py-2 text-center">{{ product.unit }}</td>
                                    <td class="px-3 py-2 text-center">{{ product.quantity_on_hand }}</td>
                                    <td class="px-3 py-2 text-center">{{ auditData.items[index].actual_quantity }}</td>
                                    <td class="px-3 py-2 text-center">{{ auditData.items[index].actual_quantity -
                                        product.quantity_on_hand || 0 }}</td>
                                    <td class="px-3 py-2">{{ auditData.items[index].discrepancy_reason }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </AppLayout>
    </div>
    <!-- ...existing code... -->
    <div style="display: none;" class="print z-50 flex items-center justify-cente">
        <div id="print-form-content">
            <!-- Nội dung form in mẫu -->
            <div class="header" style="text-align:center;">
                <h1 style="margin:0;font-size:20px;text-transform:uppercase;">CÔNG TY TNHH SUVAN</h1>
                <p style="margin:5px 0;font-size:12px;">Địa chỉ: Trung tâm quận 1, TP. HCM</p>
                <p style="margin:5px 0;font-size:12px;">Số điện thoại: 0123 456 789</p>
                <h1 style="margin:0;font-size:20px;text-transform:uppercase;">PHIẾU KIỂM KHO
                </h1>
            </div>
            <div class="ticket-info" style="display:flex;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:12px;">Số phiếu: {{ audit.id }} | Ngày: {{ audit.audit_date ? (new
                    Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-' }}</p>
                <p style="font-size:12px;">Kho kiểm: {{ audit.audited_zones?.join(', ') }}</p>
            </div>
            <div class="person-info" style="margin-bottom:20px;">
                <p><strong>Thông tin kiểm kho:</strong></p>
                <p>Người kiểm kho: {{ audit.user?.name || '_____________________________' }}</p>
                <p>Lý do kiểm kho: _____________________________</p>
            </div>
            <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã hàng</th>
                        <th>Tên hàng hóa</th>
                        <th>Đơn vị</th>
                        <th>SL kho</th>
                        <th>SL kiểm</th>
                        <th>Chênh lệch</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, idx) in filteredProducts" :key="product.id">
                        <td>{{ idx + 1 }}</td>
                        <td>{{ product.code }}</td>
                        <td>
                            {{ product.name_product }}
                            <template
                                v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                                <div class="text-xs text-gray-500 mt-1">
                                    <span v-for="(value, key) in product.variant_attributes" :key="key" class="mr-2">
                                        <span class="font-medium">{{ value.attribute.name }}:</span> {{
                                            value.name }}
                                    </span>
                                </div>
                            </template>
                        </td>
                        <td>{{ product.unit }}</td>
                        <td>{{ product.quantity_on_hand }}</td>
                        <td>{{ auditData.items[idx].actual_quantity }}</td>
                        <td>{{ auditData.items[idx].actual_quantity - product.quantity_on_hand }}</td>
                        <td>{{ auditData.items[idx].discrepancy_reason }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="total" style="text-align:right;margin-bottom:20px;font-size:12px;">
                <p><strong>Tổng số lượng thực tế:</strong> {{auditData.items.reduce((sum, i) => sum +
                    (i.actual_quantity || 0), 0)}}</p>
                <p><strong>Tổng chênh lệch:</strong> {{auditData.items.reduce((sum, i, idx) => sum +
                    ((i.actual_quantity || 0) - (filteredProducts[idx]?.quantity_on_hand || 0)), 0)}}
                </p>
            </div>
            <!-- <div class="signature" style="display:flex;justify-content:space-between;font-size:12px;">
                <div>
                    <p>Người kiểm kho</p>
                    <p>____________________</p>
                </div>
                <div>
                    <p>Thủ kho</p>
                    <p>____________________</p>
                </div>
                <div>
                    <p>Kế toán</p>
                    <p>____________________</p>
                </div> 
                <div>
                    <p>Quản lý kho</p>
                    <p>____________________</p>
                </div>
            </div> -->
            <p style="text-align:center;font-size:12px;">Ngày {{ new Date().getDate() }} tháng {{ new
                Date().getMonth() + 1 }} năm {{ new Date().getFullYear() }}</p>
        </div>
    </div>
</template>
<script setup>
import { reactive, ref, computed } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Waiting from '../../components/Waiting.vue';
import ToastClient from '../../components/ToastClient.vue';
const page = usePage();
const audit = page.props.audit;
console.log(audit);

const toastRef = ref(null)
const auditData = ref({
    items: audit.items.map(item => ({
        actual_quantity: item.actual_quantity,
        discrepancy_reason: item.discrepancy_reason,
    }))
});
const filteredProducts = computed(() =>
    audit.items.map((item, idx) => ({
        id: item.id,
        zone: item.zone,
        code: item.product_variant?.product?.code || '',
        name_product: item.product_variant?.product?.name || '',
        unit: item.unit || item.product_variant?.product?.unit || '',
        quantity_on_hand: item.expected_quantity,
        variant_attributes: item.product_variant?.attributes || {},
    }))
);
console.log(filteredProducts);
const handleSync = async () => {
    try {
        const res = await fetch('/api/inventory/update', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ audit_id: audit.id })
        });
        const data = await res.json();
        if (res.ok) {
            toastRef.value?.triggerToast('Đồng bộ thành công!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 800); // Đợi toast hiển thị rồi reload
        } else {
            toastRef.value?.triggerToast(data.message || 'Đồng bộ thất bại!', 'error');
        }
    } catch (e) {
        toastRef.value?.triggerToast('Có lỗi khi đồng bộ!', 'error');
    }
};
const handleReject = async () => {
    try {
        const res = await fetch('/api/inventory-audit/update', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ audit_id: audit.id, reject: true }) // Thêm reject: true
        });
        const data = await res.json();
        if (res.ok) {
            toastRef.value?.triggerToast('Đã từ chối phiếu kiểm kho!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            toastRef.value?.triggerToast(data.message || 'Từ chối thất bại!', 'error');
        }
    } catch (e) {
        toastRef.value?.triggerToast('Có lỗi khi từ chối!', 'error');
    }
};
const handlePrint = () => {
    window.print();
};
</script>
<style>
@media print {
    .print{
        display: block !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>