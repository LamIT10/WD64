<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="max-w-4xl mx-auto">
        <h1 class="text-lg text-purple-700 font-semibold mb-6 border-b border-gray-200 pb-2">
          <i class="fas fa-info-circle mr-2"></i>Chi tiết khách hàng
        </h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">ID</p>
              <p class="text-sm font-medium text-gray-900">{{ customer.id }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Họ tên</p>
              <p class="text-sm font-medium text-gray-900">{{ customer.name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Email</p>
              <p class="text-sm font-medium text-gray-900">{{ customer.email || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Số điện thoại</p>
              <p class="text-sm font-medium text-gray-900">{{ customer.phone || '-' }}</p>
            </div>
            <div class="sm:col-span-2">
              <p class="text-sm text-gray-500">Địa chỉ</p>
              <p class="text-sm font-medium text-gray-900">{{ customer.address || '-' }}</p>
            </div>
          </div>

          <div v-if="customer.ranks?.length" class="pt-4 border-t border-gray-200">
            <h2 class="text-sm font-semibold text-gray-600 mb-2">Hạng khách hàng:</h2>
            <ul class="space-y-2">
              <li v-for="rank in customer.ranks" :key="rank.id" class="flex items-center">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mr-2">
                  {{ rank.name }}
                </span>
                <span class="text-sm text-gray-500">{{ rank.description || '-' }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="mt-6 flex items-center space-x-3">
          <Link :href="route('admin.customers.edit', customer.id)"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
          <i class="fas fa-edit"></i>
          <span>Sửa khách hàng</span>
          </Link>
          <Link :href="route('admin.customers.index')"
            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors flex items-center space-x-2">
          <i class="fas fa-arrow-left"></i>
          <span>Quay lại danh sách</span>
          </Link>
        </div>
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