<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-purple-700 font-semibold">Thêm Sản Phẩm Mới</h5>
                <button
                        class="px-4 py-2 bg-purple-50 rounded hover:text-purple-500 text-purple-600 transition-colors"
                    >
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
            </div>
            <div
                class="max-w-6xl mx-auto bg-white rounded shadow-md overflow-hidden"
            >
                <!-- Header -->

                <!-- Main Content -->
                <div class="flex flex-col md:flex-row">
                    <!-- Left Panel - Fields -->
                    <div class="w-full md:w-1/2 p-6 border-r border-gray-200">
                        <div class="space-y-6">
                            <!-- Product Name -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Tên sản phẩm *</label
                                >
                                <input
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                    placeholder="Nhập tên sản phẩm..."
                                    required
                                />
                            </div>

                            <!-- SKU -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >SKU *</label
                                >
                                <div class="flex">
                                    <input
                                        type="text"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập mã SKU..."
                                        required
                                    />
                                    <button
                                        class="px-3 bg-gray-100 border-t border-r border-b border-gray-300 rounded-r-lg text-gray-600 hover:bg-gray-200 transition-colors"
                                    >
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Danh mục *</label
                                >
                                <select
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                >
                                    <option disabled selected>
                                        Chọn danh mục
                                    </option>
                                    <option>Điện tử</option>
                                    <option>Thời trang</option>
                                    <option>Gia dụng</option>
                                    <option>Thực phẩm</option>
                                </select>
                            </div>

                            <!-- Price -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Giá bán (VNĐ) *</label
                                >
                                <div class="relative">
                                    <input
                                        type="number"
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        placeholder="Nhập giá bán..."
                                        required
                                    />
                                    <span
                                        class="absolute left-3 top-2.5 text-gray-500"
                                        >₫</span
                                    >
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >Mô tả sản phẩm</label
                                >
                                <textarea
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all h-24"
                                    placeholder="Nhập mô tả chi tiết..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel - Images & Documents -->
                    <div class="w-full md:w-1/2 p-6 bg-gray-50">
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Hình ảnh sản phẩm *</label
                                >
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors cursor-pointer"
                                >
                                    <div
                                        class="flex flex-col items-center justify-center space-y-2"
                                    >
                                        <i
                                            class="fas fa-cloud-upload-alt text-3xl text-gray-400"
                                        ></i>
                                        <p class="text-sm text-gray-500">
                                            Kéo thả hình ảnh vào đây hoặc click
                                            để chọn
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            JPG, PNG tối đa 5MB
                                        </p>
                                    </div>
                                    <input
                                        type="file"
                                        class="hidden"
                                        multiple
                                        accept="image/*"
                                    />
                                </div>
                                <div
                                    class="grid grid-cols-3 gap-2 mt-3"
                                    id="image-preview"
                                >
                                    <!-- Image previews will appear here -->
                                </div>
                            </div>

                            <!-- Document Upload -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Tài liệu đính kèm</label
                                >
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-purple-400 transition-colors cursor-pointer"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <i
                                                class="fas fa-file-pdf text-red-500"
                                            ></i>
                                            <span class="text-sm"
                                                >Thêm tài liệu PDF</span
                                            >
                                        </div>
                                        <button
                                            class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition-colors"
                                        >
                                            Chọn file
                                        </button>
                                    </div>
                                    <input
                                        type="file"
                                        class="hidden"
                                        accept=".pdf,.doc,.docx"
                                    />
                                </div>
                            </div>

                            <!-- Variants -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Biến thể sản phẩm</label
                                >
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input
                                            type="text"
                                            class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Tên biến thể"
                                        />
                                        <input
                                            type="text"
                                            class="w-20 px-3 py-1.5 border border-gray-300 rounded-lg text-sm"
                                            placeholder="SKU"
                                        />
                                        <input
                                            type="number"
                                            class="w-24 px-3 py-1.5 border border-gray-300 rounded-lg text-sm"
                                            placeholder="Giá"
                                        />
                                        <button
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <button
                                        class="flex items-center text-sm text-purple-600 hover:text-purple-800 mt-1"
                                    >
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        Thêm biến thể
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div
                    class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200"
                >
                    <button
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                    >
                        Hủy bỏ
                    </button>
                    <button
                        class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md"
                    >
                        <i class="fas fa-save mr-2"></i> Lưu sản phẩm
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { route } from "ziggy-js";
import AppLayout from "../Layouts/AppLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
const props = defineProps({
    suppliers: {
        type: Array,
        default: () => [],
    },
});
</script>

<style lang="css" scoped></style>
