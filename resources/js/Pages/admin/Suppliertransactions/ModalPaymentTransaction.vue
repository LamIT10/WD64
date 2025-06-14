<template>
    <div>
        <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <!-- Box nội dung modal -->
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Header modal -->
                <div class="border-b p-4 bg-indigo-50 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-indigo-800 uppercase">Cập nhật thanh toán công nợ</h3>
                </div>

                <!-- Nội dung modal -->
                <div class="p-4">
                    <input 
                        class="w-full p-2 border border-indigo-300 rounded focus:border-indigo-500 focus:ring-indigo-500"
                        type="text" 
                        v-model="form.payment"
                        placeholder="Nhập số tiền thanh toán">
                </div>

                <!-- Footer modal -->
                <div class="border-t p-4 flex justify-end bg-indigo-50 rounded-b-lg">
                    <button 
                        class="px-4 py-2 me-2 text-indigo-700 border border-indigo-300 rounded hover:bg-indigo-100 transition-colors" 
                        @click="handleCloseModal()">
                        Huỷ bỏ
                    </button>
                    <button 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors" 
                        @click="handleSubmit()">
                        Đồng ý
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const { transactionSupplierEdit } = defineProps({
    transactionSupplierEdit: Object,
})

const form = useForm({
    payment: transactionSupplierEdit.purchase_order.total_amount - transactionSupplierEdit.paid_amount
})

const handleSubmit = () => {
    form.patch(route('admin.supplier-transaction.updatePayment', {
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

<style lang="scss" scoped></style>