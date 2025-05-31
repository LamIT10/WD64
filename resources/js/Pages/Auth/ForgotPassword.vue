<template>
  <AuthLayout>
    <form @submit.prevent="submitForm" class="bg-white px-10 h-full">
      <h1 class="text-2xl mb-5">Quên mật khẩu</h1>
      <input id="email" v-model="form.email" type="text" placeholder="Nhập email của bạn"
        class="bg-gray-200 border-none p-3 text-sm rounded-lg w-full outline-none" />
      <div class="w-full text-left" v-if="form.errors.email">
        <span class="text-red-600 text-left">{{ form.errors.email }}</span>
      </div>
      <!-- <Link :href="route('password.request')" @click.prevent="showForgot = true" class="text-gray-700 text-xs my-3 block text-right" > Quay lại? </Link> -->
      <button
        class="bg-teal-600 text-white text-xs py-3 px-10 border border-transparent rounded-lg font-semibold uppercase mt-3 cursor-pointer">
        Gửi liên kết
      </button>
    </form>
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

function submitForm() {
  form.post("/forgot-password");
}

</script>

<style></style>