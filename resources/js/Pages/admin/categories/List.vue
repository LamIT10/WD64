<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <!-- Header -->
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
        <h5 class="text-lg text-purple-700 font-semibold">
          Danh sách Danh Mục
        </h5>
        <div class="flex items-center space-x-3">
          <!-- Search bar -->
          <div class="relative">
            <input type="text" placeholder="Tìm kiếm Danh Mục..."
              class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" />
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
          <Link :href="route('admin.categories.create')"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
          <i class="fas fa-plus"></i>
          <span>Thêm mới</span>
          </Link>
        </div>
      </div>

      <!-- Supplier Table -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tên danh mục
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Mô Tả
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <CategoryItem v-for="category in categories.data" :key="category.id" :category="category" />
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Hiển thị <span class="font-medium">1</span> đến
            <span class="font-medium">2</span> của
            <span class="font-medium">10</span> kết quả
          </div>
          <div class="flex space-x-1">
            <button
              class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 disabled:opacity-50">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-3 py-1 bg-purple-600 text-white rounded-md">
              1
            </button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
              2
            </button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import CategoryItem from '../../components/CategoryItem.vue'


const props = defineProps({
  categories: Object,
})
</script>

<style scoped>
/* Có thể thêm style tùy chỉnh nếu cần */
</style>