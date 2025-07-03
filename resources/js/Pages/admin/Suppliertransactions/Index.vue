<template>
    <AppLayout>
        <div class="p-6">
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách công nợ nhà cung cấp
                </h5>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <select @change="handlePerpage()" v-model="formPerpage.perPage"
                            class="appearance-none bg-indigo-50 border border-indigo-200 text-indigo-700 py-2 px-4 pr-8 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="75">75</option>
                            <option value="100">100</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-indigo-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    <button @click="toggleSearchForm"
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors shadow-sm">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                </div>

            </div>
            <div v-if="showSearchForm" class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form @submit.prevent="submitSearch" class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-2 gap-6 mb-6 w-full">
                        <!-- Role Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-parachute-box mr-2 text-indigo-500"></i>
                                Nhà cung cấp
                            </label>
                            <div class="relative">
                                <select v-model="searchForm.supplierFilter"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                    style="max-height: 50px; overflow-y: auto;">
                                    <option value="0">Tất cả nhà cung cấp</option>
                                    <option class="text-black" v-for="supplier in listSuppliers" :value="supplier.id"
                                        :key="supplier.id">
                                        {{ supplier.name }}
                                    </option>
                                </select>

                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <i
                                    class="fa-solid fa-parachute-box absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">

                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="relative w-[50%]">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-money-bill-wave mr-2 text-indigo-500"></i>
                                        Số tiền nợ từ
                                    </label>
                                    <div class="relative">
                                        <input v-model="searchForm.fromPayment"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                            style="max-height: 50px; overflow-y: auto;" />
                                        <i
                                            class="fa-solid fa-money-bill-wave absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                                    </div>
                                </div>
                                <div class="relative w-[50%]">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-money-bill-wave mr-2 text-indigo-500"></i>
                                        Đến
                                    </label>
                                    <div class="relative">
                                        <input v-model="searchForm.toPayment"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                            style="max-height: 50px; overflow-y: auto;" />
                                        <i
                                            class="fa-solid fa-money-bill-wave absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-signal mr-2 text-indigo-500"></i>
                                Trạng thái
                            </label>
                            <div class="relative">
                                <select v-model="searchForm.statusFilter"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                    style="max-height: 50px; overflow-y: auto;">
                                    <option value="0">Tất cả trạng thái</option>
                                    <option class="text-black" value="4">Đã thanh toán</option>
                                    <option class="text-black" value="1">Hết hạn</option>
                                    <option class="text-black" value="2">Còn hạn</option>
                                    <option class="text-black" value="3">Sắp hết hạn</option>
                                </select>

                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                <i
                                    class="fa-solid fa-signal absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">

                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="relative w-[50%]">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-clock mr-2 text-indigo-500"></i>
                                        Hạn công nợ từ
                                    </label>
                                    <div class="relative">
                                        <input v-model="searchForm.fromCreditDueDate" type="date"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                            style="max-height: 50px; overflow-y: auto;" />
                                        <i
                                            class="fa-solid fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="relative w-[50%]">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-clock mr-2 text-indigo-500"></i>
                                        Đến
                                    </label>
                                    <div class="relative">
                                        <input v-model="searchForm.toCreditDueDate" type="date"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none transition-all"
                                            style="max-height: 50px; overflow-y: auto;" />
                                        <i
                                            class="fa-solid fa-clock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="justify-center text-center space-x-3 pt-4 border-t border-gray-200 w-full">
                        <div class="flex justify-center">
                            <button type="button" @click="resetSearch"
                                class="flex items-center me-5 gap-2 px-5 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-undo-alt"></i>
                                Đặt lại
                            </button>
                            <button type="submit"
                                class="flex items-center ms-5 gap-2 px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-search"></i>
                                Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white">
                <div class="">
                    <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-2 w-[100px]">
                                    Mã đơn hàng
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Nhà cung cấp
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Trạng thái
                                </th>
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
                                <th scope="row"
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ "MDH - " + item.purchase_order_id }}
                                </th>
                                <th scope="row"
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <Link v-if="authStore.hasPermission('admin.supplier_transaction.show')" :href="route('admin.supplier-transaction.show', {
                                        id: item.purchase_order.supplier_id
                                    })">
                                    {{ item.purchase_order.supplier.name }}
                                    </Link>
                                    <span v-else>{{ item.purchase_order.supplier.name }}</span>
                                </th>
                                <th scope="row"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold">
                                    <span v-if="item.outstanding_amount == 0"
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold  bg-green-100 text-green-700 mt-1">
                                        Đã thanh toán
                                    </span>
                                    <span v-else-if="CheckDate(item.credit_due_date) == 3"
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700 mt-1">
                                        Sắp hết hạn
                                    </span>
                                    <span v-else-if="CheckDate(item.credit_due_date) == 2"
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 mt-1">
                                        Còn hạn
                                    </span>
                                    <span v-else-if="CheckDate(item.credit_due_date) == 1"
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 mt-1" >
                                        Hết hạn
                                    </span>
                                </th>
                                <th scope="row"
                                    class="px-4 py-2 font-medium text-gray-900 auto-format-number whitespace-nowrap dark:text-white">
                                    {{ item.outstanding_amount + " ₫" }}
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
                                <td class="px-4 py-3 text-sm text-gray-500 relative">
                                    <button @click="toggleActionPopup(index)" :disabled="item.outstanding_amount <= 0"
                                        class="text-gray-500 hover:text-gray-700 focus:outline-none disabled:opacity-50"
                                        aria-label="Action menu">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>

                                    <!-- Popup hành động -->
                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <div v-if="activePopup === index" :id="'popup' + (index + 1)"
                                            class="absolute right-0 top-8 z-[1000] w-44 max-h-64 bg-white rounded-lg shadow-md ring-1 ring-gray-200 overflow-y-auto popup"
                                          >
                                            <div class="py-1 text-sm">
                                                <button v-if="item.outstanding_amount != 0"
                                                    v-can="'admin.supplier_transaction.update_credit_due_date'"
                                                    @click="OpenEditCreditDueDate(item)"
                                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                                    Cập nhật hạn công nợ
                                                </button>
                                                <button v-if="item.outstanding_amount != 0"
                                                    v-can="'admin.supplier_transaction.update_paid_amount'"
                                                    @click="openPayment(item)"
                                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                                    Cập nhật thanh toán
                                                </button>
                                            </div>
                                        </div>
                                    </transition>
                                </td>
                            </tr>
                            <tr v-if="transactionSupplier.data.length === 0">
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Không có công nợ nào được tìm thấy
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <Pagination :data="transactionSupplier" :perPage="formPerpage.perPage" />
            </div>
            <ModalCreditDueDate v-if="isOpenCredit" :transactionSupplierEdit="transactionSupplierEdit"
                @closeModal="HandleCloseModal" />
            <ModalPaymentTransaction v-if="isOpenPayment" :transactionSupplierEdit="transactionSupplierEdit"
                @closeModal="HandleCloseModal" />
        </div>
    </AppLayout>
