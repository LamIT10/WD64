<script setup>
import Header from '../partials/Header.vue';
import Sidebar from '../partials/SideBar.vue';
import Footer from '../partials/Footer.vue';
import Toast from '../../components/Toast.vue';
</script>
<template>
    <Toast />
    <div class="bg-[#F8F9FA] flex flex-wrap">
        <nav class="w-[20%] h-[938px] bg-[#FCFCFD]">
            <Sidebar />
        </nav>
        <div class="w-[80%]">
            <header class="w-full">
                <Header />
            </header>
            <slot />
        </div>
    </div>


    <!-- <Footer /> -->

</template>