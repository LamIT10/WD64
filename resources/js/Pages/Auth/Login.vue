<template>
  <AuthLayout>
    <div class="relative">
      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-6 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-indigo-700 text-center mb-6 tracking-tight">Đăng nhập</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Truy cập hệ thống quản lý kho SUVAN</p>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input id="email" v-model="form.email" type="email"
            class="w-full px-4 py-3 text-sm rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 shadow-sm hover:shadow-md"
            placeholder="email@example.com">
          <div class="w-full text-left text-xs mt-1" v-if="errors.email || form.errors.email">
            <span class="text-red-500">{{ errors.email || form.errors.email }}</span>
          </div>
        </div>

        <!-- Password -->
        <div>
          <div class="flex justify-between mb-1.5">
            <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
            <Link :href="route('password.request')" @click.prevent="showForgot = true"
              class="text-xs text-indigo-600 hover:text-indigo-500 font-medium transition">Quên mật khẩu?</Link>
          </div>
          <div class="relative">
            <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
              class="w-full px-4 py-3 text-sm rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 shadow-sm hover:shadow-md"
              placeholder="••••••••">
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
          <div class="w-full text-left text-xs mt-1" v-if="errors.password || form.errors.password">
            <span class="text-red-500">{{ errors.password || form.errors.password }}</span>
          </div>
        </div>

        <!-- reCAPTCHA -->
        <div class="form-group recaptcha-container">
          <vue-recaptcha class="w-full" ref="recaptcha" :sitekey="siteKey" @verify="onVerify" @expired="onExpired"></vue-recaptcha>
          <div class="w-full text-left text-xs mt-1" v-if="errors['g-recaptcha-response'] || form.errors['g-recaptcha-response']">
            <span class="text-red-500">{{ errors['g-recaptcha-response'] || form.errors['g-recaptcha-response'] }}</span>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" :disabled="form.processing"
          class="w-full py-3 px-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-indigo-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02] flex items-center justify-center">
          <span v-if="!form.processing">ĐĂNG NHẬP</span>
          <svg v-else class="w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
          </div>
          <div class="relative flex justify-center">
            <span class="px-3 bg-white text-xs text-gray-500 font-medium">hoặc tiếp tục với</span>
          </div>
        </div>

        <!-- Google Login -->
        <a :href="route('google.login')"
          class="w-full flex items-center justify-center py-3 px-4 border border-gray-200 bg-gray-50 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-200 transform hover:scale-[1.02]">
          <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.545 10.239v3.821h5.445c-0.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866 0.549 3.921 1.453l2.814-2.814c-1.784-1.664-4.153-2.675-6.735-2.675-5.522 0-10 4.477-10 10s4.478 10 10 10c8.396 0 10-7.524 10-10 0-0.167-0.007-0.333-0.020-0.500h-9.980z" />
          </svg>
          Đăng nhập với Google
        </a>
      </form>

      <!-- Success Message -->
      <transition name="fade">
        <div v-if="form.isSuccessful" class="mt-6 p-4 bg-green-100 text-green-700 text-sm rounded-lg flex items-center gap-2 animate-pulse">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          Đăng nhập thành công! Đang chuyển hướng...
        </div>
      </transition>
    </div>
  </AuthLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { reactive, ref } from "vue";
import VueRecaptcha from "vue3-recaptcha2";
import { Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import Toast from '../components/Toast.vue';
import AuthLayout from "./AuthLayout.vue";

const showForgot = ref(false);
const showPassword = ref(false);
const siteKey = "6LccsEErAAAAABih7tLBLalq7fbEgVICF-gGn7Gk";
const recaptcha = ref(null);

const form = useForm({
  email: "",
  password: "",
  "g-recaptcha-response": "",
});

const errors = reactive({
  email: "",
  password: "",
  "g-recaptcha-response": "",
});

function validate() {
  let valid = true;
  errors.email = "";
  errors.password = "";
  errors["g-recaptcha-response"] = "";

  if (!form.email) {
    errors.email = "Vui lòng nhập email.";
    valid = false;
  } else if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(form.email)) {
    errors.email = "Email không hợp lệ.";
    valid = false;
  }

  if (!form.password) {
    errors.password = "Vui lòng nhập mật khẩu.";
    valid = false;
  } else if (form.password.length < 6) {
    errors.password = "Mật khẩu phải có ít nhất 6 ký tự.";
    valid = false;
  }

  if (!form["g-recaptcha-response"]) {
    errors["g-recaptcha-response"] = "Vui lòng xác minh reCAPTCHA.";
    valid = false;
  }

  return valid;
}

function submitForm() {
  if (!validate()) return;
  form.post(route('login'), {
    onSuccess: () => {
      resetForm();
    },
    onError: (error) => {
      errors.email = error.email || "";
      errors.password = error.password || "";
      errors["g-recaptcha-response"] = error["g-recaptcha-response"] || "";
      resetForm();
    },
  });
}

function onVerify(token) {
  form["g-recaptcha-response"] = token;
}

function onExpired() {
  form["g-recaptcha-response"] = "";
}

function resetForm() {
  form["g-recaptcha-response"] = "";
  if (recaptcha.value) recaptcha.value.reset();
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