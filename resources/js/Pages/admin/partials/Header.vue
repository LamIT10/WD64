<template>
    <!-- Header -->
    <header
        class="bg-white shadow-sm border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <div class="flex items-center space-x-2 bg-purple-50 rounded-lg px-3 py-1.5">
            <div class="text-purple-600">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="text-sm hidden sm:block">
                <span>Đang xử lý</span>
                <span class="font-medium ml-1">5 mục</span>
            </div>
        </div>
        <!-- Right side - User controls -->
        <div class="flex items-center space-x-3">
            <!-- Quick Actions -->
            <div class="flex items-center space-x-1 sm:space-x-2">
                <!-- QR Code Scanner -->
                <button
                    class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200 relative group"
                    title="Quét mã vạch">
                    <i class="fas fa-qrcode text-lg"></i>
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                        Quét mã vạch
                    </span>
                </button>

                <!-- Dark Mode Toggle -->
                <button
                    class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200 group"
                    title="Chế độ tối">
                    <i class="fas fa-moon text-lg"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button
                    class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200 group">
                    <i class="fas fa-bell text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">3</span>
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                        Thông báo
                    </span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="flex items-center space-x-2 pl-2 border-l border-gray-200">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-gray-800">
                        Nguyễn Văn Admin
                    </div>
                    <div class="text-xs text-gray-500">Quản trị viên</div>
                </div>
                <div class="relative group">
                    <img class="inline-block h-8 w-8 rounded-full object-cover cursor-pointer"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="User profile" />

                    <!-- Dropdown arrow indicator -->
                    <div @click.stop="showDropdown = !showDropdown"
                        class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 cursor-pointer select-none">
                        <div
                            class="bg-gray-200 group-hover:bg-purple-600 w-3 h-3 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-chevron-down text-[6px] text-gray-500 group-hover:text-white"></i>
                        </div>
                    </div>
                    <!-- Dropdown menu -->
                    <transition name="fade">
                        <div v-if="showDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-40 py-2 text-sm">
                            <Link :href="route('profile')" 
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">Quản lí cá nhân
                            </Link>
                            <Link :href="route('logout')" method="post" 
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">Đăng xuất
                            </Link>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>

import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js';

document.addEventListener("DOMContentLoaded", function () {
    const notificationBadge = document.querySelector(".animate-pulse");
    notificationBadge.classList.add("animate-ping-once");

    setTimeout(() => {
        notificationBadge.classList.remove("animate-ping-once");
    }, 1000);
});
// Dropdown trạng thái
const showDropdown = ref(false)

// Đóng dropdown khi click ra ngoài
function handleClickOutside(event) {
  const dropdown = document.querySelector('.group')
  if (dropdown && !dropdown.contains(event.target)) {
    showDropdown.value = false
  }
}
document.addEventListener('click', handleClickOutside)

</script>

<style lang="css" scoped>
@keyframes ping {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    75%,
    100% {
        transform: scale(1.2);
        opacity: 0;
    }
}

.animate-ping-once {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) 1;
}
</style>
