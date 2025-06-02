<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
        <h5 class="text-lg text-purple-700 font-semibold">Quản lý khách hàng</h5>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" placeholder="Tìm kiếm khách hàng..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Link :href="route('admin.customers.create')"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
          <i class="fas fa-plus"></i>
          <span>Thêm khách hàng</span>
          </Link>
        </div>
      </div>

      <!-- Customer Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  #
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tên
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  SĐT
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Công nợ
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Hạng
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Hành động
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(customer, index) in customers.data || []" :key="customer.id"
                class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ index + 1 + ((customers.meta?.current_page || 1) - 1) * (customers.meta?.per_page || 10) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-md flex items-center justify-center">
                      <i class="fas fa-user text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
                      <div class="text-sm text-gray-500">{{ customer.email || '-' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ customer.phone || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ customer.email || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ customer.current_debt }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                    v-if="customer.rank">
                    {{ customer.rank.name }}
                  </span>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                    v-else>
                    -
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="relative inline-block text-left">
                    <button class="text-gray-400 hover:text-gray-600 focus:outline-none"
                      @click="toggleDropdown(customer.id)">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div v-if="activeDropdown === customer.id"
                      class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                      <div class="py-1">
                        <Link :href="route('admin.customers.show', customer.id)"
                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-green-600"></i>
                        Chi tiết
                        </Link>
                        <Link :href="route('admin.customers.edit', customer.id)"
                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        <i class="fas fa-edit mr-2 text-purple-600"></i>
                        Sửa
                        </Link>
                        <button @click="handleDelete(customer.id)"
                          class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          <i class="fas fa-trash-alt mr-2 text-red-500"></i>
                          Xóa
                        </button>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Hiển thị
            <span class="font-medium">{{ customers.meta?.from || 1 }}</span> đến
            <span class="font-medium">{{ customers.meta?.to || 0 }}</span> của
            <span class="font-medium">{{ customers.meta?.total || 0 }}</span> kết quả
          </div>
          <div class="flex space-x-1">
            <button v-for="(link, index) in customers.links" :key="index"
              class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50"
              :class="{ 'bg-purple-600 text-white': link.active, 'disabled:opacity-50': !link.url }"
              :disabled="!link.url">
              <Link v-if="link.url" :href="link.url" v-html="formatLabel(link.label)" class="block" />
              <span v-else v-html="formatLabel(link.label)" class="block text-gray-500" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({
  customers: Object,
});

const activeDropdown = ref(null);

const toggleDropdown = (id) => {
  activeDropdown.value = activeDropdown.value === id ? null : id;
};

const handleDelete = (id) => {
  if (confirm('Bạn có chắc muốn xóa khách hàng này không?')) {
    useForm({}).delete(route('admin.customers.destroy', id), {
      onSuccess: () => { },
    });
  }
};

function formatLabel(label) {
  return label
    .replace(/«/g, '<i class="fas fa-chevron-left"></i>')
    .replace(/»/g, '<i class="fas fa-chevron-right"></i>')
    .replace(/Previous/i, '')
    .replace(/Next/i, '');
}
</script>

<style scoped>
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