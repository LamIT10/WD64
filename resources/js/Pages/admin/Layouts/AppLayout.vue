<script setup>
import Header from "../partials/Header.vue";
import Sidebar from "../partials/SideBar.vue";
import Toast from "../../components/Toast.vue";
</script>
<template>
    <Toast />
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    <Sidebar />
    <div
        id="main-content"
        class="min-h-screen transition-all duration-300 lg:ml-56"
    >
        <Header />
        <main class="p-3 sm:p-6">
          <slot />
        </main>
    </div>
</template>
