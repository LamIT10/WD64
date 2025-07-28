<template>
    <AppLayout>
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <h2 class="text-lg font-medium text-gray-900">
                            All Notifications
                        </h2>
                        <span
                            class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full"
                            >{{ notifications.length }}</span
                        >
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-3"
                                >Only Show Unread</span
                            >
                            <button
                                @click="toggleUnreadOnly"
                                :class="
                                    showUnreadOnly
                                        ? 'bg-blue-600'
                                        : 'bg-gray-200'
                                "
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <span
                                    :class="
                                        showUnreadOnly
                                            ? 'translate-x-6'
                                            : 'translate-x-1'
                                    "
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-200">
                <div
                    v-for="notification in notifications.data"
                    :key="notification.id"
                    @click="goToSaleOrder(notification)"
                    class="px-6 py-4 hover:bg-gray-50"
                >
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-1">
                            <i :class="getIconClass(notification.type)"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ notification.title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ notification.message }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4 ml-4">
                                    <span class="text-sm text-gray-500">{{
                                        notification.time
                                    }}</span>
                                    <button
                                        @click="markAsRead(notification.id)"
                                        v-if="!notification.isRead"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        Mark as Read
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Hiển thị {{ notifications.current_page }} /
                    {{ notifications.last_page }} trang (Tổng cộng
                    {{ notifications.total }} thông báo)
                </div>
                <div class="flex gap-2">
                    <button
                        @click="changePage(notifications.current_page - 1)"
                        :disabled="notifications.current_page === 1"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Sau
                    </button>
                    <button
                        v-for="page in notifications.last_page"
                        :key="page"
                        @click="changePage(page)"
                        :class="{
                            'px-4 py-2 rounded-md': true,
                            'bg-indigo-600 text-white':
                                page === notifications.current_page,
                            'bg-gray-200 text-gray-700 hover:bg-gray-300':
                                page !== notifications.current_page,
                        }"
                    >
                        {{ page }}
                    </button>
                    <button
                        @click="changePage(notifications.current_page + 1)"
                        :disabled="
                            notifications.current_page ===
                            notifications.last_page
                        "
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                    >
                        Trước
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import axios from "axios";
// Reactive data
const showUnreadOnly = ref(false);
const entriesPerPage = ref(20);

const { notifications } = defineProps({
    notifications: {
        type: Object,
        required: true,
    },
});

// Computed properties
const filteredNotifications = computed(() => {
    let filtered = notifications.value;

    if (showUnreadOnly.value) {
        filtered = filtered.filter((n) => !n.read);
    }

    return filtered.slice(0, entriesPerPage.value);
});

// Methods
const toggleUnreadOnly = () => {
    showUnreadOnly.value = !showUnreadOnly.value;
};

const markAsRead = (id) => {
    const notification = notifications.value.find((n) => n.id === id);
    if (notification) {
        notification.read = true;
    }
};

const markAllAsRead = () => {
    notifications.value.forEach((n) => (n.read = true));
};

const getIconClass = (type) => {
    switch (type) {
        case "order_created":
            return "fas fa-shopping-cart text-blue-500";
        case "order_approved":
            return "fas fa-check-circle text-green-500";
        case "order_rejected":
            return "fas fa-times-circle text-red-500";
        case "order_completed":
            return "fas fa-check-double text-purple-500";
        case "order_pending":
            return "fas fa-clock text-yellow-500";
        default:
            return "fas fa-bell text-gray-500";
    }
};

function changePage(page) {
    if (page < 1 || page > notifications.last_page) return;
    router.get(
        route("admin.notifications.show-all"),
        { page },
        { preserveState: true, preserveScroll: true }
    );
}

async function goToSaleOrder(notification) {
    if (notification.data && notification.data.order_id) {
        const res = await axios.get("/admin/sale-orders/find-page", {
            params: { order_id: notification.data.order_id },
        });
        const page = res.data.page || 1;
        router.visit(
            route("admin.sale-orders.index", {
                page,
                sale_order_id: notification.data.order_id,
            })
        );
    }
}
</script>
<style scoped></style>
