<template>
    <!-- Header -->
    <header
        class="bg-white shadow-sm border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30"
    >
        <div
            class="flex items-center px-3 py-1.5"
        >
            <!-- <div class="text-indigo-600">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="text-sm hidden sm:block">
                <span>Đang xử lý</span>
                <span class="font-medium ml-1">5 mục</span>
            </div> -->
        </div>
        <!-- Right side - User controls -->
        <div class="flex items-center space-x-3">
            <!-- Quick Actions -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <!-- QR Code Scanner -->
                <button
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 relative group"
                    title="Quét mã vạch">
                    <i class="fas fa-qrcode text-lg"></i>
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                        Quét mã vạch
                    </span>
                </button>

                <!-- Dark Mode Toggle -->
                <button
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    title="Chế độ tối">
                    <i class="fas fa-moon text-lg"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="relative" ref="notificationRef">
                <button @click="toggleNotifications"
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 group">
                    <i class="fas fa-bell text-lg"></i>
                    <span v-if="unreadCount > 0"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-[24px] w-[24px] flex items-center justify-center animate-pulse">
                        {{ unreadCount > 10 ? "10+" : unreadCount }}
                    </span>
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                        Thông báo
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                    <div v-if="showNotifications"
                        class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-40 origin-top-right">
                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-800">
                                Thông báo
                            </h3>
                            <button v-if="unreadCount > 0" @click="markAllAsRead"
                                class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                Đánh dấu tất cả đã đọc
                            </button>
                        </div>

                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500 text-sm">
                                Không có thông báo nào
                            </div>
                            <div v-else v-for="notification in notifications" :key="notification.id"
                                @click="markAsRead(notification)" :class="[
                                    'p-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors',
                                    !notification.isRead ? 'bg-blue-50' : '',
                                ]">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <i :class="getNotificationIcon(
                                            notification.type
                                        )
                                            "></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p :class="[
                                                'text-sm font-medium',
                                                !notification.isRead
                                                    ? 'text-gray-900'
                                                    : 'text-gray-700',
                                            ]">
                                                {{ notification.title }}
                                            </p>
                                            <div v-if="!notification.isRead"
                                                class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ notification.message }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ notification.time }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div v-if="notifications.length > 0"
                            class="p-3 border-t border-gray-100 w-full flex justify-center">
                            <Link :href="route('admin.notifications.show-all')"
                                class="w-full text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Xem tất cả thông báo
                            </Link>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- User Profile -->
            <div class="flex items-center space-x-2 pl-2 border-l border-gray-200">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-gray-800">
                        Nguyễn Văn Admin
                    </div>
                    <div class="text-xs text-gray-500">Quản trị viên</div>
                </div>
                <div class="relative group">
                    <img class="inline-block h-8 w-8 rounded-full object-cover cursor-pointer"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="User profile" />

                    <!-- Dropdown arrow indicator -->
                    <div @click.stop="showDropdown = !showDropdown"
                        class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 cursor-pointer select-none">
                        <div
                            class="bg-gray-200 group-hover:bg-indigo-600 w-3 h-3 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-chevron-down text-[6px] text-gray-500 group-hover:text-white"></i>
                        </div>
                    </div>
                    <!-- Dropdown menu -->
                    <transition name="fade">
                        <div v-if="showDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-40 py-2 text-sm">
                            <Link :href="route('profile')"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">Quản lí cá nhân
                            </Link>
                            <Link :href="route('logout')" method="post"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">Đăng xuất
                            </Link>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import emitter from "../../../eventBus.js";
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import axios from "axios";

document.addEventListener("DOMContentLoaded", function () {
    const notificationBadge = document.querySelector(".animate-pulse");
    if (notificationBadge) {
        notificationBadge.classList.add("animate-ping-once");
        setTimeout(() => {
            notificationBadge.classList.remove("animate-ping-once");
        }, 1000);
    }
});

// Reactive state
const showUserDropdown = ref(false);
const showNotifications = ref(false);
const notificationRef = ref(null);
const userDropdownRef = ref(null);
const notifications = ref([]);
const unreadCount = ref(0);

