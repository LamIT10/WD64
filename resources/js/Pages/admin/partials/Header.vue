<template>
    <div class="w-[90%] h-[48px] mx-auto my-0 mt-[20px] flex justify-between items-center">
        <div class="flex gap-[5px]">
            <p class="text-[18px] font-semibold">Customer Order</p>
            <p class="text-[10px] text-[#756C6C] self-end">16 of 100 entries</p>
        </div>
        <div class="flex gap-x-[10px] h-full">
            <div class="relative shadow rounded-[10px] mr-[15px]">
                <input type="text"
                    class="w-[331px] h-full py-[9px] px-[40px] bg-[#FCFCFD] placeholder-[#C3C3C3] text-[14px] rounded-[10px]"
                    placeholder="Search">
                <i
                    class="fa-solid fa-magnifying-glass text-[20px] text-[#C3C3C3] absolute top-[50%] translate-y-[-50%] left-[5%]"></i>
            </div>
            <button class="px-[14px] py-[20px] bg-[#BE202F] rounded-[12px] flex justify-center items-center mr-[15px]">
                <i class="fa-solid fa-filter text-[#fff] text-[20px]"></i>
            </button>
            <button
                class="px-[14px] py-[20px] rounded-[12px] flex justify-center gap-x-[5px] items-center text-[#BE202F] bg-[#FCFCFD] shadow-2xl mr-[6px]">
                <i class="fa-solid fa-file-export text-[20px]"></i>
                <p class="text-[14px]">Export</p>
            </button>
            <div class="w-[2px] h-[70%] bg-[#000] opacity-10 self-center mr-[6px]"></div>
            <div class="self-center">
                <i class="fa-solid fa-bell text-[24px] text-[#000]"></i>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped></style>