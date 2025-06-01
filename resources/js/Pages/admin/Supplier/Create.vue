<template>
    <AppLayout>
        <div class="bg-gray-50 p-6">
            <!-- Header -->
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-purple-700 font-semibold">
                    Thêm Nhà Cung Cấp Mới
                </h5>
                <Waiting
                    route-name="admin.suppliers.index"
                    :route-params="{}"
                    :color="'bg-purple-50 text-purple-700'"
                >
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <form @submit.prevent="hanldeSubmitForm" class="space-y-6">
                    <div class="space-y-2">
                        <label for="supplier-name" class="block text-sm font-medium text-purple-700">
                            Tên nhà cung cấp <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="supplier-name"
                            name="supplier-name"
                            placeholder="Nhập tên nhà cung cấp"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Đại diện -->
                        <div class="space-y-2">
                            <label for="representative" class="block text-sm font-medium text-purple-700">
                                Người đại diện <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="representative"
                                name="representative"

                                placeholder="Nhập tên người đại diện"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                            />
                        </div>

                        <!-- Số điện thoại -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-purple-700">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    placeholder="Nhập số điện thoại"
                                    class="w-full pl-12 px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-purple-700">
                                Email
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Nhập địa chỉ email"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                            />
                        </div>

                        <!-- Mã số thuế -->
                        <div class="space-y-2">
                            <label for="tax-code" class="block text-sm font-medium text-purple-700">
                                Mã số thuế
                            </label>
                            <input
                                type="text"
                                id="tax-code"
                                name="tax-code"
                                placeholder="Nhập mã số thuế"
                                class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                            />
                        </div>
                    </div>

                    <!-- Row 4: Địa chỉ -->
                    <div class="space-y-2">
                        <label for="address" class="block text-sm font-medium text-purple-700">
                            Địa chỉ <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="Nhập địa chỉ đầy đủ"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all duration-200"
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button
                            type="reset"
                            class="px-6 py-3 text-purple-700 bg-white border border-purple-300 rounded-lg hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                        >
                            Nhập lại
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-3 text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300 transition-all duration-200 shadow-md hover:shadow-lg"
                        >
                            <span class="font-medium">Thêm nhà cung cấp</span>
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
import { Link, usePage } from "@inertiajs/vue3";
import Waiting from "../../components/Waiting.vue";

const props = defineProps({
    suppliers: {
        type: Object,
        default: () => {},
    },
});
</script>