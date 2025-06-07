<!-- resources/js/Pages/ReturnsCreate.vue -->
<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div
                class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center"
            >
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Thêm Bản Ghi Trả Hàng
                </h5>
                <Waiting
                    route-name="returns.index"
                    :route-params="{}"
                    :color="'bg-indigo-50 text-indigo-700'"
                >
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

            <!-- Form -->
            <div class="p-6 bg-white rounded shadow-md">
                <form @submit.prevent="handleSubmitForm" class="space-y-6">
                    <!-- Row 1: Tên khách hàng, Số điện thoại, Địa chỉ tỉnh -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Trường 1: Tên khách hàng -->
                        <div class="space-y-2">
                            <label
                                for="customer"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Tên khách hàng
                            </label>
                            <Combobox v-model="form.customer">
                                <div class="relative">
                                    <ComboboxInput
                                        id="customer"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập tên khách hàng"
                                        @input="
                                            debouncedSearchCustomer(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredCustomers.length > 0"
                                    >
                                        <ComboboxOption
                                            v-for="customer in filteredCustomers"
                                            :key="customer.id"
                                            :value="customer.name"
                                            v-slot="{ active }"
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
                            <p
                                v-if="form.errors.customer"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.customer }}
                            </p>
                        </div>

                        <!-- Trường 2: Số điện thoại -->
                        <div class="space-y-2">
                            <label
                                for="phone"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Số điện thoại
                            </label>
                            <input
                                v-model="form.phone"
                                type="text"
                                id="phone"
                                name="phone"
                                placeholder="Nhập số điện thoại"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                            />
                            <p
                                v-if="form.errors.phone"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <!-- Trường 3: Địa chỉ tỉnh -->
                        <div class="space-y-2">
                            <label
                                for="province"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Địa chỉ tỉnh
                            </label>
                            <Combobox v-model="form.province">
                                <div class="relative">
                                    <ComboboxInput
                                        id="province"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập tỉnh/thành phố"
                                        @input="
                                            debouncedSearchProvince(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredProvinces.length > 0"
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
                                    v-else-if="provinceMessage"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    {{ provinceMessage }}
                                </p>
                            </Combobox>
                            <p
                                v-if="form.errors.province"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.province }}
                            </p>
                        </div>
                    </div>

                    <!-- Row 2: Địa chỉ quận/huyện, Địa chỉ phường/xã, Sản phẩm, Số lượng -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Trường 4: Địa chỉ quận/huyện -->
                        <div class="space-y-2">
                            <label
                                for="district"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Địa chỉ quận/huyện
                            </label>
                            <Combobox v-model="form.district">
                                <div class="relative">
                                    <ComboboxInput
                                        id="district"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập quận/huyện"
                                        :disabled="!form.province"
                                        @input="
                                            debouncedSearchDistrict(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredDistricts.length > 0"
                                    >
                                        <ComboboxOption
                                            v-for="district in filteredDistricts"
                                            :key="district.id"
                                            :value="district.name"
                                            v-slot="{ active }"
                                            @click="selectDistrict(district)"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ district.name }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                                <p
                                    v-if="isLoadingDistrict"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    Đang tải...
                                </p>
                                <p
                                    v-else-if="districtMessage"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    {{ districtMessage }}
                                </p>
                            </Combobox>
                            <p
                                v-if="form.errors.district"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.district }}
                            </p>
                        </div>

                        <!-- Trường 5: Địa chỉ phường/xã -->
                        <div class="space-y-2">
                            <label
                                for="ward"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Địa chỉ phường/xã
                            </label>
                            <Combobox v-model="form.ward">
                                <div class="relative">
                                    <ComboboxInput
                                        id="ward"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập phường/xã"
                                        :disabled="!form.district"
                                        @input="
                                            debouncedSearchWard(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredWards.length > 0"
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
                                    v-else-if="wardMessage"
                                    class="text-gray-500 text-sm mt-1"
                                >
                                    {{ wardMessage }}
                                </p>
                            </Combobox>
                            <p
                                v-if="form.errors.ward"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.ward }}
                            </p>
                        </div>

                        <!-- Trường 6: Sản phẩm -->
                        <div class="space-y-2">
                            <label
                                for="product"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Sản phẩm
                            </label>
                            <Combobox v-model="form.product">
                                <div class="relative">
                                    <ComboboxInput
                                        id="product"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập tên sản phẩm"
                                        @input="
                                            debouncedSearchProduct(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredProducts.length > 0"
                                    >
                                        <ComboboxOption
                                            v-for="product in products"
                                            :key="product.id"
                                            :value="product.name"
                                            v-slot="{ active }"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ product.name }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                            <p
                                v-if="form.errors.product"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.product }}
                            </p>
                        </div>
                    </div>

                    <!-- Row 3: Số lượng, Đơn vị tính -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Trường 7: Số lượng -->
                        <div class="space-y-2">
                            <label
                                for="quantity"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Số lượng
                            </label>
                            <Combobox v-model="form.quantity">
                                <div class="relative">
                                    <ComboboxInput
                                        id="quantity"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập số lượng"
                                        @input="
                                            debouncedSearchQuantity(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredQuantities.length > 0"
                                    >
                                        <ComboboxOption
                                            v-for="quantity in filteredQuantities"
                                            :key="quantity"
                                            :value="quantity"
                                            v-slot="{ active }"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ quantity }} Kg
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                            <p
                                v-if="form.errors.quantity"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.quantity }}
                            </p>
                        </div>

                        <!-- Trường 8: Đơn vị tính -->
                        <div class="space-y-2">
                            <label
                                for="unit"
                                class="block text-sm font-medium text-indigo-700"
                            >
                                Đơn vị tính
                            </label>
                            <Combobox v-model="form.unit">
                                <div class="relative">
                                    <ComboboxInput
                                        id="unit"
                                        class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                        placeholder="Nhập đơn vị tính"
                                        @input="
                                            debouncedSearchUnit(
                                                $event.target.value
                                            )
                                        "
                                    />
                                    <ComboboxButton
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
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
                                        class="absolute z-10 mt-1 max-h-[152px] w-full overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        v-if="filteredUnits.length > 0"
                                    >
                                        <ComboboxOption
                                            v-for="unit in filteredUnits"
                                            :key="unit"
                                            :value="unit"
                                            v-slot="{ active }"
                                        >
                                            <span
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block px-4 py-2 text-sm',
                                                ]"
                                            >
                                                {{ unit }}
                                            </span>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                            <p
                                v-if="form.errors.unit"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.unit }}
                            </p>
                        </div>
                    </div>

                    <!-- Row 4: Ghi chú -->
                    <div class="space-y-2">
                        <label
                            for="remarks"
                            class="block text-sm font-medium text-indigo-700"
                        >
                            Ghi chú
                        </label>
                        <textarea
                            id="remarks"
                            v-model="form.remarks"
                            name="remarks"
                            rows="3"
                            placeholder="Nhập ghi chú"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                        ></textarea>
                        <p
                            v-if="form.errors.remarks"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.remarks }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="reset"
                            class="px-4 py-2 text-indigo-700 bg-white border border-indigo-300 rounded hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                            @click="resetForm"
                        >
                            Nhập lại
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 shadow-md hover:shadow-lg"
                            :disabled="form.processing"
                        >
                            <span class="font-medium"
                                >Thêm bản ghi trả hàng</span
                            >
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { route } from "ziggy-js";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
} from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

