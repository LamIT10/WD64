<template>
  <AppLayout>
    <div class="bg-gray-50 p-6">
      <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
        <h5 class="text-lg text-indigo-700 font-semibold">Thêm khách hàng mới</h5>
        <Waiting route-name="admin.customers.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
          <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </Waiting>
      </div>
      <div class="mx-auto bg-white rounded shadow-md overflow-hidden">
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
              <div v-if="!previewUrl" class="w-32 h-32 mx-auto bg-gray-100 rounded flex items-center justify-center">
                <i class="fas fa-camera text-gray-400 text-2xl"></i>
              </div>
              <img v-if="previewUrl" :src="previewUrl" alt="Avatar" class="w-32 h-32 mx-auto object-cover rounded-full" />
              <input type="file" accept="image/*" ref="avatarInput" @change="handleFileChange" class="hidden" />
              <button type="button" @click="triggerInput" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Chọn ảnh
              </button>
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
                  <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại *</label>
                  <input v-model="form.phone" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập số điện thoại..." />
                  <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Hạng khách hàng</label>
                  <select v-model="form.rank_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all">
                    <option value="" disabled>Chọn hạng...</option>
                    <option v-for="rank in rankTemplates" :key="rank.id" :value="rank.id">{{ rank.name }}</option>
                  </select>
                  <p class="text-sm text-gray-500 mt-1">Hạng sẽ tự động cập nhật dựa trên tổng chi tiêu.</p>
                  <p v-if="form.errors.rank_id" class="text-red-500 text-sm mt-1">{{ form.errors.rank_id }}</p>
                  <p v-if="!rankTemplates.length" class="text-yellow-600 text-sm mt-1">Không có hạng khách hàng khả dụng.</p>
                </div>
              </div>

              <!-- Row 3: Password + Confirm Password -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                  <input v-model="form.password" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập mật khẩu..." />
                  <p v-if="form.errors.password && !form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu</label>
                  <input v-model="form.password_confirmation" type="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập lại mật khẩu..." />
                  <p v-if="form.errors.password && form.errors.password.toLowerCase().includes('khớp')" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
              </div>

              <!-- Additional Information Section -->
              <div v-if="showAdditionalInfo">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 mt-6">Thông tin bổ sung</h3>
                <div class="space-y-6">
                  <!-- Row 4: Address + Current Debt -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                      <input v-model="form.address" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập địa chỉ..." />
                      <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Công nợ</label>
                      <input v-model.number="form.current_debt" type="number" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập công nợ..." />
                      <p v-if="form.errors.current_debt" class="text-red-500 text-sm mt-1">{{ form.errors.current_debt }}</p>
                      <p v-if="form.current_debt > computedMaxDebtLimit" class="text-yellow-600 text-sm mt-1">
                        Trạng thái sẽ được đặt thành "Vượt công nợ" do công nợ vượt giới hạn {{ formatNumber(computedMaxDebtLimit) }}.
                      </p>
                    </div>
                  </div>

                  <!-- Row 5: Total Spent + Status -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tổng chi tiêu</label>
                      <input v-model.number="form.total_spent" type="number" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all" placeholder="Nhập tổng chi tiêu..." />
                      <p v-if="form.errors.total_spent" class="text-red-500 text-sm mt-1">{{ form.errors.total_spent }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                      <select v-model="form.status" :disabled="form.current_debt > computedMaxDebtLimit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:outline-none focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="debt_exceeded" v-if="form.current_debt > computedMaxDebtLimit">Vượt công nợ</option>
                        <option value="active" v-else>Hoạt động</option>
                        <option value="inactive" v-else>Không hoạt động</option>
                    </select>

                      <p v-if="form.errors.status" class="text-red-500 text-sm mt-1">{{ form.errors.status }}</p>
                    </div>
                  </div>

                  <!-- Row 6: Max Debt Limit -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Giới hạn công nợ</label>
                      <input :value="formatNumber(computedMaxDebtLimit)" type="text" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-0" />
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
          <button type="button" @click="confirmSubmit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md" :disabled="form.processing">
            <i class="fas fa-save mr-2"></i> Lưu
          </button>
        </div>
      </div>
    </div>
    <Toast />
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import Toast from '../../components/Toast.vue';
import { ref, watch, computed, onMounted } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
  rankTemplates: {
    type: Array,
    default: () => [],
  },
});

const rankTemplates = props.rankTemplates;
const showAdditionalInfo = ref(true);
const avatarInput = ref(null);
const previewUrl = ref(null);

const form = useForm({
  name: '',
  phone: '',
  email: null,
  password: '',
  password_confirmation: '',
  address: null,
  current_debt: 0,
  total_spent: 0,
  rank_id: '',
  avatar: null,
  status: 'active',
});

