<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div
                class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow-sm"
            >
                <div>
                    <h1 class="text-xl font-semibold text-indigo-800">
                        Nhật ký đơn nhập hàng
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Mã đơn:
                        <span class="font-medium">{{ purchase?.code }}</span>
                    </p>
                </div>
                <Waiting
                    route-name="admin.purchases.index"
                    :route-params="{}"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-md hover:bg-indigo-100"
                >
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </Waiting>
            </div>

            <!-- Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-history mr-2 text-indigo-600"></i>
                    Lịch sử thay đổi
                </h2>

                <div v-if="logs && logs.length > 0" class="relative">
                    <!-- Timeline line -->
                    <div
                        class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"
                    ></div>

                    <div
                        v-for="log in logs"
                        :key="log.id"
                        class="relative flex items-start mb-8 last:mb-0"
                    >
                        <!-- Timeline dot -->
                        <div
                            :class="getTimelineDotClass(log.type)"
                            class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center z-10 border-4 border-white shadow"
                        >
                            <i
                                :class="getTimelineIcon(log.type)"
                                class="text-white text-sm"
                            ></i>
                        </div>

                        <!-- Content -->
                        <div class="ml-6 flex-1">
                            <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <h3 class="font-semibold text-gray-900">
                                        {{ getLogTypeText(log.type) }}
                                    </h3>
                                    <span class="text-xs text-gray-500">
                                        {{ formatDateTime(log.created_at) }}
                                    </span>
                                </div>

                                <p class="text-gray-700 text-sm mb-2">
                                    <span v-if="log.type != 6">
                                        <template v-for="(part, idx) in log.detail.split('.')" :key="idx">
                                            {{ part }}<span v-if="idx < log.detail.split('.').length - 1">.<br /></span>
                                        </template>
                                    </span>
                                    <span v-else>
                                        Phiếu nhập:
                                        <Link
                                            :href="
                                                route('admin.receiving.index')
                                            "
                                            class="mr-2 px-2 py-1 cursor-pointer hover:bg-indigo-100 rounded text-indigo-600 bg-indigo-50"
                                            :data="{
                                                code: log.detail,
                                            }"
                                        >
                                            {{ log.detail }}
                                        </Link>
                                    </span>
                                </p>

                                <div
                                    class="flex items-center text-gray-500"
                                >
                                    <i class="fas fa-user mr-1"></i> Người thao
                                    tác: <span class="ml-1 font-semibold"> {{ getUserName(log) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center py-12">
                    <i
                        class="fas fa-clipboard-list text-gray-300 text-4xl mb-4"
                    ></i>
                    <p class="text-gray-500">
                        Chưa có nhật ký nào cho đơn hàng này
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Waiting from "../../components/Waiting.vue";
import { Link } from "@inertiajs/vue3";

const { logs, purchase, purchaseId } = defineProps({
    logs: { default: () => [] },
    purchase: { default: () => ({}) },
    purchaseId: { default: null },
});

// Format date time
const formatDateTime = (dateString) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${day}/${month}/${year} ${hours}:${minutes}`;
};

// Get log type text
const getLogTypeText = (type) => {
    const typeMap = {
        1: "Tạo đơn hàng",
        2: "Phê duyệt đơn hàng",
        3: "Từ chối đơn hàng",
        4: "Cập nhật đơn hàng",
        5: "Kết thúc đơn hàng",
        6: "Tạo phiếu nhập kho",
    };
    return typeMap[type] || "Hoạt động khác";
};

// Get timeline dot class
const getTimelineDotClass = (type) => {
    const classMap = {
        1: "bg-blue-500", // Tạo đơn
        2: "bg-green-500", // Phê duyệt
        3: "bg-red-500", // Từ chối
        4: "bg-yellow-500", // Cập nhật
        5: "bg-gray-500", // Kết thúc
        6: "bg-purple-500", // Tạo phiếu nhập
    };
    return classMap[type] || "bg-gray-400";
};

// Get timeline icon
const getTimelineIcon = (type) => {
    const iconMap = {
        1: "fas fa-plus", // Tạo đơn
        2: "fas fa-check", // Phê duyệt
        3: "fas fa-times", // Từ chối
        4: "fas fa-edit", // Cập nhật
        5: "fas fa-flag", // Kết thúc
        6: "fas fa-warehouse", // Tạo phiếu nhập
    };
    return iconMap[type] || "fas fa-circle";
};

// Get user name from log
const getUserName = (log) => {
    if (log.created_by && log.created_by) return log.created_by.name;
    if (log.updated_by && log.updated_by) return log.updated_by.name;
    if (log.approved_by && log.approved_by) return log.approved_by.name;
    if (log.refused_by && log.refused_by) return log.refused_by.name;
    if (log.end_by && log.end_by) return log.end_by.name;
    if (log.create_grn_by && log.create_grn_by) return log.create_grn_by.name;
    return "Hệ thống";
};
</script>
