<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg text-purple-700 font-semibold mb-6">Sửa thông tin khách hàng</h2>
        <form @submit.prevent="handleUpdate" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="(label, field) in fieldLabels" :key="field">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
            <input v-model="form[field]" :type="inputTypes[field] || 'text'"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors[field] }" :required="field === 'name'" />
            <span v-if="form.errors[field]" class="text-red-500 text-sm mt-1">{{ form.errors[field] }}</span>
          </div>
          <div class="md:col-span-2 flex justify-end space-x-3">
            <Link :href="route('admin.customers.index')"
              class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
            Hủy
            </Link>
            <button type="submit"
              class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
              Cập nhật
            </button>
          </div>
        </form>

        <!-- Thêm hạng khách hàng -->
        <h3 class="text-lg text-purple-700 font-semibold mt-10 mb-6">Thêm hạng khách hàng</h3>
        <form @submit.prevent="handleAddRank" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="(label, field) in rankFieldLabels" :key="field">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
            <input v-model="rankForm[field]" :type="rankInputTypes[field] || 'text'"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': rankForm.errors[field] }" />
            <span v-if="rankForm.errors[field]" class="text-red-500 text-sm mt-1">{{ rankForm.errors[field] }}</span>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea v-model="rankForm.note"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': rankForm.errors.note }" rows="3"></textarea>
            <span v-if="rankForm.errors.note" class="text-red-500 text-sm mt-1">{{ rankForm.errors.note }}</span>
          </div>
          <div class="md:col-span-2 flex justify-end space-x-3">
            <button type="submit"
              class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
              Thêm hạng
            </button>
          </div>
        </form>

        <!-- Danh sách hạng -->
        <h3 class="text-lg text-purple-700 font-semibold mt-10 mb-6">Danh sách hạng của khách hàng</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tên hạng
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tổng chi tiêu tối thiểu
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  % giảm giá
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  % tín dụng
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ghi chú
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="rank in customer.ranks" :key="rank.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ rank.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.min_total_spent }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.discount_percent }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.credit_percent }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ rank.note || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { watch } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const { customer } = defineProps({ customer: Object });

// Form chính (cập nhật khách hàng)
const form = useForm({
  name: '',
  contact_person: '',
  phone: '',
  email: '',
  password_confirmation: '',
  address: '',
  current_debt: 0,
});

watch(
  () => customer,
  (data) => {
    if (!data) return;
    Object.assign(form, {
      name: data.name || '',
      contact_person: data.contact_person || '',
      phone: data.phone || '',
      email: data.email || '',
      address: data.address || '',
      current_debt: data.current_debt || 0,
    });
  },
  { immediate: true }
);

const fieldLabels = {
  name: 'Tên khách hàng',
  contact_person: 'Người liên hệ',
  phone: 'Số điện thoại',
  email: 'Email',
  password_confirmation: 'Mật khẩu',
  address: 'Địa chỉ',
  current_debt: 'Công nợ',
};

const inputTypes = {
  email: 'email',
  password_confirmation: 'password',
  current_debt: 'number',
};

// Form thêm rank
const rankForm = useForm({
  name: '',
  min_total_spent: 0,
  discount_percent: 0,
  credit_percent: 0,
  note: '',
});

const rankFieldLabels = {
  name: 'Tên hạng',
  min_total_spent: 'Tổng chi tiêu tối thiểu',
  discount_percent: '% giảm giá',
  credit_percent: '% tín dụng',
};

const rankInputTypes = {
  min_total_spent: 'number',
  discount_percent: 'number',
  credit_percent: 'number',
};

const handleUpdate = () => {
  form.put(route('admin.customers.update', customer.id), {
    onSuccess: () => {
    },
  });
};

const handleAddRank = () => {
  rankForm.post(route('admin.customers.ranks.store', customer.id), {
    onSuccess: () => {
      rankForm.reset();
    },
  });
};
</script>

<style scoped lang="css">
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