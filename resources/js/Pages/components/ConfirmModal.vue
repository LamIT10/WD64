<template>
    <div>
        <!-- Button to trigger the modal (optional, can be passed via slot) -->
        <slot name="trigger" :openModal="openModal"></slot>

        <!-- Modal -->
        <div
            v-if="isOpen"
            class="fixed inset-0 z-50 flex items-start justify-center pt-20"
            style="background-color: rgba(0, 0, 0, 0.6)"
            @click.self="closeModal"
        >
            <div
                class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 animate-slide-down"
            >
                <div
                    class="flex justify-between items-center p-4 border-b border-gray-200"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ title }}
                    </h3>
                    <button
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-4 whitespace-normal">
                    {{ message }}
                </div>

                <div
                    class="flex justify-end space-x-2 p-4 border-t border-gray-200"
                >
                    <button
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                    >
                        Hủy
                    </button>
                    <button
                        @click="confirmAction"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
                    >
                        Xác nhận xoá
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    title: {
        type: String,
        default: "Xác nhận xóa",
    },
    message: {
        type: String,
        default: "Bạn có chắc chắn muốn xóa mục này không?",
    },
    routeName: {
        type: String,
        required: true,
    },
    routeParams: {
        type: Object,
        default: () => ({}),
    },
});

const isOpen = ref(false);

const openModal = () => {
    isOpen.value = true;
};

const closeModal = () => {
    isOpen.value = false;
};

const confirmAction = () => {
    const form = useForm({});
    form.delete(route(props.routeName, props.routeParams), {
        onSuccess: () => {
            closeModal();
        },
    });
};

// Expose openModal to parent component
defineExpose({ openModal });
</script>

<style scoped>
.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>