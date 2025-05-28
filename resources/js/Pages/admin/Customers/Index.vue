<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Quản lý khách hàng</h2>
          <Link :href="route('admin.customers.create')" class="bg-[#BE202F] text-white px-4 py-2 rounded">
            Thêm khách hàng
          </Link>
        </div>
        <table class="w-full text-[12px] text-[#000]">
          <thead>
            <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
              <th class="p-[10px] text-left">#</th>
              <th class="p-[10px] text-left">Tên</th>
              <th class="p-[10px] text-left">Người liên hệ</th>
              <th class="p-[10px] text-left">SĐT</th>
              <th class="p-[10px] text-left">Email</th>
              <th class="p-[10px] text-left">Công nợ</th>
              <th class="p-[10px] text-left">Hạng</th>
              <th class="p-[10px] text-left">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(customer, index) in customers" :key="customer.id">
              <td class="p-[10px]">{{ index + 1 }}</td>
              <td class="p-[10px]">{{ customer.name }}</td>
              <td class="p-[10px]">{{ customer.contact_person || '-' }}</td>
              <td class="p-[10px]">{{ customer.phone || '-' }}</td>
              <td class="p-[10px]">{{ customer.email || '-' }}</td>
              <td class="p-[10px]">{{ customer.current_debt }}</td>
              <td class="p-[10px]">
                <span v-if="customer.ranks.length">{{ customer.ranks[0].name }}</span>
                <span v-else>-</span>
              </td>
              <td class="p-[10px]">
                <Link :href="route('admin.customers.edit', customer.id)" class="text-[#2A66FF] font-bold mr-2">Sửa</Link>
                <button @click="handleDelete(customer.id)" class="text-[#BE202F] font-bold">Xóa</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({
  customers: Array,
});

const handleDelete = (id) => {
  if (confirm('Bạn có chắc muốn xóa khách hàng này không?')) {
    useForm({}).delete(route('customers.destroy', id), {
      onSuccess: () => alert('Xóa khách hàng thành công.'),
    });
  }
};
</script>

<style scoped lang="scss"></style>
