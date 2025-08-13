<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100">
            <!-- Flash Messages -->
            <div
                v-if="$page.props.flash.success"
                class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50"
            >
                {{ $page.props.flash.success }}
            </div>
            <div
                v-if="$page.props.errors.error"
                class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50"
            >
                {{ $page.props.errors.error }}
            </div>

            <!-- Top Header -->
            <div
                class="bg-indigo-600 text-white p-3 flex items-center justify-between relative"
            >
                <div class="flex items-center space-x-4 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <!-- Search Product Input -->
                        <svg
                            class="w-4 h-4 absolute z-10 left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                        <Combobox v-model="selectedProduct">
                            <div class="relative">
                                <ComboboxInput
                                    id="searchProduct"
                                    class="w-full bg-white pl-10 pr-4 py-2 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="Tìm kiếm sản phẩm"
                                    @input="
                                        debouncedSearchProduct(
                                            $event.target.value
                                        )
                                    "
                                />
                                <ComboboxButton
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2"
                                >
                                    <ChevronDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </ComboboxButton>
                            </div>
                            <transition
                                leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <ComboboxOptions
                                    v-if="filteredProducts.length > 0"
                                    class="absolute z-10 mt-1 max-h-60 w-[300px] overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none left-0 top-full"
                                >
                                    <ComboboxOption
                                        v-for="product in filteredProducts"
                                        :key="product.variant_id"
                                        :value="product"
                                        v-slot="{ active }"
                                        @pointerdown="selectProduct(product)"
                                    >
                                        <span
                                            :class="[
                                                active
                                                    ? 'bg-gray-100 text-gray-900'
                                                    : 'text-gray-700',
                                                'block px-4 py-2 text-sm',
                                            ]"
                                        >
                                            {{ product.product_name }} ({{
                                                formatPrice(product.sale_price)
                                            }})
                                        </span>
                                    </ComboboxOption>
                                </ComboboxOptions>
                            </transition>
                            <p
                                v-if="isLoadingProduct"
                                class="text-gray-500 text-sm mt-1 absolute left-0 top-full bg-white px-2 py-1"
                            >
                                Đang tải...
                            </p>
                            <p
                                v-else-if="productError"
                                class="text-red-500 text-sm mt-1 absolute left-0 top-full bg-white px-2 py-1"
                            >
                                {{ productError }}
                            </p>
                            <p
                                v-else-if="productMessage"
                                class="text-gray-500 text-sm mt-1 absolute left-0 top-full bg-white px-2 py-1"
                            >
                                {{ productMessage }}
                            </p>
                        </Combobox>
                    </div>
                </div>
            </div>

            <div class="flex h-screen">
                <!-- Left Panel - Product Information -->
                <div class="w-1/2 p-4 space-y-4">
                    <!-- Product Cards -->
                    <div
                        v-for="(productGroup, index) in groupedProducts"
                        :key="index"
                        class="bg-white rounded-lg border-2 border-indigo-200 p-4 relative"
                        :ref="`productCard-${index}`"
                    >
                        <div
                            v-for="(product, productIndex) in productGroup"
                            :key="productIndex"
                        >
                            <div
                                v-if="productIndex > 0"
                                class="border-t border-dashed border-gray-300 my-3"
                            ></div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-600 font-medium">{{
                                        product.id
                                    }}</span>
                                    <span class="text-gray-800">{{
                                        product.name
                                    }}</span>
                                    <svg
                                        class="w-4 h-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button
                                        class="p-1 hover:bg-gray-100 rounded"
                                        title="Thêm"
                                        @click="selectProduct(product)"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                    </button>
                                    <button
                                        @click="toggleProductMenu(product.id)"
                                        class="p-1 hover:bg-gray-100 rounded"
                                        title="Tùy chọn"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Product Menu -->
                            <div
                                v-if="showProductMenu === product.id"
                                class="absolute right-4 top-16 bg-white border rounded-lg shadow-lg py-2 z-10 w-[220px]"
                            >
                                <button
                                    @click="removeProduct(product)"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center space-x-2"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    <span>Xóa sản phẩm</span>
                                </button>
                                <button
                                    class="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center space-x-2"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        />
                                    </svg>
                                    <span>Ghi chú</span>
                                </button>
                                <button
                                    class="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center space-x-2"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <span>Xem chi tiết</span>
                                </button>
                                <button
                                    @click="selectAllVariants(product)"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center space-x-2"
                                >
                                    <i class="fa-solid fa-check-double"></i>
                                    <span>Chọn tất cả biến thể</span>
                                </button>
                                <button
                                    v-if="product.hasOtherUnits"
                                    @click="toggleUnitConverter(product)"
                                    class="w-full px-4 py-2 text-left hover:bg-gray-50 flex items-center space-x-2"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                                        />
                                    </svg>
                                    <span>{{
                                        product.useCustomUnit
                                            ? "Tắt chuyển đổi đơn vị"
                                            : "Bật chuyển đổi đơn vị khác"
                                    }}</span>
                                </button>
                            </div>

                            <!-- Quantity and Price -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <button
                                        @click="decreaseQuantity(product)"
                                        class="w-8 h-8 border rounded flex items-center justify-center hover:bg-gray-50"
                                        :disabled="product.quantity <= 1"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 12H4"
                                            />
                                        </svg>
                                    </button>
                                    <input
                                        v-model.number="product.quantity"
                                        class="w-12 text-center border-b border-red-300 focus:outline-none focus:border-red-500"
                                        type="number"
                                        min="1"
                                        @input="validateQuantity(product)"
                                    />
                                    <button
                                        @click="increaseQuantity(product)"
                                        class="w-8 h-8 border rounded flex items-center justify-center hover:bg-gray-50"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    v-if="product.useCustomUnit"
                                    class="converter"
                                >
                                    <div class="input-section">
                                        <select
                                            v-model="product.selectedUnitId"
                                            class="select"
                                            @change="updateUnit(product)"
                                        >
                                            <option
                                                v-for="unit in product.availableUnits"
                                                :key="unit.unit_id"
                                                :value="unit.unit_id"
                                            >
                                                {{ unit.unit_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="output-section">
                                        <span class="output"
                                            >{{
                                                product.conversionFactor || 1
                                            }}
                                            {{ product.defaultUnitName }}</span
                                        >
                                    </div>
                                </div>
                                <div class="text-right flex space-x-4">
                                    <span class="text-gray-600">{{
                                        formatPrice(product.price)
                                    }}</span>
                                    <span class="text-lg font-semibold">
                                        {{
                                            formatPrice(
                                                product.price *
                                                    product.quantity *
                                                    (product.conversionFactor ||
                                                        1)
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                            <!-- Lỗi số lượng vượt kho -->
                            <p
                                v-if="product.quantityError"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ product.quantityError }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg p-4 space-y-3">
                        <div class="flex items-center space-x-2 text-gray-600">
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                />
                            </svg>
                            <span>Ghi chú</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Tạm tính</span>
                                <div class="flex items-center space-x-4">
                                    <span>{{ formatPrice(subtotal) }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span>Giảm giá</span>
                                <span>{{ customer_discount }} %</span>
                            </div>
                            <div
                                class="flex justify-between font-semibold text-lg"
                            >
                                <span>Tổng cộng</span>
                                <span class="text-indigo-600">{{
                                    formatPrice(totalAmount)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Customer Information -->
                <div class="w-1/2 p-4 space-y-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"
                                />
                            </svg>
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">
                                {{ currentDateTime }}
                            </div>
                        </div>
                    </div>

                    <!-- Customer Form -->
                    <div class="bg-white rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span>Đối tác giao hàng</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-500">Chọn đối tác</span>
                                <svg
                                    class="w-4 h-4 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                                <button
                                    class="p-1 hover:bg-gray-100 rounded"
                                    title="Thêm đối tác"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div class="relative">
                            <svg
                                class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                            <Combobox v-model="form.customer">
                                <div class="relative">
                                    <ComboboxInput
                                        id="customer"
                                        class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                        placeholder="Khách hàng"
                                        @input="
                                            debouncedSearchCustomer(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2"
                                    >
                                        <ChevronDownIcon
                                            class="h-5 w-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </ComboboxButton>
                                </div>
                                <transition
                                    leave-active-class="transition ease-in duration-100"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <ComboboxOptions
                                        v-if="filteredCustomers.length > 0"
                                        class="absolute z-10 mt-1 max-h-60 w-[300px] overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <ComboboxOption
                                            v-for="customer in filteredCustomers"
                                            :key="customer.id"
                                            :value="customer"
                                            v-slot="{ active }"
                                            @pointerdown="
                                                selectCustomer(customer)
                                            "
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ customer.name }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                        </div>
                        <p
                            v-if="isLoadingCustomer"
                            class="text-gray-500 text-sm mt-1"
                        >
                            Đang tải...
                        </p>
                        <p
                            v-else-if="customerError"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ customerError }}
                        </p>
                        <p
                            v-else-if="customerMessage"
                            class="text-gray-500 text-sm mt-1"
                        >
                            {{ customerMessage }}
                        </p>

                        <!-- Phone Number -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-3 h-3 bg-indigo-500 rounded-full"
                            ></div>
                            <span class="text-indigo-600">{{
                                form.phone || "Chưa chọn khách hàng"
                            }}</span>
                            <svg
                                class="w-4 h-4 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </div>

                        <!-- Customer Details -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-3 h-3 bg-green-500 rounded-full"
                                ></div>
                                <input
                                    v-model="form.recipientName"
                                    type="text"
                                    placeholder="Tên người nhận"
                                    class="flex-1 py-2 border-b border-gray-200 focus:outline-none focus:border-indigo-500"
                                />
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    placeholder="Số điện thoại"
                                    class="flex-1 py-2 border-b border-gray-200 focus:outline-none focus:border-indigo-500"
                                />
                            </div>
                            <!-- Province -->
                            <Combobox v-model="form.province">
                                <div class="relative">
                                    <ComboboxInput
                                        id="province"
                                        class="w-full py-2 border-b border-gray-200 focus:outline-none focus:border-indigo-500"
                                        placeholder="Tỉnh/Thành phố"
                                        @input="
                                            debouncedSearchProvince(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2"
                                    >
                                        <ChevronDownIcon
                                            class="h-5 w-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </ComboboxButton>
                                </div>
                                <transition
                                    leave-active-class="transition ease-in duration-100"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <ComboboxOptions
                                        v-if="filteredProvinces.length > 0"
                                        class="absolute z-10 mt-1 max-h-60 w-[300px] overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <ComboboxOption
                                            v-for="province in filteredProvinces"
                                            :key="province.id"
                                            :value="province.name"
                                            v-slot="{ active }"
                                            @click="selectProvince(province)"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ province.name }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                                <p
                                    v-if="isLoadingProvince"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    Đang tải...
                                </p>
                                <p
                                    v-else-if="provinceError"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ provinceError }}
                                </p>
                                <p
                                    v-else-if="provinceMessage"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    {{ provinceMessage }}
                                </p>
                            </Combobox>

                            <!-- Ward -->
                            <Combobox v-model="form.ward">
                                <div class="relative">
                                    <ComboboxInput
                                        id="ward"
                                        class="w-full py-2 border-b border-gray-200 focus:outline-none focus:border-indigo-500"
                                        placeholder="Phường/Xã"
                                        :disabled="!form.province"
                                        @input="
                                            debouncedSearchWard(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute right-2 top-1/2 transform -translate-x-1/2"
                                    >
                                        <ChevronDownIcon
                                            class="h-5 w-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </ComboboxButton>
                                </div>
                                <transition
                                    leave-active-class="transition ease-in duration-100"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <ComboboxOptions
                                        v-if="filteredWards.length > 0"
                                        class="absolute z-10 mt-1 max-h-60 w-[300px] overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <ComboboxOption
                                            v-for="ward in filteredWards"
                                            :key="ward.id"
                                            :value="ward.name"
                                            v-slot="{ active }"
                                            @click="selectWard(ward)"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ ward.name }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                                <p
                                    v-if="isLoadingWard"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    Đang tải...
                                </p>
                                <p
                                    v-else-if="wardError"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ wardError }}
                                </p>
                                <p
                                    v-else-if="wardMessage"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    {{ wardMessage }}
                                </p>
                            </Combobox>
                            <input
                                v-model="form.address_detail"
                                type="text"
                                placeholder="Địa chỉ chi tiết (Số nhà, ngõ, đường)"
                                class="w-full py-2 border-b border-gray-200 focus:outline-none focus:border-indigo-500"
                                :disabled="!form.ward"
                            />
                        </div>

                        <!-- Note -->
                    </div>

                    <!-- Payment Section -->
                    <div class="bg-white rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span>Số tiền phải trả</span>
                                <button
                                    class="p-1 hover:bg-gray-100 rounded"
                                    title="Tùy chọn thanh toán"
                                ></button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold">{{
                                formatPrice(totalAmount)
                            }}</span>
                        </div>
                    </div>

                    <!-- Complete Button -->
                    <button
                        class="w-full bg-indigo-600 text-white py-4 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition-colors"
                        @click="handleComplete"
                        :disabled="form.processing || hasQuantityError"
                    >
                        {{ form.processing ? "Đang xử lý..." : "HOÀN TẤT" }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
} from "@headlessui/vue";
import AppLayout from "../Layouts/AppLayout.vue";
// Debounce function to optimize API calls
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
};

