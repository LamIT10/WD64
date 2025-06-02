<template>
  <AppLayout>
    <div class="w-full h-auto my-4 p-5 bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-white rounded-2xl shadow p-6 mb-10">
        <!-- Cập nhật thông tin khách hàng -->
        <h2 class="text-2xl font-bold mb-6">Sửa thông tin khách hàng</h2>
        <form @submit.prevent="handleUpdate" class="space-y-4">
          <div v-for="(label, field) in fieldLabels" :key="field">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
            <input
              v-model="form[field]"
              :type="inputTypes[field] || 'text'"
              class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :required="field === 'name'"
            />
            <span v-if="form.errors[field]" class="text-red-500 text-sm">{{ form.errors[field] }}</span>
          </div>
          <button type="submit" class="bg-[#BE202F] text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
            Cập nhật
          </button>
        </form>
        <h3 class="text-xl font-bold mt-10 mb-4">Thêm hạng khách hàng</h3>
        <form @submit.prevent="handleAddRank" class="grid grid-cols-2 gap-4">
          <div v-for="(label, field) in rankFieldLabels" :key="field" class="col-span-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
            <input
              v-model="rankForm[field]"
              :type="rankInputTypes[field] || 'text'"
              class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              :required="field !== 'note'"
            />
            <span v-if="rankForm.errors[field]" class="text-red-500 text-sm">{{ rankForm.errors[field] }}</span>
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea
              v-model="rankForm.note"
              class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows="3"
            ></textarea>
            <span v-if="rankForm.errors.note" class="text-red-500 text-sm">{{ rankForm.errors.note }}</span>
          </div>
          <div class="col-span-2 mt-2">
            <button type="submit" class="bg-[#2A66FF] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
              Thêm hạng
            </button>
          </div>
        </form>
        <h3 class="text-xl font-bold mt-10 mb-4">Danh sách hạng của khách hàng</h3>
        <table class="w-full text-sm text-gray-700 border-t border-gray-200">
          <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
              <th class="p-3 text-left">Tên hạng</th>
              <th class="p-3 text-left">Tổng chi tiêu tối thiểu</th>
              <th class="p-3 text-left">% giảm giá</th>
              <th class="p-3 text-left">% tín dụng</th>
              <th class="p-3 text-left">Ghi chú</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="rank in customer.ranks" :key="rank.id" class="border-b border-gray-200">
              <td class="p-3">{{ rank.name }}</td>
              <td class="p-3">{{ rank.min_total_spent }}</td>
              <td class="p-3">{{ rank.discount_percent }}%</td>
              <td class="p-3">{{ rank.credit_percent }}%</td>
              <td class="p-3">{{ rank.note || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
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
    onSuccess: () => alert('Cập nhật thông tin khách hàng thành công.'),
  });
};

const handleAddRank = () => {
  rankForm.post(route('admin.customers.ranks.store', customer.id), {
    onSuccess: () => {
      rankForm.reset();
      alert('Thêm hạng khách hàng thành công.');
    },
  });
};
</script>

<style scoped lang="scss"></style>
