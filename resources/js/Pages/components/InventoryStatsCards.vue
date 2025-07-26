<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <Link :href="route('admin.products.index')" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4 hover:shadow-md transition">
      <div class="p-3 bg-indigo-50 rounded-lg flex items-center justify-center">
        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">Tổng loại sản phẩm trong kho</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ cardData.count_product }}</p>
      </div>
    </Link>
    <!-- Card 2 -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4">
      <div class="p-3 bg-red-50 rounded-lg flex items-center justify-center">
        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
          </path>
        </svg>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">Sản phẩm sắp hết hàng</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ cardData.product_is_out_of_stock }}</p>
        <p class="text-xs text-red-500 mt-1">Cần nhập</p>
      </div>
    </div>
    <!-- Card 3 -->
    <a href="/admin/inventory/statistics" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4 hover:shadow-md transition">
      <div class="p-3 bg-blue-50 rounded-lg flex items-center justify-center">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
          </path>
        </svg>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">Thống kê tồn kho</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Đến trang thống kê</p>
        <p class="text-xs text-blue-400 mt-2">Xem chi tiết thống kê kho</p>
      </div>
    </a>
    <!-- Card 4 -->
    <a href="/admin/inventory/history" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-start space-x-4 hover:shadow-md transition">
      <div class="p-3 bg-green-50 rounded-lg flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 7V3m8 4V3m-9 8h10m-12 8a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12z"/>
        </svg>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">Lịch sử tồn kho</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Đến trang lịch sử kho</p>
        <p class="text-xs text-gray-400 mt-2">Cập nhật lần cuối: {{ lastUpdated }}</p>
      </div>
    </a>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const cardData = ref({
  count_product: 0,
  product_is_out_of_stock: 0,
});

const lastUpdated = ref('Đang tải...');

onMounted(async () => {
  const res = await fetch('/api/inventory/stats');
  if (res.ok) {
    const data = await res.json();
    cardData.value = data;
    // Hiển thị giờ:phút (2 số), nếu không phải hôm nay thì hiển thị dd/MM/yyyy
    const now = new Date();
    const pad = n => n.toString().padStart(2, '0');
    const today = new Date();
    if (
      now.getDate() === today.getDate() &&
      now.getMonth() === today.getMonth() &&
      now.getFullYear() === today.getFullYear()
    ) {
      lastUpdated.value = `${pad(now.getHours())}:${pad(now.getMinutes())} hôm nay`;
    } else {
      lastUpdated.value = `${pad(now.getDate())}/${pad(now.getMonth() + 1)}/${now.getFullYear()}`;
    }
  } else {
    lastUpdated.value = 'Không thể lấy dữ liệu';
  }
});
</script>
