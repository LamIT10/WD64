<template>
    <AppLayout>
        <div v-if="flash && flash.success" class="w-[90%] mx-auto my-2 p-3 bg-green-100 text-green-700 rounded">
            {{ flash.success }}
        </div>
        <div class="w-full h-auto my-[10px]">
            <table class="w-[90%] mx-auto rounded-[5px]">
                <tr
                    class="w-full text-[#101010] uppercase px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] rounded-[10px] opacity-50 text-[12px] shadow-2xl">
                    <td class="w-[5%] text-center">#</td>
                    <td class="w-[15%] text-center">ID Danh Mục</td>
                    <td class="w-[15%] text-center">Tên Danh Mục</td>
                    <td class="w-[15%] text-center">Mô tả</td>
                    <td class="w-[15%] text-center">Ngày Tạo</td>
                    <td class="w-[15%] text-center">Chức Năng</td>
                </tr>

                <!-- Danh mục cha + con -->
                <template v-for="(category, index) in categories.data" :key="category.id">
                    <!-- Danh mục cha -->
                    <tr @click="toggleCategory(category.id)"
                        class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-[#f0f0f0] text-[12px] w-full mt-[10px] rounded-[10px] shadow-md cursor-pointer hover:bg-[#e0e0e0]">
                        <td class="w-[5%] text-center">{{ index + 1 }}</td>
                        <td class="w-[15%] text-center">{{ category.id }}</td>
                        <td class="w-[15%] text-center font-bold">{{ category.name }}</td>
                        <td class="w-[15%] text-center">{{ category.description }}</td>
                        <td class="w-[15%] text-center">{{ category.created_at }}</td>
                        <td class="w-[15%] text-center">
                            <i :class="expanded[category.id] ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"
                                class="text-[#6B7280] mr-2 cursor-pointer"></i>
                            <Link :href="route('admin.categories.show', category.id)"
                                class="text-blue-500 hover:underline mx-1">Xem</Link>
                            <Link :href="route('admin.categories.edit', category.id)"
                                class="text-yellow-500 hover:underline mx-1">Sửa</Link>
                            <button @click.stop="deleteCategory(category.id)"
                                class="text-red-500 hover:underline mx-1">Xóa</button>
                        </td>
                    </tr>

                    <!-- Danh mục con -->
                    <tr v-for="(child, childIndex) in category.children" :key="child.id" v-show="expanded[category.id]"
                        class="text-[#000] px-[20px] py-[10px] flex gap-x-[20px] justify-between items-center bg-white text-[12px] w-full mt-[5px] rounded-[10px] shadow child-row">
                        <td class="w-[5%] text-center">{{ childIndex }}</td>
                        <td class="w-[15%] text-center">{{ child.id }}</td>
                        <td class="w-[15%] text-center ml-[10px]">{{ child.name }}</td>
                        <td class="w-[15%] text-center">{{ child.description }}</td>
                        <td class="w-[15%] text-center">{{ child.created_at }}</td>
                        <td class="w-[15%] text-center">
                            <i class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer mr-2"></i>
                            <Link :href="route('admin.categories.show', child.id)"
                                class="text-blue-500 hover:underline mx-1">Xem</Link>
                            <Link :href="route('admin.categories.edit', child.id)"
                                class="text-yellow-500 hover:underline mx-1">Sửa</Link>
                            <button @click.stop="deleteCategory(child.id)"
                                class="text-red-500 hover:underline mx-1">Xóa</button>
                        </td>
                    </tr>
                </template>
            </table>
        </div>
        <Link :href="route('admin.categories.create')"
            class="text-blue-600 border-2 px-2 py-2 mt-5 hover:underline ml-10">+
        Thêm danh mục mới</Link>

    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue'
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const flash = usePage().props.flash
console.log(flash);


defineProps({
    categories: Object
})

const expanded = ref({})
const toggleCategory = (categoryId) => {
    expanded.value[categoryId] = !expanded.value[categoryId]
}


const deleteCategory = (id) => {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        router.delete(route('admin.categories.destroy', id))
    }
}
</script>

<style lang="" scoped>

</style>