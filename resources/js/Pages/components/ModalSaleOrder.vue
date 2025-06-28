<!-- resources/js/components/ReturnModal.vue -->
<template>
    <VueFinalModal
        :modelValue="isOpen"
        class="fixed inset-0 flex justify-center items-center z-50"
        content-class="w-[630px] min-h-[460px] max-w-[90%] mx-auto my-[20px] shadow-lg rounded-[10px] bg-white transform relative z-50"
        overlay-class="fixed inset-0 bg-[rgb(75,85,99)]/50 z-40"
        content-transition="modal-content-transition"
        overlay-transition="modal-overlay-transition"
        @update:modelValue="emit('update:isOpen', $event)"
        @click-outside="closeModal"
        @keyup.esc="closeModal"
    >
        <div class="flex flex-col gap-y-[20px] p-[8px]">
            <div
                class="w-full h-[10%] flex justify-between items-center px-[20px] py-[10px] rounded-[10px] bg-white shadow"
            >
                <p class="text-[14px] text-[#000]">Return</p>
                <i
                    class="fa-solid fa-xmark text-[12px] text-[#D93F21] cursor-pointer"
                    @click="closeModal"
                ></i>
            </div>

            <div
                class="w-full grid grid-cols-1 gap-[10px] p-[20px] rounded-[10px] bg-white shadow text-[12px]"
            >
                <div class="col-span-1 h-[65px] flex flex-col">
                    <label class="text-[12px]">Tên khách hàng</label>
                    <Menu as="div" class="relative flex-1">
                        <MenuButton
                            class="w-full h-[40px] mt-2.5 inline-flex justify-between items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        >
                            {{ selectedCustomerName || "Chọn khách hàng" }}
                            <ChevronDownIcon
                                class="h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                        </MenuButton>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="absolute left-0 z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="py-1">
                                    <MenuItem
                                        v-for="customer in customers"
                                        :key="customer.id"
                                        v-slot="{ active }"
                                    >
                                        <button
                                            @click="selectCustomer(customer)"
                                            :class="[
                                                active
                                                    ? 'bg-gray-100 text-gray-900'
                                                    : 'text-gray-700',
                                                'block w-full px-4 py-2 text-left text-sm',
                                            ]"
                                        >
                                            {{ customer.name }}
                                        </button>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                    <span
                        v-if="form.errors.customer_id"
                        class="text-red-500 text-xs"
                        >{{ form.errors.customer_id }}</span
                    >
                </div>

                <div class="col-span-1 h-[65px] flex flex-col">
                    <label class="text-[12px]">Địa chỉ khách hàng</label>
                    <input
                        v-model="form.customer_address"
                        class="w-full bg-white border border-[#D9D9D9] p-2.5 rounded-[5px] placeholder-[#A5A1A1] mt-2.5 text-sm"
                        placeholder="Nhập địa chỉ khách hàng"
                        type="text"
                    />
                    <span
                        v-if="form.errors.customer_address"
                        class="text-red-500 text-xs"
                        >{{ form.errors.customer_address }}</span
                    >
                </div>

                <div class="col-span-1 h-[65px] flex flex-col">
                    <label class="text-[12px]">Tên sản phẩm</label>
                    <Menu as="div" class="relative flex-1">
                        <MenuButton
                            class="w-full h-[40px] mt-2.5 inline-flex justify-between items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                        >
                            {{ selectedProductName || "Chọn sản phẩm" }}
                            <ChevronDownIcon
                                class="h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                        </MenuButton>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <MenuItems
                                class="absolute left-0 z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="py-1">
                                    <MenuItem
                                        v-for="product in products"
                                        :key="product.id"
                                        v-slot="{ active }"
                                    >
                                        <button
                                            @click="selectProduct(product)"
                                            :class="[
                                                active
                                                    ? 'bg-gray-100 text-gray-900'
                                                    : 'text-gray-700',
                                                'block w-full px-4 py-2 text-left text-sm',
                                            ]"
                                        >
                                            {{ product.name }}
                                        </button>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                    <span
                        v-if="form.errors.product_id"
                        class="text-red-500 text-xs"
                        >{{ form.errors.product_id }}</span
                    >
                </div>

                <div class="grid grid-cols-2 gap-[10px]">
                    <!-- Số lượng (1/2 hàng) -->
                    <div class="h-[65px] flex flex-col">
                        <label class="text-[12px]">Số lượng</label>
                        <input
                            v-model="form.quantity"
                            class="w-full bg-white border border-[#D9D9D9] p-2.5 rounded-[5px] placeholder-[#A5A1A1] mt-2.5 text-sm"
                            placeholder="Nhập số lượng"
                            type="number"
                        />
                        <span
                            v-if="form.errors.quantity"
                            class="text-red-500 text-xs"
                            >{{ form.errors.quantity }}</span
                        >
                    </div>

                    <div class="h-[65px] flex flex-col">
                        <label class="text-[12px]">Đơn vị</label>
                        <Menu as="div" class="relative flex-1">
                            <MenuButton
                                class="w-full h-[40px] mt-2.5 inline-flex justify-between items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                                {{ selectedUnitName || "Chọn đơn vị" }}
                                <ChevronDownIcon
                                    class="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </MenuButton>

                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute left-0 z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <div class="py-1">
                                        <MenuItem
                                            v-for="unit in units"
                                            :key="unit.id"
                                            v-slot="{ active }"
                                        >
                                            <button
                                                @click="selectUnit(unit)"
                                                :class="[
                                                    active
                                                        ? 'bg-gray-100 text-gray-900'
                                                        : 'text-gray-700',
                                                    'block w-full px-4 py-2 text-left text-sm',
                                                ]"
                                            >
                                                {{ unit.name }}
                                            </button>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                        <span
                            v-if="form.errors.unit_id"
                            class="text-red-500 text-xs"
                            >{{ form.errors.unit_id }}</span
                        >
                    </div>
                </div>
            </div>

            <button
                class="h-[48px] py-2.5 px-6 text-white text-sm font-semibold text-center rounded-[10px] bg-[#BE202F] mt-2.5 self-end"
                :disabled="form.processing"
                @click="submitForm"
            >
                Submit
            </button>
        </div>
    </VueFinalModal>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { VueFinalModal } from "vue-final-modal";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
    isOpen: Boolean,
    customers: Array,
    products: Array,
    units: Array,
});

