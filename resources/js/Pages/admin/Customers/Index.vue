<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="max-w-full overflow-x-auto mx-auto bg-white rounded-[10px] shadow p-[20px] mb-[30px]">
        <div class="flex justify-between items-center mb-6 min-w-max">
          <h2 class="text-xl font-bold">Quản lý khách hàng</h2>
          <Link :href="route('admin.customers.create')"
            class="flex items-center gap-2 bg-[#BE202F] hover:bg-[#9e1a28] transition-colors text-white px-4 py-2 rounded shadow">
            <i class="fas fa-plus"></i>
            Thêm khách hàng
          </Link>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full min-w-max text-[13px] text-[#000] border-collapse">
            <thead>
              <tr class="bg-[#f9f9f9] text-[#6b7280] uppercase">
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Tên</th>
                <th class="p-3 text-left">Người liên hệ</th>
                <th class="p-3 text-left">SĐT</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Công nợ</th>
                <th class="p-3 text-left">Hạng</th>
                <th class="p-3 text-bettwen">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(customer, index) in customers.data || []" :key="customer.id"
                class="border-b hover:bg-[#f0f4f8]">
                <td class="p-3">
                  {{ index + 1 + ((customers.meta?.current_page || 1) - 1) * (customers.meta?.per_page || 10) }}
                </td>
                <td class="p-3">{{ customer.name }}</td>
                <td class="p-3">{{ customer.contact_person || '-' }}</td>
                <td class="p-3">{{ customer.phone || '-' }}</td>
                <td class="p-3">{{ customer.email || '-' }}</td>
                <td class="p-3">{{ customer.current_debt }}</td>
                <td class="p-3">
                  <span v-if="customer.ranks && customer.ranks.length">{{ customer.ranks[0].name }}</span>
                  <span v-else>-</span>
                </td>
                <td class="p-3 flex gap-3">
                  <Link :href="route('admin.customers.show', customer.id)"
                    class="flex items-center gap-1 text-green-600 hover:text-green-800 transition" title="Chi tiết">
                    <i class="fas fa-info-circle"></i> Chi tiết
                  </Link>
                  <Link :href="route('admin.customers.edit', customer.id)"
                    class="flex items-center gap-1 text-blue-600 hover:text-blue-800 transition" title="Sửa">
                    <i class="fas fa-edit"></i> Sửa
                  </Link>
                  <button @click="handleDelete(customer.id)"
                    class="flex items-center gap-1 text-red-600 hover:text-red-800 transition" title="Xóa">
                    <i class="fas fa-trash-alt"></i> Xóa
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Phân trang -->
      <nav class="mt-4 flex justify-center">
        <ul class="inline-flex -space-x-px">
          <li v-for="(link, index) in customers.links" :key="index" class="cursor-pointer px-3 py-1 border"
            :class="{ 'bg-gray-300 font-bold': link.active, 'rounded-l': index === 0, 'rounded-r': index === customers.links.length - 1 }">
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

defineProps({
  customers: Object,
});

const handleDelete = (id) => {
  if (confirm('Bạn có chắc muốn xóa khách hàng này không?')) {
    useForm({}).delete(route('admin.customers.destroy', id), {
      onSuccess: () => alert('Xóa khách hàng thành công.'),
    });
  }
};

function formatLabel(label) {
  return label
    .replace(/&laquo;/g, '«')
    .replace(/&raquo;/g, '»')
    .replace(/Previous/i, 'Trước')
    .replace(/Next/i, 'Tiếp');
}
</script>

<style scoped lang="scss">
</style>
