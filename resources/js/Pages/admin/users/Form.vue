<template>
  <form @submit.prevent="submit">
    <div class="mb-4">
      <label class="block mb-1">Tên</label>
      <input v-model="form.name" class="w-full border p-2" type="text" />
      <div class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</div>
    </div>

    <div class="mb-4">
      <label class="block mb-1">Email</label>
      <input v-model="form.email" class="w-full border p-2" type="email" />
      <div class="text-red-500 text-sm" v-if="form.errors.email">{{ form.errors.email }}</div>
    </div>

    <div class="mb-4">
      <label class="block mb-1">Mật khẩu</label>
      <input v-model="form.password" class="w-full border p-2" type="password" />
      <div class="text-red-500 text-sm" v-if="form.errors.password">{{ form.errors.password }}</div>
    </div>

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
      {{ method === 'put' ? 'Cập nhật' : 'Thêm' }}
    </button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps({
  user: { type: Object, default: () => ({}) },
  submitUrl: String,
  method: { type: String, default: 'post' }
});

const form = useForm({
  name: props.user.name || '',
  email: props.user.email || '',
  password: ''
});

function submit() {
  if (method === 'post') {
    form.post(submitUrl);
  } else {
    form.put(submitUrl);
  }
}
</script>