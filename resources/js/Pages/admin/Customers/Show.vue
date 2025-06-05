<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">Chi tiết khách hàng</h5>
                <div class="flex space-x-3">
                    <Link :href="route('admin.customers.edit', customer.id)"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Sửa khách hàng</span>
                    </Link>
                    <Link :href="route('admin.customers.index')"
                        class="px-4 py-2 bg-indigo-50 rounded hover:text-indigo-500 text-indigo-600 transition-colors flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </Link>
                </div>
            </div>
            <div class="mx-auto bg-white rounded-lg shadow-md overflow-hidden animate-fade-in">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-id-card text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">ID</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ customer.id || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Họ tên</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ customer.name || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-envelope text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Email</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ customer.email || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-phone text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Số điện thoại</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ customer.phone || 'Chưa cập nhật' }}
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-star text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Hạng khách hàng</label>
                            </div>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-indigo-100 text-indigo-800': customer.rank,
                                        'bg-gray-100 text-gray-800': !customer.rank
                                    }">
                                    {{ customer.rank ? customer.rank.name : 'Chưa cập nhật' }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow md:col-span-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-map-marker-alt text-indigo-600"></i>
                                <label class="text-sm font-semibold text-gray-700">Địa chỉ</label>
                            </div>
                            <div class="mt-2 text-gray-600">
                                {{ customer.address || 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

defineProps({
    customer: Object,
});
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>