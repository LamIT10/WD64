<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-purple-700 font-semibold">Danh sách Khu Vực Kho</h5>
                <div class="flex items-center space-x-3">
                    <button @click="openZoneModal" v-can="'admin.warehouse-zone.create'"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Thêm mới</span>
                    </button>
                </div>
            </div>

            <!-- Zones Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên khu vực</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mô tả</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="zone in zones.data" :key="zone.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ zone.name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ zone.description }}</td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex justify-end space-x-1">
                                    <!-- Nút Sửa -->
                                    <button @click="openEditModal(zone)" v-can="'admin.warehouse-zone.edit'"
                                        class="inline-flex items-center p-1.5 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-md transition-colors"
                                        title="Chỉnh sửa">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>

                                    <!-- Nút Xóa -->
                                    <ConfirmModal :route-name="'admin.warehouse-zones.destroy'" v-can="'admin.warehouse-zone.delete'"
                                        :route-params="{ id: zone.id }" title="Xác nhận xóa khu vực">
                                        <template #trigger="{ openModal }">
                                            <button @click.stop="openModal"
                                                class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </template>
                                    </ConfirmModal>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!zones.data?.length">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Không có khu vực nào.
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ zones.from || 0 }}</span> đến
                        <span class="font-medium">{{ zones.to || 0 }}</span> của
                        <span class="font-medium">{{ zones.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex space-x-1">
                        <template v-for="(link, index) in zones.links" :key="index">
                            <button v-if="link.url" :class="[
                                'px-3 py-1 rounded-md border',
                                link.active ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'text-gray-600 hover:bg-gray-100 border-gray-300'
                            ]" @click.prevent="router.visit(link.url)" v-html="link.label" />
                            <span v-else class="px-3 py-1 text-gray-400" v-html="link.label"></span>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm -->
        <WarehouseZoneCreateModal :show="showZoneModal" @close="closeZoneModal" @created="handleZoneCreated" />
        <!-- Modal Sửa -->
        <WarehouseZoneEditModal v-if="editingZone" :show="showEditModal" :zone="editingZone" @close="closeEditModal"
            @updated="handleZoneUpdated" />
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

import AppLayout from '../Layouts/AppLayout.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import WarehouseZoneCreateModal from '../../components/WarehouseZoneCreateModal.vue';
import WarehouseZoneEditModal from '../../components/WarehouseZoneEditModal.vue';

const props = defineProps({
    zones: Object,
});

const showZoneModal = ref(false);
const showEditModal = ref(false);
const editingZone = ref(null);


// ➕ Modal Thêm
const openZoneModal = () => {
    showZoneModal.value = true;
};
const closeZoneModal = () => {
    showZoneModal.value = false;
};
const handleZoneCreated = (data) => {
    if (!data?.id) return;
    props.zones.data.push(data);
    closeZoneModal();
};

// ✏️ Modal Sửa
const openEditModal = (zone) => {
    editingZone.value = { ...zone };
    showEditModal.value = true;
};
const closeEditModal = () => {
    showEditModal.value = false;
    editingZone.value = null;
};
const handleZoneUpdated = (updatedZone) => {
    const index = props.zones.data.findIndex(z => z.id === updatedZone.id);
    if (index !== -1) {
        props.zones.data[index] = updatedZone;
    }
    closeEditModal();
};
</script>

<style scoped>
tr {
    transition: background-color 0.2s ease;
}
</style>
