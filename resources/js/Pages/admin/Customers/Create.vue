<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg text-purple-700 font-semibold mb-6">Thêm khách hàng mới</h2>
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng</label>
            <input v-model="form.name" type="text"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.name }" />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input v-model="form.phone" type="text"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.phone }" />
            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.email }" />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
            <input v-model="form.password" type="password"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.password }" />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
            <input v-model="form.address" type="text"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.address }" />
            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
            <input v-model="form.current_debt" type="number" step="0.01"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.current_debt }" />
            <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
          </div>
        </form>
        <div class="mt-6 flex justify-end space-x-3">
          <Link :href="route('admin.customers.index')"
            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
          Hủy
          </Link>
          <button @click="submit" type="button"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
            Lưu
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
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
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>