// Hàm debounce để trì hoãn tìm kiếm
const debounce = (func, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
};

const props = defineProps({
    saleOrders: { type: Array, default: [] },
    customers: { type: Array, default: [] },
    products: { type: Array, default: [] },
    units: { type: Array, default: [] },
});

// Dữ liệu mẫu cho các dropdown
const customerOptions = [
    { id: 1, name: "Nguyễn Văn A" },
    { id: 2, name: "Trần Thị B" },
    { id: 3, name: "Lê Văn C" },
    { id: 4, name: "Phạm Thị D" },
];

const quantityOptions = Array.from({ length: 11 }, (_, i) => i.toString());
const unitOptions = ["Kg", "Cái", "Hộp", "Bộ"];

// Dữ liệu trạng thái
const filteredCustomers = ref([]);
const filteredProvinces = ref([]);
const filteredDistricts = ref([]);
const filteredWards = ref([]);
const filteredProducts = ref([]);
const filteredQuantities = ref([]);
const filteredUnits = ref([]);

const isLoadingProvince = ref(false);
const isLoadingDistrict = ref(false);
const isLoadingWard = ref(false);

const provinceMessage = ref("");
const districtMessage = ref("");
const wardMessage = ref("");

const selectedProvince = ref(null);
const selectedDistrict = ref(null);

