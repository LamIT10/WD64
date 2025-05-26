<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <h2 class="text-xl font-bold mb-4">Sửa thông tin khách hàng</h2>
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block text-sm font-medium">Tên khách hàng</label>
            <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Người liên hệ</label>
            <input v-model="form.contact_person" type="text" class="w-full border rounded p-2" />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Số điện thoại</label>
            <input v-model="form.phone" type="text" class="w-full border rounded p-2" />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded p-2" />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Mật khẩu</label>
            <input v-model="form.password" type="password" class="w-full border rounded p-2" required />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Địa chỉ</label>
            <textarea v-model="form.address" class="w-full border rounded p-2"></textarea>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Công nợ</label>
            <input v-model="form.current_debt" type="number" step="0.01" class="w-full border rounded p-2" />
          </div>
          <button type="submit" class="bg-[#BE202F] text-white px-4 py-2 rounded">Cập nhật</button>
        </form>

        <h3 class="text-lg font-bold mt-8 mb-4">Customer Ranks</h3>
        <form @submit.prevent="submitRank" class="mb-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium">Rank Name</label>
              <input v-model="rankForm.name" type="text" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block text-sm font-medium">Min Total Spent</label>
              <input v-model="rankForm.min_total_spent" type="number" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block text-sm font-medium">Discount Percent</label>
              <input v-model="rankForm.discount_percent" type="number" class="w-full border rounded p-2" required />
            </div>
            <div>
              <label class="block text-sm font-medium">Credit Percent</label>
              <input v-model="rankForm.credit_percent" type="number" class="w-full border rounded p-2" required />
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-medium">Note</label>
              <textarea v-model="rankForm.note" class="w-full border rounded p-2"></textarea>
            </div>
          </div>
          <button type="submit" class="bg-[#2A66FF] text-white px-4 py-2 rounded mt-4">Add Rank</button>
        </form>

        <table class="w-full text-[12px] text-[#000]">
          <thead>
            <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
              <th class="p-[10px] text-left">Name</th>
              <th class="p-[10px] text-left">Min Total Spent</th>
              <th class="p-[10px] text-left">Discount Percent</th>
              <th class="p-[10px] text-left">Credit Percent</th>
              <th class="p-[10px] text-left">Note</th>
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


defineProps({
  customer: Object,
});

const form = useForm({
  name: customer.name,
  contact_person: customer.contact_person,
  phone: customer.phone,
  email: customer.email,
  password: customer.password,
  address: customer.address,
  current_debt: customer.current_debt,
});

const rankForm = useForm({
  name: '',
  min_total_spent: 0,
  discount_percent: 0,
  credit_percent: 0,
  note: '',
});

const submit = () => {
  form.put(route('customers.update', customer.id), {
    onSuccess: () => alert('Cập nhật thông tin khách hàng thành công.'),
  });
};

const submitRank = () => {
  rankForm.post(route('customers.ranks.store', customer.id), {
    onSuccess: () => {
      rankForm.reset();
      alert('Rank added successfully.');
    },
  });
};
</script>

<style lang="scss" scoped></style>