// Props
const props = defineProps({
    products: { type: Array, default: () => [] },
});

// Reactive state
const customer_discount = ref(0);
const showProductMenu = ref(null);
const codEnabled = ref(true);
const packageWeight = ref(500);
const packageLength = ref(10);
const packageWidth = ref(10);
const packageHeight = ref(10);
const localProducts = ref([]);
const filteredProducts = ref([]);
const filteredCustomers = ref([]);
const filteredProvinces = ref([]);
const filteredWards = ref([]);
const isLoadingCustomer = ref(false);
const isLoadingProduct = ref(false);
const isLoadingProvince = ref(false);
const isLoadingWard = ref(false);
const customerMessage = ref("");
const productMessage = ref("");
const provinceMessage = ref("");
const wardMessage = ref("");
const selectedProduct = ref(null);
const selectedProvince = ref(null);

// Validation errors
const customerError = ref("");
const productError = ref("");
const provinceError = ref("");
const wardError = ref("");

// Form state
const form = useForm({
    recipientName: "",
    customer: "",
    customer_id: null,
    phone: "",
    province: "",
    ward: "",
    address_detail: "",
    items: [],
    total_amount: 0,
});

// Initialize localProducts from localStorage or props
const savedCart = localStorage.getItem("cart");
if (savedCart) {
    try {
        const parsedCart = JSON.parse(savedCart);
        if (Array.isArray(parsedCart)) {
            localProducts.value = parsedCart.map((product) => ({
                ...product,
                useCustomUnit: product.useCustomUnit || false,
                selectedUnitId: product.selectedUnitId || null,
                hasOtherUnits: product.hasOtherUnits || false,
                availableUnits: product.availableUnits || [],
                conversionFactor: Number(product.conversionFactor) || 1,
                defaultUnitId: product.defaultUnitId || null,
                defaultUnitName: product.defaultUnitName || "cái",
                quantityError: product.quantityError || "",
                quantity_on_hand: product.quantity_on_hand || 0,
            }));
        }
    } catch (error) {
        console.error("Lỗi khi parse dữ liệu từ localStorage:", error);
        localProducts.value = [];
    }
} else {
    localProducts.value = props.products.map((product) => ({
        id: product.variant_id || null,
        name: product.product_name || "Unknown",
        price: Number(product.sale_price) || 0,
        quantity: 1,
        attribute_value_id: product.attribute_value_id || [],
        product_id: product.product_id || null,
        useCustomUnit: false,
        selectedUnitId: null,
        hasOtherUnits: false,
        availableUnits: [],
        conversionFactor: 1,
        defaultUnitId: product.default_unit_id || null,
        defaultUnitName: product.default_unit_name || "cái",
        quantityError: "",
        quantity_on_hand: 0,
    }));
}

