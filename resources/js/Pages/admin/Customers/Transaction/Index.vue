<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-start border border-gray-200">
        <div>
          <h5 class="text-lg text-indigo-700 font-semibold mb-2">
            Công nợ Khách hàng
          </h5>
        </div>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" placeholder="Tìm theo tên, số điện thoại..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>


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
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Mã đơn hàng
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Họ tên
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Trạng thái
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                  Tông tiền nợ
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
                  Điều chỉnh
                </th>



              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(debt, index) in props.customerTransaction.data" :key="debt.id" @click="handleClick(debt.id)"
                class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                <!-- Checkbox for individual row -->
                <td class="px-4 py-4 whitespace-nowrap">
                  <input type="checkbox" :value="debt.id" v-model="selectedcustomerTransaction"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500 font-medium">
                  {{ 'DH' + debt.id.toString().padStart(4, '0') }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    {{ debt.customer?.name || '-' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span :class="{
                    'bg-green-100 text-green-700': debt.status === 'Đã thanh toán',
                    'bg-yellow-100 text-yellow-700': debt.status === 'Chưa thanh toán',
                    'bg-red-100 text-red-700': debt.status === 'Đã quá hạn',
                  }" class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold">
                    {{ debt.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-800">
                  {{ formatCurrency(debt.remaining_amount) }}
                </td>

                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                  {{
                    debt.credit_due_date ? formatDate(debt.credit_due_date) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ debt.transaction_date ? formatDate(debt.transaction_date) : 'Chưa giao dịch' }}

                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="relative inline-block text-left">
                    <div>
                      <button @click.stop="toggleDropdown(debt.id)" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <!-- Thay icon bằng SVG dễ nhấn hơn -->
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                      </button>
                    </div>

                    <!-- Dropdown menu -->
                    <transition enter-active-class="transition ease-out duration-100"
                      enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                      leave-active-class="transition ease-in duration-75"
                      leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                      <div v-show="activeDropdown === debt.id"
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="py-1">
                          <button @click.stop="openDueDateEditModal(debt)"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            Cập nhật hạn công nợ
                          </button>
                          <button @click.stop="openPaymentModal(debt)"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            Cập nhật thanh toán
                          </button>
                        </div>
                      </div>
                    </transition>
                  </div>
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
      <PaymentEditModal :visible="showPaymentModal" :debt="selectedDebt" @closeModal="showPaymentModal = false" />
      <DueDateEditModal :visible="showDueDateModal" :debt="selectedDebt" @closeModal="showDueDateModal = false" />

    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm} from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import PaymentEditModal from './PaymentEditModal.vue';
import DueDateEditModal from './DueDateEditModal.vue'
import { route } from 'ziggy-js';

const props = defineProps({
  customerTransaction: Array,
});



const activeDropdown = ref(null);
const showPaymentModal = ref(false);
const showDueDateModal = ref(false);
const selectedDebt = ref(null);

const toggleDropdown = (id) => {
  activeDropdown.value = activeDropdown.value === id ? null : id;
};

const closeDropdown = () => {
  activeDropdown.value = null;
};

const openPaymentModal = (debt) => {
  selectedDebt.value = debt
  showPaymentModal.value = true
  closeDropdown()
}

const openDueDateEditModal = (debt) => {
  selectedDebt.value = debt;
 showDueDateModal.value = true;
  closeDropdown();
};

// Đóng dropdown khi click ra ngoài
document.addEventListener('click', () => {
  closeDropdown();
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
};

const handleClick = (debtId) => {
    // Kiểm tra xem có văn bản nào đang được chọn không
    if (window.getSelection().toString()) {
        // Nếu có, ngừng hành động click (không chuyển hướng)
        return;
    }
    // Nếu không có văn bản đang được chọn, chuyển hướng
    router.visit(route('admin.customer-transaction.show', debtId));
};
</script>

<style scoped>
.dropdown-container {
  position: relative;
  display: inline-block;
}

td {
  position: relative;
}

/* Tùy chỉnh nút action */
button[type="button"] {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Hiệu ứng hover cho nút */
button[type="button"]:hover {
  background-color: #f3f4f6;
}

/* Đảm bảo dropdown luôn hiển thị trên cùng */
.z-50 {
  z-index: 50;
}

/* Tùy chỉnh dropdown item */
.py-1 button {
  transition: all 0.2s ease;
}

.py-1 button:hover {
  background-color: #f9fafb;
}

.dropdown-menu {
  right: 0;
  min-width: 160px;
}

.relative {
  position: relative;
}

.absolute {
  position: absolute;
}

.z-50 {
  z-index: 50;
}
</style>