<template>
  <AppLayout>
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 p-6 min-h-screen">
      <div v-if="!customer" class="mx-auto bg-white rounded-xl shadow-lg p-6">
        <p class="text-red-500">Không tìm thấy khách hàng.</p>
      </div>
      <div v-else class="mx-auto bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
        <div class="p-4 shadow-sm rounded-lg bg-white mb-4 flex justify-between items-center border border-gray-200">
          <h5 class="text-lg text-indigo-700 font-semibold">Cập nhật khách hàng</h5>
          <Link :href="route('admin.customers.index')"
            class="px-4 py-2 bg-indigo-50 rounded hover:text-indigo-500 text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left"></i> Quay lại
          </Link>
        </div>

        <div class="flex flex-col md:flex-row">
          <div class="w-full p-6">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Name + Email -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng *</label>
                  <input v-model="form.name" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập tên khách hàng..." />
                  <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input v-model="form.email" type="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập địa chỉ email..." />
                  <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>
              </div>

              <!-- Phone + Rank -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                  <input v-model="form.phone" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập số điện thoại..." />
                  <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                </div>
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

              <!-- Address + Debt -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                  <input v-model="form.address" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập địa chỉ..." />
                  <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
                  <input v-model="form.current_debt" type="number" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập công nợ..." />
                  <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
                </div>
              </div>

              <!-- Avatar -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ảnh đại diện</label>
                <div v-if="customer.avatar" class="mb-2">
                  <img :src="`/storage/${customer.avatar}`" alt="Avatar"
                    class="h-20 w-20 rounded-full object-cover border border-indigo-200" />
                </div>
                <input type="file" accept="image/*" @change="form.avatar = $event.target.files[0]"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
                <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">{{ form.errors.avatar }}</p>
              </div>

              <!-- Password + Confirm -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                  <input v-model="form.password" type="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                    placeholder="Nhập mật khẩu mới..." />
                  <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')"
                    class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
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

        <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
          <Link :href="route('admin.customers.index')"
            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
            Hủy bỏ
          </Link>
          <button type="submit" @click="submit"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md"
            :disabled="form.processing">
            <i class="fas fa-save mr-2"></i> Cập nhật khách hàng
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '../Layouts/AppLayout.vue'

// Destructure props
const { customer, rankTemplates } = defineProps({
  customer: Object,
  rankTemplates: Array,
})

// Khởi tạo form với dữ liệu từ customer
const form = useForm({
  name: customer?.name || '',
  phone: customer?.phone || '',
  email: customer?.email || '',
  address: customer?.address || '',
  current_debt: customer?.current_debt || 0,
  rank_id: customer?.rank_id || '',
  avatar: null,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  if (!customer?.id) {
    console.error('Customer ID không tồn tại')
    return
  }

  form.put(route('admin.customers.update', customer.id), {
    onSuccess: () => {
      form.reset('password', 'password_confirmation', 'avatar')
    },
    onError: (errors) => {
      console.error(errors)
    }
  })
}
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
.animate-fade-in {
  animation: fadeIn 0.5s ease-in;
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
