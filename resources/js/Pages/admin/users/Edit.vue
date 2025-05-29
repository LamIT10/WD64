<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Sửa người dùng</h1>
    <UserForm :user="user" :submit-url="`/users/${user.id}`" method="put" />
  </div>
</template>

<script setup>
import UserForm from './Form.vue';
const props = defineProps({ user: Object });
</script>
