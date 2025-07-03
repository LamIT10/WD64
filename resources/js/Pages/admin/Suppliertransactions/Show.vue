<template>
    <AppLayout>
        <div class="mx-10  max-w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header section -->
            <div class="bg-indigo-50 rounded-lg p-6 mb-6 border border-indigo-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-indigo-900 mb-2">
                            Công nợ nhà cung cấp: {{ supplierTransaction.name_supplier }}
                        </h1>
                        <p class="text-indigo-800 font-medium">
                            Đơn hàng: {{ supplierTransaction.order_code }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span v-if="supplierTransaction.status == 4"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Đã thanh toán
                        </span>
                        <span v-else-if="supplierTransaction.status == 3"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Sắp hết hạn
                        </span>
                        <span v-else-if="supplierTransaction.status == 2"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Còn hạn
                        </span>
                        <span v-else-if="supplierTransaction.status == 1"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Hết hạn
                        </span>
                        <Waiting route-name="admin.supplier-transaction.index">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </Waiting>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h2 class="text-sm font-semibold text-indigo-700">Thông tin liên hệ</h2>
                        <div class="mt-2 space-y-2 text-gray-800">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ supplierTransaction.name_supplier }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                {{ supplierTransaction.phone }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ supplierTransaction.email }}
                            </p>
                            <p class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-1 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ supplierTransaction.address }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h2 class="text-sm font-semibold text-indigo-700">Thông tin hạn công nợ</h2>
                        <div class="mt-2 space-y-2 text-gray-800">
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Ngày giao dịch: {{ supplierTransaction.transaction_date }}
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hạn công nợ: {{ supplierTransaction.credit_due_date }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debt summary cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                    <h3 class="text-sm font-medium text-gray-700">Tổng tiền đơn hàng</h3>
                    <p class="mt-2 text-2xl font-semibold text-indigo-700">
                        {{ formatNumber(supplierTransaction.total_amount) }} ₫
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <h3 class="text-sm font-medium text-gray-700">Đã thanh toán</h3>
                    <p class="mt-2 text-2xl font-semibold text-green-700">
                        {{ formatNumber(supplierTransaction.paid_amount) }} ₫
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600">
                    <h3 class="text-sm font-medium text-gray-700">Số tiền nợ</h3>
                    <p class="mt-2 text-2xl font-semibold text-red-700">
                        {{ formatNumber(supplierTransaction.total_amount -
                            supplierTransaction.paid_amount) }} ₫
                    </p>
                </div>
            </div>

            <!-- Transaction details -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-indigo-800">Chi tiết công nợ</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Tổng tiền đơn hàng
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Đã thanh toán
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Số tiền nợ
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Ghi chú
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="supplierTransaction.status == 4"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Đã thanh toán
                                    </span>
                                    <span v-else-if="supplierTransaction.status == 3"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Sắp hết hạn
                                    </span>
                                    <span v-else-if="supplierTransaction.status == 2"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Còn hạn
                                    </span>
                                    <span v-else-if="supplierTransaction.status == 1"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Hết hạn
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 auto-format-number">
                                    {{ formatNumber(supplierTransaction.total_amount) + " ₫" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 auto-format-number">
                                    {{ formatNumber(supplierTransaction.paid_amount) + " ₫" }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 auto-format-number">
                                    {{ formatNumber(supplierTransaction.total_amount -
                                        supplierTransaction.paid_amount) + " ₫" }}
                                </td>
                                <td class="px-6 py-4 text-gray-900">
                                    {{ supplierTransaction.description || 'Không có ghi chú' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden mt-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-indigo-800">Chi tiết đơn hàng</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    STT
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Tên sản phẩm
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Số lượng đặt
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Số lượng thực tế
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Quy đổi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Giá trên đơn vị
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Tổng giá
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50" v-for="(item, index) in supplierTransaction.list_item_order">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    {{ item.product_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    {{ item.quantity_ordered }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    {{ item.quantity_received }}
                                </td>
                                <td class="px-6 py-4 text-gray-900">
                                    <p v-for="value in item.convertion">
                                        {{ value }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-gray-900">
                                    {{ item.unit_price }}
                                </td>
                                <td class="px-6 py-4 text-gray-900">
                                    {{ item.subtotal }}
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden mt-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-indigo-800">Lịch sử cập nhật công nợ</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    STT
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Giá trị cập nhật
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Loại
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Ghi chú
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Ngày thực hiện
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50"
                                v-for="(item, index) in supplierTransaction.supplier_debt_histort">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span v-if="item.update_type == 'payment'">

                                        {{ formatNumber(item.new_value) + " ₫" }}
                                    </span>
                                    <span v-else>
                                        {{ formatDate(item.new_value) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="item.update_type == 'payment'">

                                       Cập nhật thanh toán công nợ
                                    </span>
                                    <span v-else>
                                        Cập nhật hạn công nợ
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 auto-format-number">
                                    {{ item.note }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 auto-format-number">
                                    {{ formatDate(item.created_at) }}
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
import Waiting from '../../components/Waiting.vue';
import AppLayout from '../Layouts/AppLayout.vue';
const { supplierTransaction } = defineProps({
    supplierTransaction: Object,
})

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString('vi-VN');
};

const formatNumber = (rawNumber) => {
    try {
        const number = parseFloat(rawNumber);
        if (isNaN(number)) {
            return rawNumber;
        }
        return number.toLocaleString('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).replace(/,/g, '.');
    } catch (error) {
        console.error('Error formatting number:', error);
        return rawNumber;
    }
}
</script>

<style scoped>
.auto-format-number {
    font-variant-numeric: tabular-nums;
}
</style>