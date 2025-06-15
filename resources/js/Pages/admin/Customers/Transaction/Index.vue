<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
        <div>
          <h5 class="text-lg text-indigo-700 font-semibold mb-2">
            Công nợ khách hàng
          </h5>
        </div>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" v-model="searchQuery" @input="handleSearch" placeholder="Tìm theo tên, số điện thoại..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Waiting route-name="admin.customers.import" :route-params="{}">
            <i class="fa fa-sign-in icon-btn"></i> Nhập file
          </Waiting>
          <Waiting route-name="admin.customers.export" :route-params="{}">
            <i class="fa fa-file-export icon-btn"></i> Xuất file
          </Waiting>

        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <!-- Checkbox column for selecting all -->
                <th class="w-12 px-4 py-3 text-left">
                  <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </th>
                <th
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Mã đơn hàng
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">

                  Họ tên

                </th>
                <th
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Số điện thoại
                </th>


                <th
                  class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Tông tiền công nợ
                </th>


                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Tổng tiền đã thanh toán
                </th>

                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Tông tiền còn lại
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Trạng thái
                </th>

                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Hạn công nợ
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Ngày giao dịch
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Hành động
                </th>



              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(debt, index) in props.customerTransaction.data" :key="debt.id"
                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                <!-- Checkbox for individual row -->
                <td class="px-4 py-4 whitespace-nowrap">
                  <input type="checkbox" :value="debt.id" v-model="selectedcustomerTransaction"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                  {{ 'DH' + debt.id.toString().padStart(4, '0') }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    {{ debt.customer?.name || '-' }}
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 font-medium">
                  {{ debt.customer?.phone || '-' }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatCurrency(debt.total_amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                  {{ formatCurrency(debt.paid_amount) }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  {{ formatCurrency(debt.remaining_amount) }}


                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span :class="{
                    'bg-green-100 text-green-700': debt.status === 'Đã thanh toán',
                    'bg-yellow-100 text-yellow-700': debt.status === 'Còn nợ',
                    'bg-red-100 text-red-700': debt.status === 'Quá hạn',
                  }" class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold">
                    {{ debt.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                  {{
                    debt.credit_due_date ? formatDate(debt.credit_due_date) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ debt.transaction_date ? formatDate(debt.transaction_date) : 'Chưa giao dịch' }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">
                  <button @click="openModal(debt)" class="hover:underline" disabled>
                    Sửa
                  </button>
                </td>


              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
          <div class="text-sm text-gray-600 font-medium">
            Hiển thị
            <span class="font-semibold">{{ props.customerTransaction.from }}</span> đến
            <span class="font-semibold">{{ props.customerTransaction.to }}</span> của
            <span class="font-semibold">{{ props.customerTransaction.total }}</span> kết quả
          </div>
          <div class="flex items-center space-x-2">
            <Link v-if="props.customerTransaction.prev_page_url"
              :href="props.customerTransaction.prev_page_url"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
            <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </span>
            <span class="text-sm text-gray-600">
              Trang {{ props.customerTransaction.current_page }} / {{ props.customerTransaction.last_page }}
            </span>
            <Link v-if="props.customerTransaction.next_page_url"
              :href="props.customerTransaction.next_page_url"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
            <i class="fas fa-chevron-right"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-right"></i>
            </span>
          </div>
        </div>
      </div>
      <PaymentEditModal :visible="showModal" :debt="selectedDebt" @close="showModal = false" />

    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import Waiting from '../../../components/Waiting.vue';
import PaymentEditModal from './PaymentEditModal.vue';

// import PaymentModal from './PaymentModal.vue';

const props = defineProps({
  customerTransaction: Array,
});



const formatCurrency = (value) => {
  const number = Number(value);
  return isNaN(number)
    ? '-'
    : number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
};
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('vi-VN')
}

const showModal = ref(false);
const selectedDebt = ref(null);

const openModal = (debt) => {
  selectedDebt.value = debt
  showModal.value = true
}
</script>
