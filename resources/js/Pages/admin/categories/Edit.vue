<template>
    <AppLayout>
        <div class="bg-gray-50 min-h-screen p-6">
            <!-- Header -->
            <div class="p-4 bg-white rounded-lg shadow border border-gray-200 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h1 class="text-xl font-bold text-purple-700">
                        Chỉnh Sửa Danh Mục
                    </h1>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <Link :href="route('admin.categories.index')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                        <span>Danh Sách</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                <div class="flex flex-col md:flex-row divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    <!-- Left Column -->
                    <div class="w-full md:w-1/2 p-6">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tên Danh Mục <span class="text-red-500">*</span>
                                </label>
                                <input type="text" v-model="form.name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Nhập tên danh mục" />
                                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Mô tả
                                </label>
                                <textarea v-model="form.description"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Nhập mô tả chi tiết" rows="5"></textarea>
                                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}
                                </p>
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
                                <Multiselect v-model="form.parent_id" :options="processedCategories" :searchable="true"
                                    placeholder="Chọn danh mục cha" :close-on-select="true" :can-clear="true"
                                    value-prop="id" label="formattedName" track-by="id" :max-height="300">
                                    <template v-slot:option="{ option }">
                                        <span :style="{ color: getLevelColor(option.level) }">
                                            {{ option.formattedName }}
                                        </span>
                                    </template>
                                </Multiselect>
                                <p v-if="errors.parent_id" class="mt-1 text-sm text-red-600">{{ errors.parent_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" @click="resetForm"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="button" @click="submitForm" :disabled="form.processing"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>
                        <span v-if="form.processing">Đang lưu...</span>
                        <span v-else>Cập nhật</span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'
import Multiselect from '@vueform/multiselect'
import { computed, ref } from 'vue'

const props = defineProps({
    category: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

const processedCategories = computed(() => {
    const result = []
    result.push({
        id: null,
        name: 'Danh mục gốc',
        formattedName: 'Danh mục gốc',
        level: 0
    })
    function processCategories(categories, level = 1) {
        categories.forEach(category => {
            if (category.id !== props.category.id) {
                result.push({
                    id: category.id,
                    name: category.name,
                    formattedName: `${'— '.repeat(level)}${category.name}`,
                    level: level
                })

                if (category.children && category.children.length) {
                    processCategories(category.children, level + 1)
                }
            }
        })
    }

    processCategories(props.categories)
    return result
})

const getLevelColor = (level) => {
    const colors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444', '#1AAED4', '#683EDD']
    return colors[level] || colors[colors.length - 1]
}
const form = useForm({
    name: props.category.name || '',
    parent_id: props.category.parent_id || '',
    description: props.category.description || ''
});

const submitForm = () => {
    form.put(route('admin.categories.update', props.category.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Cập nhật thành công');
        },
        onError: (errors) => {
            console.error('Lỗi validate:', errors);
        }
    });
}
const resetForm = () => {
    form.reset();
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<style scoped>
:deep(.multiselect) {
    width: 100%;
    min-height: 42px;
}

:deep(.multiselect-input) {
    min-height: 42px;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}

:deep(.multiselect-dropdown) {
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    max-height: 300px;
}

:deep(.multiselect-option) {
    padding-left: calc(0.5rem * var(--level));
    white-space: nowrap;
}

:deep(.multiselect-option.is-pointed) {
    background-color: #f3f4f6;
}

:deep(.multiselect-option.is-selected) {
    background-color: #ede9fe;
    color: #7c3aed !important;
    font-weight: 500;
}
</style>