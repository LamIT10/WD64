<template>
    <AppLayout>
      <div class="container mx-auto px-4 py-8">
  
        <!-- Header -->
        <div class="mb-6">
          <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-500 text-white shadow-lg">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white to-transparent"></div>
            <div class="flex items-center justify-between px-6 py-5 relative z-10">
              <div>
                <h1 class="text-xl font-semibold tracking-tight">Nhật ký đơn xuất</h1>
                <p class="text-white/90 mt-1 text-sm">
                  Mã đơn:
                  <span class="font-semibold">{{ saleOrderCode }}</span>
                </p>
              </div>
              <Waiting
                route-name="admin.sale-orders.index"
                :route-params="{}"
                class="group inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors backdrop-blur ring-1 ring-white/20"
              >
                <i class="fas fa-arrow-left mr-1 transition-transform group-hover:-translate-x-0.5"></i>
                <span>Quay lại</span>
              </Waiting>
            </div>
            <div class="px-6 pb-4 text-xs text-white/80">
              <i class="fas fa-clock mr-1"></i>
              Cập nhật theo thời gian thực khi có thay đổi.
            </div>
          </div>
        </div>
  
        <!-- Timeline / Table Card -->
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100">
          <div class="px-6 pt-6 pb-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <i class="fas fa-history text-indigo-600"></i>
              <h2 class="text-base font-semibold text-gray-800">Lịch sử thay đổi</h2>
            </div>
            <div class="text-sm text-gray-500">
              Tổng bản ghi:
              <span class="font-medium text-gray-700">{{ totalRows }}</span>
            </div>
          </div>
  
          <div class="relative overflow-hidden">
            <!-- viền trang trí -->
            <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-indigo-500 via-sky-400 to-indigo-500"></div>
  
            <div class="relative overflow-x-auto rounded-b-2xl">
              <table class="w-full text-sm text-left text-gray-700">
                <thead class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-y border-gray-200 text-xs uppercase text-gray-500">
                  <tr>
                    <th class="px-4 py-3 w-14 text-center">#</th>
                    <th class="px-4 py-3">Hành động</th>
                    <th class="px-4 py-3">Ghi chú</th>
                    <th class="px-4 py-3">Người thực hiện</th>
                    <th class="px-4 py-3 whitespace-nowrap">Ngày thực hiện</th>
                  </tr>
                </thead>
  
                <tbody class="divide-y divide-gray-100">
                  <!-- Trạng thái rỗng -->
                  <tr v-if="!data || !data.length">
                    <td colspan="5" class="px-6 py-12 text-center">
                      <div class="inline-flex flex-col items-center gap-2 text-gray-500">
                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                          <i class="fas fa-inbox text-gray-400"></i>
                        </div>
                        <div class="text-sm">Chưa có lịch sử.</div>
                      </div>
                    </td>
                  </tr>
  
                  <!-- Dòng dữ liệu -->
                  <tr
                    v-for="(item, index) in data"
                    :key="item.id || index"
                    class="group hover:bg-indigo-50/40 transition-colors"
                  >
                    <td class="px-4 py-3 text-center text-gray-600">
                      <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-xs group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-colors">
                        {{ index + 1 }}
                      </span>
                    </td>
  
                    <td class="px-4 py-3">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-indigo-100 shadow-[0_0_0_1px_rgba(99,102,241,0.05)]"
                        :title="item.action_name"
                      >
                        <i class="fas fa-tag text-[11px]"></i>
                        {{ item.action_name || '—' }}
                      </span>
                    </td>
  
                    <td class="px-4 py-3">
                      <!-- Rút gọn 1 dòng + tooltip xem đầy đủ -->
                      <span class="block max-w-2xl truncate text-gray-700" :title="item.content">
                        {{ item.content || '—' }}
                      </span>
                    </td>
  
                    <td class="px-4 py-3 whitespace-nowrap">
                      <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full bg-gradient-to-br from-indigo-100 to-sky-100 ring-1 ring-indigo-100 flex items-center justify-center text-[11px] text-indigo-700">
                          <i class="fas fa-user"></i>
                        </div>
                        <span class="text-gray-800">{{ (item.user && item.user.name) || '—' }}</span>
                      </div>
                    </td>
  
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                      <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                      <span :title="item.created_at">{{ formatDateTime(item.created_at) }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
  
          </div>
        </div>
  
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { computed } from "vue";
  import AppLayout from "../Layouts/AppLayout.vue";
  import Waiting from "../../components/Waiting.vue";
  import { Link } from "@inertiajs/vue3";
  
  const { data, saleOrderCode } = defineProps({
    data: { default: () => ({}) },
    saleOrderCode: { default: () => "" },
  });
  
  // Tổng số dòng (chỉ để hiển thị)
  const totalRows = computed(() => (Array.isArray(data) ? data.length : 0));
  
  // Format date time (giữ nguyên logic)
  const formatDateTime = (dateString) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${day}/${month}/${year} ${hours}:${minutes}`;
  };
  </script>
  
  <style scoped>
  /* Nhẹ nhàng hơn khi cuộn bảng (nếu bảng cao và sticky header) */
  tbody::-webkit-scrollbar {
    height: 8px;
    width: 8px;
  }
  tbody::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 9999px;
  }
  </style>
  