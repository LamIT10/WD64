<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">📄 Chi tiết khách hàng</h1>

      <div class="bg-white rounded-xl shadow-md p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">ID</p>
            <p class="text-base font-medium text-gray-800">{{ customer.id }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Họ tên</p>
            <p class="text-base font-medium text-gray-800">{{ customer.name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="text-base font-medium text-gray-800">{{ customer.email || '-' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Số điện thoại</p>
            <p class="text-base font-medium text-gray-800">{{ customer.phone || '-' }}</p>
          </div>
          <div class="sm:col-span-2">
            <p class="text-sm text-gray-500">Địa chỉ</p>
            <p class="text-base font-medium text-gray-800">{{ customer.address || '-' }}</p>
          </div>
        </div>

        <div v-if="customer.ranks?.length" class="pt-4 border-t">
          <h2 class="text-sm font-semibold text-gray-600 mb-2">Hạng khách hàng:</h2>
          <ul class="list-disc pl-5 text-gray-800 space-y-1">
            <li v-for="rank in customer.ranks" :key="rank.id">
              <span class="font-medium">{{ rank.name }}</span> — {{ rank.description }}
            </li>
          </ul>
        </div>
      </div>

      <div class="mt-6 flex items-center gap-4">
        <Link :href="route('admin.customers.edit', customer.id)"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
          ✏️ Sửa khách hàng
        </Link>
        <Link :href="route('admin.customers.index')"
          class="text-gray-700 hover:underline underline-offset-4">
          ← Quay lại danh sách
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({
  customer: Object,
});
</script>
