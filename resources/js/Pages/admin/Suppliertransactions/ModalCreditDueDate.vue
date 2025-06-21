<template>
    <div>
        <!-- Overlay với hiệu ứng mờ và transition mượt mà -->
        <transition name="fade">
            <div
                class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-opacity duration-300">
                <!-- Modal với hiệu ứng scale và shadow đẹp hơn -->
                <transition name="modal">
                    <div
                        class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 ease-out">
                        <!-- Header modal với gradient indigo -->
                        <div class="p-6 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
                            <h3 class="text-xl font-semibold text-white uppercase tracking-wider">Cập nhật hạn công nợ
                            </h3>
                        </div>

                        <!-- Nội dung modal với padding rộng hơn -->
                        <div class="p-6 space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày hạn công nợ</label>
                            <input
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm"
                                type="date" v-model="form.credit_due_date">
                        </div>

                        <!-- Footer modal với nút bấm được thiết kế lại -->
                        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                            <button
                                class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                                @click="handleCloseModal()">
                                Huỷ bỏ
                            </button>
                            <button
                                class="px-5 py-2.5 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm"
                                @click="handleSubmit()">
                                Đồng ý
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const { transactionSupplierEdit } = defineProps({
    transactionSupplierEdit: Object,
})

const form = useForm({
    credit_due_date: transactionSupplierEdit.credit_due_date.replace("T00:00:00.000000Z", ""),
})

const handleSubmit = () => {
    form.patch(route('admin.supplier-transaction.update', {
        id: transactionSupplierEdit.id,
    }), {
        onSuccess: () => {
            emit('closeModal');
        }
    });
}

const emit = defineEmits();

const handleCloseModal = () => {
    emit('closeModal');
}
</script>

<style scoped>
/* Hiệu ứng fade cho overlay */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Hiệu ứng cho modal */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>