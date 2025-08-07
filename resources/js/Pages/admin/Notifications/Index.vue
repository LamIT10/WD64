<template>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">🔔 Real-time Notifications Test</h1>

        <!-- Send Notification Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Send Test Notification</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">User ID</label>
                    <input 
                        v-model="form.user_id" 
                        type="number" 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter user ID"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Type</label>
                    <select 
                        v-model="form.type" 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="info">Info</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Title</label>
                    <input 
                        v-model="form.title" 
                        type="text" 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Notification title"
                    />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Message</label>
                    <textarea 
                        v-model="form.message" 
                        rows="3"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Notification message"
                    ></textarea>
                </div>
                <div class="md:col-span-2">
                    <button 
                        @click="sendNotification" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded transition-colors"
                        :disabled="sending"
                    >
                        {{ sending ? 'Sending...' : 'Send Notification' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Connection Status -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Connection Status</h2>
            <div class="flex items-center mb-4">
                <span class="mr-2">WebSocket Status:</span>
                <span 
                    :class="{'text-green-500': connected, 'text-red-500': !connected}"
                    class="font-medium"
                >
                    {{ connected ? '🟢 Connected' : '🔴 Disconnected' }}
                </span>
            </div>
            <div class="text-sm text-gray-600">
                Listening on channels: 
                <code class="bg-gray-100 px-2 py-1 rounded">notifications.user.{{ currentUserId }}</code>,
                <code class="bg-gray-100 px-2 py-1 rounded">notifications.all</code>
            </div>
        </div>

        <!-- Live Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">📨 Live Notifications</h2>
                <button 
                    @click="clearNotifications" 
                    class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm transition-colors"
                >
                    Clear All
                </button>
            </div>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <div v-if="liveNotifications.length === 0" class="text-center text-gray-500 py-8">
                    No live notifications received yet. Send a test notification above!
                </div>
                <div 
                    v-for="(notification, index) in liveNotifications" 
                    :key="index"
                    class="border-l-4 p-4 rounded-lg"
                    :class="getNotificationStyle(notification.type)"
                >
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <div class="flex items-center mb-2">
                                <span class="font-semibold">{{ notification.title }}</span>
                                <span class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded">{{ notification.type }}</span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ notification.message }}</p>
                            <div class="text-xs text-gray-500">
                                User ID: {{ notification.user_id }} | 
                                {{ formatTime(notification.received_at) }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ notification.is_read ? '✓ Read' : '○ Unread' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Log -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">📋 Event Log</h2>
                <button 
                    @click="clearLogs" 
                    class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm transition-colors"
                >
                    Clear Log
                </button>
            </div>
            
            <div class="bg-gray-50 border rounded p-4 h-64 overflow-y-auto font-mono text-sm">
                <div v-if="logs.length === 0" class="text-gray-500 text-center py-4">
                    Event logs will appear here...
                </div>
                <div v-for="(log, index) in logs" :key="index" class="mb-1">
                    <span class="text-gray-500">{{ formatTime(log.time) }}</span>
                    <span class="ml-2" :class="getLogColor(log.type)">{{ log.message }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

// Form data
const form = ref({
    user_id: 1,
    type: 'info',
    title: 'Test Notification',
    message: 'This is a test notification from the real-time system!'
});

// State
const sending = ref(false);
const connected = ref(false);
const currentUserId = ref(1);
const liveNotifications = ref([]);
const logs = ref([]);

// Channels
let userChannel = null;
let allChannel = null;

const addLog = (message, type = 'info') => {
    logs.value.unshift({
        message,
        type,
        time: new Date()
    });
};

const sendNotification = async () => {
    sending.value = true;
    try {
        const response = await axios.post('/admin/notifications/send-test', form.value);
        addLog(`✅ Notification sent successfully to User ${form.value.user_id}`, 'success');
        console.log('Notification sent:', response.data);
    } catch (error) {
        addLog(`❌ Error sending notification: ${error.response?.data?.message || error.message}`, 'error');
        console.error('Error:', error);
    } finally {
        sending.value = false;
    }
};

const clearNotifications = () => {
    liveNotifications.value = [];
    addLog('🗑️ Live notifications cleared', 'info');
};

const clearLogs = () => {
    logs.value = [];
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString();
};

const getNotificationStyle = (type) => {
    const styles = {
        info: 'border-blue-500 bg-blue-50',
        success: 'border-green-500 bg-green-50',
        warning: 'border-yellow-500 bg-yellow-50',
        error: 'border-red-500 bg-red-50'
    };
    return styles[type] || styles.info;
};

const getLogColor = (type) => {
    const colors = {
        success: 'text-green-600',
        error: 'text-red-600',
        warning: 'text-yellow-600',
        info: 'text-blue-600'
    };
    return colors[type] || 'text-gray-600';
};

onMounted(() => {
    if (window.Echo) {
        addLog('🔧 Initializing real-time notifications...', 'info');

        // Subscribe to user-specific notifications
        userChannel = window.Echo.channel(`notifications.user.${currentUserId.value}`);
        userChannel.listen('NotificationCreated', (e) => {
            addLog(`📨 Received notification for User ${e.notification.user_id}`, 'success');
            liveNotifications.value.unshift({
                ...e.notification,
                received_at: new Date()
            });
            console.log('User notification received:', e);
        });

        // Subscribe to all notifications
        allChannel = window.Echo.channel('notifications.all');
        allChannel.listen('NotificationCreated', (e) => {
            addLog(`📢 Global notification received for User ${e.notification.user_id}`, 'info');
            console.log('Global notification received:', e);
        });

        // Monitor connection status
        if (window.Echo.connector && window.Echo.connector.pusher) {
            const pusher = window.Echo.connector.pusher;
            
            pusher.connection.bind('connected', () => {
                connected.value = true;
                addLog('✅ Connected to notification server', 'success');
            });

            pusher.connection.bind('disconnected', () => {
                connected.value = false;
                addLog('❌ Disconnected from notification server', 'error');
            });

            pusher.connection.bind('error', (error) => {
                connected.value = false;
                addLog(`🔥 Connection error: ${error.error || error}`, 'error');
            });

            // Check current state
            connected.value = pusher.connection.state === 'connected';
        }

        addLog('📡 Subscribed to notification channels', 'success');
    } else {
        addLog('❌ Echo not initialized', 'error');
    }
});

onUnmounted(() => {
    if (userChannel) {
        userChannel.stopListening('NotificationCreated');
    }
    if (allChannel) {
        allChannel.stopListening('NotificationCreated');
    }
    addLog('🔌 Unsubscribed from notification channels', 'info');
});
</script>
