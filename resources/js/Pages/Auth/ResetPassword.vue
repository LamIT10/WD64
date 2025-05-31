<template>
  <Toast />
  <div
    class="container bg-white rounded-[30px] shadow-lg relative overflow-hidden h-[600px] w-[788px] max-w-full min-h-[580px]"
    id="container">
    <!-- Form Đăng nhập -->
    <div key="login"
      class="form-container sign-in absolute top-0 left-0 h-full w-1/2 z-2 transition-all duration-600 ease-in-out">
      <form @submit.prevent="submit" class="bg-white px-10 h-full flex flex-col justify-center">
        <h1 class="text-2xl mb-5">Đặt lại mật khẩu</h1>
        <input type="hidden" v-model="form.token" />
        <input type="hidden" v-model="form.email" />
        <div v-if="form.errors.email" class="w-full text-left mb-2">
          <span class="text-red-600">{{ form.errors.email }}</span>
        </div>

        <div v-if="form.errors.token" class="w-full text-left mb-2">
          <span class="text-red-600">{{ form.errors.token }}</span>
        </div>
        <div class="w-full text-left mb-3">
          <label for="password" class="block text-sm font-medium mb-1">Mật khẩu mới</label>
          <input id="password" v-model="form.password" type="password" placeholder="Nhập mật khẩu mới"
            class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
        </div>

        <div class="w-full text-left mb-3">
          <label for="password_confirmation" class="block text-sm font-medium mb-1">Xác nhận mật khẩu</label>
          <input id="password_confirmation" v-model="form.password_confirmation" type="password"
            placeholder="Nhập lại mật khẩu mới"
            class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
        </div>
        <div v-if="form.errors.password" class="w-full text-left mb-2">
          <span class="text-red-600">{{ form.errors.password }}</span>
        </div>
        <button type="submit" :disabled="form.processing"
          class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">
          Đặt lại mật khẩu
        </button>
      </form>
    </div>

    <!-- Toggle Container giữ nguyên -->
    <div
      class="toggle-container absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-all duration-600 ease-in-out rounded-[150px_0_0_100px] z-[1000]">
      <div
        class="toggle bg-gradient-to-r from-indigo-500 to-teal-600 text-white relative left-[-100%] h-full w-[200%] transform translate-x-0 transition-all duration-600 ease-in-out">
        <div
          class="toggle-panel toggle-right absolute w-1/2 h-full flex items-center justify-center flex-col px-8 text-center top-0 right-0 transform translate-x-0 transition-all duration-600 ease-in-out">
          <h1 class="text-2xl mb-5">SUVAN xin chào!</h1>
          <p class="text-sm leading-5 mb-5">Đăng nhập để tiếp tục công việc của bạn.</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { useForm } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import Toast from '../components/Toast.vue';

const page = usePage();

const form = useForm({
  token: page.props.token || "",
  email: page.props.email || "",
  password: "",
  password_confirmation: "",
});

function submit() {
  form.post("/reset-password");
}
</script>

<style scoped>
</style>