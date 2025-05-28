<template>
    <div>
        <AppLayout>
            <div class="w-full h-[203px] my-[10px] mt-10">
                <table class="w-[90%] mx-auto my-0 rounded-[5px]">
                    <tr
                        class="w-full font-bold text-[#171717] uppercase px-[20px]  py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] rounded-[10px] text-[12px] shadow-2xl">
                        <td class="w-[5%] text-center">#</td>
                        <td class="w-[40%] text-center">Tên</td>
                        <td class="w-[40%] text-center">Mô tả</td>
                        <td class="w-[55%] text-center">Thao tác</td>
                    </tr>
                    <tr v-for="(permission, index) in permissions.data" :key="permission.id"
                        class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff]  text-[12px] w-full mt-[15px] rounded-[10px] shadow-2xl">
                        <td class="w-[5%] text-center">{{ index + 1 }}</td>
                        <td class="w-[40%] text-center">{{ permission.name }}</td>
                        <td class="w-[40%] text-center">{{ permission.description }}</td>
                        <td class="w-[55%] text-center flex space-x-2 justify-center">
                            <Link :href="route('admin.permission.edit', permission.id)">
                            <button
                                class="flex items-center gap-1 px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                <i class="fas fa-edit"></i>
                                Sửa
                            </button>
                            </Link>
                            <button @click="hanldeDelete(permission.id)"
                                class="flex items-center gap-1 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                                Xoá
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <Link :href="route('admin.permission.create')" title="Thêm quyền">
                    <div
                        class="p-[5px] bg-[#BE202F] rounded text-white font-bold mt-3 w-[50px] text-center hover:bg-[#a85959]">
                        +
                    </div>
                    </Link>
                    </tr>
                </table>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { route } from 'ziggy-js';

const { permissions } = defineProps({
    permissions: {
        type: Object,
        default: {}
    }
});
const hanldeDelete = (id) => {
    if (confirm("Bạn có xác nhận xoá")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.permission.destroy', id));
    }
}
</script>

<style lang="scss" scoped></style>