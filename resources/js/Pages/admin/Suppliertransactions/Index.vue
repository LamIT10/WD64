<template>
    <AppLayout>
        <div class="p-6">
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Nhà cung cấp
                </h5>
                <div class="flex items-center space-x-3">
                    <form @submit="submitSearch" class="relative">
                        <button type="submit" class="">Tìm kiếm</button>
                        <input type="text" name="search" v-model="form.search" placeholder="Tìm kiếm nhà cung cấp..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
                    </form>
                    <Waiting route-name="admin.suppliers.create" :route-params="{}">
                        <i class="fas fa-plus mr-1"></i> Thêm mới
                    </Waiting>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="relative overflow-x-auto shadow-md">
                        <table
                            class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400 overflow-visible">
                            <thead
                                class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <!-- <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                        </div>
                                    </th> -->
                                    <th scope="col" class="px-4 py-2 w-[100px]">
                                        Mã đơn hàng
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Nhà cung cấp
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Trạng thái
                                    </th>
                                    <!-- <th scope="col" class="px-4 py-2">
                                        Tổng tiền đơn hàng
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Số tiền đã thanh toán
                                    </th> -->
                                    <th scope="col" class="px-4 py-2">
                                        Số tiền nợ
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Ngày giao dịch
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Hạn công nợ
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Ghi chú
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in transactionSupplier.data" :key="item.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <!-- <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-table-search-1" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                        </div>
                                    </td> -->
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ "MDH - " + item.purchase_order_id }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.supplier.name }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">


                                        <span v-if="item.purchase_order.total_amount - item.paid_amount == 0"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Đã thanh toán
                                        </span>
                                        <span
                                            v-else-if="new Date() > new Date(new Date(item.credit_due_date).setDate(new Date(item.credit_due_date).getDate() + 7))"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Sắp hết hạn
                                        </span>
                                        <span v-else-if="Date.parse(item.credit_due_date) > Date.parse(today) == true"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Còn hạn
                                        </span>
                                        <span v-else-if="Date.parse(item.credit_due_date) < Date.parse(today) == true"
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Hết hạn
                                        </span>
                                    </th>
                                    <!-- <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 auto-format-number whitespace-nowrap dark:text-white">
                                        {{ item.purchase_order.total_amount }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 auto-format-number whitespace-nowrap dark:text-white">
                                        {{ item.paid_amount }}
                                    </th> -->
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 auto-format-number whitespace-nowrap dark:text-white">
                                        {{ item.purchase_order.total_amount - item.paid_amount }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 py-2 font-medium auto-format-date text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.transaction_date }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 auto-format-date py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.credit_due_date }}
                                    </th>
                                    <th scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ item.description }}
                                    </th>
                                    <!-- <td class="px-4 py-2">
                                        {{ supplier.contact_person }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ supplier.phone }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ supplier.email }}
                                    </td>
                                    <td class="px-4 py-2"
                                        style="text-wrap: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis">
                                        {{ supplier.address }}
                                    </td> -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 relative"
                                        style="overflow: visible;">
                                        <button @click="toggleActionPopup(index)"
                                            class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <!-- Popup hành động -->
                                        <div v-show="activePopup === index" :id="'popup' + (index + 1)"
                                            class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1">
                                                <p @click="OpenEditCreditDueDate(item)"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cập
                                                    nhật hạn công nợ</p>
                                                <p @click="openPayment(item)"
                                                    v-if="item.purchase_order.total_amount - item.paid_amount > 0"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Cập nhật thanh toán</p>
                                                <!-- <a href="#"
                                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Xóa</a> -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->

                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Hiển thị <span class="font-medium">{{ transactionSupplier.from }}</span> đến
                            <span class="font-medium">{{ transactionSupplier.to }}</span> của
                            <span class="font-medium">{{ transactionSupplier.total }}</span> kết quả
                        </div>
                        <div class="flex space-x-1">
                            <Link v-if="transactionSupplier.prev_page_url" :href="transactionSupplier.prev_page_url"
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                            <i class="fas fa-chevron-left"></i>
                            </Link>
                            <span v-else
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>

                            <!-- Page Numbers -->
                            <template v-for="page in transactionSupplier.links">
                                <Link v-if="page.url && !page.active && page.label !== '...'" :href="page.url"
                                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                                {{ page.label }}
                                </Link>
                                <span v-else-if="page.active" class="px-3 py-1 bg-indigo-600 text-white rounded-md">
                                    {{ page.label }}
                                </span>
                                <span v-else-if="page.label === '...'"
                                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600">
                                    {{ page.label }}
                                </span>
                            </template>

                            <Link v-if="transactionSupplier.next_page_url" :href="transactionSupplier.next_page_url"
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                            <i class="fas fa-chevron-right"></i>
                            </Link>
                            <span v-else
                                class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <ModalCreditDueDate v-if="isOpenCredit" :transactionSupplierEdit="transactionSupplierEdit"
                    @closeModal="HandleCloseModal" />
                <ModalPaymentTransaction v-if="isOpenPayment" :transactionSupplierEdit="transactionSupplierEdit"
                    @closeModal="HandleCloseModal" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { onMounted, onUnmounted, onUpdated, ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { useForm, Link } from '@inertiajs/vue3';
import ModalCreditDueDate from './ModalCreditDueDate.vue';
import ModalPaymentTransaction from './ModalPaymentTransaction.vue';
const today = new Date();
const { transactionSupplier } = defineProps({
    transactionSupplier: Object
});
const form = useForm({
    error: ""
})
const isOpenCredit = ref(false);
const isOpenPayment = ref(false);
const transactionSupplierEdit = ref();
const OpenEditCreditDueDate = (item) => {
    // isOpenCredit.value = true;
    // transactionSupplierEdit.value = item;
        // setDate(item.credit_due_date + 7);
    // date.setDate(date.getDate())


}
const openPayment = (item) => {
    isOpenPayment.value = true;
    transactionSupplierEdit.value = item;
}
const HandleCloseModal = () => {
    isOpenCredit.value = false;
    isOpenPayment.value = false;
}
// State để theo dõi popup đang active
const activePopup = ref(null);

// Toggle popup
const toggleActionPopup = (index) => {
    activePopup.value = activePopup.value === index ? null : index;
};

// Đóng popup khi click ra ngoài
const handleOutsideClick = (event) => {
    if (!event.target.closest('button') && !event.target.closest('[id^="popup"]')) {
        activePopup.value = null;
    }
};

const editLabel = () => {
    transactionSupplier.links.forEach(element => {
        if (element.label == "&laquo; Previous") element.label = "Previous";
        if (element.label == "Next &raquo;") element.label = "Next"
    });
}
editLabel();

class DateFormatter {
    constructor() {
        // Tìm tất cả các element có class 'auto-format-date'
        this.elements = document.querySelectorAll('.auto-format-date');
        this.init();
    }

    init() {
        this.elements.forEach(element => {
            // Lấy nội dung ngày từ element
            const isoDate = element.textContent.trim();
            // Format ngày và cập nhật nội dung
            const formattedDate = this.formatDate(isoDate);
            element.textContent = formattedDate;
        });
    }

    formatDate(isoDate) {
        try {
            const date = new Date(isoDate);
            if (isNaN(date.getTime())) {
                return isoDate; // Trả về nguyên gốc nếu ngày không hợp lệ
            }
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Tháng bắt đầu từ 0
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        } catch (error) {
            console.error('Error formatting date:', error);
            return isoDate; // Trả về nguyên gốc nếu có lỗi
        }
    }
}
class NumberFormatter {
    constructor() {
        // Tìm tất cả các element có class 'auto-format-number'
        this.elements = document.querySelectorAll('.auto-format-number');
        this.init();
    }

    init() {
        this.elements.forEach(element => {
            // Lấy nội dung số từ element
            const rawNumber = element.textContent.trim();
            // Format số và cập nhật nội dung
            const formattedNumber = this.formatNumber(rawNumber);
            element.textContent = formattedNumber;
        });
    }

    formatNumber(rawNumber) {
        try {
            // Chuyển đổi chuỗi thành số
            const number = parseFloat(rawNumber);
            if (isNaN(number)) {
                return rawNumber; // Trả về nguyên gốc nếu không phải số
            }
            // Format số với dấu chấm phân tách hàng nghìn
            return number.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).replace(/,/g, '.');
        } catch (error) {
            console.error('Error formatting number:', error);
            return rawNumber; // Trả về nguyên gốc nếu có lỗi
        }
    }
}
onMounted(() => {
    new DateFormatter();
    new NumberFormatter();
    document.addEventListener('click', handleOutsideClick);
});
onUpdated(() => {
    new DateFormatter();
    document.addEventListener('click', handleOutsideClick);
})
onUnmounted(() => {
    document.removeEventListener('click', handleOutsideClick);
});

</script>

<style lang="css" scoped></style>