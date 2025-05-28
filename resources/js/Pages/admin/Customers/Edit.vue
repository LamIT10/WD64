<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <h2 class="text-xl font-bold mb-4">Sửa thông tin khách hàng</h2>
        <form @submit.prevent="handleUpdate">
          <div class="mb-4" v-for="(label, field) in fieldLabels" :key="field">
            <label class="block text-sm font-medium">{{ label }}</label>
            <input
              v-model="form[field]"
              :type="inputTypes[field] || 'text'"
              class="w-full border rounded p-2"
              :required="field === 'name'"
            />
          </div>
          <button type="submit" class="bg-[#BE202F] text-white px-4 py-2 rounded">Cập nhật</button>
        </form>

        <h3 class="text-lg font-bold mt-8 mb-4">Customer Ranks</h3>
        <form @submit.prevent="handleAddRank" class="mb-4">
          <div class="grid grid-cols-2 gap-4">
            <div v-for="(label, field) in rankFieldLabels" :key="field" class="col-span-1">
              <label class="block text-sm font-medium">{{ label }}</label>
              <input
                v-model="rankForm[field]"
                :type="rankInputTypes[field] || 'text'"
                class="w-full border rounded p-2"
                :required="field !== 'note'"
              />
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium">Ghi chú</label>
              <textarea v-model="rankForm.note" class="w-full border rounded p-2"></textarea>
            </div>
          </div>
          <button type="submit" class="bg-[#2A66FF] text-white px-4 py-2 rounded mt-4">Thêm hạng</button>
        </form>

        <table class="w-full text-[12px] text-[#000]">
          <thead>
            <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
              <th class="p-[10px] text-left">Tên hạng</th>
              <th class="p-[10px] text-left">Tổng chi tiêu tối thiểu</th>
              <th class="p-[10px] text-left">% giảm giá</th>
              <th class="p-[10px] text-left">% tín dụng</th>
              <th class="p-[10px] text-left">Ghi chú</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="rank in customer.ranks" :key="rank.id">
              <td class="p-[10px]">{{ rank.name }}</td>
              <td class="p-[10px]">{{ rank.min_total_spent }}</td>
              <td class="p-[10px]">{{ rank.discount_percent }}%</td>
              <td class="p-[10px]">{{ rank.credit_percent }}%</td>
              <td class="p-[10px]">{{ rank.note || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { watch } from 'vue';

const { customer } = defineProps({
  customer: Object,
});

// Update form
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

// Rank form
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
