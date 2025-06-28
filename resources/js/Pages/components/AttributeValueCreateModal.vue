<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 modal-overlay">
      <Transition name="modal-content" appear>
        <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md flex flex-col">
          <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50">
            <h3 class="text-lg font-semibold text-indigo-700">
              <i class="fas fa-plus mr-2"></i>
              Thêm Giá Trị Thuộc Tính
            </h3>
            <button
              @click="$emit('close')"
              class="text-gray-500 hover:text-gray-700 transition-colors"
            >
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          <div class="flex-1 p-6">
            <form @submit.prevent="handleSubmit" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-indigo-700">
                  Tên giá trị thuộc tính <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="Nhập giá trị thuộc tính"
                  class="w-full px-4 py-2 mt-1 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200"
                />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                  {{ form.errors.name }}
                </p>
              </div>
              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition-colors"
                >
                  Hủy
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200"
                  :disabled="form.processing || !attributeId"
                >
                  {{ form.processing ? 'Đang xử lý...' : 'Thêm' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
  show: Boolean,
  attributeId: [String, Number, null],
});

const emit = defineEmits(['close', 'created']);

const form = useForm({
  name: '',
  attribute_id: null,
});

const handleSubmit = () => {

  form.attribute_id = props.attributeId;
  form.post(route('admin.attribute-values.store'), {
    onSuccess: (page) => {
      const attributeValue = page.props.flash?.data || {};
      console.log(attributeValue);
      
      emit('created', attributeValue);
      form.reset();
      emit('close');
    },
    onError: (errors) => {
      console.error('Lỗi khi thêm giá trị thuộc tính:', errors);
    },
  });
};
</script>

<style scoped>
.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-content-enter-active {
  animation: modal-enter 0.3s ease-out both;
}

.modal-content-leave-active {
  animation: modal-leave 0.2s ease-in both;
}

@keyframes modal-enter {
  from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes modal-leave {
  from {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
  to {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
  }
}
</style>