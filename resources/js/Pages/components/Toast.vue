<template>
    <div
        v-if="showToast"
        class="fixed top-4 left-1/2 -translate-x-1/2 z-50 transition-all duration-400 ease-in-out"
        :class="{
            'opacity-0 -translate-y-5 scale-95': !isVisible,
            'opacity-100 translate-y-0 scale-100': isVisible,
            'bg-green-100 border-green-500': toastType === 'success',
            'bg-red-100 border-red-500': toastType === 'error',
        }"
    >
        <div class="flex items-center gap-2 px-4 py-3 rounded-md shadow-lg border">
            <svg
                v-if="toastType === 'success'"
                class="w-5 h-5 flex-shrink-0 text-green-600"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
            </svg>
            <svg
                v-else
                class="w-5 h-5 flex-shrink-0 text-red-600"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
            </svg>
            <span class="text-gray-800">{{ toastMessage }}</span>
            <button 
                @click="hideToast"
                class="ml-2 p-1 rounded hover:bg-gray-200 transition-colors"
            >
                <svg
                    class="w-4 h-4 text-gray-500"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M6 18L18 6M6 6l12 12"
                    ></path>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const showToast = ref(false);
const isVisible = ref(false);
const toastMessage = ref("");
const toastType = ref("success");
const page = usePage();
let timeout = null;

const hideToast = () => {
    isVisible.value = false;
    setTimeout(() => {
        showToast.value = false;
    }, 400); // Thời gian bằng với duration transition
};

onMounted(() => {
    watch(
        () => page.props.flash,
        (flash) => {
            if (flash.success || flash.error) {
                showToast.value = true;
                toastMessage.value = flash.success || flash.error;
                toastType.value = flash.success ? "success" : "error";
                
                // Hiệu ứng xuất hiện
                setTimeout(() => {
                    isVisible.value = true;
                }, 10);
                
                // Tự động ẩn sau 5 giây
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    hideToast();
                }, 5000);
            }
        },
        { immediate: true }
    );
});
</script>

<style>
/* Không cần thêm CSS vì đã dùng Tailwind */
</style>