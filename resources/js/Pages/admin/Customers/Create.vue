<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <h2 class="text-xl font-bold mb-4">Thêm khách hàng mới</h2>
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block text-sm font-medium">Tên khách hàng</label>
            <input v-model="form.name" type="text" class="w-full border rounded p-2" />
            <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Người liên hệ</label>
            <input v-model="form.contact_person" type="text" class="w-full border rounded p-2" />
            <span v-if="form.errors.contact_person" class="text-red-500 text-sm">{{ form.errors.contact_person }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Số điện thoại</label>
            <input v-model="form.phone" type="text" class="w-full border rounded p-2" />
            <span v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded p-2" />
            <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Mật khẩu</label>
            <input v-model="form.password" type="password" class="w-full border rounded p-2" />
            <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Địa chỉ</label>
            <input v-model="form.address" type="text" class="w-full border rounded p-2" />
            <span v-if="form.errors.address" class="text-red-500 text-sm">{{ form.errors.address }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Công nợ</label>
            <input v-model="form.current_debt" type="number" step="0.01" class="w-full border rounded p-2" />
            <span v-if="form.errors.current_debt" class="text-red-500 text-sm">{{ form.errors.current_debt }}</span>
          </div>
          <button type="submit" class="bg-[#BE202F] text-white px-4 py-2 rounded">Lưu</button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';


const form = useForm({
  name: '',
  contact_person: '',
  phone: '',
  email: '',
  password: '',
  address: '',
  current_debt: 0,
});

const submit = () => {
  form.post(route('admin.customers.store'), {
    onSuccess: () => alert('Thêm khách hàng mới thành công.'),
  });
};
</script>

<style lang="scss" scoped></style>