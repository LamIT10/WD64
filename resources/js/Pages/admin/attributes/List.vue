<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div
                class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
                <h5 class="text-lg text-purple-700 font-semibold">
                    Danh sách Thuộc Tính
                </h5>
                <div class="flex items-center space-x-3">
                    <!-- Search bar -->
                    <div class="relative">
                        <input v-model="searchQuery" type="text" placeholder="Tìm kiếm Thuộc Tính..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            @input="handleSearch" />
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button @click="openAttributeModal" v-can="'admin.attribute.create'"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Thêm mới</span>
                    </button>
                </div>
            </div>

            <!-- Attribute Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên thuộc tính
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giá trị thuộc tính
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hành động
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template v-for="attribute in attributes.data" :key="attribute.id">
                                <tr @click="toggleAttributeValues(attribute.id)"
                                    class="cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="flex items-center">
                                            <i
                                                :class="['fas', expandedAttributes[attribute.id] ? 'fa-chevron-down' : 'fa-chevron-right', 'mr-2 transition-transform duration-300', expandedAttributes[attribute.id] ? 'rotate-90' : '']"></i>
                                            {{ attribute.name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span v-if="attributeValues[attribute.id]?.length">
                                            {{attributeValues[attribute.id].map(v => v.name).join(', ')}}
                                        </span>
                                        <span v-else>Chưa có giá trị</span>
                                    </td>
                                    <td
                                        class="flex justify-end px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click.stop="openAttributeValueModal(attribute.id)" v-can="'admin.attribute.create'"
                                            class="inline-flex items-center p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-md transition-colors mr-2">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                        <ConfirmModal :route-name="'admin.attributes.destroy'" v-can="'admin.attribute.delete'"
                                            :route-params="{ id: attribute.id }" title="Xác nhận xóa thuộc tính">
                                            <template #trigger="{ openModal }">
                                                <button @click.stop="openModal"
                                                    class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </template>
                                        </ConfirmModal>
                                    </td>
                                </tr>
                                <!-- Accordion for Attribute Values -->
                                <tr v-if="expandedAttributes[attribute.id]" class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-4">
                                        <Transition name="accordion" mode="out-in">
                                            <div class="pl-8">
                                                <ul class="space-y-2">
                                                    <li v-for="value in attributeValues[attribute.id]" :key="value.id"
                                                        class="flex items-center justify-between">
                                                        <!-- CHỈ phần tên giá trị mới animate -->
                                                        <span class="text-sm text-gray-700 animate-slide-in">{{
                                                            value.name }}</span>

                                                        <!-- KHÔNG animate phần modal để tránh lỗi hiển thị -->
                                                        <ConfirmModal :route-name="'admin.attribute-values.destroy'"
                                                            :route-params="{ id: value.id }"
                                                            title="Xác nhận xóa giá trị thuộc tính">
                                                            <template #trigger="{ openModal }">
                                                                <button @click.stop="openModal"
                                                                    class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                                                    <i class="fas fa-trash text-sm"></i>
                                                                </button>
                                                            </template>
                                                        </ConfirmModal>
                                                    </li>

                                                    <li v-if="!attributeValues[attribute.id]?.length"
                                                        class="text-sm text-gray-500 animate-slide-in">
                                                        Chưa có giá trị thuộc tính.
                                                    </li>
                                                </ul>
                                            </div>
                                        </Transition>
                                    </td>
                                </tr>
                            </template>
                            <tr v-if="!attributes.data || attributes.data.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Không có thuộc tính nào.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Hiển thị <span class="font-medium">{{ attributes.from || 0 }}</span> đến
                        <span class="font-medium">{{ attributes.to || 0 }}</span> của
                        <span class="font-medium">{{ attributes.total || 0 }}</span> kết quả
                    </div>
                    <div class="flex space-x-1">
                        <template v-for="(link, index) in attributes.links" :key="index">
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
        <!-- Modals -->
        <AttributeCreateModal :show="showAttributeModal" @close="closeAttributeModal"
            @created="handleAttributeCreated" />
        <AttributeValueCreateModal :show="showAttributeValueModal" :attributeId="modalAttributeId"
            @close="closeAttributeValueModal" @created="handleAttributeValueCreated" />
    </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import AttributeCreateModal from '../../components/AttributeCreateModal.vue';
import AttributeValueCreateModal from '../../components/AttributeValueCreateModal.vue';
import { route } from 'ziggy-js';

const props = defineProps({
    attributes: Object,
    attributeValues: Object,
});

const searchQuery = ref('');
const handleSearch = () => {
    router.get(
        route('admin.attributes.index'),
        { search: searchQuery.value },
        { preserveState: true, replace: true }
    );
};

// Accordion Handling
const expandedAttributes = ref({});

const toggleAttributeValues = (attributeId) => {
    expandedAttributes.value[attributeId] = !expandedAttributes.value[attributeId];
};

// Modal Handling
const showAttributeModal = ref(false);
const showAttributeValueModal = ref(false);
const modalAttributeId = ref(null);
const attributeValues = reactive(props.attributeValues || {});

const openAttributeModal = () => {
    showAttributeModal.value = true;
};

const closeAttributeModal = () => {
    showAttributeModal.value = false;
};

const handleAttributeCreated = (data) => {
    if (!data || !data.id || !data.name) {
        console.error('Dữ liệu thuộc tính không hợp lệ:', data);
        return;
    }
    props.attributes.data.push(data);
    attributeValues.value[data.id] = [];
    closeAttributeModal();
};

const openAttributeValueModal = (attributeId) => {
    modalAttributeId.value = attributeId;
    showAttributeValueModal.value = true;
};

const closeAttributeValueModal = () => {
    showAttributeValueModal.value = false;
    modalAttributeId.value = null;
};

const handleAttributeValueCreated = (data) => {
    if (!data || !data.id || !data.name || !data.attribute_id) {
        console.error('Dữ liệu giá trị thuộc tính không hợp lệ:', data);
        return;
    }
    if (!attributeValues.value[modalAttributeId.value]) {
        attributeValues.value[modalAttributeId.value] = [];
    }
    // Tạo bản sao mới của attributeValues để kích hoạt reactivity
    attributeValues.value = {
        ...attributeValues.value,
        [modalAttributeId.value]: [...attributeValues.value[modalAttributeId.value], data],
    };
    closeAttributeValueModal();
};


// Handle Attribute Value Deletion
const handleAttributeValueDeleted = (attributeId, valueId) => {
    if (attributeValues.value[attributeId]) {
        attributeValues.value = {
            ...attributeValues.value,
            [attributeId]: attributeValues.value[attributeId].filter((v) => v.id !== valueId),
        };
    }
};

// Listen for deletion events from ConfirmModal
router.on('success', (event) => {
    const flash = event.detail.page.props.flash;
    if (flash && flash.data && flash.data.route === 'admin.attribute-values.destroy') {
        const valueId = flash.data.id;
        const attributeId = Object.keys(attributeValues.value).find((key) =>
            attributeValues.value[key].some((value) => value.id === valueId)
        );
        if (attributeId) {
            handleAttributeValueDeleted(attributeId, valueId);
        }
    }
});
</script>

<style scoped>
/* Accordion animation */
.accordion-enter-active,
.accordion-leave-active {
    transition: all 0.4s ease-out;
}

.accordion-enter-from,
.accordion-leave-to {
    opacity: 0;
    max-height: 0;
    transform: translateY(-10px);
}

.accordion-enter-to,
.accordion-leave-from {
    opacity: 1;
    max-height: 300px;
    /* Adjust based on content */
    transform: translateY(0);
}

/* Slide-in animation for list items */
.animate-slide-in {
    animation: slide-in 0.3s ease-out forwards;
}

@keyframes slide-in {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Smooth transition for table row hover */
tr {
    transition: background-color 0.2s ease;
}

/* Rotate chevron icon */
.fa-chevron-right {
    transition: transform 0.3s ease;
}

.fa-chevron-right.rotate-90 {
    transform: rotate(90deg);
}
</style>