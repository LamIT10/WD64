<template>
  <Toast />
  <div class="font-sans bg-gradient-to-br from-indigo-100 via-gray-50 to-purple-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-5xl flex flex-col md:flex-row bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:scale-[1.01]">
      <!-- Ảnh kho hàng bên trái -->
      <div class="w-full md:w-1/2 relative animate-fade-in-left">
        <img :src="chotImg"
             class="w-full h-full object-cover"
             alt="Kho hàng giấy in">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 via-transparent to-transparent"></div>
        <div class="absolute bottom-4 left-4 text-white">
          <h2 class="text-xl font-semibold">SUVAN</h2>
          <p class="text-sm opacity-80">Quản lý kho hàng thông minh</p>
        </div>
      </div>
      
      <!-- Form đăng nhập bên phải -->
      <div class="w-full md:w-1/2 p-8 animate-fade-in-right">
        <!-- <div class="text-center mb-8">
          <h1 class="text-3xl font-extrabold text-indigo-700 tracking-tight">Đăng nhập</h1>
          <p class="text-sm text-gray-500 mt-1">Truy cập hệ thống quản lý kho chuyên nghiệp</p>
        </div> -->
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
import Toast from '../components/Toast.vue';
import chotImg from '@/assets/images/chot.jpg';
</script>

<style scoped>
/* Animation cho hình ảnh và form */
@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(20px); }
  to { opacity: 1; transform: translateX(0); }
}
.animate-fade-in-left {
  animation: fadeInLeft 0.6s ease-out;
}
.animate-fade-in-right {
  animation: fadeInRight 0.6s ease-out;
}
</style>