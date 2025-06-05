<template>
  <AuthLayout>
    <div class="relative">
      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-6 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-indigo-700 text-center mb-6 tracking-tight">Khôi phục mật khẩu</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Nhập email của bạn để nhận mã khôi phục</p>

        <!-- Email Input -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input id="email" v-model="form.email" type="email"
            class="w-full px-4 py-3 text-sm rounded-lg border border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 shadow-sm hover:shadow-md"
            placeholder="email@example.com">
          <div class="w-full text-left text-xs mt-1" v-if="errors.email || form.errors.email">
            <span class="text-red-500">{{ errors.email || form.errors.email }}</span>
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
            <span v-if="!form.processing">Gửi mã</span>
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
          Mã khôi phục đã được gửi đến email của bạn!
        </div>
      </transition>
    </div>
  </AuthLayout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import AuthLayout from "./AuthLayout.vue";

const showForgot = ref(false);

const form = useForm({
  email: "",
});

const errors = ref({
  email: "",
});

function validate() {
  errors.value.email = "";
  if (!form.email) {
    errors.value.email = "Vui lòng nhập email.";
    return false;
  }
  if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(form.email)) {
    errors.value.email = "Email không hợp lệ.";
    return false;
  }
  return true;
}

function submitForm() {
  if (!validate()) return;
  form.post(route('password.email'), {
    onSuccess: () => {
      errors.value.email = "";
    },
    onError: (serverErrors) => {
      errors.value.email = serverErrors.email || "";
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