<template>
  <AppLayout>
    <div class="px-[20px] py-[30px] max-w-[600px] mx-auto">
      <h1 class="text-[24px] font-bold mb-[20px]">Cập nhật người dùng</h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Họ tên</label>
          <input v-model="form.name" type="text" class="w-full border px-4 py-2 rounded-[6px]" />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
            {{ form.errors.name }}
          </p>
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Email</label>
          <input v-model="form.email" type="email" class="w-full border px-4 py-2 rounded-[6px]" />
        </div>
        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
          {{ form.errors.email }}
        </p>
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Điện thoại</label>
          <input v-model="form.phone" type="text" class="w-full border px-4 py-2 rounded-[6px]" />
        </div>
        <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
          {{ form.errors.phone }}
        </p>
        <button type="submit" class="bg-[#2A66FF] text-white px-[20px] py-[10px] rounded-[8px] font-medium">
          Cập nhật
        </button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
const props = defineProps({ user: Object });

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  phone: props.user.phone,
});

function submit() {
  form.put(route('admin.users.update', props.user.id), {
    onError: (errors) => {
      console.error(errors);
    }
  });
}
</script>
