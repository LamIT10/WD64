<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
        <h5 class="text-lg text-indigo-700 font-semibold">Thêm khách hàng mới</h5>
        <Link :href="route('admin.customers.index')"
          class="px-4 py-2 bg-indigo-50 rounded hover:text-indigo-500 text-indigo-600 transition-colors">
          <i class="fas fa-arrow-left"></i> Quay lại
        </Link>
      </div>
      <div class="mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex flex-col md:flex-row">
          <div class="w-full p-6">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Row 1: Name + Email -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng *</label>
                  <input v-model="form.name" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập tên khách hàng..." />
                  <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>
                <!-- Email -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input v-model="form.email" type="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập địa chỉ email..." />
                  <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>
              </div>

              <!-- Row 2: Phone + Rank -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                  <input v-model="form.phone" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập số điện thoại..." />
                  <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                </div>
                <!-- Rank -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Hạng khách hàng</label>
                  <select v-model="form.rank_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option value="" disabled>Chọn hạng...</option>
                    <option v-for="rank in rankTemplates" :key="rank.id" :value="rank.id">{{ rank.name }}</option>
                  </select>
                  <p v-if="form.errors.rank_id" class="text-red-500 text-sm mt-1">{{ form.errors.rank_id }}</p>
                </div>
              </div>

              <!-- Row 3: Address + Current Debt -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Address -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                  <input v-model="form.address" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập địa chỉ..." />
                  <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                </div>
                <!-- Current Debt -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
                  <input v-model="form.current_debt" type="number" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập công nợ..." />
                  <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
                </div>
              </div>

              <!-- Row 4: Avatar -->
              <div class="grid grid-cols-1 gap-4">
                <!-- Avatar -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                  <input type="file" accept="image/*" @change="form.avatar = $event.target.files[0]"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
                  <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">{{ form.errors.avatar }}</p>
                </div>
              </div>

              <!-- Row 5: Password + Confirm Password -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                  <input v-model="form.password" type="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập mật khẩu..." />
                  <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')"
                    class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
                <!-- Confirm Password -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                  <input v-model="form.password_confirmation" type="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập lại mật khẩu..." />
                  <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')"
                    class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
          <Link :href="route('admin.customers.index')"
            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
            Hủy bỏ
          </Link>
          <button type="submit" @click="submit"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md"
            :disabled="form.processing">
            <i class="fas fa-save mr-2"></i> Lưu khách hàng
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

defineProps({
  rankTemplates: Array,
});

const form = useForm({
  name: '',
  phone: '',
  email: '',
  password: '',
  password_confirmation: '',
  address: '',
  current_debt: 0,
  rank_id: '',
  avatar: null,
});

const submit = () => {
  form.post(route('admin.customers.store'), {
    onSuccess: () => {
      form.reset();
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>