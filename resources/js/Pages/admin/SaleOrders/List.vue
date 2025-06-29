<template>
    <AppLayout>
        <div class="px-4 py-6">
            <div
                class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center"
            >
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách đơn hàng xuất
                </h5>
                <div class="flex gap-2">
                    <Waiting
                        route-name="admin.sale-orders.create"
                        :route-params="{}"
                        class="inline-flex items-center px-4 shadow-xl py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                    >
                        <i class="fas fa-plus mr-1"></i> Tạo đơn hàng xuất
                    </Waiting>
                    <button
                        @click="exportExcel"
                        class="inline-flex items-center px-4 shadow-xl py-3 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        <i class="fas fa-file-excel mr-1"></i> Xuất Excel
                    </button>
                </div>
            </div>

            <div
                v-if="listOrders.error"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"
            >
                {{ listOrders.error }}
            </div>

            <div
                v-else-if="!filteredOrders.length"
                class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6"
            >
                Không có đơn hàng nào để hiển thị.
            </div>

            <div v-else>
                <div class="mb-6">
                    <div class="bg-white rounded p-3">
                        <nav
                            class="flex gap-3 justify-start items-center"
                            aria-label="Tabs"
                        >
                            <Waiting
                                route-name="admin.sale-orders.index"
                                :route-params="{}"
                                :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 font-semibold border border-indigo-600 shadow-sm hover:shadow-md hover:bg-indigo-100 hover:text-indigo-900 transition-all duration-200 ease-in-out animate-fade-in cursor-pointer'"
                                @click="setActiveTab('all')"
                            >
                                <i class="fa-solid fa-border-all text-xl"></i>
                                Tất cả đơn xuất
                            </Waiting>
                            <Waiting
                                route-name="admin.sale-orders.index"
                                :route-params="{ status: 'pending' }"
                                :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-50 text-yellow-800 font-semibold border border-yellow-500 shadow-sm hover:shadow-md hover:bg-yellow-100 hover:text-yellow-900 transition-all duration-200 ease-in-out cursor-pointer'"
                                @click="setActiveTab('pending')"
                            >
                                <i
                                    class="fa-solid fa-hourglass-start text-xl"
                                ></i>
                                Chờ duyệt
                            </Waiting>
                            <Waiting
                                route-name="admin.sale-orders.index"
                                :route-params="{ status: 'shipped' }"
                                :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-500 shadow-sm hover:shadow-md hover:bg-blue-100 hover:text-blue-900 transition-all duration-200 ease-in-out cursor-pointer'"
                                @click="setActiveTab('shipped')"
                            >
                                <span class="fa-solid fa-truck text-xl"></span>
                                Đang giao hàng
                            </Waiting>
                            <Waiting
                                route-name="admin.sale-orders.index"
                                :route-params="{ status: 'completed' }"
                                :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-purple-50 text-purple-700 font-semibold border border-purple-500 shadow-sm hover:shadow-md hover:bg-purple-100 hover:text-purple-900 transition-all duration-200 ease-in-out cursor-pointer'"
                                @click="setActiveTab('completed')"
                            >
                                <i
                                    class="fa-solid fa-file-circle-check text-xl"
                                ></i>
                                Hoàn thành
                            </Waiting>
                            <Waiting
                                route-name="admin.sale-orders.index"
                                :route-params="{ status: 'rejected' }"
                                :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-red-700 font-semibold border border-red-500 shadow-sm hover:shadow-md hover:bg-red-100 hover:text-red-900 transition-all duration-200 ease-in-out cursor-pointer'"
                                @click="setActiveTab('rejected')"
                            >
                                <i class="fa-solid fa-ban text-xl"></i>
                                Từ chối
                            </Waiting>
                        </nav>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="mb-6 bg-white p-6 rounded-sm border border-gray-100"
                >
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1"
                            >
                                Khách hàng
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    v-model="filters.customer"
                                    class="peer w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm placeholder-gray-400 transition-all"
                                    placeholder="Nhập tên hoặc mã khách hàng..."
                                />
                                <i
                                    class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm peer-focus:text-indigo-500 transition"
                                ></i>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1"
                            >
                                Ngày đặt hàng
                            </label>
                            <input
                                type="date"
                                v-model="filters.order_date"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition-all"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1"
                            >
                                Trạng thái
                            </label>
                            <select
                                v-model="filters.status"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm bg-white transition-all"
                            >
                                <option value="">Tất cả</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="shipped">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="rejected">Từ chối</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white shadow overflow-hidden overflow-x-auto">
                    <table class="w-full text-left shadow-sm text-gray-500">
                        <thead
                            class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300"
                        >
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input
                                            id="checkbox-all-search"
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                        />
                                        <label
                                            for="checkbox-all-search"
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Mã đơn xuất
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Khách hàng
                                </th>
                                <th scope="col" class="px-4 py-2">
                                    Ngày tạo đơn
                                </th>
                                <th scope="col" class="px-4 py-2">Số lượng</th>
                                <th scope="col" class="px-4 py-2">
                                    Trạng thái
                                </th>
                                <th scope="col" class="px-4 py-2 text-center">
                                    Địa chỉ giao hàng
                                </th>
                                <th scope="col" class="px-4 py-2 text-end">
                                    Thành tiền
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="order in filteredOrders"
                                :key="order.id"
                                class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
                                @click="openModal(order)"
                            >
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input
                                            :id="
                                                'checkbox-table-search-' +
                                                order.id
                                            "
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                        />
                                        <label
                                            :for="
                                                'checkbox-table-search-' +
                                                order.id
                                            "
                                            class="sr-only"
                                            >checkbox</label
                                        >
                                    </div>
                                </td>
                                <th
                                    scope="row"
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ order.id }}
                                </th>
                                <td
                                    class="px-4 py-2 text-indigo-700 font-semibold"
                                >
                                    {{ order.customer.name }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ formatDate(order.created_at) }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ order.total_quantity }}
                                </td>
                                <td class="px-4 py-2 text-nowrap">
                                    <span
                                        :class="{
                                            'text-yellow-600 bg-yellow-100 px-2 py-1 rounded-xl':
                                                order.status === 'pending',
                                            'text-blue-600 bg-blue-100 px-2 py-1 rounded-xl':
                                                order.status === 'shipped',
                                            'text-purple-600 bg-purple-100 px-2 py-1 rounded-xl':
                                                order.status === 'completed',
                                            'text-red-600 bg-red-100 px-2 py-1 rounded-xl':
                                                order.status === 'rejected',
                                        }"
                                    >
                                        {{ getStatusText(order.status) }}</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-2 text-orange-600 font-semibold text-center"
                                >
                                    {{
                                        order.address_delivery ||
                                        "Chưa xác định"
                                    }}
                                </td>
                                <td
                                    class="px-4 py-2 text-blue-800 font-semibold flex items-center justify-end"
                                >
                                    {{ formatCurrencyVND(order.total_amount) }}
                                    <i class="fa-solid fa-tag text-lg ml-2"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div
                    v-if="isModalOpen"
                    class="fixed inset-0 overflow-y-auto z-50"
                >
                    <div
                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                    >
                        <div
                            class="fixed inset-0 z-40 transition-opacity"
                            aria-hidden="true"
                        >
                            <div
                                class="absolute inset-0 bg-gray-500 opacity-50"
                                @click="closeModal"
                            ></div>
                        </div>

                        <div
                            class="inline-block relative z-50 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                        >
                            <div class="bg-white px-4 p-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                    >
                                        <div
                                            class="flex justify-between p-3 items-center"
                                        >
                                            <h3
                                                class="text-lg leading-6 font-medium text-gray-900"
                                            >
                                                Đơn xuất {{ selectedOrder.id }}
                                            </h3>
                                            <button
                                                @click="closeModal"
                                                class="text-gray-400 hover:text-gray-500"
                                            >
                                                <span class="sr-only"
                                                    >Close</span
                                                >
                                                <svg
                                                    class="h-6 w-6"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                        <table
                                            class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm"
                                        >
                                            <tbody>
                                                <tr
                                                    class="border-b border-gray-200"
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                    >
                                                        📞 Số điện thoại
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 text-gray-900"
                                                    >
                                                        {{
                                                            selectedOrder
                                                                .customer
                                                                .phone ||
                                                            "Chưa xác định"
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="border-b border-gray-200"
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                    >
                                                        📧 Email
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 text-gray-900"
                                                    >
                                                        {{
                                                            selectedOrder
                                                                .customer
                                                                .email ||
                                                            "Chưa xác định"
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="border-b border-gray-200"
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                    >
                                                        📍 Địa chỉ giao hàng
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 text-gray-900"
                                                    >
                                                        {{
                                                            selectedOrder.address_delivery ||
                                                            "Chưa xác định"
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                    >
                                                        📌 Trạng thái
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <span
                                                            class="inline-block px-3 py-1 rounded-xl text-sm font-medium"
                                                            :class="{
                                                                'text-yellow-700 bg-yellow-100 border border-yellow-300':
                                                                    selectedOrder.status ===
                                                                    'pending',
                                                                'text-blue-700 bg-blue-100 border border-blue-300':
                                                                    selectedOrder.status ===
                                                                    'shipped',
                                                                'text-purple-700 bg-purple-100 border border-yellow-300':
                                                                    selectedOrder.status ===
                                                                    'completed',
                                                                'text-red-700 bg-red-100 border border-red-300':
                                                                    selectedOrder.status ===
                                                                    'rejected',
                                                            }"
                                                        >
                                                            {{
                                                                getStatusText(
                                                                    selectedOrder.status
                                                                )
                                                            }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="mt-6">
                                            <h4
                                                class="font-medium text-gray-900 mb-2"
                                            >
                                                Chi tiết đơn hàng
                                            </h4>
                                            <div
                                                class="border border-gray-200 overflow-hidden"
                                            >
                                                <table
                                                    class="min-w-full divide-y divide-gray-200"
                                                >
                                                    <thead
                                                        class="bg-indigo-400 text-white"
                                                    >
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 border border-white text-left text-xs font-medium uppercase tracking-wider"
                                                            >
                                                                Sản phẩm
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 border border-white text-left text-xs font-medium uppercase tracking-wider"
                                                            >
                                                                Số lượng
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 border border-white text-left text-xs font-medium uppercase tracking-wider"
                                                            >
                                                                Đơn vị
                                                            </th>
                                                            <th
                                                                class="px-6 py-3 border border-white text-left text-xs font-medium uppercase tracking-wider"
                                                            >
                                                                Thành tiền
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody
                                                        class="bg-white divide-y divide-gray-200"
                                                    >
                                                        <tr
                                                            v-for="item in selectedOrder.items"
                                                            :key="item.id"
                                                        >
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                            >
                                                                {{
                                                                    item
                                                                        .product_variant
                                                                        .product
                                                                        .name
                                                                }}
                                                                <span
                                                                    v-if="
                                                                        item
                                                                            .product_variant
                                                                            .attributes
                                                                            .length
                                                                    "
                                                                >
                                                                    -
                                                                    {{
                                                                        item.product_variant.attributes
                                                                            .map(
                                                                                (
                                                                                    attr
                                                                                ) =>
                                                                                    attr.name
                                                                            )
                                                                            .join(
                                                                                " - "
                                                                            )
                                                                    }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                            >
                                                                {{
                                                                    item.quantity_ordered
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                            >
                                                                {{
                                                                    item.unit
                                                                        .name
                                                                }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                                            >
                                                                {{
                                                                    formatCurrencyVND(
                                                                        item.subtotal
                                                                    )
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex justify-end">
                                            <div
                                                class="bg-gray-50 p-4 rounded-lg w-64"
                                            >
                                                <div
                                                    class="flex justify-between mb-2"
                                                >
                                                    <span class="text-gray-600"
                                                        >Tổng tiền đơn:</span
                                                    >
                                                    <span class="font-medium">{{
                                                        formatCurrencyVND(
                                                            selectedOrder.total_amount
                                                        )
                                                    }}</span>
                                                </div>
                                                <div
                                                    class="flex justify-between mb-2"
                                                >
                                                    <span class="text-gray-600"
                                                        >Đã thanh toán:</span
                                                    >
                                                    <span class="font-medium">{{
                                                        formatCurrencyVND(0)
                                                    }}</span>
                                                </div>
                                                <div
                                                    class="flex justify-between border-t border-gray-200 pt-2 mt-2"
                                                >
                                                    <span
                                                        class="text-gray-900 font-medium"
                                                        >Cần thanh toán:</span
                                                    >
                                                    <span
                                                        class="text-gray-900 font-medium"
                                                        >{{
                                                            formatCurrencyVND(
                                                                selectedOrder.total_amount
                                                            )
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                            >
                                <button
                                    v-if="selectedOrder.status === 'pending'"
                                    @click="approveOrder(selectedOrder.id)"
                                    class="w-full inline-flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-regular fa-circle-check"></i>
                                    Duyệt đơn
                                </button>
                                <button
                                    v-if="selectedOrder.status === 'pending'"
                                    @click="rejectOrder(selectedOrder.id)"
                                    class="w-full shadow-xl flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-ban"></i>
                                    Từ chối
                                </button>
                                <button
                                    @click="closeModal"
                                    type="button"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Đóng
                                </button>
                                <Waiting
                                    v-if="
                                        ['shipped'].includes(
                                            selectedOrder.status
                                        )
                                    "
                                    route-name="admin.shipping.create"
                                    :route-params="{ id: selectedOrder.id }"
                                    :color="'mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm'"
                                >
                                    <i
                                        class="fa-solid fa-truck text-xl mr-1"
                                    ></i>
                                    Tạo phiếu giao hàng
                                </Waiting>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import { useForm } from "@inertiajs/vue3";

const { listOrders } = defineProps({
    listOrders: {
        default: () => ({ data: [] }),
    },
});

console.log("listOrders:", listOrders);

function formatCurrencyVND(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}

const isModalOpen = ref(false);
const selectedOrder = ref({ items: [], customer: { phone: "", email: "" } });
const activeTab = ref("all");
const filters = ref({
    customer: "",
    order_date: "",
    status: "",
});

function setActiveTab(tab) {
    activeTab.value = tab;
}

function openModal(order) {
    selectedOrder.value = order;
    isModalOpen.value = true;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function closeModal() {
    isModalOpen.value = false;
}

function exportExcel() {
    window.location.href = route("admin.sale-orders.export");
}

const filteredOrders = computed(() => {
    let orders = listOrders.data || [];

    if (activeTab.value !== "all") {
        orders = orders.filter(
            (order) => order.status.toLowerCase() === activeTab.value
        );
    }

    if (filters.value.customer) {
        orders = orders.filter((order) =>
            order.customer.name
                .toLowerCase()
                .includes(filters.value.customer.toLowerCase())
        );
    }
    if (filters.value.order_date) {
        orders = orders.filter(
            (order) =>
                new Date(order.order_date).toDateString() ===
                new Date(filters.value.order_date).toDateString()
        );
    }
    if (filters.value.status) {
        orders = orders.filter(
            (order) => order.status === filters.value.status
        );
    }

    return orders;
});

const approve = useForm({});
const approveOrder = (id) => {
    approve.post(route("admin.sale-orders.approve", id), {
        onSuccess: () => {
            closeModal();
            const order = listOrders.data.find((o) => o.id === id);
            if (order) {
                order.status = "shipped";
            }
        },
        onError: (errors) => {
            console.error("Lỗi khi phê duyệt đơn hàng:", errors);
        },
    });
};

const reject = useForm({});
const rejectOrder = (id) => {
    reject.post(route("admin.sale-orders.reject", id), {
        onSuccess: () => {
            closeModal();
            listOrders.data = listOrders.data.filter(
                (order) => order.id !== id
            );
        },
        onError: (errors) => {
            console.error("Lỗi khi từ chối đơn hàng:", errors);
        },
    });
};

const getStatusText = (status) => {
    switch (status) {
        case "pending":
            return "Chờ duyệt";
        case "shipped":
            return "Đang giao hàng";
        case "completed":
            return "Hoàn thành";
        case "rejected":
            return "Từ chối";
        default:
            return "Không xác định";
    }
};
</script>

<style scoped>
.bg-gray-500.opacity-50 {
    background-color: rgba(0, 0, 0, 0.5);
}
th,
td {
    vertical-align: middle;
}
tr:hover {
    transition: background-color 0.2s ease;
}
input,
select {
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.fixed.inset-0 {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from {
        top: -100px;
    }
    to {
        top: 0;
    }
}
</style>
