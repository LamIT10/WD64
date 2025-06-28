<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-purple-700 font-semibold">Danh sách Đơn Vị</h5>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input v-model="searchQuery" type="text" placeholder="Tìm kiếm Đơn Vị..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500"
                            @input="handleSearch" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button @click="openUnitModal"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Thêm mới</span>
                    </button>
                </div>
            </div>

            <!-- Units Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên đơn vị</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ký hiệu</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="unit in units.data" :key="unit.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ unit.name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ unit.symbol }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <ConfirmModal :route-name="'admin.units.destroy'" :route-params="{ id: unit.id }"
                                    title="Xác nhận xóa đơn vị">
                                    <template #trigger="{ openModal }">
                                        <button @click.stop="openModal"
                                            class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </template>
                                </ConfirmModal>
                            </td>
                        </tr>
                        <tr v-if="!units.data?.length">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Không có đơn vị nào.
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ units.from || 0 }}</span> đến
                        <span class="font-medium">{{ units.to || 0 }}</span> của
                        <span class="font-medium">{{ units.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex space-x-1">
                        <template v-for="(link, index) in units.links" :key="index">
                            <button v-if="link.url" :class="[
                                'px-3 py-1 rounded-md border',
                                link.active ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100 border-gray-300'
                            ]" @click.prevent="router.visit(link.url)" v-html="link.label" />
                            <span v-else class="px-3 py-1 text-gray-400" v-html="link.label"></span>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <UnitCreateModal :show="showUnitModal" @close="closeUnitModal" @created="handleUnitCreated" />
    </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import UnitCreateModal from '../../components/UnitCreateModal.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    units: Object,
});

const searchQuery = ref('');
const showUnitModal = ref(false);

const handleSearch = () => {
    router.get(route('admin.units.index'), {
        search: searchQuery.value
    }, {
        preserveState: true,
        replace: true
    });
};

const openUnitModal = () => {
    showUnitModal.value = true;
};

const closeUnitModal = () => {
    showUnitModal.value = false;
};

const handleUnitCreated = (data) => {
    if (!data?.id || !data?.name || !data?.symbol) return;
    props.units.data.push(data);
    closeUnitModal();
};
</script>
<style scoped>
tr {
    transition: background-color 0.2s ease;
}
</style>