// Sync localProducts with localStorage
watch(
    localProducts,
    (newProducts) => {
        localStorage.setItem("cart", JSON.stringify(newProducts));
    },
    { deep: true }
);

// Computed properties
const currentDateTime = computed(() =>
    new Date().toLocaleString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    })
);

const groupedProducts = computed(() => {
    const groups = [];
    const productMap = new Map();
    localProducts.value.forEach((product) => {
        if (!productMap.has(product.id)) {
            productMap.set(product.id, []);
            groups.push(productMap.get(product.id));
        }
        productMap.get(product.id).push(product);
    });
    return groups;
});

const totalQuantity = computed(() =>
    localProducts.value.reduce(
        (sum, product) =>
            sum + (product.quantity || 1) * (product.conversionFactor || 1),
        0
    )
);

const subtotal = computed(() =>
    localProducts.value.reduce(
        (sum, product) =>
            sum +
            (Number(product.price) || 0) *
                (product.quantity || 1) *
                (product.conversionFactor || 1),
        0
    )
);

const totalAmount = computed(
    () => subtotal.value * (1 - customer_discount.value / 100)
);

const hasQuantityError = computed(() =>
    localProducts.value.some((product) => product.quantityError)
);

// Format price utility
const formatPrice = (price) => {
    if (price == null || isNaN(price)) {
        console.warn("Giá trị giá không hợp lệ:", price);
        return "0 ₫";
    }
    return Number(price).toLocaleString("vi-VN", {
        style: "currency",
        currency: "VND",
    });
};

