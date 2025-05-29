<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Chi tiết người dùng</h1>
    <div class="mb-4">
      <strong>ID:</strong> {{ user.id }}
    </div>
    <div class="mb-4">
      <strong>Tên:</strong> {{ user.name }}
    </div>
    <div class="mb-4">
      <strong>Email:</strong> {{ user.email }}
    </div>
    <Link href="/users" class="text-blue-500">← Quay lại danh sách</Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({ user: Object });
</script>