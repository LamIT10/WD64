<template>
    <div>
        <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <!-- Box nội dung modal (không trong suốt) -->
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Header modal -->
                <div class="border-b p-4 bg-indigo-50 rounded-t-lg">
                    <h3 class="text-lg font-semibold text-indigo-800 uppercase">Cập nhật hạn công nợ</h3>
                </div>

                <!-- Nội dung modal -->
                <div class="p-4">
                    <input class="w-full p-2 auto-format-date border border-indigo-300 rounded focus:border-indigo-500 focus:ring-indigo-500" 
                           type="date" 
                           v-model="form.credit_due_date">
                </div>

                <!-- Footer modal -->
                <div class="border-t p-4 flex justify-end bg-indigo-50 rounded-b-lg">
                    <button class="px-4 py-2 me-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors" 
                            @click="handleCloseModal()">
                        Huỷ bỏ
                    </button>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors" 
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
console.log(transactionSupplierEdit);

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

<style lang="scss" scoped></style>