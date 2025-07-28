<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
        <h5 class="text-lg text-indigo-700 font-semibold">Quản lý hạng khách hàng</h5>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" v-model="searchQuery" @input="debounceSearch" placeholder="Tìm kiếm hạng khách hàng..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Waiting route-name="admin.ranks.create" :route-params="{}" :color="'bg-indigo-600 text-white hover:bg-indigo-700'">
            <i class="fas fa-plus mr-1"></i> Thêm hạng
          </Waiting>
        </div>
      </div>

      <!-- Notification -->
      <div v-if="notification" :class="`fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${notification.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`">
        {{ notification.message }}
      </div>

      <!-- Ranks Table -->
      <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  #
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Tên hạng
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Tổng chi tiêu tối thiểu
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  % giảm giá
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  % tín dụng
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Ghi chú
                </th>
                <th scope="col"
                  class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Trạng thái
                </th>
                <th scope="col"
                  class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                  Hành động
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="(rank, index) in ranks.data || []" :key="rank.id"
                class="hover:bg-gray-50 transition-colors duration-200"
                :class="{ 'opacity-50': rank.status === 'inactive' }">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                  {{ index + 1 + ((ranks.current_page || 1) - 1) * (ranks.per_page || 10) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                  {{ rank.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ rank.min_total_spent ? rank.min_total_spent.toLocaleString('vi-VN') : '0' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ rank.discount_percent || 0 }}%
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ rank.credit_percent || 0 }}%
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ rank.note || 'Chưa cập nhật' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2.5 py-0.5 text-xs font-medium rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': rank.status === 'active',
                      'bg-red-100 text-red-800': rank.status === 'inactive',
                    }">
                    {{ rank.status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="relative inline-block text-left">
                    <button class="text-gray-400 hover:text-gray-600 focus:outline-none"
                      @click="toggleDropdown(rank.id)">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div v-if="activeDropdown === rank.id"
                      class="absolute right-0 z-20 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                      <div class="py-1">
                        <Link :href="route('admin.ranks.edit', rank.id)"
                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          <i class="fas fa-edit mr-2 text-indigo-600"></i>
                          Sửa
                        </Link>
                        <button @click="handleToggleStatus(rank)"
                          class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                          <i :class="rank.status === 'active' ? 'fas fa-eye-slash mr-2 text-red-600' : 'fas fa-eye mr-2 text-green-600'"></i>
                          {{ rank.status === 'active' ? 'Ẩn' : 'Bật' }}
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
            <span class="font-semibold">{{ ranks.from || 1 }}</span> đến
            <span class="font-semibold">{{ ranks.to || 0 }}</span> của
            <span class="font-semibold">{{ ranks.total || 0 }}</span> kết quả
          </div>
          <div class="flex items-center space-x-2">
            <Link v-if="ranks.prev_page_url" :href="addSearchToUrl(ranks.prev_page_url)"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-left"></i>
            </span>
            <span class="text-sm text-gray-600">
              Trang {{ ranks.current_page || 1 }} / {{ ranks.last_page || 1 }}
            </span>
            <Link v-if="ranks.next_page_url" :href="addSearchToUrl(ranks.next_page_url)"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200">
              <i class="fas fa-chevron-right"></i>
            </Link>
            <span v-else class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
              <i class="fas fa-chevron-right"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { debounce } from 'lodash';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

defineProps({
  ranks: Object,
});

const activeDropdown = ref(null);
const searchQuery = ref('');
const notification = ref(null);

const { flash, errors } = usePage();

const toggleDropdown = (id) => {
  activeDropdown.value = activeDropdown.value === id ? null : id;
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    activeDropdown.value = null;
  }
};

const debounceSearch = debounce(() => {
  router.get(
    route('admin.ranks.index'),
    { search: searchQuery.value },
    { preserveState: true, preserveScroll: true }
  );
}, 300);

const addSearchToUrl = (url) => {
  const urlObj = new URL(url);
  if (searchQuery.value) {
    urlObj.searchParams.set('search', searchQuery.value);
  }
  return urlObj.toString();
};

const handleToggleStatus = (rank) => {
  const action = rank.status === 'active' ? 'ẩn' : 'bật';
  const confirmMessage = rank.status === 'active'
    ? 'Ẩn hạng này sẽ chuyển các khách hàng liên quan sang hạng mặc định "Sắt". Bạn có chắc muốn tiếp tục?'
    : 'Bật hạng này sẽ chuyển trạng thái sang hoạt động. Bạn có chắc muốn tiếp tục?';

  if (confirm(confirmMessage)) {
    router.patch(route('admin.ranks.destroy', rank.id), {}, {
      onSuccess: () => {
        activeDropdown.value = null;
        notification.value = { type: 'success', message: `Hạng đã được ${action} thành công!` };
        setTimeout(() => { notification.value = null; }, 3000);
      },
      onError: (err) => {
        console.error('Lỗi:', err);
        notification.value = { type: 'error', message: err.message || `Có lỗi xảy ra khi ${action} hạng!` };
        setTimeout(() => { notification.value = null; }, 3000);
      },
    });
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
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
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>