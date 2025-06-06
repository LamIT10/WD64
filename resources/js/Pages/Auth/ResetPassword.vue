<template>
  <AuthLayout>
    <div class="relative">
      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-indigo-700 text-center mb-6 tracking-tight">Đặt lại mật khẩu</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Nhập mật khẩu mới để khôi phục tài khoản của bạn</p>

        <!-- Hidden Inputs -->
        <input type="hidden" v-model="form.token" />
        <input type="hidden" v-model="form.email" />
        <div v-if="form.errors.email" class="w-full text-left text-xs mt-1">
          <span class="text-red-500">{{ form.errors.email }}</span>
        </div>
        <div v-if="form.errors.token" class="w-full text-left text-xs mt-1">
          <span class="text-red-500">{{ form.errors.token }}</span>
        </div>

        <!-- Password Input -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Mật khẩu mới</label>
          <div class="relative">
            <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
              class="w-full px-4 py-3 text-sm rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 shadow-sm hover:shadow-md"
              placeholder="Nhập mật khẩu mới">
            <button type="button" @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
            </button>
          </div>
          <div v-if="form.errors.password" class="w-full text-left text-xs mt-1">
            <span class="text-red-500">{{ form.errors.password }}</span>
          </div>
        </div>

        <!-- Password Confirmation Input -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Xác nhận mật khẩu</label>
          <div class="relative">
            <input id="password_confirmation" v-model="form.password_confirmation" :type="showPasswordConfirm ? 'text' : 'password'"
              class="w-full px-4 py-3 text-sm rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 shadow-sm hover:shadow-md"
              placeholder="Nhập lại mật khẩu mới">
            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
              class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
              <svg v-if="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="roundBW" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('login')"
            class="w-full sm:w-auto py-3 px-6 bg-white text-indigo-600 border-2 border-indigo-200 text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]">
            Quay lại
          </Link>
          <button type="submit" :disabled="form.processing"
            class="w-full sm:w-auto py-3 px-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02] flex items-center justify-center">
            <span v-if="!form.processing">Đặt lại mật khẩu</span>
            <svg v-else class="w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </button>
        </div>
      </form>

      <!-- Success Message -->
      <transition name="fade">
        <div v-if="form.isSuccessful" class="mt-6 p-4 bg-green-100 text-green-700 text-sm rounded-lg flex items-center gap-2 animate-pulse">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Mật khẩu của bạn đã được đặt lại thành công!
        </div>
      </transition>
    </div>
  </AuthLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import AuthLayout from "./AuthLayout.vue";
import Toast from '../components/Toast.vue';

const page = usePage();

const form = useForm({
  token: page.props.token || "",
  email: page.props.email || "",
  password: "",
  password_confirmation: "",
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

function submit() {
  form.post(route('password.update'), {
    onSuccess: () => {
      form.reset('password', 'password_confirmation');
    },
  });
}
</script>

<style scoped>
/* Animation cho form */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Transition cho success message */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>