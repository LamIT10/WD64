<template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl relative animate-fade-in-up">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-indigo-700">Cập nhật công nợ</h2>
        <button @click="close" class="text-gray-500 hover:text-gray-700 text-xl">
          &times;
        </button>
      </div>

      <!-- Nội dung form -->
      <div class="space-y-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1">Khách hàng</label>
          <div class="text-gray-900 font-semibold">
            {{ debt.customer?.name|| '-' }}
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700">Mã đơn hàng</label>
          <div class="text-gray-900 font-semibold">
         {{ 'DH' + debt.id.toString().padStart(4, '0') }}
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Số tiền đã trả</label>
          <input type="number" v-model="form.paid_amount"
            class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-indigo-200" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Hạn công nợ</label>
          <input type="date" v-model="form.credit_due_date"
            class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-indigo-200" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Ngày giao dịch</label>
          <input type="date" v-model="form.transaction_date"
            class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-indigo-200" />
        </div>
      </div>

      <!-- Nút hành động -->
      <div class="mt-6 flex justify-end space-x-3">
        <button @click="close" class="px-4 py-2 bg-gray-200 rounded text-gray-700 hover:bg-gray-300">
          Huỷ
        </button>
        <button @click="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
          Cập nhật
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  visible: Boolean,
  debt: Object
})
const emit = defineEmits(['close'])

const form = useForm({
  paid_amount: 0,
  credit_due_date: '',
  transaction_date: ''
})

watch(() => props.debt, (newDebt) => {
  if (newDebt) {
    form.paid_amount = newDebt.paid_amount ?? 0
    form.credit_due_date = newDebt.credit_due_date ?? ''
    form.transaction_date = newDebt.transaction_date ?? new Date().toISOString().slice(0, 10)
  }
}, { immediate: true })

const close = () => emit('close')

const submit = () => {
  router.put(`/admin/customer-transactions/${props.debt.id}`, form, {
    onSuccess: () => {
      close()
    },
    onError: () => alert('Có lỗi xảy ra khi cập nhật công nợ.')
  })
}
</script>
