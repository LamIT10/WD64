<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white shadow-lg">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <a href="admin/dashboard" class="flex items-center text-white hover:text-indigo-200 transition-colors">
            <i class="fas fa-arrow-left text-lg mr-2"></i>
            <span class="font-medium">Trang chủ</span>
          </a>
        </div>
        <div class="text-right">
          <h1 class="text-2xl font-bold">Quản lý tài khoản</h1>
          <p class="text-indigo-200 text-sm">Hồ sơ cá nhân của bạn</p>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Profile -->
        <aside class="w-full lg:w-1/4">
          <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-8">
            <div class="bg-gradient-to-r from-indigo-100 to-indigo-50 p-6 text-center">
              <div class="relative mx-auto w-32 h-32 rounded-full border-4 border-white shadow-md mb-4 overflow-hidden">
                <img v-if="$page.props.auth.user?.avatar" :src="`/storage/${$page.props.auth.user.avatar}`"
                  :key="$page.props.auth.user.avatar" class="w-full h-full object-cover" alt="User profile" />
                <div v-else
                  class="flex h-32 w-32 rounded-full bg-indigo-500 text-white text-4xl items-center justify-center cursor-pointer">
                  {{ getInitial($page.props.auth.user?.name) }}
                </div>
              </div>
              <h2 class="text-xl font-bold text-gray-800">{{ $page.props.auth.user?.name }}</h2>
              <p class="text-indigo-600 font-medium">{{ $page.props.auth.user?.position }}</p>
              <p class="text-sm text-gray-500 mt-1">{{ $page.props.auth.user?.department }}</p>
            </div>

            <nav class="p-4">
              <ul class="space-y-1">
                <li v-for="nav in [
                  { id: 'personal-info', label: 'Thông tin cá nhân', icon: 'fas fa-user-circle' },
                  { id: 'work-info', label: 'Thông tin công việc', icon: 'fas fa-briefcase' },
                  { id: 'system-info', label: 'Thông tin hệ thống', icon: 'fas fa-cog' },
                  { id: 'security', label: 'Tài khoản & bảo mật', icon: 'fas fa-shield-alt' }
                ]" :key="nav.id">
                  <a :href="`#${nav.id}`"
                    class="nav-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-indigo-50 transition-colors"
                    :class="{ active: activeSection === nav.id }">
                    <i :class="nav.icon" class="mr-3 text-lg"></i>
                    <span>{{ nav.label }}</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </aside>

        <!-- Main Sections -->
        <main class="w-full lg:w-3/4 space-y-6">
          <!-- Personal Info -->
          <section id="personal-info" class="bg-white rounded-xl shadow-md overflow-hidden">
            <div
              class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100 flex justify-between items-center">
              <div>
                <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                  <i class="fas fa-user-circle mr-2"></i>Thông tin cá nhân
                </h2>
                <p class="text-sm text-gray-500">Thông tin cơ bản về bạn</p>
              </div>
              <!-- <button class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-50 transition-colors">
      <i class="fas fa-pen mr-2"></i>Chỉnh sửa
    </button> -->
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Họ và tên -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Họ và tên</label>
                <p class="text-gray-800 font-medium">
                  {{ $page.props.auth.user?.name || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Ngày sinh -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Ngày sinh</label>
                <p class="text-gray-800">
                  {{ formatDate($page.props.auth.user?.birthday) || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Giới tính -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Giới tính</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.gender === 'male' || $page.props.auth.user?.gender === '1' ? 'Nam' :
                    $page.props.auth.user?.gender === 'female' || $page.props.auth.user?.gender === '0' ? 'Nữ' :
                      'Chưa cập nhật' }}
                </p>
              </div>

              <!-- CMND/CCCD -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">CMND/CCCD</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.identity_number || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Địa Chỉ -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Địa Chỉ</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.address || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Facebook -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Facebook</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.facebook || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Số điện thoại -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Số điện thoại</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.phone || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Email -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Email công ty</label>
                <p class="text-gray-800">
                  {{ $page.props.auth.user?.email || 'Chưa cập nhật' }}
                </p>
              </div>
            </div>
          </section>


          <!-- Work Info -->
          <section id="work-info" class="bg-white rounded-xl shadow-md overflow-hidden">
            <div
              class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100 flex justify-between items-center">
              <div>
                <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                  <i class="fas fa-briefcase mr-2"></i>Thông tin công việc
                </h2>
                <p class="text-sm text-gray-500">Thông tin về vị trí và nhiệm vụ của bạn</p>
              </div>
              <!-- <button class="px-4 py-2 bg-white border border-gray-200 text-gray-500 rounded-lg cursor-not-allowed"
                disabled>
                <i class="fas fa-pen mr-2"></i>Chỉnh sửa
              </button> -->
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Mã nhân viên</label>
                <p class="text-gray-800 font-medium">{{ $page.props.auth.user?.employee_code || "Chưa cập nhật" }}</p>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Vai trò</label>
                <div v-if="$page.props.auth.user?.roles && $page.props.auth.user?.roles.length"
                  class="flex flex-wrap gap-2">
                  <span v-for="(role, index) in $page.props.auth.user.roles" :key="index"
                    class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 flex items-center">
                    <i class="fas fa-user-shield mr-1 text-xs"></i>
                    {{ role }}
                  </span>
                </div>
                <div v-else>
                  <p class="text-gray-800">Chưa cập nhật</p>
                </div>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Vị trí công việc</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.position || "Chưa cập nhật" }}</p>
              </div>
              <!-- <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Ca làm việc</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.shift }}</p>
              </div> -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Ngày bắt đầu làm việc</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.start_date || "Chưa cập nhật" }}</p>
              </div>
            </div>
          </section>

          <!-- System Info -->
          <section id="system-info" class="bg-white rounded-xl shadow-md overflow-hidden">
            <div
              class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100 flex justify-between items-center">
              <div>
                <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                  <i class="fas fa-cog mr-2"></i>Thông tin hệ thống
                </h2>
                <p class="text-sm text-gray-500">Hoạt động và phân quyền của bạn</p>
              </div>
              <!-- <button class="px-4 py-2 bg-white border border-gray-200 text-gray-500 rounded-lg cursor-not-allowed"
                disabled>
                <i class="fas fa-pen mr-2"></i>Chỉnh sửa
              </button> -->
            </div>
            <div class="p-6 space-y-6">
              <div class="flex items-center gap-2">Trạng thái:
                <span class="h-2 w-2 rounded-full bg-green-500"></span> 
                <p class="text-green-600 font-medium">{{ $page.props.auth.user?.status }}</p>
              </div>
              <p>Lần đăng nhập gần nhất: {{ $page.props.auth.user?.last_login }}</p>
              <div v-for="activity in $page.props.auth.user?.activities" :key="activity.id"
                class="border-l-2 border-indigo-200 pl-4 py-1">
                <p class="text-sm">{{ activity.action }} - <span class="text-gray-500 text-xs">{{ activity.time
                    }}</span></p>
              </div>
              <div class="grid grid-cols-2 gap-2 mt-2">
                <div v-for="perm in $page.props.auth.user?.permissions" :key="perm"
                  class="flex items-center gap-1 bg-indigo-50 p-2 rounded">
                  <i class="fas fa-check-circle text-indigo-600"></i>
                  <span class="text-sm">{{ perm }}</span>
                </div>
              </div>
            </div>
          </section>

          <!-- Security -->
          <section id="security" class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100">
              <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                <i class="fas fa-shield-alt mr-2"></i>Tài khoản & bảo mật
              </h2>
              <p class="text-sm text-gray-500">Bảo vệ tài khoản của bạn</p>
            </div>
            <div class="p-6 space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-500">Email đăng nhập</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.email }}</p>
              </div>
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">Mật khẩu</h3>
                  <p class="text-sm text-gray-500">Cập nhật mật khẩu thường xuyên</p>
                </div>
                <button @click="showPasswordModal = true"
                  class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Đổi mật khẩu</button>
              </div>         
            </div>
          </section>
        </main>
      </div>
    </div>

    <!-- Password Modal -->
    <transition name="modal">
      <div v-if="showPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Đổi mật khẩu</h3>
            <button @click="showPasswordModal = false" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <form @submit.prevent="changePassword" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
              <input type="password" v-model="password.current" required class="w-full border rounded p-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
              <input type="password" v-model="password.new" required class="w-full border rounded p-2" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
              <input type="password" v-model="password.confirm" required class="w-full border rounded p-2" />
            </div>
            <div class="flex justify-end gap-2">
              <button type="button" @click="showPasswordModal = false"
                class="px-4 py-2 bg-gray-200 rounded">Hủy</button>
              <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Cập nhật</button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const activeSection = ref('');
const showPasswordModal = ref(false);
const password = ref({ current: '', new: '', confirm: '' });

const getInitial = (name) => !name ? '' : name.split(' ').map(n => n[0]).join('').toUpperCase();

const handleScroll = () => {
  let current = '';
  document.querySelectorAll('section').forEach(sec => {
    if (window.pageYOffset >= sec.offsetTop - 200) current = sec.id;
  });
  activeSection.value = current;
};

const changePassword = () => {
  alert(`Đổi mật khẩu: ${password.value.current} -> ${password.value.new}`);
  password.value = { current: '', new: '', confirm: '' };
  showPasswordModal.value = false;
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

function formatDate(date) {
  if (!date) return 'Chưa cập nhật';
  const d = new Date(date);
  return d.toLocaleDateString('vi-VN'); // sẽ ra dd/mm/yyyy
} 
</script>

<style scoped>
.nav-item.active {
  background-color: #e0e7ff;
}
</style>
