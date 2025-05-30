<template>
  <AppLayout>
    <div class="w-full p-6 bg-gray-100">
      <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Thêm khách hàng mới</h2>
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng</label>
            <input v-model="form.name" type="text" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Người liên hệ</label>
            <input v-model="form.contact_person" type="text" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.contact_person" class="text-red-500 text-sm mt-1">{{ form.errors.contact_person }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input v-model="form.phone" type="text" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
            <input v-model="form.password" type="password" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
            <input v-model="form.address" type="text" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
            <input v-model="form.current_debt" type="number" step="0.01" class="w-full border rounded-lg p-2 focus:ring focus:border-blue-300" />
            <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
          </div>
        </form>
        <div class="mt-8 text-right">
          <button @click="submit" type="button" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            Lưu
          </button>
        </div>
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
  });
};
</script>
