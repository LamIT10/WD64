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
              <div>
                <button v-if="!isEditing" @click="isEditing = true"
                  class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 rounded-lg hover:bg-indigo-50 transition-colors">
                  <i class="fas fa-pen mr-2"></i>Chỉnh sửa
                </button>
                <div v-else class="flex gap-2">
                  <button @click="savePersonalInfo"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Lưu
                  </button>
                  <button @click="cancelEdit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Hủy
                  </button>
                </div>
              </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Họ và tên -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Họ và tên</label>
                <template v-if="isEditing">
                  <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
                </template>
                <p v-else class="text-gray-800 font-medium">
                  {{ $page.props.auth.user?.name || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Ngày sinh -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Ngày sinh</label>
                <template v-if="isEditing">
                  <input v-model="form.birthday" type="date" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.birthday" class="text-red-600 text-sm mt-1">{{ errors.birthday[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{ formatDate($page.props.auth.user?.birthday) || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Giới tính -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Giới tính</label>
                <template v-if="isEditing">
                  <select v-model="form.gender" class="w-full border rounded px-3 py-2">
                    <option value="">Chọn giới tính</option>
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                  </select>
                  <p v-if="errors.gender" class="text-red-600 text-sm mt-1">{{ errors.gender[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{
                    $page.props.auth.user?.gender === 'male' || $page.props.auth.user?.gender === '1'
                      ? 'Nam'
                      : $page.props.auth.user?.gender === 'female' || $page.props.auth.user?.gender === '0'
                        ? 'Nữ'
                        : 'Chưa cập nhật'
                  }}
                </p>
              </div>

              <!-- CMND/CCCD -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">CMND/CCCD</label>
                <template v-if="isEditing">
                  <input v-model="form.identity_number" type="text" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.identity_number" class="text-red-600 text-sm mt-1">{{ errors.identity_number[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{ $page.props.auth.user?.identity_number || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Địa chỉ -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Địa chỉ</label>
                <template v-if="isEditing">
                  <input v-model="form.address" type="text" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.address" class="text-red-600 text-sm mt-1">{{ errors.address[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{ $page.props.auth.user?.address || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Facebook -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Facebook</label>
                <template v-if="isEditing">
                  <input v-model="form.facebook" type="text" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.facebook" class="text-red-600 text-sm mt-1">{{ errors.facebook[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{ $page.props.auth.user?.facebook || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Số điện thoại -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Số điện thoại</label>
                <template v-if="isEditing">
                  <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
                  {{ $page.props.auth.user?.phone || 'Chưa cập nhật' }}
                </p>
              </div>

              <!-- Email -->
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Email công ty</label>
                <template v-if="isEditing">
                  <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" />
                  <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</p>
                </template>
                <p v-else class="text-gray-800">
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
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Mã nhân viên</label>
                <p class="text-gray-800 font-medium">{{ $page.props.auth.user?.employee_code || 'Chưa cập nhật' }}</p>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Vai trò</label>
                <div v-if="$page.props.auth.role" class="flex flex-wrap gap-2">
                  <span
                    class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 flex items-center">
                    <i class="fas fa-user-shield mr-1 text-xs"></i>
                    {{ $page.props.auth.role.name }}
                  </span>
                </div>
                <div v-else>
                  <p class="text-gray-800">Chưa cập nhật</p>
                </div>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Vị trí công việc</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.position || 'Chưa cập nhật' }}</p>
              </div>
              <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-500">Ngày bắt đầu làm việc</label>
                <p class="text-gray-800">{{ formatDate($page.props.auth.user?.start_date) || 'Chưa cập nhật' }}</p>
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
            </div>
            <div class="p-6 space-y-6">
              <div class="flex items-center gap-2">
                Trạng thái:
                <span class="h-2 w-2 rounded-full" :class="{
                  'bg-green-500': $page.props.auth.user?.status === 'active',
                  'bg-red-500': $page.props.auth.user?.status === 'inactive'
                }"></span>
                <p class="font-medium" :class="{
                  'text-green-600': $page.props.auth.user?.status === 'active',
                  'text-red-600': $page.props.auth.user?.status === 'inactive'
                }">
                  {{ $page.props.auth.user?.status === 'active' ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                </p>
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
              <!-- Email đăng nhập -->
              <div>
                <label class="block text-sm font-medium text-gray-500">Email đăng nhập</label>
                <p class="text-gray-800">{{ $page.props.auth.user?.email }}</p>
              </div>

              <!-- Phần mật khẩu hiện tại -->
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">Mật khẩu</h3>
                  <p class="text-sm text-gray-500">Cập nhật mật khẩu thường xuyên</p>
                </div>
                <div class="flex gap-2">
                  <button @click="showPasswordForm = !showPasswordForm"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Đổi mật khẩu
                  </button>
                  <a href="/forgot-password" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Lấy lại mật khẩu
                  </a>
                </div>
              </div>

              <!-- Form đổi mật khẩu (toggle) -->
              <div v-if="showPasswordForm" class="mt-6 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Mật khẩu hiện tại</label>
                  <div class="relative">
                    <input :type="showCurrentPassword ? 'text' : 'password'" v-model="passwordForm.current_password"
                      class="w-full border rounded px-3 py-2 pr-10" />
                    <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500">
                      <i :class="showCurrentPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                  </div>
                  <p v-if="passwordErrors.current_password" class="text-red-600 text-sm mt-1">
                    {{ passwordErrors.current_password[0] }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-500">Mật khẩu mới</label>
                  <div class="relative">
                    <input :type="showNewPassword ? 'text' : 'password'" v-model="passwordForm.new_password"
                      class="w-full border rounded px-3 py-2 pr-10" />
                    <button type="button" @click="showNewPassword = !showNewPassword"
                      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500">
                      <i :class="showNewPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                  </div>
                  <p v-if="passwordErrors.new_password" class="text-red-600 text-sm mt-1">
                    {{ passwordErrors.new_password[0] }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-500">Xác nhận mật khẩu mới</label>
                  <div class="relative">
                    <input :type="showConfirmPassword ? 'text' : 'password'"
                      v-model="passwordForm.new_password_confirmation" class="w-full border rounded px-3 py-2 pr-10" />
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500">
                      <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                  </div>
                  <p v-if="passwordErrors.new_password_confirmation" class="text-red-600 text-sm mt-1">
                    {{ passwordErrors.new_password_confirmation[0] }}
                  </p>

                </div>
                <div class="flex gap-2">
                  <button @click="changePassword"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Lưu
                  </button>
                  <button @click="showPasswordForm = false"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                    Hủy
                  </button>
                </div>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const activeSection = ref('')
const page = usePage()
const user = page.props.auth.user
const role = page.props.auth.role

const isEditing = ref(false)

const form = ref({
  name: user?.name || '',
  birthday: user?.birthday || '',
  gender: user?.gender || '',
  identity_number: user?.identity_number || '',
  address: user?.address || '',
  facebook: user?.facebook || '',
  phone: user?.phone || '',
  email: user?.email || ''
})

// errors state để hiển thị validate
const errors = ref({})

const savePersonalInfo = () => {
  router.post(route('profile.update'), form.value, {
    onSuccess: () => {
      isEditing.value = false
      errors.value = {}
    },
    onError: (pageError) => {
      const rawErrors = pageError?.props?.errors || page.props.errors || {}
      // Chuyển tất cả giá trị thành mảng string
      const formattedErrors = {}
      for (const key in rawErrors) {
        formattedErrors[key] = Array.isArray(rawErrors[key])
          ? rawErrors[key]
          : [String(rawErrors[key])]
      }
      errors.value = formattedErrors
    }
  })
}

const cancelEdit = () => {
  isEditing.value = false
  errors.value = {}
  form.value = {
    name: user?.name || '',
    birthday: user?.birthday || '',
    gender: user?.gender || '',
    identity_number: user?.identity_number || '',
    address: user?.address || '',
    facebook: user?.facebook || '',
    phone: user?.phone || '',
    email: user?.email || ''
  }
}

const getInitial = (name) =>
  !name ? '' : name.split(' ').map((n) => n[0]).join('').toUpperCase()

const handleScroll = () => {
  let current = ''
  document.querySelectorAll('section').forEach((sec) => {
    if (window.pageYOffset >= sec.offsetTop - 200) {
      current = sec.id
    }
  })
  activeSection.value = current
}

onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

function formatDate(date) {
  if (!date) return 'Chưa cập nhật'
  const d = new Date(date)
  return d.toLocaleDateString('vi-VN')
}

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const showPasswordForm = ref(false)

const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const passwordErrors = ref({})

const changePassword = () => {
  router.post(route('profile.change-password'), passwordForm.value, {
    onSuccess: () => {
      passwordForm.value = { current_password: '', new_password: '', new_password_confirmation: '' }
      passwordErrors.value = {}
      showPasswordForm.value = false
      alert('Đổi mật khẩu thành công!')
    },
    onError: (pageError) => {
      const rawErrors = pageError?.props?.errors || page.props.errors || {}
      const formattedErrors = {}
      for (const key in rawErrors) {
        formattedErrors[key] = Array.isArray(rawErrors[key]) ? rawErrors[key] : [String(rawErrors[key])]
      }
      passwordErrors.value = formattedErrors
    }
  })
}

</script>

<style scoped>
.nav-item.active {
  background-color: #e0e7ff;
}
</style>
