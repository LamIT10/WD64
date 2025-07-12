<template>
    <AppLayout>
        <div class="px-4 py-6">
            <div
                class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center"
            >
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách đơn hàng nhập
                </h5>
                <Waiting
                    route-name="admin.purchases.create"
                    :route-params="{}"
                    class="inline-flex items-center px-4 shadow-xl py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                    <i class="fas fa-plus mr-1"></i> Tạo yêu cầu nhập kho
                </Waiting>
            </div>

            <div class="mb-6">
                <div class="bg-white rounded p-3">
                    <nav
                        class="flex gap-3 justify-start items-center"
                        aria-label="Tabs"
                    >
                        <!-- TẤT CẢ -->
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{}"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 font-semibold border border-indigo-600 shadow-sm hover:shadow-md hover:bg-indigo-100 hover:text-indigo-900 transition-all duration-200 ease-in-out animate-fade-in cursor-pointer'"
                        >
                            <i class="fa-solid fa-border-all text-xl"></i>
                            Tất cả đơn nhập
                        </Waiting>

                        <!-- CHỜ DUYỆT -->
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{ order_status: 0 }"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-50 text-yellow-800 font-semibold border border-yellow-500 shadow-sm hover:shadow-md hover:bg-yellow-100 hover:text-yellow-900 transition-all duration-200 ease-in-out cursor-pointer'"
                        >
                            <i class="fa-solid fa-hourglass-start text-xl"></i>
                            Chờ duyệt
                        </Waiting>

                        <!-- ĐÃ DUYỆT -->
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{ order_status: 1 }"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 text-green-700 font-semibold border border-green-500 shadow-sm hover:shadow-md hover:bg-green-100 hover:text-green-900 transition-all duration-200 ease-in-out cursor-pointer'"
                        >
                            <i
                                class="fa-solid fa-file-circle-check text-xl"
                            ></i>
                            Đã duyệt
                        </Waiting>
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{ order_status: 2 }"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold border border-blue-500 shadow-sm hover:shadow-md hover:bg-blue-100 hover:text-blue-900 transition-all duration-200 ease-in-out cursor-pointer'"
                        >
                            <i
                                class="fa-solid fa-file-circle-check text-xl"
                            ></i>
                            Nhập một phần
                        </Waiting>
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{ order_status: 3 }"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-purple-50 text-purple-700 font-semibold border border-purple-500 shadow-sm hover:shadow-md hover:bg-purple-100 hover:text-purple-900 transition-all duration-200 ease-in-out cursor-pointer'"
                        >
                            <i
                                class="fa-solid fa-file-circle-check text-xl"
                            ></i>
                            Hoàn thành
                        </Waiting>
                        <Waiting
                            route-name="admin.purchases.index"
                            :route-params="{ order_status: 4 }"
                            :color="'flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-red-700 font-semibold border border-red-500 shadow-sm hover:shadow-md hover:bg-red-100 hover:text-red-900 transition-all duration-200 ease-in-out cursor-pointer'"
                        >
                            <i class="fa-solid fa-ban text-xl"></i>
                            Từ chối
                        </Waiting>
                    </nav>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 bg-white p-6 rounded-sm border border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Nhà cung cấp -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1"
                        >
                            Nhà cung cấp
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                v-model="filters.supplier"
                                class="peer w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm placeholder-gray-400 transition-all"
                                placeholder="Nhập tên hoặc mã nhà cung cấp..."
                            />
                            <i
                                class="fa-solid fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm peer-focus:text-indigo-500 transition"
                            ></i>
                        </div>
                    </div>

                    <!-- Ngày tạo đơn -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1"
                        >
                            Ngày tạo đơn
                        </label>
                        <input
                            type="date"
                            v-model="filters.created_at"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition-all"
                        />
                    </div>

                    <!-- Trạng thái -->
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
                            <option value="0">Chờ duyệt</option>
                            <option value="1">Đã duyệt</option>
                            <option value="2">Nhập một phần</option>
                            <option value="3">Đã hoàn thành</option>
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
                            <th scope="col" class="px-4 py-2">Mã đơn nhập</th>
                            <th scope="col" class="px-4 py-2">Nhà cung cấp</th>
                            <th scope="col" class="px-4 py-2">Ngày tạo đơn</th>
                            <th scope="col" class="px-4 py-2">Người tạo đơn</th>
                            <th scope="col" class="px-4 py-2">Trạng thái</th>
                            <th scope="col" class="px-4 py-2 text-center">
                                Ngày giao dự kiến
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
                                            'checkbox-table-search-' + order.id
                                        "
                                        type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 focus:ring-2"
                                    />
                                    <label
                                        :for="
                                            'checkbox-table-search-' + order.id
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
                            <td class="px-4 py-2 text-indigo-700 font-semibold">
                                {{ order.supplier.name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ formatDate(order.created_at) }}
                            </td>
                            <td class="px-4 py-2">{{ order.user.name }}</td>
                            <td class="px-4 py-2">
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
                            <td
                                class="px-4 py-2 text-orange-600 font-semibold text-center"
                            >
                                {{ formatDate(order.order_date) }}
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
                <div
                    class="px-4 py-2 border-t border-gray-200 flex items-center justify-between"
                >
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">1</span> đến
                        <span class="font-medium">2</span> của
                        <span class="font-medium">10</span> kết quả
                    </div>
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
                                            <tr v-if="selectedOrder.order_status == 4">
                                                <td
                                                    class="bg-gray-50 font-medium text-gray-700 px-4 py-2"
                                                >
                                                    Lý do từ chối
                                                </td>
                                                <td>
                                                    <span class="ml-4 font-bold text-red-600">
                                                        {{selectedOrder.reason}}
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
                                v-if="selectedOrder.order_status == 0"
                                @click="approveOrder(selectedOrder.id)"
                                style="height: max-content"
                                class="w-full inline-flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-regular fa-circle-check"></i>
                                Duyệt đơn
                            </button>
                            <button
                                v-if="selectedOrder.order_status == 0"
                                style="height: max-content"
                                @click="editOrder(selectedOrder.id)"
                                class="w-full inline-flex shadow-xl justify-center gap-1 items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <i class="fa-regular fa-circle-check"></i>
                                Chỉnh sửa
                            </button>
                            <!-- Nút Từ chối mở box lý do -->
                            <button
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
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500"
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

                            <Waiting
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
                                Tạo phiếu nhập
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

const { listOrders } = defineProps({
    listOrders: {
        default: () => ({}),
    },
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
const filters = ref({
    supplier: "",
    created_at: "",
    status: "",
});

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

const filteredOrders = computed(() => {
    let orders = listOrders.data || [];

    if (activeTab.value !== "all") {
        orders = orders.filter(
            (order) => order.status.toLowerCase() === activeTab.value
        );
    }

    if (filters.value.supplier) {
        orders = orders.filter((order) =>
            order.supplier.name
                .toLowerCase()
                .includes(filters.value.supplier.toLowerCase())
        );
    }
    if (filters.value.created_at) {
        orders = orders.filter(
            (order) =>
                new Date(order.created_at).toDateString() ===
                new Date(filters.value.created_at).toDateString()
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
</style>
