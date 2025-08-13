<template>
    <AppLayout>
        <div class="px-4 py-6">
           <div class="p-4 bg-white shadow-md rounded-lg mb-4 flex justify-between items-center">
  
            <h2 class="text-xl font-bold text-indigo-700">
                Danh sách đơn hàng nhập
            </h2>     
            <div class="flex gap-3">
                <Waiting
                v-can="'admin.purchase.create'"
                route-name="admin.purchases.create"
                :route-params="{}"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-md"
                >
                <i class="fas fa-plus"></i>
                <span>Tạo yêu cầu nhập kho</span>
                </Waiting>

               <a
                :href="route('admin.purchases.export', {
                    order_status: filterForm.order_status,
                    supplier: filterForm.supplier,
                    code: filterForm.code,
                    start: filterForm.start,
                    end: filterForm.end,
                })"
                class="inline-flex items-center px-4 shadow-xl py-3 bg-green-600 text-white rounded-md hover:bg-green-700"
                download
                >
                <i class="fas fa-file-excel mr-1"></i> Xuất Excel
                </a>
            </div>
            </div>
            <div class="mb-6">
                <div class="bg-white rounded p-3">
                    <div class="flex gap-3">
                        <button
                            @click="filterByStatus('')"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 font-semibold border border-indigo-500 shadow-sm hover:shadow-md hover:text-indigo-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-border-all text-xl"
                            ></i>
                            Tất cả
                        </button>
                        <button
                            @click="filterByStatus(0)"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 text-amber-700 font-semibold border border-amber-500 shadow-sm hover:shadow-md hover:text-amber-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-hourglass-start text-xl"
                            ></i>
                            Chờ duyệt
                        </button>
                        <button
                            @click="filterByStatus(1)"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 text-green-700 font-semibold border border-green-500 shadow-sm hover:shadow-md hover:text-green-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-circle-check text-xl"
                            ></i>
                            Đã duyệt
                        </button>
                        <button
                            @click="filterByStatus(2)"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-500 shadow-sm hover:shadow-md hover:text-blue-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-bars-progress text-xl"
                            ></i>
                            Nhập một phần
                        </button>
                        <button
                            @click="filterByStatus(3)"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-purple-50 text-purple-700 font-semibold border border-purple-500 shadow-sm hover:shadow-md hover:text-purple-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-clipboard-check text-xl"
                            ></i>
                            Hoàn thành
                        </button>
                        <button
                            @click="filterByStatus(4)"
                            class="btn-shiny flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-red-700 font-semibold border border-red-500 shadow-sm hover:shadow-md hover:bg-red-100 hover:text-red-900 transition-all duration-200 ease-in-out cursor-pointer"
                        >
                            <i
                                class="fa-solid fa-ban text-xl"
                            ></i>
                            Từ chối
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div
                class="bg-white shadow rounded p-6 mb-6 flex flex-wrap gap-6 items-end"
            >
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Nhà cung cấp</label
                    >
                    <input
                        v-model="filterForm.supplier"
                        type="text"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 transition"
                        placeholder="Nhập tên nhà cung cấp"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Mã đơn hàng</label
                    >
                    <input
                        v-model="filterForm.code"
                        type="text"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 transition"
                        placeholder="Nhập mã đơn hàng"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Từ ngày</label
                    >
                    <input
                        v-model="filterForm.start"
                        type="date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 transition"
                        placeholder="Nhập mã đơn hàng"
                    />
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Đến ngày</label
                    >
                    <input
                        v-model="filterForm.end"
                        type="date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-indigo-500 transition"
                        placeholder="Nhập mã đơn hàng"
                    />
                </div>
                <div class="flex gap-4">
                    <button
                        @click="submitFilter"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition font-medium"
                    >
                        Lọc
                    </button>
                    <button
                        type="button"
                        @click="resetFilter"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-100 transition font-medium"
                    >
                        Xóa lọc
                    </button>
                </div>
                <!-- <div class="mt-4 flex gap-3">
                    <button
                        @click="submitFilter"
                        class="px-5 py-2 bg-indigo-600 text-white rounded"
                    >
                        LỌC
                    </button>
                    <button
                        @click="resetFilter"
                        class="px-5 py-2 border rounded"
                    >
                        XÓA LỌC
                    </button>
                </div> -->
            </div>

            <!-- Table -->
            <div class="bg-white shadow overflow-hidden overflow-x-auto">
                <table class="w-full text-left shadow-sm text-gray-500">
                    <thead
                        class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300"
                    >
                        <tr>
                            <th scope="col" class="px-4 py-3">Mã đơn nhập</th>
                            <th scope="col" class="px-4 py-3">Nhà cung cấp</th>
                            <th scope="col" class="px-4 py-3">Ngày tạo đơn</th>
                            <th scope="col" class="px-4 py-3">Người tạo đơn</th>
                            <th scope="col" class="px-4 py-3">Trạng thái</th>
                            <th scope="col" class="px-4 py-3 text-center">
                                Ngày giao dự kiến
                            </th>
                            <th scope="col" class="px-4 py-3 text-end">
                                Thành tiền
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="order in ordersToShow"
                            :key="order.id"
                            class="bg-white border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
                            @click="openModal(order)"
                        >
                            <th
                                scope="row"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap"
                            >
                                {{ order.code }}
                            </th>
                            <td class="px-4 py-3 text-indigo-700 font-semibold">
                                {{ order.supplier.name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ formatDate(order.created_at) }}
                            </td>
                            <td class="px-4 py-3">{{ order.user.name }}</td>
                            <td class="px-4 py-3">
                                <span
                                    :class="{
                                        'text-yellow-600 bg-yellow-100 px-2 py-1 rounded-xl':
                                            order.order_status == 0,
                                        'text-green-600 bg-green-100 px-2 py-1 rounded-xl':
                                            order.order_status == 1,
                                        'text-blue-600 bg-blue-100 px-2 py-1 rounded-xl':
                                            order.order_status == 2,
                                        'text-purple-600 bg-purple-100 px-2 py-1 rounded-xl':
                                            order.order_status == 3,
                                        'text-red-600 bg-red-100 px-2 py-1 rounded-xl':
                                            order.order_status == 4,
                                    }"
                                >
                                    {{
                                        getStatusText(order.order_status)
                                    }}</span
                                >
                            </td>
                            <td class="px-4 py-3 font-semibold text-center">
                                <span>
                                    {{
                                        order.order_date
                                            ? formatDate(order.order_date)
                                            : "Chưa xác định"
                                    }}
                                    <span
                                        v-if="
                                            order.order_date &&
                                            new Date(order.order_date) <
                                                new Date()
                                        "
                                    >
                                        <i
                                            class="fas fa-exclamation-triangle text-red-500 ml-2"
                                            title="Ngày giao đã quá hạn"
                                        ></i>
                                    </span>
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-blue-800 font-semibold flex items-center justify-end"
                            >
                                {{ formatCurrencyVND(order.total_amount) }}
                                <i class="fa-solid fa-tag text-lg ml-2"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div
                    class="px-4 py-2 border-t border-gray-200 flex items-center justify-end"
                >
                    <div class="flex justify-end space-x-1 mt-4">
                        <button
                            v-for="link in listOrders.links"
                            :key="link.label"
                            v-html="link.label"
                            :disabled="!link.url"
                            @click="$inertia.visit(link.url)"
                            :class="[
                                'px-3 py-1 rounded-md text-sm',
                                link.active
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100',
                                !link.url && 'opacity-50 cursor-not-allowed',
                            ]"
                        ></button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div v-if="isModalOpen" class="fixed inset-0 overflow-y-auto z-50">
                <div
                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <div
                        class="fixed inset-0 z-40 transition-opacity"
                        aria-hidden="true"
                    >
                        <div
                            class="absolute inset-0 hihi"
                            @click="closeModal"
                        ></div>
                    </div>

                    <div
                        class="inline-block relative z-50 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-[80%] sm:w-full"
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
                                            Đơn nhập {{ selectedOrder.id }}
                                        </h3>
                                        <button
                                            @click="closeModal"
                                            class="text-gray-400 hover:text-gray-500"
                                        >
                                            <span class="sr-only">Close</span>
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
                                            <!-- Nhà cung cấp -->
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                >
                                                    🏢 Nhà cung cấp
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-gray-900"
                                                >
                                                    {{
                                                        selectedOrder.supplier
                                                            .name
                                                    }}
                                                </td>
                                            </tr>
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2 w-1/3"
                                                >
                                                    Ngày giao dự kiến
                                                </td>
                                                <td
                                                    class="px-4 py-2 text-gray-900"
                                                >
                                                    {{
                                                        selectedOrder.order_date
                                                            ? formatDate(
                                                                  selectedOrder.order_date
                                                              )
                                                            : "Chưa xác định"
                                                    }}
                                                </td>
                                            </tr>

                                            <!-- Trạng thái -->
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
                                                                selectedOrder.order_status ==
                                                                0,
                                                            'text-green-700 bg-green-100 border border-green-300':
                                                                selectedOrder.order_status ==
                                                                1,
                                                            'text-gray-500 bg-gray-100 border border-gray-300':
                                                                ![
                                                                    0, 1,
                                                                ].includes(
                                                                    selectedOrder.order_status
                                                                ),
                                                        }"
                                                    >
                                                        {{
                                                            getStatusText(
                                                                selectedOrder.order_status
                                                            )
                                                        }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                >
                                                    Danh sách phiếu nhập
                                                </td>
                                                <td
                                                    class="px-4 py-2"
                                                    v-if="
                                                        selectedOrder
                                                            .good_receipts
                                                            .length > 0
                                                    "
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'admin.receiving.index'
                                                            )
                                                        "
                                                        class="mr-2 px-2 py-1 cursor-pointer hover:bg-indigo-100 rounded text-indigo-600 bg-indigo-50"
                                                        v-for="good in selectedOrder.good_receipts"
                                                        :data="{
                                                            code: good.code,
                                                        }"
                                                        :key="good.id"
                                                    >
                                                        {{ good.code }}
                                                    </Link>
                                                </td>
                                                <td
                                                    v-else
                                                    class="text-gray-500"
                                                >
                                                    <span class="ml-4"
                                                        >Chưa nhập hàng</span
                                                    >
                                                </td>
                                            </tr>
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                >
                                                    Người tạo đơn
                                                </td>
                                                <td>
                                                    <span class="ml-4">{{
                                                        selectedOrder.user.name
                                                    }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                class="border-b border-gray-200"
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                >
                                                    Ngày tạo đơn
                                                </td>
                                                <td>
                                                    <span class="ml-4">{{
                                                        formatDate(
                                                            selectedOrder.created_at
                                                        )
                                                    }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    selectedOrder.order_status ==
                                                    4
                                                "
                                            >
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                >
                                                    Lý do từ chối
                                                </td>
                                                <td>
                                                    <span
                                                        class="ml-4 font-bold text-red-600"
                                                    >
                                                        {{
                                                            selectedOrder.reason
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
                                                    class="bg-indigo-400 text-white text-xs uppercase"
                                                >
                                                    <tr>
                                                        <th
                                                            rowspan="2"
                                                            class="px-6 py-3 border border-white text-left font-medium tracking-wider"
                                                        >
                                                            Sản phẩm
                                                        </th>
                                                        <th
                                                            colspan="2"
                                                            class="px-6 py-3 border border-white text-center font-medium tracking-wider"
                                                        >
                                                            Số lượng
                                                        </th>
                                                        <th
                                                            rowspan="2"
                                                            class="px-6 py-3 border border-white text-center font-medium tracking-wider"
                                                        >
                                                            Đơn vị
                                                        </th>
                                                        <th
                                                            rowspan="2"
                                                            class="px-6 py-3 border border-white text-left font-medium tracking-wider"
                                                        >
                                                            Thành tiền
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th
                                                            class="px-6 py-2 border border-white text-center font-medium tracking-wider"
                                                        >
                                                            Đặt
                                                        </th>
                                                        <th
                                                            class="px-6 py-2 border border-white text-center font-medium tracking-wider"
                                                        >
                                                            Đã nhận
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
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600"
                                                        >
                                                            {{
                                                                item
                                                                    .product_variant
                                                                    .product
                                                                    .name
                                                            }}
                                                            <span
                                                                v-for="(
                                                                    attribute,
                                                                    index
                                                                ) in item
                                                                    .product_variant
                                                                    .attributes"
                                                                :key="index"
                                                                class="ml-1"
                                                            >
                                                                {{
                                                                    attribute.name
                                                                }}
                                                                {{
                                                                    attribute.value
                                                                }}
                                                            </span>
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 text-center whitespace-nowrap text-sm text-blue-600"
                                                        >
                                                            {{
                                                                item.quantity_ordered
                                                            }}
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 text-center whitespace-nowrap text-sm text-indigo-700 font-semibold"
                                                        >
                                                            {{
                                                                item.quantity_received
                                                            }}
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500"
                                                        >
                                                            {{ item.unit.name }}
                                                        </td>

                                                        <td
                                                            class="px-6 py-4 font-semibold whitespace-nowrap text-sm text-indigo-800"
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
                                            <div class="text-right mb-2">
                                                <span class="font-medium"
                                                    >{{
                                                        selectedOrder.items
                                                            .length
                                                    }}
                                                    sản phẩm</span
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
                                v-can="'admin.purchase.approve'"
                                v-if="selectedOrder.order_status == 0"
                                @click="approveOrder(selectedOrder.id)"
                                style="height: max-content"
                                class="w-full inline-flex justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-regular fa-circle-check"></i>
                                Duyệt đơn
                            </button>
                            <button
                                v-can="'admin.purchase.edit'"
                                v-if="selectedOrder.order_status == 0"
                                style="height: max-content"
                                @click="editOrder(selectedOrder.id)"
                                class="w-full inline-flex justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-regular fa-circle-check"></i>
                                Chỉnh sửa
                            </button>
                            <!-- Nút Từ chối mở box lý do -->
                            <button
                                v-can="'admin.purchase.cancel'"
                                v-if="
                                    selectedOrder.order_status == 0 &&
                                    !isRejecting
                                "
                                @click="isRejecting = true"
                                class="w-full flex justify-center gap-1 items-center rounded-md px-4 py-2 bg-red-600 text-white hover:bg-red-800 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-solid fa-ban"></i>
                                Từ chối
                            </button>

                            <!-- Khung nhập lý do -->
                            <div v-if="isRejecting" class="mt-4 w-[50%]">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    for="rejectionReason"
                                >
                                    Lý do từ chối
                                </label>
                                <textarea
                                    id="rejectionReason"
                                    v-model="rejectionReason"
                                    rows="4"
                                    placeholder="Nhập lý do từ chối đơn nhập..."
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:outline-none focus:ring-red-500"
                                ></textarea>
                                <div class="flex justify-end gap-2 mt-3">
                                    <button
                                        @click="
                                            isRejecting = false;
                                            rejectionReason = '';
                                        "
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                                    >
                                        Huỷ
                                    </button>
                                    <button
                                        @click="cancelOrder(selectedOrder.id)"
                                        :disabled="!rejectionReason.trim()"
                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
                                    >
                                        Xác nhận từ chối
                                    </button>
                                </div>
                            </div>
                            <button
                                v-if="selectedOrder.order_status == 2"
                                @click="endOrder(selectedOrder.id)"
                                style="height: max-content"
                                class="w-full inline-flex justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-pink-600 text-base font-medium text-white hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-solid fa-xmark text-xl"></i> Kết
                                thúc đơn
                            </button>
                            <Waiting
                                v-can="'admin.receiving.create'"
                                v-if="
                                    selectedOrder.order_status == 1 ||
                                    selectedOrder.order_status == 2
                                "
                                route-name="admin.receiving.create"
                                :route-params="{ id: selectedOrder.id }"
                                :color="'mt-3 w-full flex shadow-xl justify-center gap-1 items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm'"
                            >
                                <i
                                    class="fa-solid fa-file-lines text-xl mr-1"
                                ></i>
                                <div>
                                    Tạo phiếu nhập
                                    <span
                                        style="margin-right: 5px"
                                        v-if="
                                            selectedOrder.good_receipts.length >
                                            0
                                        "
                                        >lần
                                        {{
                                            selectedOrder.good_receipts.length +
                                            1
                                        }}</span
                                    >
                                </div>
                            </Waiting>
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
import { Link, useForm } from "@inertiajs/vue3";

const { listOrders, filters: initialFilters } = defineProps({
    listOrders: {
        default: () => ({}),
    },
    filters: { type: Object, default: () => ({}) },
});
const isRejecting = ref(false);
const rejectionReason = ref("");

function formatCurrencyVND(value) {
    if (value == null || isNaN(value)) return "0 ₫";
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
        minimumFractionDigits: 0,
    }).format(value);
}

const isModalOpen = ref(false);
const selectedOrder = ref({ items: [] });
const activeTab = ref("all");
const filterForm = useForm({
    order_status: initialFilters?.order_status ?? "",
    supplier: initialFilters?.supplier ?? "",
    code: initialFilters?.code ?? "",
    start: initialFilters?.start ?? "",
    end: initialFilters?.end ?? "",
});
const submitFilter = () => {
    filterForm.get(route("admin.purchases.index"), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
const filterByStatus = (status) => {
    filterForm.order_status = (status ?? "") === "" ? "" : Number(status);
    submitFilter();
};
const resetFilter = () => {
    filterForm.supplier = "";
    filterForm.code = "";
    filterForm.start = "";
    filterForm.end = "";

    filterForm.get(route("admin.purchases.index"), {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
};
function openModal(order) {
    selectedOrder.value = order;
    isModalOpen.value = true;
}

function formatDate(dateString) {
    if (!dateString) return "";
    const iso = String(dateString);
    const ymd = iso.slice(0, 10);
    const [y, m, d] = ymd.split("-");
    return `${d}/${m}/${y}`;
}

function closeModal() {
    isModalOpen.value = false;
}
const ordersToShow = computed(() => {
    const arr = listOrders?.data;
    return Array.isArray(arr) ? arr : [];
});
// const filteredOrders = computed(() => {
//     let orders = listOrders.data || [];

//     if (activeTab.value !== "all") {
//         orders = orders.filter(
//             (order) => order.status.toLowerCase() === activeTab.value
//         );
//     }
//     if(initialFilters != undefined){
//     if (initialFilters.supplier) {
//         orders = orders.filter((order) =>
//             order.supplier.name
//                 .toLowerCase()
//                 .includes(initialFilters.supplier.toLowerCase())
//         );
//     }
//     if (initialFilters.created_at) {
//         orders = orders.filter(
//             (order) =>
//                 new Date(order.created_at).toDateString() ===
//                 new Date(initialFilters.created_at).toDateString()
//         );
//     }
//     if (initialFilters.status) {
//         orders = orders.filter(
//             (order) => order.status === initialFilters.status
//         );
//     }
// }

//     return orders;
// });
const approve = useForm({});
const end = useForm({});
const cancel = useForm({
    reason: "",
});
const edit = useForm({});
const approveOrder = (id) => {
    approve.post(route("admin.purchases.approve", id), {
        onSuccess: () => {
            closeModal();
        },
    });
};
const endOrder = (id) => {
    end.post(route("admin.purchases.end", id), {
        onSuccess: () => {
            closeModal();
        },
    });
};
const cancelOrder = (id) => {
    if (!rejectionReason.value.trim()) return;

    cancel.reason = rejectionReason.value;

    cancel.post(route("admin.purchases.cancel", id), {
        onSuccess: () => {
            closeModal();
            isRejecting.value = false;
            rejectionReason.value = "";
            cancel.reset();
        },
    });
};
const editOrder = (id) => {
    edit.get(route("admin.purchases.edit", id), {
        onSuccess: () => {
            closeModal();
        },
    });
};
const getStatusText = (status) => {
    switch (status) {
        case 0:
            return "Chờ duyệt";
        case 1:
            return "Đã duyệt";
        case 2:
            return "Nhập một phần";
        case 3:
            return "Đã hoàn thành";
        case 4:
            return "Đã từ chối";
        default:
            return "Không xác định";
    }
};
const renderDate = function (dateString) {
    const today = new Date();
    const inputDate = new Date(dateString);

    today.setHours(0, 0, 0, 0);
    inputDate.setHours(0, 0, 0, 0);

    if (inputDate < today) {
        return `<span style="color:red;">
                  <i class="fas fa-exclamation-triangle"></i> ${dateString}
                </span>`;
    } else {
        return `<span>${dateString}</span>`;
    }
};
</script>

<style scoped>
.hihi {
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
.btn-shiny{
  position: relative;
  overflow: hidden;
}

/* Vệt sáng */
.btn-shiny::before{
  content: "";
  position: absolute;
  top: -50%;
  left: -130%;
  width: 45%;
  height: 200%;
  background: linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent);
  transform: rotate(25deg);
  pointer-events: none;
}

.btn-shiny:hover::before{
  animation: shiny-sweep .9s ease;
}

@keyframes shiny-sweep{
  0%   { left: -130%; }
  100% { left: 130%; }
}

</style>
