<template>
  <AuthLayout>
    <form @submit.prevent="submitForm" class="bg-white px-10 h-full">
      <h1 class="text-2xl mb-5">Đăng nhập</h1>
      <div class="social-icons flex gap-2 my-5">
        <a href="/auth/google"
          class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
          <img class="items-center justify-center" src="https://developers.google.com/identity/images/g-logo.png"
            alt="Google" style="width:20px;vertical-align:middle;">
          <!-- Đăng nhập Google -->
        </a>
      </div>
      <span class="text-xs">hoặc đăng nhập bằng </span>
      <input id="email" v-model="form.email" type="text" placeholder="Nhập email của bạn"
        class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
      <div class="w-full text-left" v-if="errors.email">
        <span class="text-red-600 text-left">{{ errors.email }}</span>
      </div>
      <input id="password" v-model="form.password" type="password" placeholder="Nhập mật khẩu của bạn"
        class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
      <div class="w-full text-left" v-if="errors.password">
        <span class="text-red-600">{{ errors.password }}</span>
      </div>
      <div class="form-group recaptcha-container">
        <vue-recaptcha ref="recaptcha" :sitekey="siteKey" @verify="onVerify" @expired="onExpired"></vue-recaptcha>
      </div>
      <Link :href="route('password.request')" @click.prevent="showForgot = true"
        class="text-gray-700 text-xs my-3 block text-right"> Quên mật khẩu? </Link>
      <button
        class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">
        Đăng nhập
      </button>
    </form>
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

const siteKey = "6LccsEErAAAAABih7tLBLalq7fbEgVICF-gGn7Gk"; // Thay bằng Site Key của bạn

const form = useForm({
  email: "",
  password: "",
  "g-recaptcha-response": "",
});

function submitForm() {
  if (!form["g-recaptcha-response"]) {
    alert("Vui lòng xác thực reCAPTCHA!");
    return;
  }
  form.post("/login", {
    // onSuccess: () => {
    //   // alert("Đăng nhập thành công!");
    // },
    onError: (error) => {
      errors.email = error.email || "";
      errors.password = error.password || "";
      errors["g-recaptcha-response"] = error["g-recaptcha-response"] || "";
      console.error("Lỗi đăng nhập:", errors);
      // alert("Đăng nhập thất bại. Vui lòng kiểm tra thông tin.");
    },
  });
}

const errors = reactive({
  email: "",
  password: "",
});

function onVerify(token) {
  form["g-recaptcha-response"] = token;
  console.log("reCAPTCHA verified:", token);
}

function onExpired() {
  form["g-recaptcha-response"] = "";
  console.log("reCAPTCHA expired");
}

</script>

<style scoped>
</style>