<template>
    <div>
        <div class="flex items-center space-x-2">
            <i :class="['fas', icon, 'text-indigo-500']"></i>
            <label class="text-sm font-medium text-gray-700">{{ label }}</label>
        </div>
        <div class="mt-1 text-gray-600 border-b border-indigo-200 pb-1">{{ value || 'Chưa cập nhật' }}</div>
    </div>
</template>

<script setup>
defineProps({
    icon: String,
    label: String,
    value: String
});
</script>
