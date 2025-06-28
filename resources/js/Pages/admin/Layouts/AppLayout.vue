<script setup>
import Header from "../partials/Header.vue";
import Sidebar from "../partials/SideBar.vue";
import Toast from "../../components/Toast.vue";
import { usePage } from "@inertiajs/vue3";
import ToastClient from "../../components/ToastClient.vue";
const page = usePage()
</script>
<template>
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
