<template>
    <AppLayout>
        <div class="min-h-screen p-6">
        <!-- Header -->
        <div class="p-4 bg-white rounded-lg shadow border border-gray-200 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h1 class="text-xl font-bold text-purple-700">
                    Chi Tiết Danh Mục
                </h1>

                <div class="flex flex-col sm:flex-row gap-3">
                    <Link :href="route('admin.categories.index')" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                        <span>Quay Lại</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-200">
                <!-- Left Column -->
                <div class="w-full md:w-1/2 p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tên Danh Mục
                            </label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                Thực phẩm đông lạnh
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mô tả
                            </label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 min-h-[100px]">
                                Các loại thực phẩm được bảo quản đông lạnh, có hạn sử dụng dài.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-full md:w-1/2 p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Danh mục cha
                            </label>
                            <div class="multiselect">
                                <div class="multiselect-input">
                                    <span class="multiselect-single">
                                        — — Thực phẩm
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ngày tạo
                            </label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                15/06/2023 10:30
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ngày cập nhật
                            </label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                20/06/2023 14:45
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <a href="#" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                    Chỉnh Sửa
                </a>
                <button type="button" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    <span>In Thông Tin</span>
                </button>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue'

</script>

<style lang="scss" scoped>

</style>