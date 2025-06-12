<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Danh sách công nợ khách hàng</h1>

    <!-- Flash message -->
    <div v-if="$page.props.flash.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
      {{ $page.props.flash.success }}
    </div>
    <div v-if="$page.props.flash.error" class="bg-red-100 text-red-800 p-3 rounded mb-4">
      {{ $page.props.flash.error }}
    </div>

    <!-- Table -->
    <div class="overflow-auto rounded-lg shadow border">
      <table class="table-auto w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2">Mã KH</th>
            <th class="px-4 py-2">Đơn hàng</th>
            <th class="px-4 py-2 text-right">Đã thanh toán</th>
            <th class="px-4 py-2 text-right">Còn lại</th>
            <th class="px-4 py-2">Hạn thanh toán</th>
            <th class="px-4 py-2">Mô tả</th>
            <th class="px-4 py-2">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="debt in debts" :key="debt.id" class="border-t">
            <td class="px-4 py-2">{{ debt.customer_id }}</td>
            <td class="px-4 py-2">{{ debt.sales_order_id || '—' }}</td>
            <td class="px-4 py-2 text-right text-green-600">{{ debt.paid_amount.toLocaleString() }}</td>
            <td
              class="px-4 py-2 text-right"
              :class="{
                'text-red-600 font-bold': debt.remaining_amount > 0 && isOverdue(debt.credit_due_date),
                'text-yellow-600': debt.remaining_amount > 0 && !isOverdue(debt.credit_due_date),
                'text-gray-500': debt.remaining_amount <= 0,
              }"
            >
              {{ debt.remaining_amount.toLocaleString() }}
            </td>
            <td class="px-4 py-2">{{ formatDate(debt.credit_due_date) }}</td>
            <td class="px-4 py-2">{{ debt.description }}</td>
            <td class="px-4 py-2">
              <button
                @click="openPayment(debt)"
                class="text-blue-600 hover:underline"
                v-if="debt.remaining_amount > 0"
              >
                Thanh toán
              </button>
              <span v-else class="text-green-600 font-semibold">✓ Đã trả</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <PaymentModal v-if="selectedDebt" :debt="selectedDebt" @close="selectedDebt = null" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
// import PaymentModal from './PaymentModal.vue';

defineProps({
  debts: Array,
});

const selectedDebt = ref(null);
const openPayment = (debt) => {
  selectedDebt.value = debt;
};

const isOverdue = (dateStr) => {
  const today = new Date();
  const due = new Date(dateStr);
  return due < today;
};

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString('vi-VN');
};
</script>
