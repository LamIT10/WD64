<template>
    <AppLayout>
        <ToastClient ref="toastRef" class="z-100" />
        <div class="px-4 py-6">
            <div
                class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center"
            >
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách đơn hàng xuất
                </h5>
                <div class="flex gap-2">
                    <Waiting
                        v-can="'admin.sales-order.create'"
                        route-name="admin.sale-orders.create"
                        :route-params="{}"
                        class="inline-flex items-center px-4 shadow-xl py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                    >
                        <i class="fas fa-plus mr-1"></i> Tạo đơn hàng xuất
                    </Waiting>
                    <button
                        @click="exportExcel"
                        v-can="'admin.sales-order.export'"
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

            <div v-else>
                <!-- Filters -->
                <div
                    class="mb-6 bg-white p-6 rounded-sm border border-gray-100"
                >
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
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
                                    @input="applyFilters"
                                />
                                <i
                                    class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm peer-focus:text-indigo-500 transition"
                                ></i>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1"
                                >Từ ngày</label
                            >
                            <input
                                type="date"
                                v-model="filters.order_date_from"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition-all"
                                @input="validateAndApplyDateFilter"
                            />
                        </div>
                        <!-- Đến ngày -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-gray-700 mb-1"
                                >Đến ngày</label
                            >
                            <input
                                type="date"
                                v-model="filters.order_date_to"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition-all"
                                @input="validateAndApplyDateFilter"
                            />
                            <div
                                v-if="dateError"
                                class="text-red-600 text-xs mt-1"
                            >
                                {{ dateError }}
                            </div>
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
                                @change="applyFilters"
                            >
                                <option value="">Tất cả</option>
                                <option value="pending">Chờ duyệt</option>
                                <option value="shipped">Đang giao hàng</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Từ chối</option>
                                <option value="returning">
                                    Đang hoàn hàng
                                </option>
                                <option value="returned">Đã hoàn hàng</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div
                    v-if="dateFilterInvalid || !filteredOrders.length"
                    class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6"
                >
                    Không có đơn hàng nào để hiển thị.
                </div>
                <div
                    v-else
                    class="bg-white shadow overflow-hidden overflow-x-auto"
                >
                    <table class="w-full text-left shadow-sm text-gray-500">
                        <thead
                            class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300"
                        >
                            <tr>
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
                                <th
                                    scope="row"
                                    class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ order.code }}
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
                                                order.status === 'cancelled',
                                            'text-orange-700 bg-orange-100 px-2 py-1 rounded-xl ':
                                                order.status === 'returning',
                                            'text-green-700 bg-green-100 px-2 py-1 rounded-xl ':
                                                order.status === 'returned',
                                        }"
                                    >
                                        {{ getStatusText(order.status) }}
                                    </span>
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
                                    class="px-4 py-2 text-blue-800 font-semibold flex items-center justify-end text-center"
                                >
                                    {{ formatCurrencyVND(order.total_amount) }}
                                    <i class="fa-solid fa-tag text-lg ml-2"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div
                    v-if="!dateFilterInvalid"
                    class="mt-4 flex justify-between items-center"
                >
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ listOrders.meta.current_page }} /
                        {{ listOrders.meta.last_page }} trang (Tổng cộng
                        {{ listOrders.meta.total }} đơn hàng)
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="
                                changePage(listOrders.meta.current_page - 1)
                            "
                            :disabled="listOrders.meta.current_page === 1"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                        >
                            Sau
                        </button>
                        <button
                            v-for="page in Object.keys(listOrders.meta.links)"
                            :key="page"
                            @click="changePage(parseInt(page))"
                            :class="{
                                'px-4 py-2 rounded-md': true,
                                'bg-indigo-600 text-white':
                                    parseInt(page) ===
                                    listOrders.meta.current_page,
                                'bg-gray-200 text-gray-700 hover:bg-gray-300':
                                    parseInt(page) !==
                                    listOrders.meta.current_page,
                            }"
                        >
                            {{ parseInt(page) }}
                        </button>
                        <button
                            @click="
                                changePage(listOrders.meta.current_page + 1)
                            "
                            :disabled="
                                listOrders.meta.current_page ===
                                listOrders.meta.last_page
                            "
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                        >
                            Trước
                        </button>
                    </div>
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
                                                Đơn xuất
                                                {{
                                                    selectedOrder.code ||
                                                    "Chưa xác định"
                                                }}
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
                                        <div
                                            v-if="errorMessage"
                                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
                                        >
                                            {{ errorMessage }}
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
                                                <tr
                                                    class="border-b border-gray-200"
                                                >
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
                                                                    'cancelled',
                                                                'text-orange-700 bg-orange-100 px-2 py-1 rounded-xl ':
                                                                    selectedOrder.status ===
                                                                    'returning',
                                                                'text-green-700 bg-green-100 px-2 py-1 rounded-xl ':
                                                                    selectedOrder.status ===
                                                                    'returned',
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
                                                <tr
                                                    v-if="
                                                        selectedOrder.status ===
                                                            'cancelled' &&
                                                        selectedOrder.note
                                                    "
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                    >
                                                        Lý do từ chối
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 text-red-700"
                                                    >
                                                        {{ selectedOrder.note }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    v-if="
                                                        [
                                                            'returning',
                                                            'returned',
                                                        ].includes(
                                                            selectedOrder.status
                                                        ) && selectedOrder.note
                                                    "
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                    >
                                                        Lý do hoàn hàng
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 text-yellow-700"
                                                    >
                                                        {{
                                                            selectedOrder.note.replace(
                                                                "[RETURN] ",
                                                                ""
                                                            )
                                                        }}
                                                    </td>
                                                </tr>
                                                <!-- Input pay_before khi trạng thái là pending -->
                                                <tr
                                                    v-if="
                                                        selectedOrder.status ===
                                                        'pending'
                                                    "
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                    >
                                                        💰 Thanh toán trước
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input
                                                            :value="
                                                                formatInputCurrency(
                                                                    pay_before
                                                                )
                                                            "
                                                            @input="
                                                                onPayBeforeInput(
                                                                    $event
                                                                )
                                                            "
                                                            @blur="
                                                                onPayBeforeBlur
                                                            "
                                                            type="text"
                                                            min="0"
                                                            :max="
                                                                selectedOrder.total_amount
                                                            "
                                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm"
                                                            placeholder="Nhập số tiền thanh toán trước"
                                                        />
                                                    </td>
                                                </tr>
                                                <!-- Input pay_after khi trạng thái là shipped -->
                                                <tr
                                                    v-if="
                                                        selectedOrder.status ===
                                                        'shipped'
                                                    "
                                                >
                                                    <td
                                                        class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                    >
                                                        💰 Thanh toán sau
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input
                                                            :value="
                                                                formatInputCurrency(
                                                                    pay_after
                                                                )
                                                            "
                                                            @input="
                                                                onPayAfterInput(
                                                                    $event
                                                                )
                                                            "
                                                            @blur="
                                                                onPayAfterBlur
                                                            "
                                                            type="text"
                                                            min="0"
                                                            :max="
                                                                selectedOrder.total_amount -
                                                                (selectedOrder.pay_before ||
                                                                    0)
                                                            "
                                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm"
                                                            placeholder="Nhập số tiền thanh toán sau"
                                                        />
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
                                                        >Đã thanh toán
                                                        trước:</span
                                                    >
                                                    <span class="font-medium">{{
                                                        formatCurrencyVND(
                                                            selectedOrder.pay_before ||
                                                                0
                                                        )
                                                    }}</span>
                                                </div>
                                                <div
                                                    class="flex justify-between mb-2"
                                                >
                                                    <span class="text-gray-600"
                                                        >Đã thanh toán
                                                        sau:</span
                                                    >
                                                    <span class="font-medium">{{
                                                        formatCurrencyVND(
                                                            selectedOrder.pay_after ||
                                                                0
                                                        )
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
                                                    >
                                                        {{
                                                            formatCurrencyVND(
                                                                (selectedOrder.total_amount ||
                                                                    0) -
                                                                    (selectedOrder.pay_before ||
                                                                        0) -
                                                                    (selectedOrder.pay_after ||
                                                                        0)
                                                            )
                                                        }}
                                                    </span>
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
                                    v-can="'admin.sales-order.approve'"
                                    v-if="selectedOrder.status === 'pending'"
                                    @click="approveOrder(selectedOrder.id)"
                                    class="w-full inline-flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    :disabled="approve.processing"
                                >
                                    <i class="fa-regular fa-circle-check"></i>
                                    Duyệt đơn
                                </button>
                                <button
                                    v-can="'admin.sales-order.reject'"
                                    v-if="selectedOrder.status === 'pending'"
                                    @click="openRejectModal(selectedOrder.id)"
                                    class="w-full shadow-xl flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-ban"></i>
                                    Từ chối
                                </button>
                                <!-- Modal nhập lý do từ chối -->
                                <div
                                    v-if="showRejectModal"
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
                                                @click="closeRejectModal"
                                            ></div>
                                        </div>
                                        <div
                                            class="inline-block relative z-50 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        >
                                            <div
                                                class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"
                                            >
                                                <div
                                                    class="sm:flex sm:items-start"
                                                >
                                                    <div
                                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                                                    >
                                                        <i
                                                            class="fa-solid fa-ban text-red-600 text-lg"
                                                        ></i>
                                                    </div>
                                                    <div
                                                        class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                                    >
                                                        <h3
                                                            class="text-lg leading-6 font-medium text-gray-900 mb-4"
                                                        >
                                                            Từ chối đơn hàng
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p
                                                                class="text-sm text-gray-500 mb-4"
                                                            >
                                                                Vui lòng nhập lý
                                                                do từ chối đơn
                                                                hàng này. Thông
                                                                tin này sẽ được
                                                                gửi đến khách
                                                                hàng.
                                                            </p>
                                                            <textarea
                                                                v-model="
                                                                    rejectReason
                                                                "
                                                                rows="4"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm text-sm resize-none"
                                                                placeholder="Nhập lý do từ chối đơn hàng..."
                                                            ></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                                            >
                                                <button
                                                    @click="submitRejectReason"
                                                    type="button"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                >
                                                    <i
                                                        class="fa-solid fa-ban mr-2"
                                                    ></i>
                                                    Xác nhận từ chối
                                                </button>
                                                <button
                                                    @click="closeRejectModal"
                                                    type="button"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                >
                                                    Hủy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    v-can="'admin.sales-order.complete'"
                                    v-if="selectedOrder.status === 'shipped'"
                                    @click="completeOrder(selectedOrder.id)"
                                    class="w-full inline-flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    :disabled="complete.processing"
                                >
                                    <i class="fa-solid fa-check-double"></i>
                                    Xác nhận hoàn thành
                                </button>
                                <button
                                    v-if="selectedOrder.status === 'shipped'"
                                    v-can="'admin.sales-order.refund'"
                                    @click="openReturnModal(selectedOrder.id)"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-undo"></i>
                                    Hoàn hàng
                                </button>
                                <!-- Modal nhập lý do hoàn hàng -->
                                <div
                                    v-if="showReturnModal"
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
                                                @click="closeReturnModal"
                                            ></div>
                                        </div>
                                        <div
                                            class="inline-block relative z-50 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        >
                                            <div
                                                class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"
                                            >
                                                <div
                                                    class="sm:flex sm:items-start"
                                                >
                                                    <div
                                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10"
                                                    >
                                                        <i
                                                            class="fa-solid fa-undo text-yellow-600 text-lg"
                                                        ></i>
                                                    </div>
                                                    <div
                                                        class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
                                                    >
                                                        <h3
                                                            class="text-lg leading-6 font-medium text-gray-900 mb-4"
                                                        >
                                                            Hoàn hàng đơn xuất
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p
                                                                class="text-sm text-gray-500 mb-4"
                                                            >
                                                                Vui lòng nhập lý
                                                                do hoàn hàng.
                                                                Thông tin này sẽ
                                                                được lưu lại.
                                                            </p>
                                                            <textarea
                                                                v-model="
                                                                    returnReason
                                                                "
                                                                rows="4"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 shadow-sm text-sm resize-none"
                                                                placeholder="Nhập lý do hoàn hàng..."
                                                            ></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                                            >
                                                <button
                                                    @click="submitReturnReason"
                                                    type="button"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm"
                                                >
                                                    <i
                                                        class="fa-solid fa-undo mr-2"
                                                    ></i>
                                                    Xác nhận hoàn hàng
                                                </button>
                                                <button
                                                    @click="closeReturnModal"
                                                    type="button"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                >
                                                    Hủy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nút xác nhận đã hoàn hàng thành công khi trạng thái là 'returning' -->
                                <button
                                    v-if="selectedOrder.status === 'returning'"
                                    v-can="'admin.sales-order.refund-confirm'"
                                    @click="confirmReturned(selectedOrder.id)"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-check"></i>
                                    Xác nhận đã hoàn hàng thành công
                                </button>
                                <button
                                    v-if="
                                        !['cancelled', 'completed'].includes(
                                            selectedOrder.status
                                        )
                                    "
                                    @click="generateQR(selectedOrder.id)"
                                    type="button"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-qrcode"></i>
                                    Thanh toán OR Code
                                </button>
                                <button
                                    v-if="selectedOrder.status === 'shipped'"
                                    @click="
                                        printInvoice(selectedOrder.encrypted_id)
                                    "
                                    type="button"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    <i class="fa-solid fa-print"></i>
                                    In hóa đơn PDF
                                </button>
                                <button
                                    @click="closeModal"
                                    type="button"
                                    class="mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="qrData"
                    class="fixed inset-0 overflow-y-auto z-60"
                    @click="closeQRModal"
                >
                    <div
                        class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20"
                    >
                        <div
                            class="fixed inset-0 z-50 transition-opacity"
                            aria-hidden="true"
                        >
                            <div
                                class="absolute inset-0 bg-gray-900 opacity-75"
                            ></div>
                        </div>
                        <div
                            class="inline-block relative z-60 pb-[20px] bg-white rounded-lg overflow-hidden shadow-xl transform transition-all"
                            @click.stop
                        >
                            <div
                                class="p-6 text-center flex flex-wrap justify-center items-center w-[500px] h-[700px] gap-[5px]"
                            >
                                <img
                                    :src="qrData"
                                    alt="QR Code"
                                    class="w-full h-[85%] mx-auto mb-4"
                                />
                                <button
                                    @click="closeQRModal"
                                    type="button"
                                    class="w-full text-center h-[8%] inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                >
                                    Đóng
                                </button>
                                <button
                                    @click="copyQR()"
                                    class="w-full mb-[20px] text-center h-[8%] inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2 bg-blue-600 text-base font-medium text-[#fff] hover:bg-blue-700"
                                >
                                    Copy QR code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import emitter from "../../../eventBus.js";
console.log("Emitter in List.vue:", emitter);
import { ref, computed, onMounted, watch } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import ToastClient from "../../components/ToastClient.vue";
import { useForm, router, usePage } from "@inertiajs/vue3";
import { clearCanvas } from "chart.js/helpers";
import axios from "axios";
const { listOrders, filters: initialFilters } = defineProps({
    listOrders: {
        default: () => ({
            data: [],
            meta: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
                links: [],
            },
        }),
    },
    filters: {
        type: Object,
        default: () => ({
            customer: "",
            order_date_from: "",
            order_date_to: "",
            status: "",
        }),
    },
});

console.log("listOrders:", listOrders);
const showQRModal = ref(false);
const showRejectModal = ref(false);
const rejectReason = ref("");
const selectedOrderId = ref(null);
const pay_before = ref(0);
const pay_after = ref(0);
const isModalOpen = ref(false);
const errorMessage = ref("");
const selectedOrder = ref({
    items: [],
    customer: { phone: "", email: "" },
    pay_before: 0,
    pay_after: 0,
});
const activeTab = ref("all");
const filters = ref({
    customer: initialFilters.customer || "",
    order_date_from: initialFilters.order_date_from || "",
    order_date_to: initialFilters.order_date_to || "",
    status: initialFilters.status || "",
});

function setActiveTab(tab) {
    activeTab.value = tab;
    applyFilters();
}

function applyFilters() {
    router.get(
        route("admin.sale-orders.index"),
        {
            status: filters.value.status,
            customer: filters.value.customer,
            order_date_from: filters.value.order_date_from,
            order_date_to: filters.value.order_date_to,
            page: 1,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function openModal(order) {
    selectedOrder.value = order;
    pay_before.value = order.pay_before || 0;
    pay_after.value = order.pay_after || 0;
    errorMessage.value = "";
    isModalOpen.value = true;
}

function formatDate(dateString) {
    if (!dateString) return "Chưa xác định";
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return "Chưa xác định";
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function closeModal() {
    isModalOpen.value = false;
    pay_before.value = 0;
    pay_after.value = 0;
    errorMessage.value = "";
}

function formatCurrencyVND(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}

function formatPageNumber(page) {
    return page;
}

function validatePayBefore() {
    if (pay_before.value === "" || pay_before.value === null) {
        errorMessage.value = "";
        return;
    }
    if (isNaN(pay_before.value) || !/^\d+$/.test(pay_before.value.toString())) {
        errorMessage.value =
            "Số tiền thanh toán trước phải là một số nguyên dương hợp lệ.";
        return;
    }
    if (pay_before.value < 0) {
        errorMessage.value = "Số tiền thanh toán trước không được nhỏ hơn 0.";
        return;
    }
    if (pay_before.value > selectedOrder.value.total_amount) {
        errorMessage.value = `Số tiền thanh toán trước (${formatCurrencyVND(
            pay_before.value
        )}) không được vượt quá tổng tiền đơn hàng (${formatCurrencyVND(
            selectedOrder.value.total_amount
        )}).`;
        return;
    }
    errorMessage.value = "";
}

function validatePayAfter() {
    if (pay_after.value === "" || pay_after.value === null) {
        errorMessage.value = "";
        return;
    }
    if (isNaN(pay_after.value) || !/^\d+$/.test(pay_after.value.toString())) {
        errorMessage.value =
            "Số tiền thanh toán sau phải là một số nguyên dương hợp lệ.";
        return;
    }
    if (pay_after.value < 0) {
        errorMessage.value = "Số tiền thanh toán sau không được nhỏ hơn 0.";
        return;
    }
    const maxPayAfter =
        selectedOrder.value.total_amount -
        (selectedOrder.value.pay_before || 0);
    if (pay_after.value > maxPayAfter) {
        errorMessage.value = `Số tiền thanh toán sau (${formatCurrencyVND(
            pay_after.value
        )}) không được vượt quá số tiền còn lại (${formatCurrencyVND(
            maxPayAfter
        )}).`;
        return;
    }
    errorMessage.value = "";
}

function changePage(page) {
    if (page < 1 || page > listOrders.meta.last_page) return;
    router.get(
        route("admin.sale-orders.index"),
        {
            page,
            status: filters.value.status,
            customer: filters.value.customer,
            order_date_from: filters.value.order_date_from,
            order_date_to: filters.value.order_date_to,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function exportExcel() {
    window.location.href = route("admin.sale-orders.export", {
        status: filters.value.status,
        customer: filters.value.customer,
        order_date_from: filters.value.order_date_from,
        order_date_to: filters.value.order_date_to,
    });
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
                new Date(order.created_at).toDateString() ===
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

const approve = useForm({ pay_before: 0 });
const approveOrder = (id) => {
    console.log("Attempting to approve order:", {
        id,
        pay_before: pay_before.value,
    });
    errorMessage.value = "";
    if (
        isNaN(pay_before.value) ||
        pay_before.value === null ||
        !/^\d+$/.test(pay_before.value.toString())
    ) {
        errorMessage.value =
            "Số tiền thanh toán trước phải là một số nguyên dương hợp lệ.";
        return;
    }
    if (pay_before.value < 0) {
        errorMessage.value = "Số tiền thanh toán trước không được nhỏ hơn 0.";
        return;
    }
    if (pay_before.value > selectedOrder.value.total_amount) {
        errorMessage.value = `Số tiền thanh toán trước (${formatCurrencyVND(
            pay_before.value
        )}) không được vượt quá tổng tiền đơn hàng (${formatCurrencyVND(
            selectedOrder.value.total_amount
        )}).`;
        return;
    }
    approve.pay_before = pay_before.value;
    approve.post(route("admin.sale-orders.approve", id), {
        onSuccess: () => {
            console.log("Order approved successfully:", {
                id,
                pay_before: approve.pay_before,
            });
            closeModal();
            const order = listOrders.data.find((o) => o.id === id);
            if (order) {
                order.status = "shipped";
                order.pay_before = approve.pay_before;
            }
            emitter.emit("notification-updated");
            applyFilters();
        },
        onError: (errors) => {
            console.error("Error approving order:", errors);
            errorMessage.value =
                errors.pay_before || "Lỗi khi phê duyệt đơn hàng";
        },
        onFinish: () => {
            console.log("Approve request finished");
        },
    });
};

const complete = useForm({ pay_after: 0, customer_id: null });
const completeOrder = (id) => {
    console.log("Attempting to complete order:", {
        id,
        pay_after: pay_after.value,
    });
    errorMessage.value = "";
    if (
        isNaN(pay_after.value) ||
        pay_after.value === null ||
        !/^\d+$/.test(pay_after.value.toString())
    ) {
        errorMessage.value =
            "Số tiền thanh toán sau phải là một số nguyên dương hợp lệ.";
        return;
    }
    if (pay_after.value < 0) {
        errorMessage.value = "Số tiền thanh toán sau không được nhỏ hơn 0.";
        return;
    }
    const maxPayAfter =
        selectedOrder.value.total_amount -
        (selectedOrder.value.pay_before || 0);
    if (pay_after.value > maxPayAfter) {
        errorMessage.value = `Số tiền thanh toán sau (${formatCurrencyVND(
            pay_after.value
        )}) không được vượt quá số tiền còn lại (${formatCurrencyVND(
            maxPayAfter
        )}).`;
        return;
    }
    complete.pay_after = pay_after.value;
    complete.customer_id = selectedOrder.value.customer.id;
    complete.post(route("admin.sale-orders.complete", id), {
        onSuccess: () => {
            console.log("Order completed successfully:", {
                id,
                pay_after: complete.pay_after,
                customer_id: complete.customer_id,
            });
            closeModal();
            const order = listOrders.data.find((o) => o.id === id);
            if (order) {
                order.status = "completed";
                order.pay_after = complete.pay_after;
            }
            emitter.emit("notification-updated");
            applyFilters();
        },
        onError: (errors) => {
            console.error("Error completing order details:", {
                errors,
                errorType: typeof errors,
                errorKeys: Object.keys(errors || {}),
                errorString: JSON.stringify(errors),
            });

            errorMessage.value =
                errors?.pay_after ||
                errors?.error ||
                errors?.message ||
                "Lỗi khi xác nhận hoàn thành đơn hàng";
        },
        onFinish: () => {
            console.log("Complete request finished");
        },
    });
};

const reject = useForm({ reject_reason: "" });
const closeRejectModal = () => {
    showRejectModal.value = false;
    rejectReason.value = "";
};
const openRejectModal = (orderId) => {
    selectedOrderId.value = orderId;
    showRejectModal.value = true;
};
const submitRejectReason = () => {
    if (!rejectReason.value.trim()) {
        toastError("Vui lòng nhập lý do từ chối.");
        return;
    }

    reject.reject_reason = rejectReason.value;

    reject.post(route("admin.sale-orders.reject", selectedOrderId.value), {
        reject_reason: rejectReason.value,
        onSuccess: () => {
            console.log("Đơn hàng đã được từ chối.");
            closeRejectModal();
            closeModal();
            emitter.emit("notification-updated");
            applyFilters();
        },
        onError: (errors) => {
            console.error("Error rejecting order:", errors);
            console.log("Có lỗi xảy ra khi từ chối đơn hàng.");
        },
        onFinish: () => {
            console.log("Reject request finished");
        },
    });
};
const rejectOrder = (id) => {
    console.log("Attempting to reject order:", { id });
    reject.post(route("admin.sale-orders.reject", id), {
        onSuccess: () => {
            console.log("Order rejected successfully:", { id });
            closeModal();
            listOrders.data = listOrders.data.filter(
                (order) => order.id !== id
            );
        },
        onError: (errors) => {
            console.error("Error rejecting order:", errors);
            errorMessage.value = "Lỗi khi từ chối đơn hàng";
        },
        onFinish: () => {
            console.log("Reject request finished");
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
        case "cancelled":
            return "Từ chối";
        case "returning":
            return "Đang hoàn hàng";
        case "returned":
            return "Đã hoàn hàng";
        default:
            return "Không xác định";
    }
};
const qrData = ref(null);
const isLoadingQR = ref(false);
const qrError = ref(null);
const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
};

const generateQR = async (orderId) => {
    isLoadingQR.value = true;
    qrError.value = null;
    try {
        const response = await axios.post(
            route("admin.sale-orders.generate-qr", orderId),
            {},
            {
                headers: {
                    "Content-Type": "application/json",
                    "X-XSRF-TOKEN": getCookie("XSRF-TOKEN"),
                },
            }
        );
        qrData.value = response.data.qrDataURL;
        console.log("QR created:", response.data);
    } catch (error) {
        qrError.value = error.response?.data?.error || "Lỗi không xác định";
        console.error("Lỗi tạo QR:", qrError.value);
    } finally {
        isLoadingQR.value = false;
    }
};
const toastRef = ref();

function toastSuccess(message) {
    toastRef.value?.triggerToast(message, "success");
}
function toastError(message) {
    toastRef.value?.triggerToast(message, "error");
}
const copyQR = async () => {
    if (!qrData.value) {
        console.error("Không có QR để copy");
        qrError.value = "Không có QR để copy";
        return;
    }

    try {
        const response = await fetch(qrData.value);
        const blob = await response.blob();

        await navigator.clipboard.write([
            new ClipboardItem({ [blob.type]: blob }),
        ]);
        console.log("toastRef:", toastRef.value);
        toastSuccess("Copy QR thành công");
        console.log("QR copied to clipboard successfully");
    } catch (error) {
        console.error("Lỗi copy QR:", error);
        toastError(
            "Copy không hỗ trợ trên browser này. Vui lòng screenshot QR."
        );
    }
};
function closeQRModal() {
    qrData.value = null;
}

const page = usePage();
onMounted(() => {
    openOrderModalIfNeeded();
});

watch(
    [() => page.props?.sale_order_id, () => listOrders.data],
    () => {
        openOrderModalIfNeeded();
    },
    { immediate: true }
);

function openOrderModalIfNeeded() {
    const saleOrderId = page.props?.sale_order_id;
    if (saleOrderId) {
        const order = listOrders.data.find((o) => o.id == saleOrderId);
        if (order) openModal(order);
    }
}
function formatInputCurrency(value) {
    if (value === null || value === undefined) return "";
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function parseCurrencyInput(str) {
    return Number(str.replace(/,/g, ""));
}

function onPayBeforeInput(e) {
    const val = parseCurrencyInput(e.target.value);
    pay_before.value = isNaN(val) ? 0 : val;
}

function onPayBeforeBlur(e) {
    e.target.value = formatInputCurrency(pay_before.value);
}

function onPayAfterInput(e) {
    const val = parseCurrencyInput(e.target.value);
    pay_after.value = isNaN(val) ? 0 : val;
}

function onPayAfterBlur(e) {
    e.target.value = formatInputCurrency(pay_after.value);
}
function printInvoice(encryptedId) {
    window.open(route("admin.sale-orders.print", encryptedId), "_blank");
}
const showReturnModal = ref(false);
const returnReason = ref("");
const selectedReturnOrderId = ref(null);

function openReturnModal(orderId) {
    selectedReturnOrderId.value = orderId;
    showReturnModal.value = true;
}
function closeReturnModal() {
    showReturnModal.value = false;
    returnReason.value = "";
}
function submitReturnReason() {
    if (!returnReason.value.trim()) {
        toastError("Vui lòng nhập lý do hoàn hàng.");
        return;
    }
    axios
        .post(route("admin.sale-orders.return", selectedReturnOrderId.value), {
            return_reason: returnReason.value,
        })
        .then(() => {
            // Cập nhật trạng thái ngay trên giao diện
            const order = listOrders.data.find(
                (o) => o.id === selectedReturnOrderId.value
            );
            if (order) {
                order.status = "returning";
                order.note = "[RETURN] " + returnReason.value;
            }
            closeReturnModal();
            closeModal();
            emitter.emit("notification-updated");
            applyFilters();
        })
        .catch(() => {
            console.log("Có lỗi xảy ra khi hoàn hàng.");
        });
}
function confirmReturned(orderId) {
    axios
        .post(route("admin.sale-orders.returned", orderId))
        .then(() => {
            // Cập nhật trạng thái ngay trên giao diện
            const order = listOrders.data.find((o) => o.id === orderId);
            if (order) {
                order.status = "returned";
            }
            closeModal();
            emitter.emit("notification-updated");
            applyFilters();
        })
        .catch(() => {
            console.log("Có lỗi xảy ra khi xác nhận hoàn hàng.");
        });
}
const dateError = ref("");
const dateFilterInvalid = ref(false);
function validateAndApplyDateFilter() {
    dateError.value = "";
    dateFilterInvalid.value = false;
    if (
        filters.value.order_date_from &&
        filters.value.order_date_to &&
        filters.value.order_date_from > filters.value.order_date_to
    ) {
        dateError.value = "Ngày bắt đầu không được lớn hơn ngày kết thúc.";
        dateFilterInvalid.value = true;
        return;
    }
    applyFilters();
}
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
