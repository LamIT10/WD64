<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-6">
            <!-- Header and Add button -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Quản lý Danh Mục</h1>
                <Link :href="route('admin.categories.create')" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Thêm danh mục
                </Link>
            </div>

            <!-- Categories table -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <!-- Table header -->
                <div class="grid grid-cols-12 bg-gray-50 px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-2 text-center">ID</div>
                    <div class="col-span-3 text-center">Tên Danh Mục</div>
                    <div class="col-span-2 text-center">Mô tả</div>
                    <div class="col-span-2 text-center">Ngày Tạo</div>
                    <div class="col-span-2 text-center">Thao Tác</div>
                </div>

                <!-- Categories list -->
                <div class="divide-y divide-gray-200">
                    <template v-for="(category, index) in categories.data" :key="category.id">
                        <!-- Parent category -->
                        <div class="transition-all duration-200 hover:bg-gray-50">
                            <div @click="toggleCategory(category.id)" 
                                 class="grid grid-cols-12 px-6 py-4 items-center cursor-pointer select-none">
                                <div class="col-span-1 text-center text-gray-600 font-medium">{{ index + 1 }}</div>
                                <div class="col-span-2 text-center text-gray-600">{{ category.id }}</div>
                                <div class="col-span-3 text-center font-semibold text-gray-800">{{ category.name }}</div>
                                <div class="col-span-2 text-center text-gray-500 truncate">{{ category.description }}</div>
                                <div class="col-span-2 text-center text-gray-500 text-sm">{{ formatDate(category.created_at) }}</div>
                                <div class="col-span-2 flex justify-center space-x-2">
                                    <button @click.stop="toggleCategory(category.id)" class="text-gray-400 hover:text-gray-600 p-1">
                                        <i :class="expanded[category.id] ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-sm"></i>
                                    </button>
                                    <Link :href="route('admin.categories.show', category.id)" 
                                          class="text-blue-500 hover:text-blue-700 p-1" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </Link>
                                    <Link :href="route('admin.categories.edit', category.id)" 
                                          class="text-yellow-500 hover:text-yellow-700 p-1" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button @click.stop="deleteCategory(category.id)" 
                                            class="text-red-500 hover:text-red-700 p-1" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Child categories -->
                            <div v-show="expanded[category.id]" class="bg-gray-50 pl-12 pr-6 transition-all duration-300">
                                <div v-for="(child, childIndex) in category.children" :key="child.id" 
                                     class="grid grid-cols-12 py-3 items-center border-t border-gray-200 hover:bg-gray-100">
                                    <div class="col-span-1 text-center text-gray-400">{{ childIndex + 1 }}</div>
                                    <div class="col-span-2 text-center text-gray-400">{{ child.id }}</div>
                                    <div class="col-span-3 text-center text-gray-600">
                                        <span class="ml-4">{{ child.name }}</span>
                                    </div>
                                    <div class="col-span-2 text-center text-gray-400 truncate">{{ child.description }}</div>
                                    <div class="col-span-2 text-center text-gray-400 text-sm">{{ formatDate(child.created_at) }}</div>
                                    <div class="col-span-2 flex justify-center space-x-2">
                                        <Link :href="route('admin.categories.show', child.id)" 
                                              class="text-blue-500 hover:text-blue-700 p-1" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </Link>
                                        <Link :href="route('admin.categories.edit', child.id)" 
                                              class="text-yellow-500 hover:text-yellow-700 p-1" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </Link>
                                        <button @click.stop="deleteCategory(child.id)" 
                                                class="text-red-500 hover:text-red-700 p-1" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue'
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'


const props = defineProps({
    categories: Object,
    
})

const expanded = ref({})
const toggleCategory = (categoryId) => {
    expanded.value[categoryId] = !expanded.value[categoryId]
}

const deleteCategory = (id) => {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        router.delete(route('admin.categories.destroy', id))
    }
}

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' }
    return new Date(dateString).toLocaleDateString('vi-VN', options)
}
</script>

<style lang="css" scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.btn-primary {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: #2563eb;
    color: #fff;
    border-radius: 0.5rem;
    transition: background 0.2s;
    font-size: 0.875rem;
    font-weight: 500;
}
.btn-primary:hover {
    background: #1d4ed8;
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}


</style>