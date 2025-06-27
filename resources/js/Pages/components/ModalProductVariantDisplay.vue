<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50 modal-overlay">
      <Transition name="modal-content" appear>
        <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-4xl max-h-[90vh] flex flex-col">
          <!-- Modal Header -->
          <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-indigo-50">
            <h3 class="text-lg font-semibold text-indigo-700">
              <i class="fas fa-boxes mr-2"></i>
              Biến thể sản phẩm: {{ productName }}
            </h3>
            <button @click="closeModal" class="text-gray-500 hover:text-gray-700 transition-colors">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>

          <!-- Modal Content -->
          <div class="flex-1 overflow-auto">
            <div class="p-4">
              <div class="overflow-x-auto scrollbar-custom">
                <table class="w-full text-left text-sm text-gray-700">
                  <thead class="text-xs text-gray-700 bg-gray-100">
                    <tr>
                      <th class="px-6 py-3">Tên thuộc tính</th>
                      <th class="px-6 py-3">Giá trị</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <template v-for="(variant, vIndex) in props.variants.product_variants" :key="vIndex">
                      <tr>
                        <td class="px-6 py-3">
                          {{ variant.attributes.att || `Thuộc tính ${aIndex + 1}` }}
                        </td>
                        <td class="px-6 py-3">{{ variant.attributes.att_value }}</td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
import { computed, watchEffect } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  variants: {
    type: Object,
    default: () => {},
  },
  selectedProduct: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['close']);

const closeModal = () => emit('close');

const productName = computed(() => props.selectedProduct?.name || 'Không xác định');


// Debug
watchEffect(() => {
  console.log("🧪 [Modal] variants:", props.variants);
});
</script>

<style scoped>
.scrollbar-custom::-webkit-scrollbar {
  height: 8px;
}
.scrollbar-custom::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}
.scrollbar-custom::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.scrollbar-custom::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
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