// Methods
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
};
const fetchNotifications = async () => {
    try {
        const response = await axios.get("/admin/notifications");
        notifications.value = response.data.notifications;
        unreadCount.value = response.data.unreadCount;
    } catch (error) {
        console.error("Error fetching notifications:", error);
    }
};

const markAsRead = async (notification) => {
    try {
        await axios.post(`/admin/notifications/${notification.id}/read`);
        const found = notifications.value.find((n) => n.id === notification.id);
        if (found) found.isRead = true;

        // Chuyển hướng theo loại thông báo kiểm kho
        if (notification.type === "inventory_audit_created" && notification.data && notification.data.audit_id) {
            router.visit(route("admin.inventory-audit.show", notification.data.audit_id));
            await fetchNotifications();
            return;
        }
        if (notification.type === "inventory_audit_rejected" && notification.data && notification.data.audit_id) {
            router.visit(route("admin.inventory-audit.show", notification.data.audit_id));
            await fetchNotifications();
            return;
        }
        if (notification.type === "inventory_audit_synced" && notification.data && notification.data.audit_id) {
            router.visit(route("admin.inventory-audit.show", notification.data.audit_id));
            await fetchNotifications();
            return;
        }

        // Chuyển hướng các loại thông báo khác
        if (notification.type === "info") {
            router.visit(route("admin.notifications.info"));
            await fetchNotifications();
            return;
        } else if (notification.data && notification.data.order_id) {
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
            await fetchNotifications();
            return;
        }
        if(notification.type === "hihi") {
            await fetchNotifications();
            return;
        }

        await fetchNotifications();
    } catch (error) {
        console.error("Error marking as read:", error);
    }
};

const markAllAsRead = async () => {
    try {
        await axios.post("/admin/notifications/read-all");
        notifications.value.forEach((n) => (n.isRead = true));
        await fetchNotifications();
    } catch (error) {
        console.error("Error marking all as read:", error);
    }
};
const getNotificationIcon = (type) => {
    switch (type) {
        case "order_created":
            return "fas fa-shopping-cart text-blue-500";
        case "order_approved":
            return "fas fa-check-circle text-green-500";
        case "order_rejected":
            return "fas fa-times-circle text-red-500";
        case "order_completed":
            return "fas fa-check-double text-indigo-500";
        case "order_pending":
            return "fas fa-clock text-yellow-500";
        // Thông báo kiểm kho
        case "inventory_audit_created":
            return "fas fa-clipboard-list text-indigo-500";
        case "inventory_audit_rejected":
            return "fas fa-times-circle text-red-500";
        case "inventory_audit_synced":
            return "fas fa-sync-alt text-green-500";
        // ✅ REAL-TIME: Add more notification types
        case "info":
            return "fas fa-info-circle text-blue-500";
        case "success":
            return "fas fa-check-circle text-green-500";
        case "warning":
            return "fas fa-exclamation-triangle text-yellow-500";
        case "error":
            return "fas fa-times-circle text-red-500";
        default:
            return "fas fa-bell text-gray-500";
    }
};

// ✅ REAL-TIME: Helper function to format notification time
const formatNotificationTime = (date) => {
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 1) return 'Vừa xong';
    if (minutes < 60) return `${minutes} phút trước`;
    if (hours < 24) return `${hours} giờ trước`;
    if (days < 7) return `${days} ngày trước`;
    return date.toLocaleDateString('vi-VN');
};

// Click outside handler
const handleClickOutside = (event) => {
    if (
        notificationRef.value &&
        !notificationRef.value.contains(event.target)
    ) {
        showNotifications.value = false;
    }
    if (
        userDropdownRef.value &&
        !userDropdownRef.value.contains(event.target)
    ) {
        showUserDropdown.value = false;
    }
};
let interval = null;
let notificationChannel = null;

