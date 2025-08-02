import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SECURE || 'false') === 'true',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
    cluster: '',
    auth: {
        headers: {}
    }
});

console.log('VITE_REVERB_APP_KEY:', import.meta.env.VITE_REVERB_APP_KEY);
console.log('VITE_REVERB_HOST:', import.meta.env.VITE_REVERB_HOST);
console.log('VITE_REVERB_PORT:', import.meta.env.VITE_REVERB_PORT);
console.log('VITE_REVERB_SECURE:', import.meta.env.VITE_REVERB_SECURE);

// Debug kết nối Pusher
window.Echo.connector.pusher.connection.bind('state_change', function(states) {
    console.log('🔄 Pusher connection state changed:', states.previous, '→', states.current);
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Pusher connected to Reverb');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ Pusher disconnected from Reverb');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.error('🔥 Pusher connection error:', err);
});

// Test subscribe to a channel
setTimeout(() => {
    const testChannel = window.Echo.channel('testing');
    console.log('📢 Bootstrap subscribed to testing channel');
    
    // Subscribe to global notifications for debugging
    const notificationChannel = window.Echo.channel('notifications.all');
    notificationChannel.listen('NotificationCreated', (e) => {
        console.log('🔔 Global notification received in bootstrap:', e);
    });
    console.log('🔔 Bootstrap subscribed to notifications.all channel');
}, 2000);