// Generic API fetch function
const fetchData = async (url) => {
    const fullUrl = `http://127.0.0.1:8000${url}`;
    try {
        const response = await fetch(fullUrl);
        if (!response.ok) {
            const errorText = await response.text();
            console.error(
                `HTTP error! URL: ${fullUrl}, Status: ${response.status}, Response: ${errorText}`
            );
            throw new Error(
                `HTTP error! Status: ${response.status} - ${errorText}`
            );
        }
        const result = await response.json();
        console.log(`API ${url} response:`, result);
        if (result.error) {
            throw new Error(result.error);
        }
        return Array.isArray(result)
            ? result
            : result.data && Array.isArray(result.data)
            ? result.data
            : result.quantity_on_hand !== undefined
            ? result
            : [];
    } catch (error) {
        console.error(`Lỗi khi gọi API ${url}:`, error.message);
        throw error;
    }
};

// Fetch inventory quantity
const fetchInventoryQuantity = async (productVariantId) => {
    try {
        const url = `/admin/sale-orders/inventory/${productVariantId}`;
        const result = await fetchData(url);
        return Number(result.quantity_on_hand) || 0;
    } catch (error) {
        console.error("Lỗi khi lấy số lượng tồn kho:", error);
        return 0;
    }
};

// Fetch customers
const fetchCustomers = async (query) => {
    try {
        isLoadingCustomer.value = true;
        customerError.value = "";
        const url = `/admin/sale-orders/search/customers?searchCustomer=${query}`;
        filteredCustomers.value = await fetchData(url);
        if (!filteredCustomers.value.length) {
            customerError.value = "Không tìm thấy khách hàng nào phù hợp.";
        }
    } catch (error) {
        customerError.value = "Có lỗi xảy ra khi tìm kiếm khách hàng.";
        filteredCustomers.value = [];
    } finally {
        isLoadingCustomer.value = false;
    }
};

