<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
  routeName: String,
  routeParams: {
    type: Object,
    default: () => ({})
  },
  color: {
    type: String,
    default: 'bg-indigo-500 hover:bg-indigo-700 text-white'
  }
})

const loading = ref(false)

const href = computed(() => route(props.routeName, props.routeParams))

function navigate() {
  if (loading.value) return
  loading.value = true
  router.visit(href.value, {
    onFinish: () => {
      loading.value = false
    }
  })
}
</script>

<template>
  <button
    type="button"
    :disabled="loading"
    @click="navigate"
    :class="`px-3 py-2 rounded transition duration-200 ${color} disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2`"
  >
    <template v-if="loading">
      <!-- Spinner quay -->
      <svg
        class="animate-spin h-5 w-5 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        ></path>
      </svg>
      <span>Đang xử lý...</span>
    </template>
    <template v-else>
      <slot></slot>
    </template>
  </button>
</template>



