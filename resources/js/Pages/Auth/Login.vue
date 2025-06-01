<template>
  <AuthLayout>
    <form @submit.prevent="submitForm" class="bg-white px-10 h-full">
      <h1 class="text-2xl mb-5">Đăng nhập</h1>
      <div class="social-icons flex gap-2 my-5">
        <a href="/auth/google"
          class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
          <img class="items-center justify-center" src="https://developers.google.com/identity/images/g-logo.png"
            alt="Google" style="width:20px;vertical-align:middle;">
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
        <div class="w-full text-left" v-if="errors['g-recaptcha-response']">
          <span class="text-red-600">{{ errors['g-recaptcha-response'] }}</span>
        </div>
      </div>
      <Link :href="route('password.request')" @click.prevent="showForgot = true"
        class="text-gray-700 text-xs my-3 block text-right"> Quên mật khẩu? </Link>
      <button
        class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer"
        :disabled="form.processing">
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

  // Email
  if (!form.email) {
    errors.email = "Vui lòng nhập email.";
    valid = false;
  } else if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(form.email)) {
    errors.email = "Email không hợp lệ.";
    valid = false;
  }

  // Password
  if (!form.password) {
    errors.password = "Vui lòng nhập mật khẩu.";
    valid = false;
  } else if (form.password.length < 6) {
    errors.password = "Mật khẩu phải có ít nhất 6 ký tự.";
    valid = false;
  }

  // reCAPTCHA
  if (!form["g-recaptcha-response"]) {
    errors["g-recaptcha-response"] = "Vui lòng xác minh reCAPTCHA.";
    valid = false;
  }

  return valid;
}

function submitForm() {
  if (!validate()) return;
  form.post("/login", {
    onError: (error) => {
      errors.email = error.email || "";
      errors.password = error.password || "";
      errors["g-recaptcha-response"] = error["g-recaptcha-response"] || "";
    },
  });
}

function onVerify(token) {
  form["g-recaptcha-response"] = token;
}

function onExpired() {
  form["g-recaptcha-response"] = "";
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* ...giữ nguyên toàn bộ style cũ của bạn ở dưới... */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
}

body {
  background-color: #c9d6ff;
  background: linear-gradient(to right, #e2e2e2, #c9d6ff);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  height: 100vh;
}

.container {
  background-color: #fff;
  border-radius: 30px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
  position: relative;
  overflow: hidden;
  width: 768px;
  max-width: 100%;
  min-height: 480px;
}

.container p {
  font-size: 14px;
  line-height: 20px;
  letter-spacing: 0.3px;
  margin: 20px 0;
}

.container span {
  font-size: 12px;
}

.container a {
  color: #333;
  font-size: 13px;
  text-decoration: none;
  margin: 15px 0 10px;
}

.container button {
  background-color: #2da0a8;
  color: #fff;
  font-size: 12px;
  padding: 10px 45px;
  border: 1px solid transparent;
  border-radius: 8px;
  font-weight: 600;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-top: 10px;
  cursor: pointer;
}

.container button.hidden {
  background-color: transparent;
  border-color: #fff;
}

.container form {
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 40px;
  height: 100%;
}

.container input {
  background-color: #eee;
  border: none;
  margin: 8px 0;
  padding: 10px 15px;
  font-size: 13px;
  border-radius: 8px;
  width: 100%;
  outline: none;
}

.form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.sign-in {
  left: 0;
  width: 50%;
  z-index: 2;
}

.container.active .sign-in {
  transform: translateX(100%);
}

.sign-up {
  left: 0;
  width: 50%;
  opacity: 0;
  z-index: 1;
}

.container.active .sign-up {
  transform: translateX(100%);
  opacity: 1;
  z-index: 5;
  animation: move 0.6s;
}

@keyframes move {

  0%,
  49.99% {
    opacity: 0;
    z-index: 1;
  }

  50%,
  100% {
    opacity: 1;
    z-index: 5;
  }
}

.social-icons {
  margin: 20px 0;
}

.social-icons a {
  border: 1px solid #ccc;
  border-radius: 20%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin: 0 3px;
  width: 40px;
  height: 40px;
}

.toggle-container {
  position: absolute;
  top: 0;
  left: 50%;
  width: 50%;
  height: 100%;
  overflow: hidden;
  transition: all 0.6s ease-in-out;
  border-radius: 150px 0 0 100px;
  z-index: 1000;
}

.container.active .toggle-container {
  transform: translateX(-100%);
  border-radius: 0 150px 100px 0;
}

.toggle {
  background-color: #2da0a8;
  height: 100%;
  background: linear-gradient(to right, #5c6bc0, #2da0a8);
  color: #fff;
  position: relative;
  left: -100%;
  height: 100%;
  width: 200%;
  transform: translateX(0);
  transition: all 0.6s ease-in-out;
}

.container.active .toggle {
  transform: translateX(50%);
}

.toggle-panel {
  position: absolute;
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0 30px;
  text-align: center;
  top: 0;
  transform: translateX(0);
  transition: all 0.6s ease-in-out;
}

.toggle-left {
  transform: translateX(-200%);
}

.container.active .toggle-left {
  transform: translateX(0);
}

.toggle-right {
  right: 0;
  transform: translateX(0);
}

.container.active .toggle-right {
  transform: translateX(200%);
}
</style>