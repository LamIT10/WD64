<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Thêm người dùng</h1>
    <UserForm :submit-url="'/users/store'" />
  </div>
</template>

<script setup>
import UserForm from './Form.vue';
</script>