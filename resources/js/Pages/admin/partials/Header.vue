<template>
    <!-- Header -->
    <header
        class="bg-white shadow-sm border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30"
    >
        <div
            class="flex items-center space-x-2 bg-indigo-50 rounded-lg px-3 py-1.5"
        >
            <div class="text-indigo-600">
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
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 relative group"
                    title="Quét mã vạch"
                >
                    <i class="fas fa-qrcode text-lg"></i>
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        Quét mã vạch
                    </span>
                </button>

                <!-- Dark Mode Toggle -->
                <button
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                    title="Chế độ tối"
                >
                    <i class="fas fa-moon text-lg"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="relative">
                <button
                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 group"
                >
                    <i class="fas fa-bell text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"
                        >3</span
                    >
                    <span
                        class="absolute -bottom-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                        Thông báo
                    </span>
                </button>
            </div>

            <!-- User Profile -->
            <div
                class="flex items-center space-x-2 pl-2 border-l border-gray-200"
            >
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-gray-800">
                        Nguyễn Văn Admin
                    </div>
                    <div class="text-xs text-gray-500">Quản trị viên</div>
                </div>
                <div class="relative group">
                    <img
                        class="inline-block h-8 w-8 rounded-full object-cover cursor-pointer"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="User profile"
                    />

                    <!-- Dropdown arrow indicator -->
                    <div
                        class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5"
                    >
                        <div
                            class="bg-gray-200 group-hover:bg-indigo-600 w-3 h-3 rounded-full flex items-center justify-center transition-colors"
                        >
                            <i
                                class="fas fa-chevron-down text-[6px] text-gray-500 group-hover:text-white"
                            ></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
document.addEventListener("DOMContentLoaded", function () {
    const notificationBadge = document.querySelector(".animate-pulse");
    notificationBadge.classList.add("animate-ping-once");

    setTimeout(() => {
        notificationBadge.classList.remove("animate-ping-once");
    }, 1000);
});
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
