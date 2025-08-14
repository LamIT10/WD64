<template>
    <AppLayout>
        <div class="p-6 bg-gray-50 min-h-screen">
            <div class="p-4 bg-white mb-6 flex justify-between items-center rounded-xl shadow-sm">
                <h5 class="text-xl text-indigo-700 font-bold">
                    Danh sách Nhà cung cấp
                </h5>
                <div class="flex items-center space-x-4">
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
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                        <button @click="toggleSearchForm"
                            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors shadow-sm">
                            <i class="fas fa-search"></i>
                            <span>Tìm kiếm</span>
                        </button>
                    </div>
                    <Waiting route-name="admin.suppliers.create" :route-params="{}" v-can="'admin.supplier.create'"
                        class="bg-indigo-600 text-white px-4 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors flex items-center shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Thêm mới
                    </Waiting>
                </div>
            </div>
            <div v-if="showSearchForm" class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form @submit.prevent="submitSearch" class="flex flex-col gap-4 w-full">
                    <div class="grid grid-cols-2 gap-6 mb-6 w-full">
                        <!-- Tên nhà cung cấp -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-building mr-2 text-indigo-500"></i>
                                Tên nhà cung cấp
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.supplierName"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên nhà cung cấp" />
                                <i
                                    class="fa-solid fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Đại diện -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-user mr-2 text-indigo-500"></i>
                                Đại diện
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.contactPerson"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên người đại diện" />
                                <i
                                    class="fa-solid fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-phone mr-2 text-indigo-500"></i>
                                Số điện thoại
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.phone"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập số điện thoại" />
                                <i
                                    class="fa-solid fa-phone absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-envelope mr-2 text-indigo-500"></i>
                                Email
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.email"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập email" />
                                <i
                                    class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa-solid fa-map-marker-alt mr-2 text-indigo-500"></i>
                                Địa chỉ
                            </label>
                            <div class="relative">
                                <input v-model="searchForm.address"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    placeholder="Nhập địa chỉ" />
                                <i
                                    class="fa-solid fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Số tiền nợ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Số tiền nợ
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="relative w-[50%]">
                                    <label class="block text-sm font-medium text-gray-700">
                                        <i class="fa-solid fa-money-bill-wave mr-2 text-indigo-500"></i>
                                        Từ
                                    </label>
                                    <div class="relative">
                                        <input v-model="searchForm.fromPayment"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                            placeholder="Số tiền từ" />
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
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                            placeholder="Số tiền đến" />
                                        <i
                                            class="fa-solid fa-money-bill-wave absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-600">
                        <thead class="text-xs text-gray-700 bg-indigo-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Tên nhà cung cấp
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Đại diện
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Số điện thoại
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Nợ hiện tại
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Địa chỉ
                                </th>
                                <th scope="col" class="px-6 py-3 font-semibold">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="supplier in suppliers.data" :key="supplier.id">
                                <tr 
                                    class="bg-white border-b border-gray-100 cursor-pointer transition-colors">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap" @click="toggleRow(supplier.id)">
                                        {{ supplier.name }}
                                    </th>
                                    <td class="px-6 py-4" @click="toggleRow(supplier.id)">
                                        {{ supplier.contact_person }}
                                    </td>
                                    <td class="px-6 py-4" @click="toggleRow(supplier.id)">
                                        {{ supplier.phone }}
                                    </td>
                                    <td class="px-6 py-4" @click="toggleRow(supplier.id)">
                                        {{ supplier.email }}
                                    </td>
                                    <td class="px-6 py-4" @click="toggleRow(supplier.id)">
                                        {{ formatNumber(supplier.debt) + " ₫" }}
                                    </td>
                                    <td @click="toggleRow(supplier.id)" class="px-6 py-4" style=" 
                                            max-width: 200px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">
                                        {{ supplier.address }}
                                    </td>
                                    <td class="px-6 py-4 relative">
                                        <button @click.stop="
                                            toggleMenu(supplier.id)
                                            " :id="'dropdownMenuIconButton-' +
                                                supplier.id
                                                "
                                            class="inline-flex items-center p-2 text-gray-600 hover:bg-indigo-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor" viewBox="0 0 4 15">
                                                <path
                                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                            </svg>
                                        </button>
                                        <div v-if="menuVisible[supplier.id]"
                                            class="z-[10000] absolute top-0 right-[100%] bg-white rounded-lg shadow-2xl border border-gray-200 w-52 transition-all duration-200">
                                            <ul class="py-2 text-sm font-medium text-gray-800">
                                                <li  v-can="'admin.supplier.edit'">
                                                    <Waiting color="bg-white" route-name="admin.suppliers.edit"
                                                        :route-params="{
                                                            id: supplier.id,
                                                        }"
                                                        class="flex items-center px-4 py-3 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150">
                                                        <i class="fas fa-edit mr-2"></i>
                                                        Sửa
                                                    </Waiting>
                                                </li>
                                                <li v-ca="'admin.supplier.product'">
                                                    <Waiting route-name="admin.suppliers.products" color="bg-white"
                                                        :route-params="{
                                                            id: supplier.id,
                                                        }"
                                                        class="flex items-center px-4 py-3 hover:bg-green-50 hover:text-green-700 text-green-600 transition-colors duration-150">
                                                        <i class="fas fa-box-open mr-2"></i>
                                                        Quản lý sản phẩm
                                                    </Waiting>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="expandedRows[supplier.id]" class="bg-indigo-100">
                                    <td colspan="7" class="px-2 py-2">
                                        <div class="ml-2 my-4 bg-white rounded-xl shadow-sm border border-indigo-200">
                                            <div class="flex items-center p-5 border-b border-indigo-100">
                                                <i class="fas fa-receipt mr-3 text-indigo-500 text-lg"></i>
                                                <h6 class="font-bold text-indigo-700 text-xl">
                                                    Lịch sử công nợ
                                                </h6>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm text-left text-gray-600">
                                                    <thead class="text-xs text-indigo-700 bg-indigo-50">
                                                        <tr>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Mã phiếu nhập
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Ngày nhận hàng
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Ghi chú
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Trạng thái
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Tổng phiếu nhập
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Người tạo
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Đã thanh toán
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Cần thành toán
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Hạn công nợ
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Ngày giao dịch
                                                            </th>
                                                            <th class="px-6 py-3 font-semibold">
                                                                Thao tác
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template v-for="purchase_order in supplier.purchase_orders || {
                                                            good_receipts: false,
                                                        }">
                                                            <tr v-for="(receipt,indexNumber) in purchase_order.good_receipts ||[]" :key="receipt.id"
                                                             class="bg-white hover:bg-indigo-50 transition-colors border-b border-indigo-100 relative">
                                                                <td class="px-6 py-3 font-medium text-indigo-600"
                                                                    @click="
                                                                        ShowDetail(
                                                                            receipt.id
                                                                        )
                                                                        ">
                                                                    {{
                                                                        receipt.code
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        formatDateForDisplay(
                                                                            receipt.receipt_date
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3 max-w-xs truncate" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        receipt.note ||
                                                                        "--"
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    <span v-if="
                                                                        receipt.total_amount -
                                                                        receipt
                                                                            .supplier_transaction
                                                                            .paid_amount ==
                                                                        0
                                                                    "
                                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 mt-1">
                                                                        Đã thanh
                                                                        toán
                                                                    </span>
                                                                    <span v-else-if="
                                                                        CheckDate(
                                                                            receipt
                                                                                .supplier_transaction
                                                                                .credit_due_date
                                                                        ) ==
                                                                        3
                                                                    "
                                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700 mt-1">
                                                                        Sắp hết
                                                                        hạn
                                                                    </span>
                                                                    <span v-else-if="
                                                                        CheckDate(
                                                                            receipt
                                                                                .supplier_transaction
                                                                                .credit_due_date
                                                                        ) ==
                                                                        2
                                                                    "
                                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 mt-1">
                                                                        Còn hạn
                                                                    </span>
                                                                    <span v-else-if="
                                                                        CheckDate(
                                                                            receipt
                                                                                .supplier_transaction
                                                                                .credit_due_date
                                                                        ) ==
                                                                        1
                                                                    "
                                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 mt-1">
                                                                        Hết hạn
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-3 font-medium" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        formatPrice(
                                                                            receipt.total_amount
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        receipt.create_by.name ||
                                                                        "--"
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3 font-medium" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        formatPrice(
                                                                            receipt
                                                                                .supplier_transaction
                                                                                ?.paid_amount ||
                                                                            0
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3 font-medium" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        formatPrice(
                                                                            receipt.total_amount -
                                                                            receipt
                                                                                .supplier_transaction
                                                                                ?.paid_amount
                                                                        )
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        receipt.supplier_transaction
                                                                            ? formatDateForDisplay(
                                                                                receipt
                                                                                    .supplier_transaction
                                                                                    .credit_due_date
                                                                            )
                                                                            : "--"
                                                                    }}
                                                                </td>
                                                                <td class="px-6 py-3" @click="
                                                                    ShowDetail(
                                                                        receipt.id
                                                                    )
                                                                    ">
                                                                    {{
                                                                        receipt.supplier_transaction
                                                                            ? formatDateForDisplay(
                                                                                receipt
                                                                                    .supplier_transaction
                                                                                    .transaction_date
                                                                            )
                                                                            : "--"
                                                                    }}
                                                                </td>
                                                                <td class="px-4 py-3 text-sm text-gray-500 absolute">
                                                                    <button @click="
                                                                        toggleActionPopup(
                                                                            receipt.id,
                                                                            $event
                                                                        )
                                                                        " :disabled="receipt
                                                                            .supplier_transaction
                                                                            ?.paid_amount -
                                                                            receipt.total_amount >= 0"
                                                                        class="text-gray-500 hover:text-gray-700 focus:outline-none disabled:opacity-50"
                                                                        aria-label="Action menu">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-4 w-4" viewBox="0 0 20 20"
                                                                            fill="currentColor">
                                                                            <path
                                                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                                        </svg>
                                                                    </button>
                                                                    <!-- Popup hành động -->
                                                                    <transition
                                                                        enter-active-class="transition ease-out duration-100"
                                                                        enter-from-class="transform opacity-0 scale-95"
                                                                        enter-to-class="transform opacity-100 scale-100"
                                                                        leave-active-class="transition ease-in duration-75"
                                                                        leave-from-class="transform opacity-100 scale-100"
                                                                        leave-to-class="transform opacity-0 scale-95">
                                                                        <div v-show="activePopup ===
                                                                            receipt.id
                                                                            " :id="'popup' +
                                                                                (receipt.id)
                                                                                "
                                                                            class="absolute right-0 top-8 z-[1000] w-44 max-h-64 bg-white rounded-lg shadow-md ring-1 ring-gray-200 overflow-y-auto popup">
                                                                            <div class="py-1 text-sm">
                                                                                <button v-can="'admin.supplier_transaction.update_credit_due_date'
                                                                                    " @click="
                                                                                        OpenEditCreditDueDate(
                                                                                            receipt
                                                                                        )
                                                                                        "
                                                                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                                                                    Cập
                                                                                    nhật
                                                                                    hạn
                                                                                    công
                                                                                    nợ
                                                                                </button>
                                                                                <button v-can="'admin.supplier_transaction.update_paid_amount'
                                                                                    " @click="
                                                                                        openPayment(
                                                                                            receipt
                                                                                        )
                                                                                        "
                                                                                    class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                                                                    Cập
                                                                                    nhật
                                                                                    thanh
                                                                                    toán
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </transition>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                        <tr v-if="
                                                            supplier
                                                                .purchase_orders
                                                                .length ===
                                                            0
                                                        ">
                                                            <td colspan="10"
                                                                class="px-6 py-6 text-center text-gray-500">
                                                                <div
                                                                    class="flex flex-col items-center justify-center py-6">
                                                                    <i
                                                                        class="fas fa-inbox text-4xl text-indigo-200 mb-3"></i>
                                                                    <span class="text-base">Không
                                                                        có phiếu
                                                                        nhập
                                                                        nào</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <Pagination :data="suppliers" :perPage="formPerpage.perPage" :searchForm="queryData" />
            </div>
            <ModalCreditDueDate v-if="isOpenCredit" :transactionSupplierEdit="transactionSupplierEdit"
                @closeModal="HandleCloseModal" />
            <ModalPaymentTransaction v-if="isOpenPayment" :transactionSupplierEdit="transactionSupplierEdit"
                @closeModal="HandleCloseModal" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import { onMounted, onUnmounted, onUpdated, reactive, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import ModalCreditDueDate from "../Suppliertransactions/ModalCreditDueDate.vue";
import ModalPaymentTransaction from "../Suppliertransactions/ModalPaymentTransaction.vue";
import Pagination from "../../components/Pagination.vue";
import { formatNumber } from "chart.js/helpers";

const { suppliers } = defineProps({
    suppliers: {
        default: () => ({}),
    },
});
const today = new Date();
const CheckDate = (date) => {
    const d = new Date(date);
    const from = new Date(d);
    from.setDate(d.getDate() - 7);

    if (today >= from && today <= d) {
        return 3; // sắp hết hạn
    } else if (Date.parse(date) > Date.parse(today) == true) {
        return 2; // còn hạn
    } else if (Date.parse(date) < Date.parse(today) == true) {
        return 1; // hết hạn
    }
};

const isOpenCredit = ref(false);
const isOpenPayment = ref(false);
const HandleCloseModal = () => {
    isOpenCredit.value = false;
    isOpenPayment.value = false;
};
const transactionSupplierEdit = reactive({});
const openPayment = (item) => {
    isOpenPayment.value = true;
    transactionSupplierEdit.value = item;
};

const OpenEditCreditDueDate = (item) => {
    isOpenCredit.value = true;
    transactionSupplierEdit.value = item;
};
const menuVisible = reactive({});
const expandedRows = reactive({});

function toggleMenu(id) {
    Object.keys(menuVisible).forEach((key) => {
        if (key !== String(id)) menuVisible[key] = false;
    });
    menuVisible[id] = !menuVisible[id];
}

function toggleRow(id) {
    expandedRows[id] = !expandedRows[id];
    Object.keys(menuVisible).forEach((key) => {
        menuVisible[key] = false;
    });
}
const activePopup = ref(null);

// Toggle popup
const toggleActionPopup = (index, event) => {
    event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
    activePopup.value = activePopup.value === index ? null : index;
    console.log(activePopup.value);
};

// Đóng popup khi click ra ngoài
const handleOutsideClick = (event) => {
    const popup = document.querySelector(".popup");
    const button = event.target.closest("button");

    if (
        !popup?.contains(event.target) &&
        !button?.matches('[aria-label="Action menu"]')
    ) {
        activePopup.value = null;
    }
};

onMounted(() => {
    document.addEventListener("click", handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener("click", handleOutsideClick);
});
onUnmounted(() => {
    document.removeEventListener("click", handleOutsideClick);
});
const getStatusText = (status) => {
    const statusMap = {
        approved: "Đã duyệt",
        pending: "Chờ duyệt",
        rejected: "Từ chối",
    };
    return statusMap[status] || "--";
};



const formatPrice = (price) => {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(price);
};

const formatDateForDisplay = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return "";
    return `${String(date.getDate()).padStart(2, "0")}/${String(
        date.getMonth() + 1
    ).padStart(2, "0")}/${date.getFullYear()}`;
};
const ShowDetail = (id) => {
    router.visit(
        route("admin.supplier-transaction.show", {
            id: id,
        })
    );
};



const formPerpage = useForm({
    perPage: "20"
})
const url = new URL(window.location.href);
// Lấy tất cả tham số từ query string
const params = new URLSearchParams(url.search);
const queryData = reactive({
    toPayment: 0,
    fromPayment: 0,
    supplierName: "",
    contactPerson: "",
    phone: "",
    email: "",
    address: "",
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
    supplierName: queryData.supplierName ?? "",
    contactPerson: queryData.contactPerson ?? "",
    phone: queryData.phone ?? "",
    email: queryData.email ?? "",
    address: queryData.address ?? "",
    fromPayment: queryData.fromPayment ?? "",
    toPayment: queryData.toPayment ?? 0,
});
// Tạo object để lưu trữ dữ liệu    



const handlePerpage = () => {
    formPerpage.get(route("admin.suppliers.index", {
        perPage: formPerpage.perPage,
        supplierName: searchForm.supplierName,
        contactPerson: searchForm.contactPerson,
        phone: searchForm.phone,
        email: searchForm.email,
        address: searchForm.address,
        fromPayment: searchForm.fromPayment,
        toPayment: searchForm.toPayment,
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
    searchForm.get(route('admin.suppliers.index'));
};

// Reset search form
const resetSearch = () => {
    searchForm.supplierName = "";
    searchForm.contactPerson = "";
    searchForm.phone = "";
    searchForm.email = "";
    searchForm.address = "";
    searchForm.fromPayment = 0;
    searchForm.toPayment = 0;
};
const array = Object.entries(queryData)
const newUrl = ref("");
for (let index = 0; index < array.length; index++) {
    if (array.length - 1 == index) {
        newUrl.value += array[index][0] + "=" + array[index][1];
    } else {
        newUrl.value += array[index][0] + "=" + array[index][1] + "&";
    }
}
</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c7c7c7;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}

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
