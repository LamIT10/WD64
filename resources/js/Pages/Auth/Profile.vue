<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white shadow-lg">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <Link  :href="route('dashboard')" class="flex items-center text-white hover:text-indigo-200 transition-colors">
            <i class="fas fa-arrow-left text-lg mr-2"></i>
            <span class="font-medium">Trang chủ</span>
          </Link>
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
              <div
                class="relative mx-auto w-32 h-32 rounded-full border-4 border-white shadow-md mb-4 overflow-hidden"
              >
                <img
                  id="avatar-preview"
                  :src="user.avatar"
                  alt="Avatar"
                  class="w-full h-full object-cover"
                />
                <button
                  class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition-colors"
                >
                  <i class="fas fa-camera text-sm"></i>
                </button>
              </div>
              <h2 class="text-xl font-bold text-gray-800">{{ user.name }}</h2>
              <p class="text-indigo-600 font-medium">{{ user.role }}</p>
              <p class="text-sm text-gray-500 mt-1">{{ user.department }}</p>
            </div>

            <nav class="p-4">
              <ul class="space-y-1">
                <li v-for="nav in navItems" :key="nav.id">
                  <a
                    :href="`#${nav.id}`"
                    class="nav-item flex items-center p-3 text-gray-700 rounded-lg hover:bg-indigo-50 transition-colors"
                    :class="{ active: activeSection === nav.id }"
                  >
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
          <!-- Personal Information -->
          <section
            id="personal-info"
            class="bg-white rounded-xl shadow-md overflow-hidden"
            ref="sections"
          >
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100">
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                    <i class="fas fa-user-circle mr-2"></i>
                    Thông tin cá nhân
                  </h2>
                  <p class="text-sm text-gray-500">Thông tin cơ bản về bạn</p>
                </div>
                <button
                  class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-50 transition-colors"
                >
                  <i class="fas fa-pen mr-2"></i>Chỉnh sửa
                </button>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="info in personalInfo" :key="info.label" class="space-y-1">
                  <label class="block text-sm font-medium text-gray-500">{{ info.label }}</label>
                  <p class="text-gray-800" :class="{ 'font-medium': info.bold }">
                    {{ info.value }}
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- Work Information -->
          <section
            id="work-info"
            class="bg-white rounded-xl shadow-md overflow-hidden"
            ref="sections"
          >
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100">
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                    <i class="fas fa-briefcase mr-2"></i>
                    Thông tin công việc
                  </h2>
                  <p class="text-sm text-gray-500">Thông tin về vị trí và nhiệm vụ của bạn</p>
                </div>
                <button
                  class="px-4 py-2 bg-white border border-indigo-200 text-gray-500 rounded-lg cursor-not-allowed"
                  disabled
                >
                  <i class="fas fa-pen mr-2"></i>Chỉnh sửa
                </button>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="info in workInfo" :key="info.label" class="space-y-1">
                  <label class="block text-sm font-medium text-gray-500">{{ info.label }}</label>
                  <p class="text-gray-800" :class="{ 'font-medium': info.bold }">
                    {{ info.value }}
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- System Information -->
          <section
            id="system-info"
            class="bg-white rounded-xl shadow-md overflow-hidden"
            ref="sections"
          >
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100">
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                    <i class="fas fa-cog mr-2"></i>
                    Thông tin hệ thống
                  </h2>
                  <p class="text-sm text-gray-500">Hoạt động và phân quyền của bạn</p>
                </div>
                <button
                  class="px-4 py-2 bg-white border border-indigo-200 text-gray-500 rounded-lg cursor-not-allowed"
                  disabled
                >
                  <i class="fas fa-pen mr-2"></i>Chỉnh sửa
                </button>
              </div>
            </div>
            <div class="p-6 space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                  <label class="block text-sm font-medium text-gray-500">Trạng thái tài khoản</label>
                  <div class="flex items-center">
                    <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                    <p class="text-green-600 font-medium">{{ systemInfo.status }}</p>
                  </div>
                </div>
                <div class="space-y-1">
                  <label class="block text-sm font-medium text-gray-500"
                    >Lần đăng nhập gần nhất</label
                  >
                  <p class="text-gray-800">{{ systemInfo.lastLogin }}</p>
                </div>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Lịch sử hoạt động</label>
                <div class="mt-2 space-y-3">
                  <div
                    v-for="activity in systemInfo.activities"
                    :key="activity.id"
                    class="flex items-start"
                  >
                    <div class="flex-shrink-0 mt-1">
                      <div class="h-2 w-2 rounded-full bg-indigo-500 mr-3"></div>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-800">{{ activity.action }}</p>
                      <p class="text-xs text-gray-500">{{ activity.time }}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Phân quyền chức năng</label>
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div
                    v-for="permission in systemInfo.permissions"
                    :key="permission"
                    class="flex items-center p-3 bg-indigo-50 rounded-lg"
                  >
                    <div class="flex-shrink-0 h-5 w-5 text-indigo-600">
                      <i class="fas fa-check-circle"></i>
                    </div>
                    <label class="ml-2 block text-sm font-medium text-gray-700">{{
                      permission
                    }}</label>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Account & Security -->
          <section
            id="security"
            class="bg-white rounded-xl shadow-md overflow-hidden"
            ref="sections"
          >
            <div class="bg-gradient-to-r from-indigo-50 to-white p-6 border-b border-indigo-100">
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-xl font-bold text-indigo-800 flex items-center">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Tài khoản & bảo mật
                  </h2>
                  <p class="text-sm text-gray-500">Bảo vệ tài khoản của bạn</p>
                </div>
              </div>
            </div>
            <div class="p-6 space-y-6">
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Email đăng nhập</label>
                <p class="text-gray-800">{{ securityInfo.email }}</p>
              </div>
              <div class="pt-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Mật khẩu</h3>
                    <p class="text-sm text-gray-500">
                      Cập nhật mật khẩu thường xuyên để bảo mật tài khoản
                    </p>
                  </div>
                  <button
                    @click="openPasswordModal"
                    class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-colors shadow-md"
                  >
                    <i class="fas fa-key mr-2"></i>Đổi mật khẩu
                  </button>
                </div>
              </div>
              <div class="pt-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">Xác thực hai lớp (2FA)</h3>
                    <p class="text-sm text-gray-500">Thêm lớp bảo mật cho tài khoản của bạn</p>
                  </div>
                  <button
                    class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-50 transition-colors"
                  >
                    <i class="fas fa-mobile-alt mr-2"></i>Thiết lập
                  </button>
                </div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>
    <!-- Password Change Modal -->
    <transition name="modal">
      <div v-if="showPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Đổi mật khẩu</h3>
            <button @click="closePasswordModal" class="text-gray-500 hover:text-gray-700">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <form @submit.prevent="submitPasswordChange" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
              <input
                v-model="passwordForm.current_password"
                type="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                :class="{ 'border-red-500': passwordForm.errors.current_password }"
              />
              <p v-if="passwordForm.errors.current_password" class="text-red-500 text-sm">
                {{ passwordForm.errors.current_password }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
              <input
                v-model="passwordForm.new_password"
                type="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                :class="{ 'border-red-500': passwordForm.errors.new_password }"
              />
              <p v-if="passwordForm.errors.new_password" class="text-red-500 text-sm">
                {{ passwordForm.errors.new_password }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
              <input
                v-model="passwordForm.new_password_confirmation"
                type="password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                :class="{ 'border-red-500': passwordForm.errors.new_password_confirmation }"
              />
              <p v-if="passwordForm.errors.new_password_confirmation" class="text-red-500 text-sm">
                {{ passwordForm.errors.new_password_confirmation }}
              </p>
            </div>
            <div class="flex justify-end space-x-2">
              <button
                type="button"
                @click="closePasswordModal"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
              >
                Hủy
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800"
                :disabled="passwordForm.processing"
              >
                {{ passwordForm.processing ? 'Đang xử lý...' : 'Cập nhật' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// User data
const user = {
  avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
  name: 'Nguyễn Văn A',
  role: 'Quản trị viên hệ thống',
  department: 'Phòng Quản trị hệ thống',
};

// Navigation items
const navItems = [
  { id: 'personal-info', label: 'Thông tin cá nhân', icon: 'fas fa-user-circle' },
  { id: 'work-info', label: 'Thông tin công việc', icon: 'fas fa-briefcase' },
  { id: 'system-info', label: 'Thông tin hệ thống', icon: 'fas fa-cog' },
  { id: 'security', label: 'Tài khoản & bảo mật', icon: 'fas fa-shield-alt' },
];

// Personal info
const personalInfo = [
  { label: 'Họ và tên', value: 'Nguyễn Văn A', bold: true },
  { label: 'Ngày sinh', value: '15/05/1990' },
  { label: 'Giới tính', value: 'Nam' },
  { label: 'Số điện thoại', value: '0987654321' },
  { label: 'Email công ty', value: 'nguyenvana@company.com' },
  { label: 'Địa chỉ làm việc', value: 'Kho A, 123 Đường XYZ, Quận 1, TP.HCM' },
];

// Work info
const workInfo = [
  { label: 'Mã nhân viên', value: 'NV001', bold: true },
  { label: 'Vai trò', value: 'Quản trị viên hệ thống' },
  { label: 'Bộ phận/Phòng ban', value: 'Quản trị hệ thống' },
  { label: 'Vị trí công việc', value: 'Admin' },
  { label: 'Ca làm việc', value: 'Hành chính' },
  { label: 'Ngày bắt đầu làm việc', value: '01/01/2020' },
];

// System info
const systemInfo = {
  status: 'Hoạt động',
  lastLogin: '31/05/2023 14:30',
  activities: [
    { id: 1, action: 'Đăng nhập hệ thống', time: '31/05/2023 14:30' },
    { id: 2, action: 'Cập nhật thông tin người dùng', time: '30/05/2023 09:15' },
  ],
  permissions: ['Quản lý người dùng', 'Phân quyền hệ thống'],
};

// Security info
const securityInfo = {
  email: 'nguyenvana@company.com',
};

// Social links
const socialLinks = [
  { name: 'Facebook', url: 'https://facebook.com', icon: 'fab fa-facebook-f' },
  { name: 'Twitter', url: 'https://twitter.com', icon: 'fab fa-twitter' },
  { name: 'LinkedIn', url: 'https://linkedin.com', icon: 'fab fa-linkedin-in' },
];

// Quick links
const quickLinks = [
  { name: 'Trang chủ', url: '/' },
  { name: 'Hỗ trợ', url: '/support' },
  { name: 'Chính sách bảo mật', url: '/privacy' },
  { name: 'Điều khoản dịch vụ', url: '/terms' },
];

// Newsletter form
const newsletterForm = useForm({
  email: '',
});

const submitNewsletter = () => {
  newsletterForm.post('/newsletter/subscribe', {
    onSuccess: () => {
      alert('Cảm ơn bạn đã đăng ký! Chúng tôi sẽ gửi thông tin sớm nhất.');
      newsletterForm.reset();
    },
    onError: (errors) => {
      console.error('Newsletter form errors:', errors);
    },
  });
};

// Password change form
const passwordForm = useForm({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});

const showPasswordModal = ref(false);

const openPasswordModal = () => {
  showPasswordModal.value = true;
};

const closePasswordModal = () => {
  showPasswordModal.value = false;
  passwordForm.reset();
};

const submitPasswordChange = () => {
  passwordForm.post('/account/password', {
    onSuccess: () => {
      alert('Mật khẩu đã được cập nhật thành công!');
      closePasswordModal();
    },
    onError: (errors) => {
      console.error('Password form errors:', errors);
    },
  });
};

// Scroll-based navbar highlighting
const activeSection = ref('');
const sections = ref([]);

const handleScroll = () => {
  let current = '';
  sections.value.forEach((section) => {
    const sectionTop = section.offsetTop;
    if (window.pageYOffset >= sectionTop - 200) {
      current = section.getAttribute('id');
    }
  });
  activeSection.value = current;
};

// Back to top button
const showBackToTop = ref(false);

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const updateScroll = () => {
  showBackToTop.value = window.scrollY > 300;
  handleScroll();
};

onMounted(() => {
  window.addEventListener('scroll', updateScroll);
  // Trigger initial scroll to set active nav
  updateScroll();
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateScroll);
});
</script>

<style scoped>

</style>