const form = useForm({
    customer: "",
    phone: "",
    province: "",
    district: "",
    ward: "",
    product: "",
    quantity: "",
    unit: "",
    remarks: "",
});

// Hàm gọi API chung (dùng cho tỉnh, quận, phường)
const fetchData = async (url) => {
    console.log("Gửi request tới:", url);
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const result = await response.json();

    console.log("Dữ liệu trả về từ API:", result);

    if (result.error) {
        throw new Error(result.error);
    }

    if (result.code !== "success") {
        throw new Error(result.message || "Lỗi không xác định từ API");
    }

    if (!Array.isArray(result.data)) {
        throw new Error("Dữ liệu không phải là mảng");
    }

    return result.data;
};

// Tìm kiếm khách hàng
const searchCustomer = (query) => {
    if (!query) {
        filteredCustomers.value = [];
        return;
    }
    filteredCustomers.value = customerOptions.filter((customer) =>
        customer.name.toLowerCase().includes(query.toLowerCase())
    );
};

const debouncedSearchCustomer = debounce(searchCustomer, 500);

// Tìm kiếm tỉnh/thành phố
const fetchProvinces = async (query) => {
    try {
        isLoadingProvince.value = true;
        provinceMessage.value = "";
        const size = 30;
        let page = 0;
        let allProvinces = [];

        while (true) {
            const url = `/proxy/provinces?page=${page}&size=${size}&query=${query}`;
            const provinces = await fetchData(url);
            allProvinces = [...allProvinces, ...provinces];

            if (provinces.length < size) {
                break;
            }
            page++;
        }

        console.log("Danh sách tỉnh/thành:", allProvinces);

        if (allProvinces.length === 0) {
            provinceMessage.value =
                "Không tìm thấy tỉnh/thành phố nào phù hợp.";
            filteredProvinces.value = [];
            return;
        }

        filteredProvinces.value = allProvinces;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách tỉnh/thành:", error);
        provinceMessage.value =
            "Có lỗi xảy ra khi tìm kiếm tỉnh/thành phố. Vui lòng thử lại.";
        filteredProvinces.value = [];
    } finally {
        isLoadingProvince.value = false;
    }
};

const debouncedSearchProvince = debounce(fetchProvinces, 500);

// Tìm kiếm quận/huyện
const fetchDistricts = async (query) => {
    if (!selectedProvince.value) {
        filteredDistricts.value = [];
        return;
    }

    try {
        isLoadingDistrict.value = true;
        districtMessage.value = "";
        const size = 30;
        let page = 0;
        let allDistricts = [];

        while (true) {
            const districtUrl = `/proxy/districts/${selectedProvince.value.id}?page=${page}&size=${size}`;
            const districts = await fetchData(districtUrl);
            allDistricts = [...allDistricts, ...districts];

            if (districts.length < size) {
                break;
            }
            page++;
        }

        console.log(
            `Quận/huyện của ${selectedProvince.value.name}:`,
            allDistricts
        );

        if (allDistricts.length === 0) {
            districtMessage.value = "Không tìm thấy quận/huyện nào phù hợp.";
            filteredDistricts.value = [];
            return;
        }

        filteredDistricts.value = query
            ? allDistricts.filter((district) =>
                  district.name.toLowerCase().includes(query.toLowerCase())
              )
            : allDistricts;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách quận/huyện:", error);
        districtMessage.value =
            "Có lỗi xảy ra khi tìm kiếm quận/huyện. Vui lòng thử lại.";
        filteredDistricts.value = [];
    } finally {
        isLoadingDistrict.value = false;
    }
};