const debouncedSearchCustomer = debounce(fetchCustomers, 500);

// Select customer
const selectCustomer = async (customer) => {
    if (!customer?.id || !customer?.name) {
        customerError.value = "Khách hàng không hợp lệ.";
        return;
    }
    form.customer = customer.name;
    form.customer_id = customer.id;
    form.phone = customer.phone || "";
    form.recipientName = customer.name;
    const customer_ranking = customer.rank_id || null;
    customer_discount.value = customer.rank.discount_percent || 0;
    // Reset address fields
    form.province = "";
    form.ward = "";
    selectedProvince.value = null;
    filteredWards.value = [];

    // Fill province, district, ward if available
    if (customer.province) {
        try {
            await fetchProvinces(customer.province);
            const matchedProvince = filteredProvinces.value.find(
                (p) => p.name.toLowerCase() === customer.province.toLowerCase()
            );
            if (matchedProvince) {
                selectProvince(matchedProvince);
                form.province = matchedProvince.name;

                // Sau khi chọn province, điền lại ward nếu có
                if (customer.ward) {
                    await fetchWards(customer.ward);
                    const matchedWard = filteredWards.value.find(
                        (w) =>
                            w.name.toLowerCase() === customer.ward.toLowerCase()
                    );
                    if (matchedWard) {
                        selectWard(matchedWard);
                        form.ward = matchedWard.name;
                    } else {
                        wardMessage.value = "Không tìm thấy phường/xã phù hợp.";
                    }
                }
            } else {
                provinceMessage.value =
                    "Không tìm thấy tỉnh/thành phố phù hợp.";
            }
        } catch (error) {
            customerError.value = "Lỗi khi điền thông tin địa chỉ.";
        }
    }

    filteredCustomers.value = [];
    customerError.value = "";
};

// Fetch products
const fetchProducts = async (query) => {
    try {
        isLoadingProduct.value = true;
        productError.value = "";
        const url = `/admin/sale-orders/search/products?searchProduct=${query}`;
        filteredProducts.value = await fetchData(url);
        if (!filteredProducts.value.length) {
            productError.value = "Không tìm thấy sản phẩm nào phù hợp.";
        }
    } catch (error) {
        productError.value = "Có lỗi xảy ra khi tìm kiếm sản phẩm.";
        filteredProducts.value = [];
    } finally {
        isLoadingProduct.value = false;
    }
};

const debouncedSearchProduct = debounce(fetchProducts, 500);

// Fetch unit conversions
const fetchUnitConversions = async (productId, defaultUnitId) => {
    try {
        const url = `/admin/sale-orders/unit-conversions/${productId}`;
        const conversions = await fetchData(url);
        const hasOtherUnits = conversions.some(
            (conv) => conv.from_unit_id === defaultUnitId
        );
        const availableUnits = conversions
            .filter((conv) => conv.from_unit_id === defaultUnitId)
            .map((conv) => ({
                unit_id: conv.to_unit_id,
                unit_name: conv.to_unit_name,
                conversion_factor: Number(conv.conversion_factor) || 1,
            }));
        if (!hasOtherUnits) {
            console.warn(`No unit conversions found for product ${productId}`);
        }
        return { hasOtherUnits, availableUnits };
    } catch (error) {
        console.error("Lỗi khi lấy đơn vị chuyển đổi:", error);
        return { hasOtherUnits: false, availableUnits: [] };
    }
};