</template>

<script setup>
import { onMounted, onUnmounted, onUpdated, reactive, ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { useForm, Link } from '@inertiajs/vue3';
import ModalCreditDueDate from './ModalCreditDueDate.vue';
import ModalPaymentTransaction from './ModalPaymentTransaction.vue';
import { route } from 'ziggy-js';
import Pagination from '../../components/Pagination.vue';
import { useAuthStore } from '../../../stores/auth';

const authStore = useAuthStore();

const formPerpage = useForm({
    perPage: "20"
})
const url = new URL(window.location.href);
// Lấy tất cả tham số từ query string
const params = new URLSearchParams(url.search);
const queryData = reactive({
    fromCreditDueDate: "",
    fromPayment: 0,
    statusFilter: 0,
    supplierFilter: 0,
    toCreditDueDate: "",
    toPayment: 0,
});
// Lặp qua tất cả các tham số và lưu vào object
params.forEach((value, key) => {
    if (key == "perPage") {
        formPerpage.perPage = value;

    } else {

        queryData[key] = value;
    }
});




// lưu lại dữ liệu sau tìm kiếm



const searchForm = useForm({
    statusFilter: queryData.statusFilter ?? 0,
    supplierFilter: queryData.supplierFilter ?? 0,
    fromCreditDueDate: queryData.fromCreditDueDate ?? 0,
    toCreditDueDate: queryData.toCreditDueDate ?? 0,
    fromPayment: queryData.fromPayment ?? "",
    toPayment: queryData.toPayment ?? 0,
});
// Tạo object để lưu trữ dữ liệu



const handlePerpage = () => {
    formPerpage.get(route("admin.supplier-transaction.index", {
        perPage: formPerpage.perPage,
    }))
}


// xử lý lọc dữ liệu
// Search form state
const showSearchForm = ref(false);

// Toggle search form visibility
const toggleSearchForm = () => {
    showSearchForm.value = !showSearchForm.value;
};

// Submit search form
const submitSearch = () => {
    searchForm.get(route('admin.supplier-transaction.index'));
};

// Reset search form
const resetSearch = () => {
    searchForm.fromCreditDueDate = "";
    searchForm.fromPayment = 0;
    searchForm.statusFilter = 0;
    searchForm.supplierFilter = 0;
    searchForm.toCreditDueDate = "";
    searchForm.toPayment = 0;
};


// xử lý trạng thái công nợ
const today = new Date();
const CheckDate = (date) => {
    const d = new Date(date);
    const from = new Date(d);
    from.setDate(d.getDate() - 7);

    if (today >= from && today <= d) {
        return 3 // sắp hết hạn
    } else if (Date.parse(date) > Date.parse(today) == true) {
        return 2; // còn hạn
    } else if (Date.parse(date) < Date.parse(today) == true) {
        return 1;  // hết hạn
    }
}
const { transactionSupplier, listSuppliers } = defineProps({
    transactionSupplier: Object,
    listSuppliers: Array,
});
const form = useForm({
    error: ""
})
const isOpenCredit = ref(false);
const isOpenPayment = ref(false);
const transactionSupplierEdit = ref();
const OpenEditCreditDueDate = (item) => {
    isOpenCredit.value = true;
    transactionSupplierEdit.value = item;

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

onMounted(() => {
    new DateFormatter();
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
<style lang="css" scoped>
.popup::-webkit-scrollbar {
    width: 6px;
}

.popup::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.popup::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
</style>