<template>
  <div class="container bg-white rounded-[30px] shadow-lg relative overflow-hidden h-[600px] w-[788px] max-w-full min-h-[580px]" id="container">
    <!-- Form Đăng nhập -->
    <transition name="fade">
      <div v-if="!showForgot" key="login" class="form-container sign-in absolute top-0 left-0 h-full w-1/2 z-2 transition-all duration-600 ease-in-out">
        <form @submit.prevent="submitForm" class="bg-white px-10 h-full">
          <h1 class="text-2xl mb-5">Đăng nhập</h1>
          <div class="social-icons flex gap-2 my-5">
            <Link :href="route('google.login')"
              class="icon border border-gray-300 rounded-[20%] flex items-center justify-center w-10 h-10">
              <img class="items-center justify-center" src="https://developers.google.com/identity/images/g-logo.png"
                alt="Google" style="width:20px;vertical-align:middle;">
              <!-- Đăng nhập Google -->
            </Link>
          </div>
          <span class="text-xs">hoặc đăng nhập bằng </span>
          <input id="email" v-model="form.email" type="text" placeholder="Nhập email của bạn"
            class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
          <div class="w-full text-left" v-if="form.errors.email">
            <span class="text-red-600 text-left">{{ form.errors.email }}</span>
          </div>
          <input id="password" v-model="form.password" type="password" placeholder="Nhập mật khẩu của bạn"
            class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
          <div class="w-full text-left" v-if="form.errors.password">
            <span class="text-red-600">{{ form.errors.password }}</span>
          </div>
          <div class="form-group recaptcha-container">
            <vue-recaptcha ref="recaptcha" :sitekey="siteKey" @verify="onVerify" @expired="onExpired"></vue-recaptcha>
          </div>
          <Link href="/forgot-password" class="text-blue-500">Đi tới trang mới</Link>
          <a href="#" @click.prevent="showForgot = true" class="text-gray-700 text-xs my-3 block text-right">Quên mật khẩu?</a>
          <button
            class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">
            Đăng nhập
          </button>
        </form>
      </div>
    </transition>
    
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
import { ref } from "vue";
import VueRecaptcha from "vue3-recaptcha2";
import { Link } from '@inertiajs/vue3';

const showForgot = ref(false);

const siteKey = "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"; // Thay bằng Site Key của bạn

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
    onSuccess: () => {
      alert("Đăng nhập thành công!");
    },
    onError: (errors) => {
      console.error("Lỗi đăng nhập:", errors);
      alert("Đăng nhập thất bại. Vui lòng kiểm tra thông tin.");
    },
  });
}

function onVerify(token) {
  form["g-recaptcha-response"] = token;
  console.log("reCAPTCHA verified:", token);
}

function onExpired() {
  form["g-recaptcha-response"] = "";
  console.log("reCAPTCHA expired");
}

</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
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
  0%, 49.99% {
    opacity: 0;
    z-index: 1;
  }
  50%, 100% {
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