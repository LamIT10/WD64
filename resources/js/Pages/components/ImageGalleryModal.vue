<template>
  <Transition name="modal">
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50 modal-overlay">
      <Transition name="modal-content" appear>
        <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-5xl max-h-[90vh] flex flex-col p-4 relative">
          <!-- Modal Header -->
          <div class="flex justify-between items-center mb-4 border-b pb-2 bg-indigo-50 px-2 py-2 rounded">
            <h3 class="text-lg font-semibold text-indigo-700">
              Thư viện hình ảnh
            </h3>
            <button @click="close" class="text-gray-500 hover:text-red-500 transition-colors">
              <i class="fas fa-times text-lg"></i>
            </button>
          </div>

          <!-- Modal Content -->
          <div class="flex-1 overflow-auto">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 px-2 pb-4">
              <img
                v-for="(img, i) in images"
                :key="i"
                :src="getImageUrl(img)"
                class="w-full h-48 object-cover rounded border border-gray-200"
                :alt="`Hình ${i + 1}`"
                loading="lazy"
              />
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup>
const props = defineProps({
  isOpen: Boolean,
  images: Array,
});

const emit = defineEmits(['close']);

const close = () => emit('close');

const getImageUrl = (img) => {
  return img?.url ? `/storage/${img.url}` : 'https://via.placeholder.com/300x200?text=No+Image';
};
</script>

<style scoped>
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

.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
}
</style>
