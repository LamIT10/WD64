<script setup>
import Header from "../partials/Header.vue";
import Sidebar from "../partials/SideBar.vue";
import Toast from "../../components/Toast.vue";
import ToastClient from "../../components/ToastClient.vue";
import { usePage, router } from "@inertiajs/vue3";
import { onMounted } from "vue";

const page = usePage();

// Bắt sự kiện loading từ Inertia
onMounted(() => {
  router.on("start", () => {
    document.body.classList.add("loading");
  });

  router.on("finish", () => {
    document.body.classList.remove("loading");
  });

  router.on("error", () => {
    console.error("Lỗi khi chuyển trang Inertia");
    document.body.classList.remove("loading");
  });
});
</script>

<template>
  <!-- Loader bằng ảnh xoay -->
  <div id="inertia-loader">
    <img
      src="/resources/js/assets/images/z6849541358512_60ab07cd7abacf63af3f220fd96cbd28.jpg"
      alt="Loading..."
      class="logo-spin"
    />
  </div>

  <!-- Layout chính -->
  <Toast :initial-flash="page.props.flash" />
  <ToastClient ref="toastRef" />
  <div id="sidebar-overlay" class="sidebar-overlay"></div>
  <Sidebar />
  <div
    id="main-content"
    style="font-size: 12px"
    class="min-h-screen transition-all duration-300 lg:ml-56"
  >
    <Header />
    <main class="p-1 sm:p-1">
      <slot />
    </main>
  </div>
</template>

<style scoped>
/* Loader toàn trang */
#inertia-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(20, 20, 20, 0.4); /* nền mờ hơn */
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(3px); /* làm mờ nền phía sau */
}

/* Ảnh xoay tròn */
.logo-spin {
  width: 130px;
  height: 130px;
  border-radius: 50%; /* bo tròn hình ảnh */
  object-fit: cover;
  animation: spin 1.5s linear infinite;
  box-shadow: 0 0 20px rgba(255, 68, 68, 0.4); /* hiệu ứng sáng nhẹ */
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

/* Kích hoạt loader khi body có class 'loading' */
body.loading #inertia-loader {
  display: flex;
}
</style>
