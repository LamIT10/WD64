<template>
    <AppLayout>
        <div class="p-6">
            <div
            
                class="p-3 bg-white mb-4 flex justify-between items-center"
            >
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Nhà cung cấp
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Tìm kiếm nhà cung cấp..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        />
                        <i
                            class="fas fa-search absolute left-3 top-3 text-gray-400"
                        ></i>
                    </div>
                    <Waiting
                        route-name="admin.suppliers.create"
                        :route-params="{}"
                    >
                        <i class="fas fa-plus mr-1"></i> Thêm mới
                    </Waiting>
                </div>
            </div>

            <div
                class="bg-white overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <div
                        class="relative overflow-x-auto shadow-md"
                    >
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
                                        Tên NCC
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Đại điện
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Số điện thoại
                                    </th>
                                    <th scope="col" class="px-4 py-2">Email</th>
                                    <th scope="col" class="px-4 py-2">
                                        Địa chỉ
                                    </th>
                                    <th scope="col" class="px-4 py-2">
                                        Hành động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="supplier in suppliers.data"
                                    :key="supplier.id"
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
                                    <th
                                        scope="row"
                                        class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ supplier.name }}
                                    </th>
                                    <td class="px-4 py-2">
                                        {{ supplier.contact_person }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ supplier.phone }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ supplier.email }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ supplier.address }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <Waiting
                                            route-name="admin.suppliers.edit"
                                            :route-params="{
                                                id: supplier.id,
                                            }"
                                            :color="'bg-blue-500 hover:bg-green-700 text-white'"
                                        >
                                            <i class="fas fa-edit mr-1"></i>
                                            Sửa
                                        </Waiting>
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
                        Hiển thị <span class="font-medium">1</span> đến
                        <span class="font-medium">2</span> của
                        <span class="font-medium">10</span> kết quả
                    </div>
                    <div class="flex justify-end space-x-1 mt-4">
                        <button
                            v-for="link in suppliers.links"
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

const { suppliers } = defineProps({
    suppliers: {
        default: () => {},
    },
});
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
