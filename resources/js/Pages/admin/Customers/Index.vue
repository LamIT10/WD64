<template>
  <AppLayout>
    <div class="w-full h-auto my-[10px] p-[20px] bg-[#f5f7fa]">
      <div class="w-[90%] mx-auto bg-[#ffffff] rounded-[10px] shadow p-[20px] mb-[30px]">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Customer Management</h2>
          <Link :href="route('admin.customers.create')" class="bg-[#BE202F] text-white px-4 py-2 rounded">
            Add Customer
          </Link>
        </div>
        <table class="w-full text-[12px] text-[#000]">
          <thead>
            <tr class="bg-[#f9f9f9] text-[#A49E9E] uppercase">
              <th class="p-[10px] text-left">#</th>
              <th class="p-[10px] text-left">Name</th>
              <th class="p-[10px] text-left">Contact Person</th>
              <th class="p-[10px] text-left">Phone</th>
              <th class="p-[10px] text-left">Email</th>
              <th class="p-[10px] text-left">Debt</th>
              <th class="p-[10px] text-left">Rank</th>
              <th class="p-[10px] text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(customer, index) in customers" :key="customer.id">
              <td class="p-[10px]">{{ index + 1 }}</td>
              <td class="p-[10px]">{{ customer.name }}</td>
              <td class="p-[10px]">{{ customer.contact_person || '-' }}</td>
              <td class="p-[10px]">{{ customer.phone || '-' }}</td>
              <td class="p-[10px]">{{ customer.email || '-' }}</td>
              <td class="p-[10px]">{{ customer.current_debt }}</td>
              <td class="p-[10px]">
                <span v-if="customer.ranks.length">{{ customer.ranks[0].name }}</span>
                <span v-else>-</span>
              </td>
              <td class="p-[10px]">
                <Link :href="route('customers.edit', customer.id)" class="text-[#2A66FF] font-bold mr-2">Edit</Link>
                <button @click="deleteCustomer(customer.id)" class="text-[#BE202F] font-bold">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { route } from 'ziggy-js';




defineProps({
  customers: Array,
});

const deleteCustomer = (id) => {
  if (confirm('Are you sure you want to delete this customer?')) {
    useForm({}).delete(route('customers.destroy', id), {
      onSuccess: () => alert('Customer deleted successfully.'),
    });
  }
};
</script>

<style lang="scss" scoped></style>