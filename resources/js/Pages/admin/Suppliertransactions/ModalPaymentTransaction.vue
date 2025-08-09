<template>
    <div>
        <!-- Overlay với hiệu ứng mờ và transition -->
        <transition name="fade">
            <div
                class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-opacity duration-200">
                <!-- Modal với hiệu ứng xuất hiện mượt mà -->
                <transition name="modal">
                    <div
                        class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 ease-out">
                        <!-- Header modal với gradient indigo -->
                        <div class="p-5 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-t-xl">
                            <h3 class="text-xl font-semibold text-white uppercase tracking-wide flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Thanh Toán Công Nợ
                            </h3>
                        </div>

                        <!-- Nội dung modal -->
                        <div class="pt-6 ps-6 pe-6 space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số tiền thanh toán (VND)<span class="text-red-500" v-if="form.errors.payment">{{ form.errors.payment }}</span></label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="text" v-model="form.payment"
                                    class="block w-full pl-4 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                    placeholder="Nhập số tiền" @change="formatCurrency" />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500">₫</span>
                                </div>
                            </div>

                            <p class="mt-2 text-sm text-gray-500">
                                Công nợ còn lại:
                                <span class="font-semibold text-indigo-600">
                                    {{ formatNumber(initialPayment) }} ₫
                                </span>
                            </p>
                        </div>
                        <div class="p-6 space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh minh chứng <span class="text-red-500" v-if="form.errors.file">{{ form.errors.file }}</span></label>
                            <input  @change="handleFileChange" type="file" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm date-picker" />
                        </div>
                        <div class="p-6 space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                            <textarea
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm date-picker"
                                type="text" v-model="form.note"></textarea>
                        </div>
                      

                        <!-- Footer modal -->
                        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-between items-center">
                            <button
                                class="px-5 py-2.5 text-indigo-600 bg-white border border-indigo-300 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                                @click="handleCloseModal()">
                                Huỷ bỏ
                            </button>
                            <button
                                class="px-5 py-2.5 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                :disabled="!form.payment || form.payment <= 0" @click="handleSubmit()">
                                Xác nhận thanh toán
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ref } from "vue";

const { transactionSupplierEdit } = defineProps({
    transactionSupplierEdit: Object,
});
// Format số tiền
const formatNumber = (value) => {
    return new Intl.NumberFormat("vi-VN").format(value);
};

// Format currency khi nhập
const formatCurrency = (event) => {
    let input = event.target.value;
    input = input.replace(/\D/g, '');
    if (input) {
        input = parseInt(input).toLocaleString('vi-VN');
    }
    this.form.payment = input;
};

// Khởi tạo form với giá trị đã format
const subInitialPayment =
    transactionSupplierEdit.value.total_amount -
    transactionSupplierEdit.value.supplier_transaction.paid_amount;
const initialPayment = subInitialPayment;
const form = useForm({
    payment: initialPayment,
    note: "",
    file: null,
    _method:"PATCH"
});
function handleFileChange(event) {
    form.file = event.target.files[0]; // lấy file đầu tiên được chọn
}
// Format giá trị ban đầu
const formattedInitialValue = ref(formatNumber(initialPayment));

const handleSubmit = () => {
    console.log("Submitting payment:", form);
    form.post(
        route("admin.supplier-transaction.updatePayment", {
            id: transactionSupplierEdit.value.supplier_transaction.id,
        }),
        {
            onSuccess: () => {
                emit("closeModal");
            },
        }
    );
};

const emit = defineEmits();

const handleCloseModal = () => {
    emit("closeModal");
};
</script>

<style scoped>
/* Hiệu ứng fade cho overlay */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Hiệu ứng cho modal */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.98);
}
</style>
