<template>
  <AppLayout>
    <div class="px-[20px] py-[30px] max-w-[600px] mx-auto">
      <h1 class="text-[24px] font-bold mb-[20px]">Thêm người dùng</h1>
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
          <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
            {{ form.errors.email }}
          </p>
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Điện thoại</label>
          <input v-model="form.phone" type="text" class="w-full border px-4 py-2 rounded-[6px]" />
          <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
            {{ form.errors.phone }}
          </p>
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Mật khẩu</label>
          <input v-model="form.password" type="password" class="w-full border px-4 py-2 rounded-[6px]" />
          <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">
            {{ form.errors.password }}
          </p>
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">Xác nhận mật khẩu</label>
          <input v-model="form.password_confirmation" type="password" class="w-full border px-4 py-2 rounded-[6px]" />
          <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">
            {{ form.errors.password }}
          </p>
        </div>
        <button type="submit" class="bg-[#2A66FF] text-white px-[20px] py-[10px] rounded-[8px] font-medium">
          Thêm người dùng
        </button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '../Layouts/AppLayout.vue'

const form = useForm({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post('/admin/users', {
    onSuccess: () => {
      router.visit('/admin/users')
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}
</script>