const emit = defineEmits(["update:isOpen"]);

const form = useForm({
    customer_id: "",
    customer_address: "",
    product_id: "",
    quantity: "",
    unit_id: "",
});

const selectedCustomerName = ref("");
const selectedProductName = ref("");
const selectedUnitName = ref("");

const selectCustomer = (customer) => {
    form.customer_id = customer.id;
    form.customer_address = customer.address;
    selectedCustomerName.value = customer.name;
};

const selectProduct = (product) => {
    form.product_id = product.id;
    selectedProductName.value = product.name;
};

const selectUnit = (unit) => {
    form.unit_id = unit.id;
    selectedUnitName.value = unit.name;
};

const closeModal = () => {
    form.reset();
    selectedCustomerName.value = "";
    selectedProductName.value = "";
    selectedUnitName.value = "";
    emit("update:isOpen", false);
};

const submitForm = () => {
    form.post("/admin/sale-orders", {
        onSuccess: () => {
            closeModal();
        },
    });
};
</script>

<style scoped>
/* Transition cho modal content */
.modal-content-transition-enter-active,
.modal-content-transition-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.modal-content-transition-enter-from,
.modal-content-transition-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
.modal-content-transition-enter-to,
.modal-content-transition-leave-from {
    opacity: 1;
    transform: scale(1);
}

/* Transition cho overlay */
.modal-overlay-transition-enter-active,
.modal-overlay-transition-leave-active {
    transition: opacity 0.3s ease;
}
.modal-overlay-transition-enter-from,
.modal-overlay-transition-leave-to {
    opacity: 0;
}
.modal-overlay-transition-enter-to,
.modal-overlay-transition-leave-from {
    opacity: 1;
}
</style>
