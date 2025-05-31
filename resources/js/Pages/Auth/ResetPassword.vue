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

<style>
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