const computedRankId = computed(() => {
  if (!rankTemplates.length) {
    console.warn('Không có rankTemplates khả dụng');
    return null;
  }
  if (!form.total_spent || form.total_spent < 0) {
    const defaultRank = rankTemplates.find((r) => r.name === 'Sắt' && r.status === 'active');
    console.log('Gán hạng mặc định:', defaultRank);
    return defaultRank ? defaultRank.id : null;
  }
  const sortedRanks = [...rankTemplates]
    .filter((r) => r.status === 'active')
    .sort((a, b) => b.min_total_spent - a.min_total_spent);
  const rank = sortedRanks.find((r) => r.min_total_spent <= form.total_spent);
  if (!rank) {
    console.warn('Không tìm thấy hạng phù hợp cho total_spent:', form.total_spent);
  }
  return rank ? rank.id : null;
});

const computedMaxDebtLimit = computed(() => {
  if (!rankTemplates.length || !form.rank_id || form.total_spent < 0) {
    console.warn('Không thể tính max_debt_limit', {
      rank_id: form.rank_id,
      total_spent: form.total_spent,
      rankTemplates: rankTemplates.length,
    });
    return 0;
  }
  const rank = rankTemplates.find((r) => r.id === Number(form.rank_id));
  if (!rank) {
    console.warn('Rank không tồn tại:', form.rank_id);
    return 0;
  }
  return Number(form.total_spent) * (rank.credit_percent / 100);
});

watch(() => form.total_spent, (newTotalSpent) => {
  form.rank_id = computedRankId.value;
});

watch([() => form.current_debt, () => computedMaxDebtLimit.value], ([newDebt, newMaxDebtLimit]) => {
  if (newDebt > newMaxDebtLimit) {
    form.status = 'debt_exceeded';
    const toast = document.querySelector('toast');
    if (toast && toast.showLocalToast) {
      toast.showLocalToast('Trạng thái đã được đặt thành "Vượt công nợ" vì công nợ vượt giới hạn.', 'warning');
    }
  } else if (form.status === 'debt_exceeded') {
    form.status = 'active';
    const toast = document.querySelector('toast');
    if (toast && toast.showLocalToast) {
      toast.showLocalToast('Công nợ trong giới hạn, trạng thái được đặt lại thành "Hoạt động".', 'success');
    }
  }
});

watch(() => form.rank_id, () => {
  if (form.current_debt === 0) {
    form.status = 'inactive';
    const toast = document.querySelector('toast');
    if (toast && toast.showLocalToast) {
      toast.showLocalToast('Công nợ bằng 0, trạng thái được đặt thành "Không hoạt động".', 'info');
    }
  }
  else if (form.current_debt > computedMaxDebtLimit.value) {
    form.status = 'debt_exceeded';
    const toast = document.querySelector('toast');
    if (toast && toast.showLocalToast) {
      toast.showLocalToast('Trạng thái đã được đặt thành "Vượt công nợ" vì công nợ vượt giới hạn.', 'warning');
    }
  }
  else if (form.status === 'debt_exceeded') {
    form.status = 'active';
    const toast = document.querySelector('toast');
    if (toast && toast.showLocalToast) {
      toast.showLocalToast('Công nợ trong giới hạn, trạng thái được đặt lại thành "Hoạt động".', 'success');
    }
  }
});

function toggleAdditionalInfo() {
  showAdditionalInfo.value = !showAdditionalInfo.value;
}

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
    previewUrl.value = null;
  }
};

function resetForm() {
  form.reset();
  form.name = '';
  form.phone = '';
  form.email = null;
  form.password = '';
  form.password_confirmation = '';
  form.address = null;
  form.current_debt = 0;
  form.total_spent = 0;
  form.rank_id = '';
  form.avatar = null;
  form.status = 'active';
  previewUrl.value = null;
  form.errors = {};
}

function confirmSubmit() {
  if (form.current_debt > computedMaxDebtLimit.value) {
    form.status = 'debt_exceeded'; // Ép buộc trạng thái thành "vượt công nợ" trước khi gửi
  }
  submit();
}

const formatNumber = (value) => {
  if (value === null || value === undefined || isNaN(Number(value))) {
    return '0.00';
  }
  return Number(value).toFixed(2);
};

const submit = () => {
  form.post(route('admin.customers.store'), {
    forceFormData: true,
    preserveState: true,
    onSuccess: () => {
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Thêm khách hàng thành công!', 'success');
      }
      resetForm();
    },
    onError: (errors) => {
      console.error('Lỗi từ backend:', errors);
      form.errors = errors; // Gán lỗi từ backend vào form
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Lỗi khi tạo khách hàng: ' + (errors.message || 'Vui lòng kiểm tra lại dữ liệu!'), 'error');
      }
    },
  });
};
</script>