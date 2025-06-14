<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Cập nhật thông tin nhà cung cấp
                </h5>
                <Waiting
                    route-name="admin.suppliers.index"
                    :route-params="{}"
                    :color="'bg-indigo-50 text-indigo-700'"
                >
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

            <div class="p-6 bg-white rounded shadow-md">
                <form @submit.prevent="hanldeSubmitForm" class="space-y-6">
                    <div class="space-y-2">
                        <label for="supplier-name" class="block text-sm font-medium text-indigo-700">
                            Tên nhà cung cấp <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="supplier-name"
                            name="name"
                            placeholder="Nhập tên nhà cung cấp"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.name }}
                                    </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Đại diện -->
                        <div class="space-y-2">
                            <label for="representative" class="block text-sm font-medium text-indigo-700">
                                Người đại diện <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="representative"
                                name="contact_person"
                                v-model="form.contact_person"
                                placeholder="Nhập tên người đại diện"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                            />
                            <p v-if="form.errors.contact_person" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.contact_person }}
                                    </p>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-indigo-700">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="tel"
                                    v-model="form.phone"
                                    id="phone"
                                    name="phone"
                                    placeholder="Nhập số điện thoại"
                                    class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.phone }}
                                    </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-indigo-700">
                                Email
                            </label>
                            <input
                                type="text"
                                v-model="form.email"
                                id="email"
                                name="email"
                                placeholder="Nhập địa chỉ email"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                            />
                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.email }}
                                    </p>
                        </div>

                        <!-- Mã số thuế -->
                        <div class="space-y-2">
                            <label for="tax-code" class="block text-sm font-medium text-indigo-700">
                                Mã số thuế
                            </label>
                            <input
                                type="text"
                                id="tax-code"
                                v-model="form.tax_code"
                                name="tax-code"
                                placeholder="Nhập mã số thuế"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                            />
                            <p v-if="form.errors.tax_code" class="text-red-500 text-sm mt-1">
                                        {{ form.errors.tax_code }}
                                    </p>
                        </div>
                    </div>

                    <!-- Row 4: Địa chỉ -->
                    <div class="space-y-2">
                        <label for="address" class="block text-sm font-medium text-indigo-700">
                            Địa chỉ <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="address"
                            v-model="form.address"
                            name="address"
                            rows="3"
                            placeholder="Nhập địa chỉ đầy đủ"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button
                            type="reset"
                            class="px-4 py-2 text-indigo-700 bg-white border border-indigo-300 rounded hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200"
                        >
                            Nhập lại
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 shadow-md hover:shadow-lg"
                        >
                            <span class="font-medium">Cập nhật cung cấp</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { route } from "ziggy-js";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import { useForm } from "@inertiajs/vue3";

const { supplier } = defineProps({
    supplier: {
        type: Object,
        default: () => {},
    },
});
const form = useForm({
    'name': supplier.name,
    'phone': supplier.phone,
    'email': supplier.email,
    'tax_code': supplier.tax_code,
    'address': supplier.address,
    'contact_person': supplier.contact_person
})
const hanldeSubmitForm = ()=>{
   form.patch(route('admin.suppliers.update', supplier.id), {
         onError: () => {
            console.error = "Something went wrong. Please try again.";
        }
    })
}
</script>