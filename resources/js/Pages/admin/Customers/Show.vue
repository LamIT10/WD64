<template>
  <AppLayout>
    <div class="bg-gray-100 min-h-screen p-6">
      <!-- Header Section -->
      <div class="bg-white rounded-lg shadow p-4 mb-6 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-indigo-700">Chi tiết khách hàng</h1>
        <div class="flex space-x-3">
          <Waiting route-name="admin.customers.edit" :route-params="{ id: customer.id }"
            class="bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded flex items-center space-x-1 transition-colors duration-200">
            <i class="fas fa-edit"></i>
            <span>Chỉnh sửa</span>
          </Waiting>
          <Waiting route-name="admin.customers.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
          </Waiting>
        </div>
      </div>

      <!-- Main Content -->
      <div class="bg-white rounded-lg shadow overflow-hidden animate-fade-in">
        <!-- Avatar -->
        <div class="p-6 text-center">
          <img :src="customer.avatar ? `/storage/${customer.avatar}` : 'https://cdn-media.sforum.vn/storage/app/media/ctv_seo3/meme-meo-cuoi-51.jpg'"
            alt="Avatar"
            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 shadow mx-auto mb-4 transform hover:scale-105 transition-transform duration-300">
        </div>

        <!-- Content Sections -->
        <div class="p-6">
          <!-- Personal Information -->
          <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin cá nhân</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-id-badge text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">ID</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ customer.id || 'Chưa cập nhật' }}</div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-user text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Họ và tên</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ customer.name || 'Chưa cập nhật' }}</div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-info-circle text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Trạng thái</label>
              </div>
              <div class="mt-1 border-b border-indigo-200 pb-1">
                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': customer.status === 'active',
                    'bg-red-100 text-red-800': customer.status === 'inactive'
                  }">
                  {{ statusLabel(customer.status) }}
                </span>
              </div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-star text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Hạng khách hàng</label>
              </div>
              <div class="mt-1 border-b border-indigo-200 pb-1">
                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-gray-200 text-gray-800': customer.rank?.name === 'Sắt',
                    'bg-amber-200 text-amber-800': customer.rank?.name === 'Đồng',
                    'bg-slate-200 text-slate-800': customer.rank?.name === 'Bạc',
                    'bg-yellow-200 text-yellow-800': customer.rank?.name === 'Vàng',
                    'bg-blue-200 text-blue-800': customer.rank?.name === 'Bạch Kim',
                    'bg-gray-100 text-gray-700': !customer.rank
                  }">
                  {{ customer.rank ? customer.rank.name : 'Chưa cập nhật' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin liên hệ</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-phone text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Số điện thoại</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ customer.phone || 'Chưa cập nhật' }}</div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-envelope text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Email</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ customer.email || 'Chưa cập nhật' }}</div>
            </div>
            <div class="md:col-span-2">
              <div class="flex items-center space-x-2">
                <i class="fas fa-map-marker-alt text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Địa chỉ</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ customer.address || 'Chưa cập nhật' }}</div>
            </div>
          </div>

          <!-- Timestamps -->
          <h3 class="text-lg font-semibold text-indigo-600 pb-2 mb-4">Thông tin hệ thống</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-calendar-plus text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Ngày tạo</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ formatDate(customer.created_at) || 'Chưa cập nhật' }}</div>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-calendar-check text-indigo-500"></i>
                <label class="text-sm font-medium text-gray-700">Ngày cập nhật</label>
              </div>
              <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ formatDate(customer.updated_at) || 'Chưa cập nhật' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { route } from 'ziggy-js';

defineProps({
  customer: Object,
});

// Hàm dịch trạng thái
const statusLabel = (status) => {
  switch (status) {
    case 'active':
      return 'Hợp tác';
    case 'inactive':
      return 'Ngừng hợp tác';
    default:
      return 'Chưa cập nhật';
  }
};

// Hàm định dạng ngày
function formatDate(date) {
  if (!date) return null;
  const d = new Date(date);
  return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
}
</script>

<style scoped>
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

button, a {
  transition: all 0.2s ease-in-out;
}
</style>