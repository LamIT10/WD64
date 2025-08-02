<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div v-if="!customer" class="mx-auto bg-white rounded shadow-md p-6">
        <p class="text-red-500">Không tìm thấy khách hàng.</p>
        <Link :href="route('admin.customers.index')" class="mt-4 inline-block px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
          Quay lại danh sách khách hàng
        </Link>
      </div>
      <div v-else class="mx-auto bg-white rounded shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
          <h5 class="text-lg text-indigo-700 font-semibold">Cập nhật khách hàng</h5>
          <Waiting route-name="admin.customers.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
            <i class="fas fa-arrow-left mr-1"></i> Quay lại
          </Waiting>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
          <nav class="flex space-x-4 px-6 pt-4">
            <button class="pb-2 px-1 border-b-2 border-indigo-600 text-indigo-600 font-medium">Thông tin</button>
          </nav>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col md:flex-row p-6">
          <!-- Left Panel - Photo Upload -->
          <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
              <div v-if="!previewUrl && !form.current_avatar" class="w-32 h-32 mx-auto bg-gray-100 rounded flex items-center justify-center">
                <i class="fas fa-camera text-gray-400 text-2xl"></i>
              </div>
              <img v-else :src="previewUrl || `/storage/${form.current_avatar}`" alt="Avatar" class="w-32 h-32 mx-auto object-cover rounded-full" />
              <input type="file" accept="image/*" ref="avatarInput" @change="handleFileChange" class="hidden" />
              <button type="button" @click="triggerInput" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Chọn ảnh
              </button>
              <div v-if="form.current_avatar" class="mt-3">
                <p class="text-sm text-gray-700 mb-1">Ảnh hiện tại:</p>
                <img :src="`/storage/${form.current_avatar}`" alt="Ảnh cũ" class="w-24 h-24 mx-auto object-cover rounded-full border border-gray-300" />
              </div>
              <p v-else class="mt-3 text-sm text-gray-500">Chưa có ảnh hiện tại</p>
              <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">{{ form.errors.avatar }}</p>
            </div>
          </div>

          <!-- Right Panel - Fields -->
          <div class="w-full md:w-3/4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin cơ bản</h3>
            <div class="space-y-6">
              <!-- Row 1: Name + Email -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tên khách hàng *</label>
                  <input v-model="form.name" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập tên khách hàng..." />
                  <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input v-model="form.email" type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập địa chỉ email..." />
                  <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>
              </div>

              <!-- Row 2: Phone + Rank -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                  <input v-model="form.phone" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập số điện thoại..." />
                  <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Hạng khách hàng</label>
                  <select v-model="form.rank_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option v-for="rank in rankTemplates" :key="rank.id" :value="rank.id">{{ rank.name }}</option>
                  </select>
                  <p v-if="form.errors.rank_id" class="text-red-500 text-sm mt-1">{{ form.errors.rank_id }}</p>
                </div>
              </div>

              <!-- Row 3: Password + Confirm Password -->
              <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                  <input v-model="form.password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập mật khẩu mới..." />
                  <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                  <input v-model="form.password_confirmation" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập lại mật khẩu..." />
                  <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
              </div> -->

              <!-- Additional Information Section -->
              <div v-if="showAdditionalInfo">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin bổ sung</h3>
                <div class="space-y-6">
                  <!-- Row 4: Address + Status -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                      <input v-model="form.address" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập địa chỉ..." />
                      <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                      <select v-model="form.status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="active">Hợp tác</option>
                        <option value="inactive">Ngừng hợp tác</option>
                      </select>
                      <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Info Button -->
        <div class="px-6 pb-6">
          <button type="button" @click="toggleAdditionalInfo" class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm font-medium">
            <i :class="showAdditionalInfo ? 'fas fa-minus' : 'fas fa-plus'" class="mr-2 text-sm"></i>
            {{ showAdditionalInfo ? 'Ẩn thông tin' : 'Thêm thông tin' }}
          </button>
        </div>

        <!-- Footer Actions -->
        <div class="flex justify-end space-x-3 p-6 bg-gray-50 border-t border-gray-200">
          <button type="reset" @click="resetForm" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
            Bỏ qua
          </button>
          <button type="button" @click="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md" :disabled="form.processing">
            <i class="fas fa-save mr-2"></i> Cập nhật
          </button>
        </div>
      </div>
      <Toast />
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import Toast from '../../components/Toast.vue';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const { customer, rankTemplates } = defineProps({
  customer: Object,
  rankTemplates: Array,
});

const showAdditionalInfo = ref(true);
const avatarInput = ref(null);
const previewUrl = ref(null);

const form = useForm({
  name: customer?.name || '',
  phone: customer?.phone || '',
  email: customer?.email || null,
  address: customer?.address || null,
  rank_id: customer?.rank_id || '',
  avatar: null,
  current_avatar: customer?.avatar || null,
  password: '',
  password_confirmation: '',
  status: customer?.status || 'active',
  _method: 'PATCH',
});

const triggerInput = () => {
  avatarInput.value.click();
};

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    if (!['image/jpeg', 'image/png'].includes(file.type)) {
      form.errors.avatar = 'Chỉ hỗ trợ định dạng JPEG hoặc PNG';
      avatarInput.value.value = '';
      return;
    }
    if (file.size > 2 * 1024 * 1024) {
      form.errors.avatar = 'Kích thước ảnh không được vượt quá 2MB';
      avatarInput.value.value = '';
      return;
    }
    form.avatar = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      previewUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.avatar = null;
    previewUrl.value = form.current_avatar ? `/storage/${form.current_avatar}` : null;
  }
};

function toggleAdditionalInfo() {
  showAdditionalInfo.value = !showAdditionalInfo.value;
}

function resetForm() {
  form.reset();
  form.name = customer?.name || '';
  form.phone = customer?.phone || '';
  form.email = customer?.email || null;
  form.address = customer?.address || null;
  form.rank_id = customer?.rank_id || '';
  form.status = customer?.status || 'active';
  form.current_avatar = customer?.avatar || null;
  previewUrl.value = form.current_avatar ? `/storage/${form.current_avatar}` : null;
  form.errors = {};
}

const submit = () => {
  if (!customer?.id) {
    console.error('Customer ID không tồn tại');
    return;
  }
  form.post(route('admin.customers.update', customer.id), {
    forceFormData: true,
    preserveState: true,
    onSuccess: () => {
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Cập nhật khách hàng thành công!', 'success');
      }
      form.reset('password', 'password_confirmation', 'avatar');
      previewUrl.value = form.current_avatar ? `/storage/${form.current_avatar}` : null;
      router.reload({
        only: ['customer'],
        onSuccess: ({ props }) => {
          const updatedCustomer = props.customer;
          form.name = updatedCustomer?.name || '';
          form.phone = updatedCustomer?.phone || '';
          form.email = updatedCustomer?.email || null;
          form.address = updatedCustomer?.address || null;
          form.rank_id = updatedCustomer?.rank_id || '';
          form.status = updatedCustomer?.status || 'active';
          form.current_avatar = updatedCustomer?.avatar || null;
          previewUrl.value = form.current_avatar ? `/storage/${form.current_avatar}` : null;
        },
      });
    },
    onError: (errors) => {
      console.error('Lỗi từ backend:', errors);
      form.errors = errors;
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Lỗi khi cập nhật khách hàng: ' + (errors.message || 'Vui lòng kiểm tra lại dữ liệu!'), 'error');
      }
    },
  });
};
</script>