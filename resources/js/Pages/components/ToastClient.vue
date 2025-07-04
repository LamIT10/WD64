<template>
  <div
    v-if="showToast"
    class="fixed top-0 left-1/2 rounded -translate-x-1/2 z-50 transition-all duration-400 ease-in-out toast-animation"
    :class="{
      'bg-green-50 border-green-500 dark:bg-green-800': toastType === 'success',
      'bg-red-50 border-red-500 dark:bg-red-800': toastType === 'error',
    }"
  >
    <div class="shadow-md flex items-center gap-2 px-4 py-2 rounded text-gray-800 dark:text-gray-200">
      <div
        v-if="toastType === 'success'"
        class="inline-flex items-center justify-center w-8 h-8 text-green-500 bg-green-200 rounded-lg dark:bg-green-800 dark:text-green-200"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
          />
        </svg>
      </div>
      <div
        v-else
        class="inline-flex items-center justify-center w-8 h-8 text-red-500 bg-red-200 rounded-lg dark:bg-red-800 dark:text-red-200"
      >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"
          />
        </svg>
      </div>
      <span class="text-sm">{{ toastMessage }}</span>
      <button
        @click="hideToast"
        class="ml-2 p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
      >
        <svg
          class="w-4 h-4 text-gray-500 dark:text-gray-300"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Toast state
const showToast = ref(false)
const toastMessage = ref('')
const toastType = ref('success')

// Hàm show toast có thể gọi từ ngoài
function triggerToast(message, type = 'success') {
  toastMessage.value = message
  toastType.value = type
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 5000)
}

// Cho phép import hàm này từ component khác
defineExpose({ triggerToast })

function hideToast() {
  showToast.value = false
}
</script>

<style scoped>
@keyframes toastEffect {
    0% {
        opacity: 0;
        top: 0;
    }
    5% {
        opacity: 1;
        top: 30px;
    }
    95% {
        opacity: 1;
        top: 30px; 
    }
    100% {
        opacity: 0;
        top: -1rem; 
    }
}

.toast-animation {
    animation: toastEffect 5s ease-in-out forwards;
}
</style>