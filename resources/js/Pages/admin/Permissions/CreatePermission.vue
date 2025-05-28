<template>
    <div>
        <AppLayout>
            <div class="px-[20px] mt-[20px]">
                <div class="w-full bg-[#FFFFFF] rounded-[10px] px-[10px] py-[10px] mt-[10px]">
                    <div class="w-[100%] mx-auto my-[10px] gap-x-[20px]">
                        <form @submit.prevent="hanldeSubmitForm" class="gap-[10px] items-end w-full">
                            <div class="flex flex-col gap-[5px] w-full">
                                <label class="text-[12px] text-[#000000] ">Tên quyền hạng <span class="text-red-500">*</span></label>
                                <input type="text" value="" v-model="form.name"
                                    class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200">
                                    <span v-if="errors.name" class="text-red-500 text-[12px]">{{ errors.name }}</span>
                            </div>
                            <div class="flex flex-col gap-[5px] w-full">
                                <label class="text-[12px] text-[#000000] ">Mô tả</label>
                                <textarea type="text" value="" v-model="form.description"
                                    class="w-full px-[12px] py-[8px] text-[12px] text-[#000] rounded-[5px]  bg-[#ffffff] border border-gray-200"></textarea>
                            </div>
                            <div class="w-full flex justify-end">
                                <button :disabled="form.processing" class="me-[3px] mt-[10px] p-[3px] ps-[10px] pe-[10px] border rounded bg-[#BE202F] text-white font-bold text-[12px]">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import { reactive } from 'vue';

const form = useForm({
    name: "",
    description: "",
})
const errors = reactive({
    name: ""
})
const hanldeSubmitForm = () => {
    form.post(route('admin.permission.store'), {
        onError: (xhr) => {
            console.log(xhr);
            errors.name = xhr.name;
        }
    })
}
</script>

<style lang="scss" scoped></style>