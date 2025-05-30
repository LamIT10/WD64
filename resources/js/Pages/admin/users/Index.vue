<template>
    <AppLayout>
        <div class="px-[20px] py-[30px]">
            <!-- Header với tiêu đề và nút thêm -->
            <div class="flex justify-between items-center mb-[30px]">
                <h1 class="text-[24px] font-bold text-[#000000]">Danh sách người dùng</h1>
                <Link :href="route('admin.users.create')"
                    class="bg-[#2A66FF] text-white px-[20px] py-[8px] rounded-[8px] text-[14px] font-medium flex items-center gap-[8px] hover:bg-[#1E4FCC] transition-colors">
                <i class="fa-solid fa-plus text-[12px]"></i>
                Thêm người dùng
                </Link>
            </div>
            <!-- Bảng dữ liệu -->
            <div class="w-full">
                <table class="w-full rounded-[10px] overflow-hidden">
                    <!-- Header bảng -->
                    <tr
                        class="w-full text-[#A49E9E] uppercase px-[20px] py-[12px] flex gap-x-[20px] justify-between items-center bg-[#F9FAFB] text-[12px] font-medium">
                        <td class="w-[8%] text-left">#</td>
                        <td class="w-[20%] text-left">User Name</td>
                        <td class="w-[20%] text-left">EMAIL</td>
                        <td class="w-[15%] text-left">ĐIỆN THOẠI</td>
                        <td class="w-[12%] text-center">TRẠNG THÁI</td>
                        <td class="w-[15%] text-center">HÀNH ĐỘNG</td>
                        <td class="w-[5%] text-center"></td>
                    </tr>

                    <!-- Dữ liệu bảng -->
                    <tr v-for="(user, index) in users.data" :key="user.id"
                        class="text-[#000000] px-[20px] py-[15px] flex gap-x-[20px] justify-between items-center bg-[#ffffff] text-[14px] w-full mt-[2px] shadow-sm hover:bg-[#F9FAFB] transition-colors">

                        <!-- ID -->
                        <td class="w-[8%] text-left font-medium">{{ index + 1 }}</td>

                        <!-- Họ tên -->
                        <td class="w-[20%] text-left">
                            <div class="font-medium">{{ user.name }}</div>
                        </td>

                        <!-- Email -->
                        <td class="w-[20%] text-left text-[#6B7280]">{{ user.email }}</td>

                        <!-- Điện thoại -->
                        <td class="w-[15%] text-left">{{ user.phone || 'Chưa cập nhật' }}</td>

                        <!-- Trạng thái -->
                        <td class="w-[12%] text-center">
                            <div
                                class="bg-[#4DB163] text-[#FFFFFF] px-[8px] py-[4px] rounded-[16px] text-[12px] font-medium inline-block">
                                Hoạt động
                            </div>
                        </td>

                        <!-- Hành động -->
                        <td class="w-[15%] text-center">
                            <div class="flex justify-center gap-[8px]">
                                <Link :href="route('admin.users.show', user.id)"
                                    class="text-[#6B7280] hover:text-[#2A66FF] transition-colors text-[12px] px-[8px] py-[4px] rounded-[6px] hover:bg-[#F3F4F6]">
                              <i class="fa-solid fa-eye"></i>    
                                </Link>
                                <Link :href="route('admin.users.edit', user.id)"
                                    class="text-[#2A66FF] hover:text-[#1E4FCC] transition-colors text-[12px] px-[8px] py-[4px] rounded-[6px] hover:bg-[#EEF2FF]">
                            <i class="fa-solid fa-pen-to-square"></i>
                                </Link>

                                <button type="submit" @click="hanldeDelete(user.id)"
                                    class="text-[#D93F21] hover:text-[#B91C1C] transition-colors text-[12px] px-[8px] py-[4px] rounded-[6px] hover:bg-[#FEF2F2]">
                             <i class="fa-solid fa-trash"></i>.
                                </button>
                            </div>
                        </td>
                        <!-- Menu 3 chấm -->
                        <td class="w-[5%] text-center">
                            <i
                                class="fa-solid fa-ellipsis text-[#6B7280] cursor-pointer hover:text-[#374151] transition-colors"></i>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Phân trang -->
              <nav class="mt-4 flex justify-center">
        <ul class="inline-flex -space-x-px">
          <li v-for="(link, index) in users.links" :key="index" class="cursor-pointer px-3 py-1 border"
            :class="{ 'bg-gray-300 font-bold': link.active, 'rounded-l': index === 0, 'rounded-r': index === users.links.length - 1 }">
            <Link v-html="formatLabel(link.label)" :href="link.url" class="block" v-if="link.url" />
            <span v-else v-html="formatLabel(link.label)" class="block text-gray-500 cursor-not-allowed" />
          </li>
        </ul>
      </nav>


        </div>
    </AppLayout>
</template>
<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
const props = defineProps({ users: Object });
const hanldeDelete = (id) => {
    if (confirm("Bạn có chắc chắn muốn xoá người dùng này không?")) {
        const formDelete = useForm({});
        formDelete.delete(route('admin.users.destroy', id));
    }
}
function formatLabel(label) {
  return label
    .replace(/&laquo;/g, '«')
    .replace(/&raquo;/g, '»')
    .replace(/Previous/i, 'Trước')
    .replace(/Next/i, 'Tiếp');
}
</script>