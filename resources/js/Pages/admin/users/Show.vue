<template>
    <AppLayout>
        <div class="px-[20px] py-[30px] max-w-[600px] mx-auto">
            <h1 class="text-[24px] font-bold mb-[20px]">Chi tiết người dùng</h1>
            <div class="space-y-4 text-[15px]">
                <div><strong>ID:</strong> {{ user.id }}</div>
                <div><strong>Họ tên:</strong> {{ user.name }}</div>
                <div><strong>Email:</strong> {{ user.email }}</div>
                <div><strong>Điện thoại:</strong> {{ user.phone || 'Chưa cập nhật' }}</div>
                <div><strong>Trạng thái:</strong> Hoạt động</div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
const props = defineProps({ user: Object });
</script>