const debouncedSearchDistrict = debounce(fetchDistricts, 500);

// Tìm kiếm phường/xã
const fetchWards = async (query) => {
    if (!selectedDistrict.value) {
        filteredWards.value = [];
        return;
    }

    try {
        isLoadingWard.value = true;
        wardMessage.value = "";
        const size = 30;
        let page = 0;
        let allWards = [];

        while (true) {
            const wardUrl = `/proxy/wards/${selectedDistrict.value.id}?page=${page}&size=${size}`;
            const wards = await fetchData(wardUrl);
            allWards = [...allWards, ...wards];

            if (wards.length < size) {
                break;
            }
            page++;
        }

        console.log(`Phường/xã của ${selectedDistrict.value.name}:`, allWards);

        if (allWards.length === 0) {
            wardMessage.value = "Không tìm thấy phường/xã nào phù hợp.";
            filteredWards.value = [];
            return;
        }

        filteredWards.value = query
            ? allWards.filter((ward) =>
                  ward.name.toLowerCase().includes(query.toLowerCase())
              )
            : allWards;
    } catch (error) {
        console.error("Lỗi khi lấy danh sách phường/xã:", error);
        wardMessage.value =
            "Có lỗi xảy ra khi tìm kiếm phường/xã. Vui lòng thử lại.";
        filteredWards.value = [];
    } finally {
        isLoadingWard.value = false;
    }
};

const debouncedSearchWard = debounce(fetchWards, 500);

// Tìm kiếm sản phẩm
const searchProduct = (query) => {
    if (!query) {
        filteredProducts.value = [];
        return;
    }
    filteredProducts.value = props.products.filter((product) =>
        product.name.toLowerCase().includes(query.toLowerCase())
    );
};

const debouncedSearchProduct = debounce(searchProduct, 500);

// Tìm kiếm số lượng
const searchQuantity = (query) => {
    if (!query) {
        filteredQuantities.value = [];
        return;
    }
    filteredQuantities.value = quantityOptions.filter((quantity) =>
        quantity.toLowerCase().includes(query.toLowerCase())
    );
};

const debouncedSearchQuantity = debounce(searchQuantity, 500);

// Tìm kiếm đơn vị tính
const searchUnit = (query) => {
    if (!query) {
        filteredUnits.value = [];
        return;
    }
    filteredUnits.value = unitOptions.filter((unit) =>
        unit.toLowerCase().includes(query.toLowerCase())
    );
};

const debouncedSearchUnit = debounce(searchUnit, 500);

// Xử lý khi chọn tỉnh
const selectProvince = (province) => {
    selectedProvince.value = province;
    form.district = "";
    form.ward = "";
    selectedDistrict.value = null;
    filteredDistricts.value = [];
    filteredWards.value = [];
    debouncedSearchDistrict("");
};

// Xử lý khi chọn quận/huyện
const selectDistrict = (district) => {
    selectedDistrict.value = district;
    form.ward = "";
    filteredWards.value = [];
    debouncedSearchWard("");
};

// Xử lý khi chọn phường/xã
const selectWard = (ward) => {
    // Không cần xử lý thêm vì phường/xã là cấp cuối
};

// Xử lý khi chọn khách hàng
const selectCustomer = (customer) => {
    form.customer = customer.name;
};

// Reset form
const resetForm = () => {
    form.reset();
    filteredCustomers.value = [];
    filteredProvinces.value = [];
    filteredDistricts.value = [];
    filteredWards.value = [];
    filteredProducts.value = [];
    filteredQuantities.value = [];
    filteredUnits.value = [];
    selectedProvince.value = null;
    selectedDistrict.value = null;
    provinceMessage.value = "";
    districtMessage.value = "";
    wardMessage.value = "";
};

const handleSubmitForm = () => {
    form.post(route("returns.store"), {
        onSuccess: () => {
            resetForm();
        },
        onError: () => {
            console.error("Something went wrong. Please try again.");
        },
    });
};
</script>
