<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <div
                class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow-sm"
            >
                <h1 class="text-xl font-semibold text-indigo-800">
                    Cập nhật đơn nhập hàng
                </h1>
                <Waiting
                    route-name="admin.purchases.index"
                    :route-params="{}"
                    class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-md hover:bg-indigo-100"
                >
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </Waiting>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-8 mx-auto">
                <table
                    class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm"
                >
                    <tbody>
                        <!-- Nhà cung cấp -->
                        <tr class="border-b-3 border-white">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3 w-1/3"
                            >
                                🏢 Nhà cung cấp
                            </td>
                            <td class="px-4 py-3 text-gray-900">
                                {{ purchase.supplier.name }}
                            </td>
                        </tr>
                        <tr class="border-b-3 border-white">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3 w-1/3"
                            >
                                Ngày giao dự kiến
                            </td>
                            <td class="px-4 py-3 text-gray-900">
                                <input
                                    type="date"
                                    :value="purchase.order_date?.slice(0, 10)"
                                    @input="
                                        purchase.order_date =
                                            $event.target.value
                                    "
                                />
                            </td>
                        </tr>

                        <tr class="border-b-3 border-white">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                📌 Trạng thái
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-block px-3 py-1 rounded-xl text-sm font-medium text-yellow-700 bg-yellow-100 border border-yellow-300"
                                >
                                    Chờ duyệt
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b-3 border-white">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                Người tạo đơn
                            </td>
                            <td>
                                <span class="ml-4">
                                    <select
                                        name=""
                                        id=""
                                        v-model="purchase.user_id"
                                    >
                                        <option
                                            v-for="user in users"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b-3 border-white">
                            <td
                                class="bg-indigo-50 font-medium text-gray-700 px-4 py-3"
                            >
                                Ngày tạo đơn
                            </td>
                            <td>
                                <span class="ml-4">{{
                                    formatDate(purchase.created_at)
                                }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="overflow-x-auto mt-10">
                    <table class="w-full border-collapse text-sm">
                        <thead class="bg-indigo-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">SẢN PHẨM</th>
                                <th class="px-4 py-3 text-center">Số lượng</th>
                                <th class="px-4 py-3 text-center">ĐƠN VỊ</th>
                                <th class="px-4 py-3 text-center">GIÁ NHẬP</th>
                                <th class="px-4 py-3 text-right">THÀNH TIỀN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in purchase.items"
                                :key="index"
                                class="border-b hover:bg-gray-50"
                            >
                                <td class="px-4 py-3">
                                    {{ item.product_variant.product.name }}
                                    -
                                    <span
                                        v-for="(attribute, index) in item
                                            .product_variant.attributes"
                                        :key="index"
                                    >
                                        {{ attribute.name }}
                                        {{ attribute.value }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input
                                        type="number"
                                        v-model.number="item.quantity_ordered"
                                        class="w-20 text-center border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                        min="0"
                                    />
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <select
                                        name=""
                                        id=""
                                        v-model="item.unit_id"
                                    >
                                        <option
                                            v-for="user in users"
                                            :key="user.id"
                                            :value="user.id"
                                        >
                                            {{ user.name }}
                                        </option>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input
                                        type="number"
                                        v-model.number="item.unit_price"
                                        class="w-24 text-right border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                                        min="0"
                                    />
                                </td>
                                <td class="px-4 py-3 text-right">
                                    {{
                                        formatCurrency(
                                            item.quantity * item.price
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <div class="text-right space-y-2">
                        <p class="text-base font-semibold text-gray-800">
                            Tổng tiền đơn: {{ formatCurrency(totalAmount) }}
                        </p>
                        <p class="text-base text-gray-600">
                            Số sản phẩm: {{ purchase.items.length }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200"
                        @click="savePurchase"
                    >
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";

const { purchase, users } = defineProps({
    purchase: {
        default: () => ({}),
    },
    users: {
        default: () => ({}),
    },
});
console.log(purchase);

const formatCurrency = (value) => {
    return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    }).format(value);
};

const savePurchase = () => {
    console.log("Saving purchase:", purchase.value);
};
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
</script>

<style lang="scss" scoped></style>
