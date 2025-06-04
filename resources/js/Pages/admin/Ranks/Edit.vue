<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg text-purple-700 font-semibold mb-6">Sửa hạng</h2>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên hạng</label>
            <input v-model="form.name" type="text"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.name }" />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tổng chi tiêu tối thiểu</label>
            <input v-model="form.min_total_spent" type="number"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.min_total_spent }" />
            <p v-if="form.errors.min_total_spent" class="text-red-500 text-sm mt-1">{{ form.errors.min_total_spent }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">% giảm giá</label>
            <input v-model="form.discount_percent" type="number"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.discount_percent }" />
            <p v-if="form.errors.discount_percent" class="text-red-500 text-sm mt-1">{{ form.errors.discount_percent }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">% tín dụng</label>
            <input v-model="form.credit_percent" type="number"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.credit_percent }" />
            <p v-if="form.errors.credit_percent" class="text-red-500 text-sm mt-1">{{ form.errors.credit_percent }}</p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea v-model="form.note"
              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
              :class="{ 'border-red-500': form.errors.note }" rows="3"></textarea>
            <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">{{ form.errors.note }}</p>
          </div>
        </form>

        <div class="mt-6 flex justify-end space-x-3">
          <Link :href="route('admin.ranks.index')"
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

const props = defineProps({
  rank: Object,
});

const form = useForm({
  name: props.rank?.name || '',
  min_total_spent: props.rank?.min_total_spent || 0,
  discount_percent: props.rank?.discount_percent || 0,
  credit_percent: props.rank?.credit_percent || 0,
  note: props.rank?.note || '',
});

const submit = () => {
  if (!props.rank?.id) {
    return;
  }

  form.patch(route('admin.ranks.update', props.rank.id), {
    onSuccess: () => {
      window.location.href = route('admin.ranks.index');
    },
    onError: (errors) => {
      console.error('Lỗi cập nhật:', errors);
    },
  });
};
</script>
