<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <div v-if="!rank" class="mx-auto bg-white rounded-xl shadow-lg p-6">
        <p class="text-red-500">Không tìm thấy hạng.</p>
      </div>
      <div v-else class="mx-auto bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
          <h5 class="text-lg text-indigo-700 font-semibold">Cập nhật hạng</h5>
          <Link :href="route('admin.ranks.index')"
            class="px-4 py-2 bg-indigo-50 rounded hover:text-indigo-500 text-indigo-600 transition-colors">
          <i class="fas fa-arrow-left"></i> Quay lại
          </Link>
        </div>
        <div class="w-full p-6">
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên hạng *</label>
                <input v-model="form.name" type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                  placeholder="Nhập tên hạng..." />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tổng chi tiêu tối thiểu</label>
                <input v-model="form.min_total_spent" type="number"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                  placeholder="Nhập tổng chi tiêu tối thiểu..." />
                <p v-if="form.errors.min_total_spent" class="text-red-500 text-sm mt-1">{{ form.errors.min_total_spent
                  }}</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">% giảm giá</label>
                <input v-model="form.discount_percent" type="number"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                  placeholder="Nhập % giảm giá..." />
                <p v-if="form.errors.discount_percent" class="text-red-500 text-sm mt-1">{{ form.errors.discount_percent
                  }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">% tín dụng</label>
                <input v-model="form.credit_percent" type="number"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                  placeholder="Nhập % tín dụng..." />
                <p v-if="form.errors.credit_percent" class="text-red-500 text-sm mt-1">{{ form.errors.credit_percent }}
                </p>
              </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                <textarea v-model="form.note"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                  placeholder="Nhập ghi chú..." rows="3"></textarea>
                <p v-if="form.errors.note" class="text-red-500 text-sm mt-1">{{ form.errors.note }}</p>
              </div>
            </div>
          </form>
        </div>
        <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
          <Link :href="route('admin.ranks.index')"
            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
          Hủy bỏ
          </Link>
          <button type="submit" @click="submit"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md"
            :disabled="form.processing">
            <i class="fas fa-save mr-2"></i> Cập nhật hạng
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
    console.error('Rank ID không tồn tại');
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

<style scoped>
.animate-fade-in {
  animation: fadeIn Space Grotesk0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>