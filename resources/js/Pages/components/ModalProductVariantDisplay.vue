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
                      <th class="px-6 py-3">#</th>
                      <th class="px-6 py-3">Tên thuộc tính</th>
                      <th class="px-6 py-3">Giá trị</th>
                      <th class="px-6 py-3">Giá nhập</th>
                      <!-- <th class="px-6 py-3">Số lượng tối thiểu</th> -->
                      <th class="px-6 py-3">Hành động</th>

                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <template v-for="(variant, vIndex) in props.variants" :key="vIndex">
                      <tr :id="'item-'+ variant.id">
                        <td class="px-6 py-3">
                          {{ vIndex + 1 }}
                        </td>
                        <td class="px-6 py-3">
                          {{ variant.attributes.att || `Thuộc tính ${aIndex + 1}` }}
                        </td>
                        <td class="px-6 py-3">{{ variant.attributes.att_value }}</td>
                        <td class="px-6 py-3">{{ formatNumber(variant.attributes.cost_price) + " ₫" }}</td>
                        <!-- <td class="px-6 py-3">{{ formatNumber(variant.attributes.min_order_quantity) }}</td> -->
                        <td class="px-6 py-3">
                          <ConfirmModal :route-name="'admin.suppliers.products.destroy'" :route-params="{
                            id: props.supplier.id,
                            variantId: variant.id,
                          }" title="Xác nhận xóa biến thể "
                            :message="`Bạn có chắc chắn muốn xóa biến thể ${productName}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xoá`" @close="removeItem">
                            <template #trigger="{
                              openModal,
                            }">
                              <button @click="
                                openModal
                              "
                                class="flex items-center w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-150">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Xóa
                              </button>
                            </template>
                          </ConfirmModal>
                        </td>
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
import { formatNumber } from 'chart.js/helpers';
import { computed, watchEffect } from 'vue';
import ConfirmModal from './ConfirmModal.vue';

const props = defineProps({
  isOpen: Boolean,
  supplier: {
    type: Object,
    default: () => ({}),
  },
  variants: {
    type: Object,
    default: () => { },
  },
  selectedProduct: {
    type: Object,
    default: () => ({}),
  },
});
console.log(props.variants);
const emit = defineEmits(['close']);

const closeModal = () => emit('close');

const productName = computed(() => props.selectedProduct?.name || 'Không xác định');

const removeItem = (params) => {
  const item = document.getElementById('item-' + params.variantId);
  item.remove();
}
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
