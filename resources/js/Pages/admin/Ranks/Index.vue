<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg text-purple-700 font-semibold mb-6">Quản lý hạng khách hàng</h2>

        <!-- Danh sách hạng -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tên hạng
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tổng chi tiêu tối thiểu
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  % giảm giá
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  % tín dụng
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ghi chú
                </th>
                <th scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Hành động
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="rank in ranks.data" :key="rank.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rank.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.min_total_spent }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.discount_percent }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.credit_percent }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.note || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link :href="route('admin.ranks.edit', rank.id)"
                    class="text-purple-600 hover:text-purple-800 mr-2">
                    <i class="fas fa-edit"></i>
                  </Link>
                  <button @click="handleDelete(rank.id)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Phân trang -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Hiển thị
            <span class="font-medium">{{ ranks.meta?.from || 1 }}</span> đến
            <span class="font-medium">{{ ranks.meta?.to || 0 }}</span> của
            <span class="font-medium">{{ ranks.meta?.total || 0 }}</span> kết quả
          </div>
          <div class="flex space-x-1">
            <button v-for="(link, index) in ranks.links" :key="index"
              class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50"
              :class="{ 'bg-purple-600 text-white': link.active, 'disabled:opacity-50': !link.url }"
              :disabled="!link.url">
              <Link v-if="link.url" :href="link.url" v-html="formatLabel(link.label)" class="block" />
              <span v-else v-html="formatLabel(link.label)" class="block text-gray-500" />
            </button>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <Link :href="route('admin.ranks.create')"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
            Thêm hạng
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({
  ranks: Object,
});

const handleDelete = (rankId) => {
  if (confirm('Xóa hạng này sẽ đặt hạng của các khách hàng liên quan thành null. Bạn có chắc muốn tiếp tục?')) {
    useForm({}).delete(route('admin.ranks.destroy', rankId), {
      onSuccess: () => {
      },
      onError: (errors) => {
        console.error('Lỗi xóa:', errors);
      },
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