// Lifecycle hooks
onMounted(() => {
    fetchNotifications();
    
    // ✅ REAL-TIME: Replace polling with WebSocket
    // interval = setInterval(fetchNotifications, 60000); // Polling mỗi 60s - DISABLED
    
    // ✅ REAL-TIME: Setup WebSocket notifications
    if (window.Echo) {
        console.log('🔔 Setting up real-time notifications in Header...');
        
        // Subscribe to user-specific notifications (assuming user ID = 1 for now)
        // TODO: Replace with actual authenticated user ID
        const currentUserId = 1; // Get from auth context
        
        notificationChannel = window.Echo.channel(`notifications.user.${currentUserId}`);
        notificationChannel.listen('NotificationCreated', (e) => {
            console.log('🔔 Real-time notification received in Header:', e);
            
            // Add new notification to the beginning of the list
            const newNotification = {
                id: e.notification.id,
                type: e.notification.type,
                title: e.notification.title,
                message: e.notification.message,
                data: e.notification.data,
                isRead: false,
                time: formatNotificationTime(new Date(e.notification.created_at))
            };
            
            notifications.value.unshift(newNotification);
            unreadCount.value++;
            
            // Show notification dropdown briefly
            showNotifications.value = true;
            setTimeout(() => {
                showNotifications.value = false;
            }, 3000);
            
            // Emit event for other components
            emitter.emit("notification-updated");
        });
        
        console.log(`🔔 Subscribed to notifications.user.${currentUserId}`);
    } else {
        console.warn('⚠️ Echo not available, falling back to polling');
        interval = setInterval(fetchNotifications, 60000);
    }
    
    emitter.on("notification-updated", () => {
        console.log("Received notification-updated event");
        fetchNotifications();
        showNotifications.value = true;
    });
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
    
    // ✅ REAL-TIME: Cleanup WebSocket subscription
    if (notificationChannel) {
        notificationChannel.stopListening('NotificationCreated');
        console.log('🔌 Unsubscribed from notification channel');
    }
    
    document.removeEventListener("click", handleClickOutside);
    emitter.off("notification-updated");
});

// --- REALTIME: Subscribe notification channel giống form Index.vue ---
const connected = ref(false);
const addEvent = (msg) => {
    console.log('[Header] Event:', msg);
};

onMounted(() => {
    if (window.Echo) {
        console.log('🔧 Header.vue initializing Echo...');
        // Subscribe to notification channel
        const channel = window.Echo.channel('notifications.all');
        console.log('📢 Header.vue: Subscribed to notifications.all channel');

        // Add both raw and Echo listeners for debugging
        if (channel.pusher) {
            channel.pusher.bind('NotificationCreated', function(data) {
                console.log('🔥 Header RAW Pusher Event:', data);
                addEvent(`📨 RAW: ${JSON.stringify(data)}`);
            });
        }
        channel.listen('.NotificationCreated', (e) => {
            console.log('🎯 Header Echo Event:', e);
            addEvent(`📨 ECHO: ${JSON.stringify(e)}`);
            // Khi nhận realtime, delay 300ms rồi fetchNotifications để tránh race condition
            setTimeout(async () => {
                console.log('[Header] Fetching notifications after realtime...');
                await fetchNotifications();
                console.log('[Header] Notifications after realtime:', notifications.value);
            }, 300);
            // Có thể show dropdown nếu muốn
            showNotifications.value = true;
            setTimeout(() => {
                showNotifications.value = false;
            }, 3000);
            // Emit event cho các component khác nếu cần
            emitter.emit("notification-updated");
        });

        // Monitor connection status
        if (window.Echo.connector && window.Echo.connector.pusher) {
            const pusher = window.Echo.connector.pusher;
            pusher.connection.bind('connected', () => {
                connected.value = true;
                addEvent('✅ Connected to Reverb server');
            });
            pusher.connection.bind('disconnected', () => {
                connected.value = false;
                addEvent('❌ Disconnected from Reverb server');
            });
            pusher.connection.bind('error', (error) => {
                connected.value = false;
                addEvent(`🔥 Connection error: ${error.error || error}`);
            });
            connected.value = pusher.connection.state === 'connected';
        }
    } else {
        console.error('Echo is not initialized');
        addEvent('❌ Error: Echo is not initialized');
    }
});

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leaveChannel('notifications.all');
    }
});

const showDropdown = ref(false);
</script>
