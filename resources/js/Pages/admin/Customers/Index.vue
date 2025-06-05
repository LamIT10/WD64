<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
        <h5 class="text-lg text-indigo-700 font-semibold">Quản lý khách hàng</h5>
        <div class="flex items-center space-x-3">
          <div class="relative">
            <input type="text" placeholder="Tìm kiếm khách hàng..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Link :href="route('admin.customers.create')"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Thêm khách hàng</span>
          </Link>
        </div>
      </div>

      <!-- Customer Table -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/80 backdrop-blur-sm">
              <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  #
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Tên
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  SĐT
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Email
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Công nợ
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Hạng
                </th>
                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Hành động
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="(customer, index) in customers.data || []" :key="customer.id"
                class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                  {{ index + 1 + ((customers.current_page || 1) - 1) * (customers.per_page || 10) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <img :src="customer.avatar ? `/storage/${customer.avatar}` : 'https://via.placeholder.com/40'"
                      alt="Avatar"
                      class="h-10 w-10 rounded-full object-cover border border-indigo-200">
                    <div>
                      <div class="text-sm font-semibold text-gray-900">{{ customer.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ customer.phone || 'Chưa cập nhật' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ customer.email || 'Chưa cập nhật' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ customer.current_debt || 0 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm"
                    :class="{
                      'bg-gray-200 text-gray-800': customer.rank?.name === 'Sắt',
                      'bg-amber-200 text-amber-800': customer.rank?.name === 'Đồng',
                      'bg-slate-200 text-slate-800': customer.rank?.name === 'Bạc',
                      'bg-yellow-200 text-yellow-800': customer.rank?.name === 'Vàng',
                      'bg-blue-100 text-blue-800': customer.rank?.name === 'Bạch Kim',
                      'bg-gray-100 text-gray-700': !customer.rank
                    }">
                    {{ customer.rank ? customer.rank.name : 'Chưa có hạng' }}
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
                          <i class="fas fa-eye mr-2 text-blue-600"></i>
                          Chi tiết
                        </Link>
                        <Link :href="route('admin.customers.edit', customer.id)"
                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          <i class="fas fa-edit mr-2 text-indigo-600"></i>
                          Sửa
                        </Link>
                        <button @click="handleDelete(customer.id)"
                          class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          <i class="fas fa-trash mr-2 text-red-500"></i>
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
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50/50">
          <div class="text-sm text-gray-600 font-medium">
            Hiển thị
            <span class="font-semibold">{{ customers.from || 1 }}</span> đến
            <span class="font-semibold">{{ customers.to || 0 }}</span> của
            <span class="font-semibold">{{ customers.total || 0 }}</span> kết quả
          </div>
          <div class="flex items-center space-x-2">
            <Link v-if="customers.prev_page_url" :href="customers.prev_page_url"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </span>
            <span class="text-sm text-gray-600">
              Trang {{ customers.current_page || 1 }} / {{ customers.last_page || 1 }}
            </span>
            <Link v-if="customers.next_page_url" :href="customers.next_page_url"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-right"></i>
            </Link>
            <span v-else
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-right"></i>
            </span>
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
      onSuccess: () => {},
    });
  }
};
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

.animate-fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>