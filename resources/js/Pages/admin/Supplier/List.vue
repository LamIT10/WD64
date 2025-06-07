<template>
    <AppLayout>
        <div class="p-6">
            <div class="p-3 bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Danh sách Nhà cung cấp
                </h5>
                <div class="flex items-center space-x-3">
                    <form @submit="submitSearch" class="relative">
                        <button type="submit" class="">Tìm kiếm</button>
                        <input
                            type="text"
                            name="search"
                            v-model= "form.search"
                            placeholder="Tìm kiếm nhà cung cấp..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        />
                    </form>
                    <Waiting
                        route-name="admin.suppliers.create"
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
                            class="w-full text-left shadow-sm rtl:text-right text-gray-500 dark:text-gray-400 overflow-visible"
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
                                    <td class="px-4 py-2" style="text-wrap: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis">
                                        {{ supplier.address }}
                                    </td>
                                    <td
                                        class="px-4 py-2 relative overflow-visible"
                                    >
                                        <button
                                            @click="toggleMenu(supplier.id)"
                                            :id="
                                                'dropdownMenuIconButton-' +
                                                supplier.id
                                            "
                                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-2 focus:outline-none dark:text-white focus:ring-indigo-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                            type="button"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor"
                                                viewBox="0 0 4 15"
                                            >
                                                <path
                                                    d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"
                                                />
                                            </svg>
                                        </button>

                                        <div
                                            v-if="menuVisible[supplier.id]"
                                            class="z-[10000] absolute top-0 right-[100%] bg-white divide-y divide-gray-100 rounded-lg w-max"
                                            style="box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);"
                                        >
                                            <ul
                                                class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                            >
                                                <li>
                                                    <Waiting
                                                        route-name="admin.suppliers.edit"
                                                        :route-params="{
                                                            id: supplier.id,
                                                        }"
                                                        :color="'text-blue-700'"
                                                    >
                                                        <i
                                                            class="fas fa-edit"
                                                        ></i>
                                                        Sửa
                                                    </Waiting>
                                                </li>
                                                <li>
                                                    <ConfirmModal
                                                        :route-name="'admin.suppliers.destroy'"
                                                        :route-params="{
                                                            id: supplier.id,
                                                        }"
                                                        title="Xác nhận xóa nhà cung cấp"
                                                        :message="`Bạn có chắc chắn muốn xóa nhà cung cấp ${supplier.name}? Bạn sẽ không thể khôi phục lại sau khi xác nhận xoá`"
                                                    >
                                                        <template
                                                            #trigger="{
                                                                openModal,
                                                            }"
                                                        >
                                                            <button
                                                                @click="
                                                                    openModal
                                                                "
                                                                class="text-sm px-3 py-2 bg-white text-red-600"
                                                            >
                                                                <i
                                                                    class="fas fa-trash-alt mr-1"
                                                                ></i>
                                                                Xóa
                                                            </button>
                                                        </template>
                                                    </ConfirmModal>
                                                </li>
                                            </ul>
                                        </div>
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
import ConfirmModal from "../../components/ConfirmModal.vue";
import { reactive } from "vue";
import { useForm } from "@inertiajs/vue3";

const { suppliers } = defineProps({
    suppliers: {
        default: () => ({}),
    },
});

const menuVisible = reactive({});

function toggleMenu(id) {
    Object.keys(menuVisible).forEach((key) => {
        if (key !== String(id)) menuVisible[key] = false;
    });
    menuVisible[id] = !menuVisible[id];
}
const form = useForm({
    search: '',
})
const submitSearch = () => {
    form.get(route('admin.suppliers.index'));
}
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

</style>
