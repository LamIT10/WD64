<template>
    <transition 
        name="toast"
        enter-active-class="toast-enter-active"
        leave-active-class="toast-leave-active"
        enter-from-class="toast-enter-from"
        leave-to-class="toast-leave-to"
    >
        <div
            v-if="visible"
            class="toast-container"
            :class="{
                'bg-success': toastType === 'success',
                'bg-error': toastType === 'error'
            }"
        >
            <div class="toast-content">
                <svg v-if="toastType === 'success'" class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg v-else class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="toast-text">{{ toastMessage }}</span>
                <button @click="closeToast" class="close-button">
                    <svg class="close-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";

const visible = ref(false);
const toastMessage = ref("");
const toastType = ref("success"); // Biến mới để theo dõi loại thông báo
const page = usePage();

const closeToast = () => {
    visible.value = false;
};

watch(
    () => page.props.flash,
    async (flash) => {
        if (flash.success || flash.error) {
            // Đảm bảo visible = false trước
            visible.value = false;
            await nextTick();
            // Set message và type
            toastMessage.value = flash.success || flash.error;
            toastType.value = flash.success ? "success" : "error";
            visible.value = true;
            
            setTimeout(() => {
                visible.value = false;
            }, 5000);
        }
    },
    { immediate: true }
);
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    border-radius: 8px;
    box-shadow: 0 4px 25px rgba(175, 175, 175, 0.3);
    padding: 12px 16px;
    min-width: 150px;
}

.bg-success {
    background: #fff;
}

.bg-error {
    background: #ffffff;
    box-shadow: 0 0 3px red;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.toast-icon {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
  
}

.bg-success .toast-icon {
    color: #0abf00; /* Xanh lá cây cho success */
}

.bg-error .toast-icon {
    color: #fc0707; /* Đỏ cho error */
}

.toast-text {
    font-size: 16px;
    color: #000;
    white-space: nowrap;
    flex: 1;
}

.close-button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s ease;
    margin-left: 8px;
}

.close-button:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.close-icon {
    width: 14px;
    height: 14px;
    color: #888;
    stroke-width: 2.5;
}

.toast-enter-active {
    animation: toast-bounce-in 0.5s cubic-bezier(0.9, -0.55, 0.265, 1.55);
}

.toast-leave-active {
    animation: toast-bounce-out 0.3s ease-in;
}

.toast-enter-from {
    transform: translateX(-50%) scale(0.6);
    opacity: 0;
}

.toast-leave-to {
    transform: translateX(-50%) scale(0.8);
    opacity: 0;
}

@keyframes toast-bounce-in {
    0% {
        transform: translateX(-50%) scale(0.8);
        opacity: 0;
    }
    50% {
        transform: translateX(-50%) scale(1.1);
        opacity: 0.8;
    }
    75% {
        transform: translateX(-50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) scale(1);
        opacity: 1;
    }
}

@keyframes toast-bounce-out {
    0% {
        transform: translateX(-50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) scale(0.7);
        opacity: 0;
    }
}
</style>