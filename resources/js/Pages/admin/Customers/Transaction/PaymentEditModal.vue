<template>
  <div v-if="visible">
    <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600">
          <h3 class="text-xl font-bold text-white uppercase tracking-wide">Cập nhật thanh toán công nợ</h3>
        </div>

        <!-- Form Content -->
        <div class="p-6 space-y-6">
          <!-- Current Debt -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nợ hiện tại</label>
            <input 
              type="number" 
              :value="props.debt?.remaining_amount ?? 0" 
              disabled
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-mono cursor-not-allowed"
            />
          </div>

          <!-- Payment Amount -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Số tiền thanh toán</label>
            <input 
              type="number" 
              v-model="form.paid_amount"
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 transition-all"
              placeholder="Nhập số tiền thanh toán"
            />
          </div>

          <!-- Transaction Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày giao dịch</label>
            <input 
              type="date" 
              v-model="form.transaction_date"
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition-all"
            />
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea 
              v-model="form.description" 
              maxlength="250" 
              rows="4"
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"
              placeholder="Ghi chú cho điều chỉnh này (tùy chọn)"
            ></textarea>
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
          <button
            class="px-6 py-2.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-100 transition-all font-medium"
            @click="handleCloseModal()"
          >
            Huỷ bỏ
          </button>
          <button 
            class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-medium"
            @click="handleSubmit()"
          >
            Đồng ý
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { watch } from 'vue'

const props = defineProps({
  visible: Boolean,
  debt: Object
})

const emit = defineEmits(['closeModal'])

const form = useForm({
  paid_amount: 0,
  transaction_date: new Date().toISOString().slice(0, 10),
  description: ''
})

// Reset form mỗi khi modal mở lại
watch(() => props.debt, (newDebt) => {
  if (newDebt) {
    form.paid_amount = 0
    form.transaction_date = new Date().toISOString().slice(0, 10)
    form.description = ''
  }
}, { immediate: true })

const handleSubmit = () => {
  router.post(route('admin.customer-transaction.add', props.debt.id), form, {
    onSuccess: () => emit('closeModal'),
    onError: () => alert('Có lỗi xảy ra khi thêm giao dịch.'),
  })
}

const handleCloseModal = () => {
  emit('closeModal')
}
</script>