// Select product
const selectProduct = async (product) => {
    if (
        !product?.variant_id ||
        !product?.product_name ||
        !product?.product_id
    ) {
        productError.value = "Sản phẩm không hợp lệ.";
        return;
    }
    selectedProduct.value = product;

    // Kiểm tra số lượng tồn kho
    const quantityOnHand = await fetchInventoryQuantity(product.variant_id);
    const quantityRequested = 1;
    if (quantityOnHand === 0) {
        productError.value = `Sản phẩm ${product.product_name} đã hết hàng`;
        return;
    }
    if (quantityRequested > quantityOnHand) {
        productError.value = `Số lượng sản phẩm ${product.product_name} vượt quá số lượng trong kho (còn ${quantityOnHand}).`;
        return;
    }

    if (!localProducts.value.some((p) => p.id === product.variant_id)) {
        const { hasOtherUnits, availableUnits } = await fetchUnitConversions(
            product.product_id,
            product.default_unit_id
        );
        localProducts.value.unshift({
            id: product.variant_id,
            name: product.product_name,
            price: Number(product.sale_price) || 0,
            quantity: quantityRequested,
            attribute_value_id: product.attribute_value_id || [],
            product_id: product.product_id,
            useCustomUnit: false,
            selectedUnitId: null,
            hasOtherUnits,
            availableUnits,
            conversionFactor: 1,
            defaultUnitId: product.default_unit_id || null,
            defaultUnitName: product.default_unit_name || "cái",
            quantityError: "",
            quantity_on_hand: quantityOnHand,
        });
        const cardRef = `productCard-0`;
        setTimeout(() => {
            const element = document.querySelector(`[ref="${cardRef}"]`);
            if (element) {
                element.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }, 100);
        productMessage.value = `Đã thêm sản phẩm: ${product.product_name}`;
        setTimeout(() => (productMessage.value = ""), 2000);
    } else {
        const existingProduct = localProducts.value.find(
            (p) => p.id === product.variant_id
        );
        const quantityRequested = (existingProduct.quantity || 0) + 1;
        if (quantityRequested > existingProduct.quantity_on_hand) {
            productError.value = `Số lượng sản phẩm ${product.product_name} vượt quá số lượng trong kho (còn ${existingProduct.quantity_on_hand}).`;
            return;
        }
        existingProduct.quantity = quantityRequested;
        await validateQuantity(existingProduct);
        productMessage.value = `Đã tăng số lượng sản phẩm: ${product.product_name}`;
        setTimeout(() => (productMessage.value = ""), 2000);
    }
    selectedProduct.value = null;
    filteredProducts.value = [];
    productError.value = "";
};

// Fetch all variants
const fetchAllVariants = async (productId) => {
    try {
        isLoadingProduct.value = true;
        productMessage.value = "";
        const url = `/admin/sale-orders/variants/${productId}`;
        const variants = await fetchData(url);
        if (!variants.length) {
            productMessage.value = "Không tìm thấy biến thể nào.";
            return;
        }
        const product = localProducts.value.find(
            (p) => p.product_id === productId
        );
        const defaultUnitId = product ? product.defaultUnitId : null;
        const defaultUnitName = product ? product.defaultUnitName : "cái";
        const { hasOtherUnits, availableUnits } = await fetchUnitConversions(
            productId,
            defaultUnitId
        );
        let addedCount = 0;
        for (const variant of variants) {
            const quantityOnHand = await fetchInventoryQuantity(
                variant.variant_id
            );
            const quantityRequested = 1;
            if (quantityOnHand === 0) {
                productMessage.value = `Sản phẩm biến thể ${variant.product_name} không tồn tại trong kho.`;
                continue;
            }
            if (quantityRequested > quantityOnHand) {
                productMessage.value = `Số lượng sản phẩm biến thể ${variant.product_name} vượt quá số lượng trong kho (còn ${quantityOnHand}).`;
                continue;
            }
            if (!localProducts.value.some((p) => p.id === variant.variant_id)) {
                localProducts.value.unshift({
                    id: variant.variant_id,
                    name: variant.product_name,
                    price: Number(variant.sale_price) || 0,
                    quantity: quantityRequested,
                    attribute_value_id: variant.attribute_value_id || [],
                    product_id: productId,
                    useCustomUnit: false,
                    selectedUnitId: null,
                    hasOtherUnits,
                    availableUnits,
                    conversionFactor: 1,
                    defaultUnitId,
                    defaultUnitName,
                    quantityError: "",
                    quantity_on_hand: quantityOnHand,
                });
                addedCount++;
            }
        }
        const cardRef = `productCard-0`;
        setTimeout(() => {
            const element = document.querySelector(`[ref="${cardRef}"]`);
            if (element) {
                element.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        }, 100);
        productMessage.value = `Đã thêm ${addedCount} biến thể.`;
        setTimeout(() => (productMessage.value = ""), 2000);
    } catch (error) {
        productMessage.value =
            "Có lỗi xảy ra khi lấy biến thể. Vui lòng thử lại.";
    } finally {
        isLoadingProduct.value = false;
    }
};

// Select all variants
const selectAllVariants = (product) => {
    if (!product.product_id) {
        productMessage.value = "Không thể lấy biến thể. Vui lòng thử lại.";
        return;
    }
    showProductMenu.value = null;
    fetchAllVariants(product.product_id);
};

// Fetch provinces
const fetchProvinces = async (query) => {
    try {
        isLoadingProvince.value = true;
        provinceError.value = "";
        provinceMessage.value = "";
        const url = `/provinces?query=${query}`;
        const response = await fetchData(url);
        filteredProvinces.value = Array.isArray(response) ? response : [];
        if (!filteredProvinces.value.length) {
            provinceMessage.value =
                "Không tìm thấy tỉnh/thành phố nào phù hợp.";
        }
    } catch (error) {
        provinceError.value = `Có lỗi xảy ra khi tìm kiếm tỉnh/thành phố: ${error.message}. Vui lòng thử lại.`;
        filteredProvinces.value = [];
    } finally {
        isLoadingProvince.value = false;
    }
};

const debouncedSearchProvince = debounce(fetchProvinces, 500);

// Fetch districts

// Fetch wards
const fetchWards = async (query) => {
    if (!selectedProvince.value) {
        filteredWards.value = [];
        return;
    }
    try {
        isLoadingWard.value = true;
        wardError.value = "";
        wardMessage.value = "";
        const url = `/wards/${selectedProvince.value.province_code}?query=${query}`;
        const response = await fetchData(url);
        filteredWards.value = Array.isArray(response) ? response : [];
        if (!filteredWards.value.length) {
            wardMessage.value = "Không tìm thấy phường/xã nào phù hợp.";
        }
    } catch (error) {
        wardError.value =
            "Có lỗi xảy ra khi tìm kiếm phường/xã. Vui lòng thử lại.";
        filteredWards.value = [];
    } finally {
        isLoadingWard.value = false;
    }
};
const debouncedSearchWard = debounce(fetchWards, 500);

// Select province
const selectProvince = (province) => {
    selectedProvince.value = province;
    form.province = province.name;
    form.ward = "";
    filteredWards.value = [];
    provinceError.value = "";
    debouncedSearchWard(""); // Sau khi chọn tỉnh, load danh sách phường/xã
};

// Select district

// Select ward
const selectWard = (ward) => {
    form.ward = ward.name;
    wardError.value = "";
};

// Validate quantity
const validateQuantity = async (product) => {
    product.quantityError = "";
    const quantityRequested =
        product.quantity *
        (product.useCustomUnit ? product.conversionFactor : 1);
    const quantityOnHand = await fetchInventoryQuantity(product.id);
    if (quantityOnHand === 0) {
        product.quantityError = `Sản phẩm ${product.name} không tồn tại trong kho.`;
        return;
    }
    if (quantityRequested > quantityOnHand) {
        product.quantityError = `Số lượng sản phẩm ${product.name} vượt quá số lượng trong kho (còn ${quantityOnHand}).`;
    }
    product.quantity_on_hand = quantityOnHand;
};

// Increase/decrease quantity
const increaseQuantity = async (product) => {
    product.quantity = (product.quantity || 1) + 1;
    await validateQuantity(product);
};

const decreaseQuantity = async (product) => {
    if (product.quantity > 1) {
        product.quantity -= 1;
        await validateQuantity(product);
    }
};

// Remove product
const removeProduct = (product) => {
    const index = localProducts.value.findIndex((p) => p.id === product.id);
    if (index !== -1) {
        localProducts.value.splice(index, 1);
        productMessage.value = `Đã xóa sản phẩm: ${product.name}`;
        setTimeout(() => (productMessage.value = ""), 2000);
    }
    showProductMenu.value = null;
};

// Toggle product menu
const toggleProductMenu = (productId) => {
    showProductMenu.value =
        showProductMenu.value === productId ? null : productId;
};

// Toggle unit converter
const toggleUnitConverter = async (product) => {
    product.useCustomUnit = !product.useCustomUnit;
    if (product.useCustomUnit && product.availableUnits.length > 0) {
        product.selectedUnitId = product.availableUnits[0].unit_id;
        product.conversionFactor =
            Number(product.availableUnits[0].conversion_factor) || 1;
        console.log(
            "Selected unit:",
            product.selectedUnitId,
            product.conversionFactor
        );
    } else {
        product.useCustomUnit = false;
        product.selectedUnitId = null;
        product.conversionFactor = 1;
    }
    await validateQuantity(product);
    showProductMenu.value = null;
};

// Update unit
const updateUnit = async (product) => {
    const selectedUnit = product.availableUnits.find(
        (unit) => unit.unit_id === product.selectedUnitId
    );
    product.conversionFactor = selectedUnit
        ? Number(selectedUnit.conversion_factor) || 1
        : 1;
    await validateQuantity(product);
};

// Handle complete
const handleComplete = () => {
    // Reset all validation errors
    customerError.value = "";
    productError.value = "";
    provinceError.value = "";
    wardError.value = "";
    localProducts.value.forEach((product) => (product.quantityError = ""));

    // Validate customer
    if (!form.customer_id) {
        customerError.value = "Vui lòng chọn khách hàng!";
    }

    // Validate products
    if (!localProducts.value.length) {
        productError.value = "Vui lòng chọn ít nhất một sản phẩm!";
    }

    // Validate address fields
    const hasAddress = form.address_detail || form.ward || form.province;
    if (!hasAddress) {
        if (!form.province)
            provinceError.value = "Vui lòng chọn tỉnh/thành phố!";
        if (!form.ward) wardError.value = "Vui lòng chọn phường/xã!";
    }

    // Check quantity errors
    if (hasQuantityError.value) {
        productError.value =
            "Một hoặc nhiều sản phẩm vượt quá số lượng trong kho.";
        return;
    }

    // Prepare form items
    form.items = localProducts.value.map((product) => ({
        product_variant_id: product.id,
        quantity: product.quantity,
        price: product.price,
        useCustomUnit: product.useCustomUnit,
        selectedUnitId: product.useCustomUnit ? product.selectedUnitId : null,
        defaultUnitId: product.defaultUnitId,
        conversionFactor: Number(product.conversionFactor) || 1,
    }));
    form.total_amount = totalAmount.value;

    // Validate form items
    if (
        !form.items.every(
            (item) =>
                item.product_variant_id &&
                item.quantity >= 1 &&
                item.defaultUnitId &&
                (!item.useCustomUnit ||
                    (item.useCustomUnit && item.selectedUnitId))
        )
    ) {
        productError.value = `Dữ liệu sản phẩm không hợp lệ! Items: ${JSON.stringify(
            form.items
        )}`;
        return;
    }

    console.log("Dữ liệu gửi đi:", {
        customer_id: form.customer_id,
        total_amount: form.total_amount,
        address_detail: form.address_detail,
        ward: form.ward,
        province: form.province,
        items: form.items,
    });

    form.post(route("admin.sale-orders.store"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            productMessage.value = "Tạo đơn hàng thành công!";
            setTimeout(() => (productMessage.value = ""), 2000);
            localProducts.value = [];
            localStorage.removeItem("cart");
            form.reset();
        },
        onError: (errors) => {
            console.error("Lỗi khi tạo đơn hàng:", errors);
            let errorMessage = "Có lỗi xảy ra khi tạo đơn hàng.";
            if (errors.customer_id) {
                errorMessage = "Khách hàng không hợp lệ.";
            } else if (errors.items) {
                errorMessage = `Dữ liệu sản phẩm không hợp lệ: ${JSON.stringify(
                    errors.items
                )}`;
            } else if (errors.total_amount) {
                errorMessage = "Tổng tiền không hợp lệ.";
            } else if (errors.error) {
                // Xử lý lỗi từ backend (như sản phẩm không tồn tại trong kho)
                localProducts.value.forEach((product) => {
                    if (errors.error.includes(product.name)) {
                        product.quantityError = errors.error;
                    }
                });
                errorMessage = errors.error;
            }
            productError.value = errorMessage;
            setTimeout(() => (productError.value = ""), 3000);
        },
    });
};

// Handle click outside to close product menu
onMounted(() => {
    try {
        console.log("Environment check:", {
            windowExists: typeof window !== "undefined",
            documentExists: typeof document !== "undefined",
            document: window.document,
            addEventListenerExists:
                window.document &&
                typeof window.document.addEventListener === "function",
        });
        if (
            typeof window !== "undefined" &&
            window.document &&
            typeof window.document.addEventListener === "function"
        ) {
            const handleClickOutside = (e) => {
                if (!e.target.closest(".relative")) {
                    showProductMenu.value = null;
                }
            };
            window.document.addEventListener("click", handleClickOutside);
            onUnmounted(() => {
                window.document.removeEventListener(
                    "click",
                    handleClickOutside
                );
            });
        } else {
            console.warn(
                "Client-side environment not available, skipping addEventListener"
            );
        }
    } catch (error) {
        console.error("Lỗi trong onMounted:", error);
    }
});
</script>

<style scoped>
.converter {
    display: flex;
    align-items: center;
    gap: 8px;
    background: white;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    width: 300px;
    height: 70px;
}

.input-section {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}

.output-section {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 1;
    font-size: 14px;
    font-weight: 500;
    color: #1f2937;
    background: #f3f4f6;
    border-radius: 4px;
    padding: 6px 8px;
}

.select {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.output {
    text-align: center;
}

/* Remove number input arrows */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
