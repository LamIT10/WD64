<script setup>
import Header from "../partials/Header.vue";
import Sidebar from "../partials/SideBar.vue";
import Toast from "../../components/Toast.vue";
import ToastClient from "../../components/ToastClient.vue";
import { usePage, router } from "@inertiajs/vue3";
import { onMounted, onBeforeUnmount, ref } from "vue";

const page = usePage();

const isLoading = ref(false);
// Dùng progress để điều phối thời điểm ẩn mượt (không hiển thị ra UI)
const progress = ref(0);

let progTimer = null;
let showTimer = null;
let finishTimer = null;

const SHOW_DELAY_MS = 120;   // tránh chớp tắt
const MIN_VISIBLE_MS = 500;  // đã hiện thì giữ tối thiểu
let shownAt = 0;

function startProgress() {
  clearInterval(progTimer);
  progress.value = 0;
  progTimer = setInterval(() => {
    // tăng dần đến ~90%
    const delta = Math.max(0.7, (90 - progress.value) / 9);
    progress.value = Math.min(progress.value + delta, 90);
  }, 80);
}

function requestStart() {
  clearTimeout(showTimer);
  showTimer = setTimeout(() => {
    if (isLoading.value) return;
    isLoading.value = true;
    shownAt = Date.now();
    startProgress();
  }, SHOW_DELAY_MS);
}

function requestFinish(success = true) {
  const hideSmooth = () => {
    clearInterval(progTimer);
    progTimer = null;
    clearInterval(finishTimer);
    finishTimer = setInterval(() => {
      progress.value += 4;
      if (progress.value >= 100) {
        progress.value = 100;
        clearInterval(finishTimer);
        setTimeout(() => {
          isLoading.value = false;
          progress.value = 0;
        }, 180);
      }
    }, 16);
  };

  if (!isLoading.value) { clearTimeout(showTimer); return; }

  const visibleFor = Date.now() - shownAt;
  const wait = Math.max(0, MIN_VISIBLE_MS - visibleFor);

  setTimeout(() => {
    if (!success) progress.value = Math.max(progress.value, 95);
    hideSmooth();
  }, wait);
}

onMounted(() => {
  router.on("start", () => { document.body.classList.add("loading"); requestStart(); });
  router.on("finish", () => { document.body.classList.remove("loading"); requestFinish(true); });
  router.on("error",  () => { console.error("Lỗi khi chuyển trang Inertia"); document.body.classList.remove("loading"); requestFinish(false); });
});

onBeforeUnmount(() => {
  clearInterval(progTimer);
  clearInterval(finishTimer);
  clearTimeout(showTimer);
});
</script>

<template>
  <!-- Loader ở giữa với 3 dấu chấm nhảy -->
  <div id="dot-loader" v-show="isLoading" aria-live="polite" role="status">
    <div class="card" aria-label="Đang tải">
      <div class="dots">
        <span class="dot d1"></span>
        <span class="dot d2"></span>
        <span class="dot d3"></span>
      </div>
      <div class="label">Đang tải...</div>
    </div>
  </div>

  <!-- Layout chính -->
  <Toast :initial-flash="page.props.flash" />
  <ToastClient ref="toastRef" />
  <div id="sidebar-overlay" class="sidebar-overlay"></div>
  <Sidebar />
  <div id="main-content" style="font-size: 12px" class="min-h-screen transition-all duration-300 lg:ml-56">
    <Header />
    <main class="p-1 sm:p-1">
      <slot />
    </main>
  </div>
</template>

<style scoped>
/* Overlay giữa màn hình, nền mờ nhẹ */
#dot-loader{
  position: fixed; inset: 0; z-index: 9999;
  display: grid; place-items: center;
  background: rgba(225, 229, 235, 0.35);
  backdrop-filter: blur(2px);
  animation: fadeIn .15s ease-out;
}
@keyframes fadeIn{ from{opacity:0} to{opacity:1} }

/* Thẻ nhỏ gọn */
.card{
  width: min(280px, 90vw);
  border-radius: 14px;
  padding: 16px 18px;
  background: rgba(18,20,26,.95);
  border: 1px solid rgba(255,255,255,.06);
  box-shadow: 0 10px 28px rgba(0,0,0,.32), inset 0 1px 0 rgba(255,255,255,.04);
  text-align: center;
  color: #eaf2ff;
}

/* Ba chấm nhảy lên xuống */
.dots{
  display: inline-flex;
  align-items: flex-end;
  gap: 10px;
  height: 26px; /* khung để thấy nhảy */
  margin: 2px 0 6px;
}
.dot{
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #8cdcff;
  box-shadow: 0 0 10px #b3e8ff66;
  animation: bounce 900ms ease-in-out infinite;
}
.d1{ animation-delay: 0s }
.d2{ animation-delay: .12s }
.d3{ animation-delay: .24s }

@keyframes bounce{
  0%, 100% { transform: translateY(0); opacity: .9 }
  50%      { transform: translateY(-8px); opacity: 1 }
}

/* Nhãn dưới */
.label{
  font-size: 12px;
  opacity: .9;
  letter-spacing: .2px;
}

/* Tuỳ chọn: giảm chuyển động nếu người dùng thiết lập */
@media (prefers-reduced-motion: reduce){
  .dot{ animation: none }
}
</style>
