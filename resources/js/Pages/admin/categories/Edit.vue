<template>
    <div>
        <AppLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chỉnh sửa danh mục
                </h2>
            </template>

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8">
                            <!-- Breadcrumb -->
                            <nav class="flex mb-6" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    <li>
                                        <div class="flex items-center">
                                            <a :href="route('admin.categories.index')"
                                                class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                                                Danh mục
                                            </a>
                                        </div>
                                    </li>
                                    <li aria-current="page">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                            </svg>
                                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Chỉnh
                                                sửa</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>

                            <!-- Form -->
                            <form @submit.prevent="updateCategory" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Tên danh mục -->
                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tên danh mục <span class="text-red-500">*</span>
                                        </label>
                                        <input id="name" v-model="form.name" type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            :class="{ 'border-red-500': errors.name }" placeholder="Nhập tên danh mục"
                                            maxlength="100" />
                                        <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                                            {{ errors.name }}
                                        </div>
                                    </div>

                                    <!-- Danh mục cha -->
                                    <div class="md:col-span-2">
                                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            Danh mục cha
                                        </label>
                                        <select id="parent_id" v-model="form.parent_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            :class="{ 'border-red-500': errors.parent_id }">
                                            <option value="">Chọn danh mục cha</option>
                                            <option v-for="cat in categories" :key="cat.id" :value="cat.id"
                                                :disabled="cat.id === form.id">
                                                {{ cat.name }}
                                            </option>
                                        </select>
                                        <div v-if="errors.parent_id" class="mt-1 text-sm text-red-600">
                                            {{ errors.parent_id }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Để trống nếu đây là danh mục gốc
                                        </div>
                                    </div>

                                    <!-- Mô tả -->
                                    <div class="md:col-span-2">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Mô tả
                                        </label>
                                        <textarea id="description" v-model="form.description" rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            :class="{ 'border-red-500': errors.description }"
                                            placeholder="Nhập mô tả cho danh mục (tùy chọn)"></textarea>
                                        <div v-if="errors.description" class="mt-1 text-sm text-red-600">
                                            {{ errors.description }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                    <button type="button" @click="resetForm"
                                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Hủy bỏ
                                    </button>
                                    <Link :href="route('admin.categories.index')"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Quay lại
                                    </Link>
                                    <button type="submit" :disabled="form.processing"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ processing ? 'Đang xử lý...' : 'Cập nhật danh mục' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
    category: Object,
    categories: Array,
    errors: Object
});

const form = useForm({
    name: props.category.name || '',
    parent_id: props.category.parent_id || '',
    description: props.category.description || ''
});

const processing = ref(false);

const updateCategory = () => {
    form.put(route('admin.categories.update', props.category.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Cập nhật thành công');
        },
        onError: (errors) => {
            console.error('Lỗi validate:', errors);
        }
    });
};

const resetForm = () => {
    form.reset();
};
</script>

<style scoped></style>
