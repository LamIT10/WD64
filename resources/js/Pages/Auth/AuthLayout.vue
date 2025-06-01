<template>
    <Toast />
    <div class="flex items-center justify-center min-h-screen">
  <div
    class="container bg-white rounded-[30px] shadow-lg relative overflow-hidden h-[600px] w-[788px] max-w-full min-h-[580px]"
    id="container">
    <!-- Form Đăng nhập -->
    <div key="login"
      class="form-container sign-in absolute top-0 left-0 h-full w-1/2 z-2 transition-all duration-600 ease-in-out">
       <slot />
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
  </div>
</template>


<script setup>
import Toast from '../components/Toast.vue';

</script>
<style scoped>
</style>