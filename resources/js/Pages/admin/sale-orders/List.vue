<template>
    <AppLayout>
        <div class="w-full h-auto p-[20px] bg-[#f5f7fa]">
            <table class="w-full rounded-[10px] bg-[#ffffff] shadow-2xl">
                <tr
                    class="w-full text-[#A49E9E] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#f9f9f9] rounded-t-[10px] text-[12px] font-semibold"
                >
                    <td class="w-[13%] text-center">
                        <strong>ID đơn đặt hàng</strong>
                    </td>
                    <td class="w-[15%] text-center">
                        <strong>Id khách hàng</strong>
                    </td>
                    <td class="w-[15%] text-center">
                        <strong>Tên khách hàng</strong>
                    </td>
                    <td class="w-[15%] text-center">
                        <strong>Địa chỉ khách hàng</strong>
                    </td>
                    <td class="w-[10%] text-center">
                        <strong>Tổng tiền</strong>
                    </td>
                    <td class="w-[15%] text-center">
                        <strong>Trạng thái đơn hàng</strong>
                    </td>
                    <td class="w-[15%] text-center">
                        <strong>Request On</strong>
                    </td>
                    <td class="w-[2%] text-center"><strong></strong></td>
                </tr>

                <tr
                    v-for="saleOrder in saleOrders"
                    :key="saleOrder.id"
                    class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[12px] w-full mt-[10px] rounded-b-[10px]"
                >
                    <td class="w-[13%] text-center">{{ saleOrder.id }}</td>
                    <td class="w-[15%] text-center">
                        {{ saleOrder.customer_id }}
                    </td>
                    <td class="w-[15%] text-center">{{ saleOrder.name }}</td>
                    <td class="w-[15%] text-center">
                        {{ saleOrder.address_delivery }}
                    </td>
                    <td class="w-[10%] text-center">
                        {{ saleOrder.total_amount }}
                    </td>
                    <td class="w-[15%] text-center">
                        <div
                            class="w-full h-[22px] rounded-[16px] bg-[#F6E9E4] flex justify-center items-center gap-[10px] font-bold"
                        >
                            <i
                                class="fa-solid fa-circle text-[#E54C42] text-[8px]"
                            ></i>
                            <p class="text-[#E54C42] text-[12px]">
                                {{ saleOrder.status }}
                            </p>
                        </div>
                    </td>
                    <td class="w-[15%] text-center">2058-03-07</td>
                    <td class="w-[2%] text-center">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </td>
                </tr>
            </table>
            <button
                class="fixed bottom-[20px] right-[20px] w-[50px] h-[50px] bg-[#BE202F] text-white rounded-full flex justify-center items-center text-[24px] shadow-lg"
                @click="isModalOpen = true"
            >
                <i class="fa-solid fa-plus"></i>
            </button>
            <ModalSaleOrder
                :isOpen="isModalOpen"
                @update:isOpen="isModalOpen = $event"
                :saleOrders="saleOrders"
                :customers="customers"
                :products="products"
                :units="units"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import ModalSaleOrder from "../../components/ModalSaleOrder.vue";

const props = defineProps({
    saleOrders: {
        type: Array,
        default: [],
    },
    customers: {
        type: Array,
        default: [],
    },
    products: {
        type: Array,
        default: [],
    },
    units: {
        type: Array,
        default: [],
    },
});
const isModalOpen = ref(false);
</script>
<style scoped></style>
