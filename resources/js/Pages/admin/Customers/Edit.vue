<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div v-if="!customer" class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-red-500">Không tìm thấy khách hàng.</p>
      </div>
      <div v-else class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg text-purple-700 font-semibold mb-6">Sửa khách hàng</h2>
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
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hạng khách hàng</label>
            <select v-model="selectedRank" @change="updateRank"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.rank_id }">
              <option value="" disabled>Chọn hạng</option>
              <option v-for="rank in rankTemplates" :key="rank.id" :value="rank.id">{{ rank.name }}</option>
            </select>
            <p v-if="form.errors.rank_id" class="text-red-500 text-sm mt-1">{{ form.errors.rank_id }}</p>
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
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  customer: Object,
  rankTemplates: Array,
});

// Gán ngắn gọn để dễ dùng trong template và logic
const customer = props.customer;
const rankTemplates = props.rankTemplates;

console.log('Customer:', customer);
console.log('Rank Templates:', rankTemplates);

const selectedRank = ref(customer?.rank_id || '');

const form = useForm({
  name: customer?.name || '',
  contact_person: customer?.contact_person || '',
  phone: customer?.phone || '',
  email: customer?.email || '',
  password: '',
  address: customer?.address || '',
  current_debt: customer?.current_debt || 0,
  rank_id: customer?.rank_id || null,
});

const updateRank = () => {
  form.rank_id = selectedRank.value || null;
};

const submit = () => {
  if (!customer?.id) {
    console.error('Customer ID không tồn tại');
    return;
  }
  form.patch(route('admin.customers.update', customer.id), {
    onSuccess: () => {
      form.reset('password');
    },
  });
};
</script>

<style scoped>
::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c4c4c4;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0a0a0;
}
</style>