<template>
    <AppLayout>
        <div class="mx-10 max-w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="bg-indigo-50 rounded-lg p-6 mb-6 border border-indigo-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-indigo-900 mb-2">
                            Công nợ khách hàng: {{ debt.customer.name }}
                        </h1>
                        <p class="text-indigo-800 font-medium">
                            Mã đơn hàng: DH{{ debt.id.toString().padStart(4, '0') }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span :class="{
                            'bg-green-100 text-green-700': debt.status === 'Đã thanh toán',
                            'bg-yellow-100 text-yellow-700': debt.status === 'Chưa thanh toán',
                            'bg-red-100 text-red-700': debt.status === 'Đã quá hạn',
                        }" class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold">
                            {{ debt.status }}
                        </span>
                        <Waiting route-name="admin.customer-transaction.index">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </Waiting>
                    </div>
                </div>

                <!-- Thông tin liên hệ + công nợ -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h2 class="text-sm font-semibold text-indigo-700">Thông tin liên hệ</h2>
                        <div class="mt-2 space-y-2 text-gray-800">
                            <p><i class="fas fa-user text-indigo-500 mr-2"></i>{{ debt.customer.name }}</p>
                            <p><i class="fas fa-phone text-indigo-500 mr-2"></i>{{ debt.customer.phone }}</p>
                            <p><i class="fas fa-envelope text-indigo-500 mr-2"></i>{{ debt.customer.email || 'Chưa cập nhật' }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h2 class="text-sm font-semibold text-indigo-700">Thông tin công nợ</h2>
                        <div class="mt-2 space-y-2 text-gray-800">
                            <p><i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>Ngày giao dịch: {{
                                formatDate(debt.transaction_date) }}</p>
                            <p><i class="fas fa-clock text-indigo-500 mr-2"></i>Hạn công nợ: {{
                                formatDate(debt.credit_due_date) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng tiền -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                    <h3 class="text-sm font-medium text-gray-700">Tổng tiền đơn hàng</h3>
                    <p class="mt-2 text-2xl font-semibold text-indigo-700">{{ formatNumber(debt.total_amount) }} VNĐ</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <h3 class="text-sm font-medium text-gray-700">Đã thanh toán</h3>
                    <p class="mt-2 text-2xl font-semibold text-green-700">{{ formatNumber(debt.paid_amount) }} VNĐ</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600">
                    <h3 class="text-sm font-medium text-gray-700">Còn nợ</h3>
                    <p class="mt-2 text-2xl font-semibold text-red-700">{{ formatNumber(debt.remaining_amount) }} VNĐ
                    </p>
                </div>
            </div>
          <div class="bg-white rounded-lg shadow overflow-hidden mb-7">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-indigo-800">Lịch sử điều chỉnh & thanh toán</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        Loại giao dịch
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        Ngày thực hiện
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        Giá trị
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                        Ghi chú
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="item in [...debt.payment_history, ...debt.adjustment_history]"
                    :key="item.id"
                    class="hover:bg-gray-50"
                >
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                        {{ item.type === 'adjustment' ? 'Điều chỉnh hạn' : 'Thanh toán' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ formatDate(item.transaction_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                        <span v-if="item.type === 'adjustment'">
                            {{ formatDate(item.credit_due_date) }}
                        </span>
                        <span v-else>
                            {{ formatNumber(item.paid_amount) + ' VNĐ' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                        {{ item.note || 'Không có ghi chú' }}
                    </td>
                </tr>

                <tr v-if="!debt.payment_history.length && !debt.adjustment_history.length">
                    <td colspan="4" class="text-center py-4 text-gray-500">Không có lịch sử thanh toán hoặc điều chỉnh</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

            <!-- Chi tiết đơn hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-indigo-800">Chi tiết đơn hàng</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    STT</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Sản phẩm</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Số lượng</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Đơn giá</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                                    Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in debt.items" :key="item.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.product_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.quantity_ordered }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatNumber(item.unit_price) }} VNĐ</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatNumber(item.subtotal) }} VNĐ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import Waiting from '../../../components/Waiting.vue';
import AppLayout from '../../Layouts/AppLayout.vue';


const { customerTransaction } = defineProps({
    debt: Object,
})

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString('vi-VN')
}

const formatNumber = (rawNumber) => {
    try {
        const number = parseFloat(rawNumber)
        if (isNaN(number)) return rawNumber
        return number.toLocaleString('vi-VN', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).replace(/,/g, '.')
    } catch (error) {
        return rawNumber
    }
}
</script>
