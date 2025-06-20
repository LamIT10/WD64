<template>
  <div v-if="visible">
    <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600">
          <h3 class="text-xl font-bold text-white uppercase tracking-wide">Điều chỉnh hạn công nợ</h3>
        </div>

        <!-- Form Content -->
        <div class="p-6 space-y-6">
          <!-- Current Due Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hạn hiện tại</label>
            <input 
              type="text" 
              :value="debt?.credit_due_date ? new Date(debt.credit_due_date).toLocaleDateString('vi-VN') : 'dd/mm/yyyy'" 
              disabled
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 font-mono cursor-not-allowed"
            />
          </div>

          <!-- New Due Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hạn mới</label>
            <input 
              type="date" 
              v-model="form.credit_due_date"
              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 transition-all"
            />
          </div>
        </div>

        <!-- Footer -->
        <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
          <button
            class="px-6 py-2.5 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-100 transition-all font-medium"
            @click="emit('closeModal')"
          >
            Huỷ
          </button>
          <button 
            class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-medium"
            @click="submit"
          >
            Cập nhật
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { watch } from 'vue'

const props = defineProps({
  visible: Boolean,
  debt: Object
})

const emit = defineEmits(['closeModal'])
const form = useForm({
  credit_due_date: '',
})

watch(() => props.debt, (newDebt) => {
  if (newDebt) {
    form.credit_due_date = ''
  }
}, { immediate: true })

const submit = () => {
  router.post(route('admin.customer-transaction.updateDueDate', props.debt.id), form, {
    onSuccess: () => emit('closeModal'),
    onError: () => alert('Cập nhật hạn công nợ thất bại.')
  })
}
</script>