<template>
    <AppLayout>
        <div class="p-6">
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Sản phẩm
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Tìm kiếm sản phẩm..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        />
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"
                        ></i>
                    </div>
                    <Waiting
                        route-name="admin.products.create"
                        :route-params="{}"
                    >
                        <i class="fas fa-plus mr-1"></i> Thêm mới
                    </Waiting>
                </div>
            </div>

            <div class="bg-white overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="relative overflow-x-auto shadow-md">
                        <table
                            class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 bg-indigo-50 border-b border-indigo-300 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-all-search"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-all-search"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Sản phẩm
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Giá
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Tồn kho
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Trạng thái
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="product in products.data"
                                    :key="product.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <input
                                                id="checkbox-table-search-1"
                                                type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="checkbox-table-search-1"
                                                class="sr-only"
                                                >checkbox</label
                                            >
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <img
                                                :src="product.image_url"
                                                class="w-16 h-16 object-cover rounded mr-3"
                                                alt="Product image"
                                            />
                                            <div>
                                                <div class="font-medium text-gray-900">{{ product.name }}</div>
                                                <div class="text-sm text-gray-500">{{ product.category.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ formatCurrency(product.price) }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ product.stock_quantity }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span
                                            :class="[
                                                'px-2 py-1 text-xs rounded-full',
                                                product.status === 'active'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                            ]"
                                        >
                                            {{
                                                product.status === 'active'
                                                    ? 'Hoạt động'
                                                    : 'Ngừng bán'
                                            }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <!-- <Waiting
                                            route-name="admin.products.edit"
                                            :route-params="{
                                                product: product.id,
                                            }"
                                            class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm"
                                        >
                                            <i class="fas fa-edit mr-1"></i>
                                            Sửa
                                        </Waiting>
                                        <button
                                            @click="deleteProduct(product.id)"
                                            class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm"
                                        >
                                            <i class="fas fa-trash mr-1"></i>
                                            Xóa
                                        </button> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    class="px-4 py-2 border-t border-gray-200 flex items-center justify-between"
                >
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ products.from }}</span> đến
                        <span class="font-medium">{{ products.to }}</span> của
                        <span class="font-medium">{{ products.total }}</span> kết quả
                    </div>
                    <div class="flex justify-end space-x-1 mt-4">
                        <button
                            v-for="link in products.links"
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
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";

const { products } = defineProps({
    products: {
        default: () => {},
    },
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
};

const deleteProduct = (id) => {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        // Xử lý xóa sản phẩm
    }
};
</script>

<style lang="css" scoped>
::-webkit-scrollbar {
    height: 6px;
    width: 6px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #c4c4c4;
    border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}
tr {
    height: 20px; /* chiều cao cố định */
    max-height: 40px;
}

td {
    white-space: nowrap; /* không xuống dòng */
    overflow: hidden;
    text-overflow: ellipsis; /* hiện dấu ... nếu text dài */
    max-width: 200px;
}
</style>