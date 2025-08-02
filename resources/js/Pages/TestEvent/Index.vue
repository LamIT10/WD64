<template>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Test Realtime Events</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold mb-3">Send Test Event</h2>
            <div class="flex">
                <input 
                    v-model="message" 
                    type="text" 
                    class="flex-grow border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter a message to broadcast" 
                />
                <button 
                    @click="sendEvent" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r transition-colors"
                    :disabled="sending"
                >
                    {{ sending ? 'Sending...' : 'Send Event' }}
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-3">Event Log</h2>
            <div class="mb-3 flex items-center">
                <span class="mr-2">Status:</span>
                <span 
                    :class="{'text-green-500': connected, 'text-red-500': !connected}"
                    class="font-medium"
                >
                    {{ connected ? 'Connected' : 'Disconnected' }}
                </span>
                <button 
                    @click="clearEvents" 
                    class="ml-auto bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm transition-colors"
                >
                    Clear Log
                </button>
            </div>
            
            <div class="border border-gray-200 rounded p-4 h-64 overflow-y-auto bg-gray-50">
                <div v-if="events.length === 0" class="text-gray-500 text-center py-4">
                    No events received yet
                </div>
                <div v-for="(event, index) in events" :key="index" class="mb-2 p-2 bg-white rounded border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500">{{ formatTime(event.time) }}</div>
                    <div class="font-medium">{{ event.message }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const message = ref('Hello from test event!');
const sending = ref(false);
const connected = ref(false);
const events = ref([]);

const sendEvent = async () => {
    sending.value = true;
    try {
        const response = await axios.post('/admin/test-event/broadcast', {
            message: message.value
        });
        console.log('✅ Event sent successfully:', response.data);
        addEvent(`📤 Sent: ${message.value}`);
        message.value = '';
    } catch (error) {
        console.error('❌ Error sending event:', error);
        addEvent(`❌ Error: ${error.response?.data?.message || error.message}`);
    } finally {
        sending.value = false;
    }
};

const addEvent = (msg) => {
    events.value.unshift({
        message: msg,
        time: new Date()
    });
};

const clearEvents = () => {
    events.value = [];
};

const formatTime = (date) => {
    return date.toLocaleTimeString();
};

onMounted(() => {
    if (window.Echo) {
        console.log('🔧 Vue component initializing Echo...');
        
        // Subscribe to channel immediately
        const channel = window.Echo.channel('testing');
        console.log('📢 Vue: Subscribed to testing channel');
        
        // Add both raw and Echo listeners for debugging
        channel.pusher.bind('TestEvent', function(data) {
            console.log('🔥 Vue RAW Pusher Event:', data);
            addEvent(`📨 RAW: ${data.message}`);
        });
        
        channel.listen('TestEvent', (e) => {
            console.log('🎯 Vue Echo Event:', e);
            addEvent(`📨 ECHO: ${e.message}`);
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

            // Check current connection state
            connected.value = pusher.connection.state === 'connected';
        }
    } else {
        console.error('Echo is not initialized');
        addEvent('❌ Error: Echo is not initialized');
    }
});

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leaveChannel('testing');
    }
});
</script>
