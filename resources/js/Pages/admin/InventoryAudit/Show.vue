<template>
    <!-- Modal xem ảnh lớn - Giao diện đẹp, responsive, có tải tất cả -->
    <div v-if="showModal && audit.images && audit.images.length" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent">
        <div @click.self="closeModal" class="absolute inset-0 z-40 cursor-pointer" style="background:rgba(0,0,0,0.01);"></div>
        <div class="relative w-full max-w-3xl mx-2 bg-white rounded-2xl shadow-2xl p-0 flex flex-col items-center overflow-hidden z-50" style="height:80vh; min-height:400px;">
            <!-- Nút đóng -->
            <button @click="closeModal" class="absolute top-4 right-4 z-10 bg-white bg-opacity-80 hover:bg-opacity-100 text-red-500 rounded-full p-2 shadow transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Ảnh chính -->
            <div class="flex-1 flex items-center justify-center w-full bg-gradient-to-br from-gray-50 to-gray-100 p-4 min-h-0">
                <img :src="audit.images[modalIndex]?.url" class="max-h-full max-w-full object-contain rounded-xl shadow-lg border border-gray-200 bg-white" :alt="'Ảnh kiểm kho ' + (modalIndex+1)" style="max-height:56vh;" />
            </div>
            <!-- Thanh điều khiển và thumbnail luôn cố định dưới -->
            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-2 px-4 py-3 bg-white border-t border-gray-100 flex-shrink-0">
                <div class="flex gap-2 items-center flex-wrap">
                    <button @click="downloadCurrentImage" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Tải ảnh này
                    </button>
                    <button @click="downloadAllImages" class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Tải tất cả
                    </button>
                    <span class="text-xs text-gray-500 ml-2">({{ modalIndex+1 }}/{{ audit.images.length }})</span>
                </div>
                <!-- Thanh thumbnail -->
                <div class="flex gap-1 overflow-x-auto py-1 w-full md:w-auto justify-center">
                    <button v-for="(img, idx) in audit.images" :key="idx" @click="modalIndex = idx" :class="['w-12 h-12 border-2 rounded-lg overflow-hidden flex-shrink-0 transition', idx === modalIndex ? 'border-indigo-500 ring-2 ring-indigo-300' : 'border-gray-200 hover:border-gray-300']">
                        <img :src="img.url" class="object-cover w-full h-full" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem ảnh lớn -->
    <div class="no-print">
        <AppLayout>

            <div class="bg-gradient-to-br from-indigo-50 to-white min-h-screen p-4 md:p-8">
                <!-- Điều hướng quay về -->
                <div class="mb-4">
                    <Link :href="route('admin.inventory-audit.index')"
                        class="inline-flex items-center px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Quay về danh sách
                    </Link>
                </div>
                <!-- Header Card -->
                <div
                    class="bg-white rounded-2xl shadow-lg p-4 mb-4 border border-indigo-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-full">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17v-2a4 4 0 014-4h3m4 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <h5 class="text-lg font-bold text-indigo-800">Phiếu Kiểm Kho</h5>
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">Mã phiếu: <span class="text-indigo-700">{{
                            audit.code }}</span></span>
                </div>

                <!-- Thông tin tổng quan phiếu kiểm kho -->
                <div class="bg-white rounded-2xl shadow p-4 mb-4 border border-indigo-100 text-sm">
                    <!-- Ảnh kiểm kho -->
                    <div v-if="audit.images && audit.images.length" class="mb-4">
                        <div class="font-semibold text-gray-700 mb-2 flex items-center justify-between">
                            <span>Ảnh kiểm kho đã đăng: ({{ audit.images.length }} ảnh)</span>
                            <button @click="downloadAllImages" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Tải tất cả
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <div v-for="(img, idx) in audit.images" :key="idx" class="relative w-28 h-28 border rounded overflow-hidden group cursor-pointer" @click="showImageModal(idx)">
                                <img :src="img.url" class="object-cover w-full h-full" :alt="'Ảnh kiểm kho ' + (idx+1)" />
                            </div>
                        </div>
                    </div>
                    <div v-else class="mb-4 text-xs text-gray-400">Không có ảnh kiểm kho</div>
                    <div class="flex flex-wrap gap-2 mb-2 items-center">
                        <span class="font-semibold text-gray-700">Khu vực:</span>
                        <span v-for="zone in audit.audited_zones" :key="zone"
                            class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">{{
                            zone }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                        <div><span class="font-semibold">Người tạo:</span> <span class="ml-1">{{ audit.user?.name || '-'
                                }}</span></div>
                        <div>
                            <span class="font-semibold">Ngày kiểm kho:</span>
                            <span class="ml-1">{{ audit.audit_date ? (new
                                Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Ngày tạo phiếu:</span>
                            <span class="ml-1">{{ audit.created_at ? (new
                                Date(audit.created_at).toLocaleDateString('vi-VN')) : '-' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Trạng thái:</span>
                            <span v-if="audit.status === 'completed'" class="ml-1 text-green-700 font-bold">Không chênh
                                lệch</span>
                            <span v-else class="ml-1 text-red-700 font-bold">Có chênh lệch</span>
                        </div>
                        <div v-if="audit.status !== 'completed'" >
                            <span class="font-semibold">Đồng bộ:</span>
                            <span v-if="audit.is_adjusted == 1" class="ml-1 text-green-700 font-bold">Đã đồng bộ</span>
                            <span v-else class="ml-1 text-red-700 font-bold">Chưa đồng bộ</span>
                        </div>
                        <div v-if="audit.is_adjusted != 1 && audit.status !== 'completed'" v-can="'admin.inventory-audit.update'"
                            class="flex items-center gap-2">
                            <span class="font-semibold">Đồng bộ dữ liệu?</span>
                            <div v-if="audit.is_adjusted == 0">
                                <button
                                    class="px-2 py-0.5 mr-3 rounded bg-green-600 hover:bg-green-700 text-white font-semibold text-xs"
                                    @click="showSyncConfirm = true">
                                    Đồng bộ
                                </button>
                                <button
                                    class="px-2 py-0.5 rounded bg-red-600 hover:bg-red-700 text-white font-semibold text-xs"
                                    @click="showRejectConfirm = true">
                                    Từ chối
                                </button>
                            </div>
                            <div v-else>
                                <span class="text-red-600 font-semibold">Đã từ chối</span>
                            </div>
                        </div>
                        <div v-if="audit.is_adjusted != 0"
                            class="flex items-center gap-2">
                            <span class="font-semibold">Người thực hiện đồng bộ: </span>
                            <div>
                               <span class="ml-1">{{ audit.approved_by?.name}}</span>
                            </div>
                        </div>
                        <div v-if="audit.is_adjusted != 0"
                            class="flex items-center gap-2">
                            <span class="font-semibold">Thời gian thực hiện: </span>
                            <div >
                               <span class="ml-1">{{ audit.adjusted_at}}</span>
                            </div>
                        </div>
                        <div class="md:col-span-2"><span class="font-semibold">Ghi chú:</span> <span class="ml-1">{{
                                audit.notes }}</span></div>
                    </div>
                </div>
                <!-- Danh sách Sản phẩm cần kiểm kê -->
                <div class="bg-white rounded-2xl shadow p-4 mb-4 border border-indigo-100">
                    <div class="flex justify-between items-center mb-2">
                        <h6 class="text-base font-bold text-indigo-800">Danh sách Sản phẩm</h6>
                        <div class="flex gap-1">
                            <button
                                class="px-2 py-0.5 rounded bg-blue-600 text-white hover:bg-blue-700 font-semibold text-xs"
                                @click="handlePrint">In phiếu</button>
                            <button
                                class="px-2 py-0.5 rounded bg-green-600 text-white hover:bg-green-700 font-semibold text-xs"
                                @click="exportToExcel">Xuất Excel</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto rounded border border-indigo-100" style="max-height: 60vh;">
                        <table class="min-w-full divide-y divide-indigo-100 text-xs">
                            <thead class="bg-indigo-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">#</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Khu vực</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Chi tiết khu vực</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Mã hàng</th>
                                    <th class="px-2 py-1 text-left font-bold text-indigo-700">Tên hàng</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Đơn vị</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Tồn kho</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">Thực tế</th>
                                    <th class="px-2 py-1 text-center font-bold text-indigo-700">SL chênh</th>
                                    <th class="px-2 py-1 text-left font-bold text-indigo-700">Ghi chú chênh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-indigo-50">
                                <tr v-for="(product, index) in filteredProducts" :key="product.id"
                                    class="hover:bg-indigo-50 transition">
                                    <td class="px-2 py-1 text-center">{{ index + 1 }}</td>
                                    <td class="px-2 py-1 text-center">{{ product.zone }}</td>
                                    <td class="px-2 py-1 text-center">{{ product.custom_location_name }}</td>
                                    <td class="px-2 py-1 text-center font-medium">{{ product.code }}</td>
                                    <td class="px-2 py-1">
                                        {{ product.name_product }}
                                        <template
                                            v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                                            <div class="text-[10px] text-gray-500 mt-0.5">
                                                <span v-for="(value, key) in product.variant_attributes" :key="key"
                                                    class="mr-1">
                                                    <span class="font-medium">{{ value.attribute.name }}:</span> {{
                                                    value.name }}
                                                </span>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-2 py-1 text-center">{{ product.unit }}</td>
                                    <td class="px-2 py-1 text-center">{{ product.quantity_on_hand }}</td>
                                    <td class="px-2 py-1 text-center">{{ auditData.items[index].actual_quantity }}</td>
                                    <td class="px-2 py-1 text-center font-bold"
                                        :class="{ 'text-red-600': (auditData.items[index].actual_quantity - product.quantity_on_hand) !== 0 }">
                                        {{ auditData.items[index].actual_quantity - product.quantity_on_hand || 0 }}
                                    </td>
                                    <td class="px-2 py-1">{{ auditData.items[index].discrepancy_reason }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
    <!-- ...existing code... -->
    <div style="display: none;" class="print z-50 flex items-center justify-cente">
        <div id="print-form-content">
            <!-- Nội dung form in mẫu -->
            <div class="header" style="text-align:center;">
                <h1 style="margin:0;font-size:20px;text-transform:uppercase;">CÔNG TY TNHH SUVAN</h1>
                <p style="margin:5px 0;font-size:12px;">Địa chỉ: Trung tâm quận 1, TP. HCM</p>
                <p style="margin:5px 0;font-size:12px;">Số điện thoại: 0123 456 789</p>
                <h1 style="margin:0;font-size:20px;text-transform:uppercase;">PHIẾU KIỂM KHO
                </h1>
            </div>
            <div class="ticket-info" style="display:flex;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:12px;">Số phiếu: {{ audit.id }} | Ngày: {{ audit.audit_date ? (new
                    Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-' }}</p>
                <p style="font-size:12px;">Kho kiểm: {{ audit.audited_zones?.join(', ') }}</p>
            </div>
            <div class="person-info" style="margin-bottom:20px;">
                <p><strong>Thông tin kiểm kho:</strong></p>
                <p>Người kiểm kho: {{ audit.user?.name }}</p>
                <p>Lý do kiểm kho: {{ audit.notes }}</p>
            </div>
            <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã hàng</th>
                        <th>Tên hàng hóa</th>
                        <th>Đơn vị</th>
                        <th>SL kho</th>
                        <th>SL kiểm</th>
                        <th>Chênh lệch</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, idx) in filteredProducts" :key="product.id">
                        <td>{{ idx + 1 }}</td>
                        <td>{{ product.code }}</td>
                        <td>
                            {{ product.name_product }}
                            <template
                                v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                                <div class="text-xs text-gray-500 mt-1">
                                    <span v-for="(value, key) in product.variant_attributes" :key="key" class="mr-2">
                                        <span class="font-medium">{{ value.attribute.name }}:</span> {{
                                            value.name }}
                                    </span>
                                </div>
                            </template>
                        </td>
                        <td>{{ product.unit }}</td>
                        <td>{{ product.quantity_on_hand }}</td>
                        <td>{{ auditData.items[idx].actual_quantity }}</td>
                        <td>{{ auditData.items[idx].actual_quantity - product.quantity_on_hand }}</td>
                        <td>{{ auditData.items[idx].discrepancy_reason }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="total" style="text-align:right;margin-bottom:20px;font-size:12px;">
                <p><strong>Tổng số lượng thực tế:</strong> {{auditData.items.reduce((sum, i) => sum +
                    (i.actual_quantity || 0), 0)}}</p>
                <p><strong>Tổng chênh lệch:</strong> {{auditData.items.reduce((sum, i, idx) => sum +
                    ((i.actual_quantity || 0) - (filteredProducts[idx]?.quantity_on_hand || 0)), 0)}}
                </p>
            </div>
            <!-- <div class="signature" style="display:flex;justify-content:space-between;font-size:12px;">
                <div>
                    <p>Người kiểm kho</p>
                    <p>____________________</p>
                </div>
                <div>
                    <p>Thủ kho</p>
                    <p>____________________</p>
                </div>
                <div>
                    <p>Kế toán</p>
                    <p>____________________</p>
                </div> 
                <div>
                    <p>Quản lý kho</p>
                    <p>____________________</p>
                </div>
            </div> -->
            <p style="text-align:center;font-size:12px;">Ngày {{ new Date().getDate() }} tháng {{ new
                Date().getMonth() + 1 }} năm {{ new Date().getFullYear() }}</p>
        </div>
    </div>
    <Teleport to="body">
        <div v-if="showSyncConfirm" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận đồng bộ phiếu kiểm kho</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Bạn có chắc chắn muốn đồng bộ phiếu kiểm kho này? Hãy kiểm tra lại thông tin trước khi đồng bộ.<br />Vui lòng xác nhận.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="showSyncConfirm = false"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                        Hủy
                    </button>
                    <button @click="confirmSync"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
        <div v-if="showRejectConfirm" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận từ chối phiếu kiểm kho</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Bạn có chắc chắn muốn từ chối phiếu kiểm kho này? Hãy kiểm tra lại thông tin trước khi từ chối.<br />Vui lòng xác nhận.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="showRejectConfirm = false"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                        Hủy
                    </button>
                    <button @click="confirmReject"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
    <ToastClient ref="toastRef" />
</template>
<script setup>

import { reactive, ref, computed, watch } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Waiting from '../../components/Waiting.vue';
import ToastClient from '../../components/ToastClient.vue';
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { inject } from "vue";
import axios from 'axios';

// Modal xem ảnh lớn
const showModal = ref(false);
const modalIndex = ref(0);
const showImageModal = (idx) => {
  modalIndex.value = idx;
  showModal.value = true;
};
const closeModal = () => {
  showModal.value = false;
};

// Tải ảnh hiện tại trong modal
const downloadCurrentImage = async () => {
  const img = audit.images[modalIndex.value];
  await downloadImage(img, modalIndex.value);
};
// Tải tất cả ảnh
const downloadAllImages = async () => {
  try {
    for (let i = 0; i < audit.images.length; i++) {
      await downloadImage(audit.images[i], i);
      // Đợi 300ms giữa các lần tải để tránh browser block
      await new Promise(r => setTimeout(r, 300));
    }
    toastSuccess('Đã tải tất cả ảnh thành công!');
  } catch (e) {
    toastError('Lỗi khi tải tất cả ảnh!');
  }
};
// Tải ảnh bất kỳ
const downloadImage = async (img, idx) => {
  try {
    const url = img.url;
    const response = await fetch(url);
    const blob = await response.blob();
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `kiem-kho-${audit.id}-anh-${idx+1}.jpg`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(link.href);
    toastSuccess('Đã tải ảnh thành công!');
  } catch (e) {
    toastError('Lỗi khi tải ảnh!');
  }
};

const page = usePage();
const audit = page.props.audit;
const toastRef = ref(); // gán ref để dùng expose

function toastSuccess(message) {
    toastRef.value?.triggerToast(message, 'success');
}
function toastError(message) {
    toastRef.value?.triggerToast(message, 'error');
}

// Xuất Excel
const exportToExcel = () => {
    const infoRows = [
        ['Mã phiếu:', audit.id],
        ['Khu vực:', (audit.audited_zones || []).join(', ')],
        ['Người tạo:', audit.user?.name || '-'],
        ['Ngày kiểm kho:', audit.audit_date ? (new Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-'],
        ['Ngày tạo phiếu:', audit.created_at ? (new Date(audit.created_at).toLocaleDateString('vi-VN')) : '-'],
        ['Trạng thái:', audit.status === 'completed' ? 'Không chênh lệch' : 'Có chênh lệch'],
        ['Ghi chú:', audit.notes || '-'],
        [],
        ['DANH SÁCH SẢN PHẨM'],
        ['STT', 'Khu vực', 'Mã hàng', 'Tên hàng', 'Đơn vị', 'Tồn kho', 'Thực tế', 'SL chênh', 'Ghi chú chênh'],
    ];
    const dataRows = filteredProducts.value.map((product, idx) => [
        idx + 1,
        product.zone,
        product.code,
        product.name_product,
        product.unit,
        product.quantity_on_hand,
        auditData.value.items[idx]?.actual_quantity || '',
        (auditData.value.items[idx]?.actual_quantity || 0) - (product.quantity_on_hand || 0),
        auditData.value.items[idx]?.discrepancy_reason || ''
    ]);
    const ws = XLSX.utils.aoa_to_sheet([...infoRows, ...dataRows]);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'KiemKho');
    XLSX.writeFile(wb, `phieu-kiem-kho-${audit.id}.xlsx`);
    toastSuccess("Đã tải file Excel thành công.");
};

// Xuất PDF
const exportToPDF = () => {
    const doc = new jsPDF();
    doc.setFont('Roboto');
    doc.setFontSize(14);
    doc.text('PHIẾU KIỂM KHO', 105, 15, { align: 'center' });

    // Chi tiết
    doc.setFontSize(10);
    let y = 25;
    doc.text(`Mã phiếu: ${audit.id}`, 14, y);
    doc.text(`Khu vực: ${(audit.audited_zones || []).join(', ')}`, 80, y);
    y += 6;
    doc.text(`Người tạo: ${audit.user?.name || '-'}`, 14, y);
    doc.text(`Ngày kiểm kho: ${audit.audit_date ? (new Date(audit.audit_date).toLocaleDateString('vi-VN')) : '-'}`, 80, y);
    y += 6;
    doc.text(`Ngày tạo phiếu: ${audit.created_at ? (new Date(audit.created_at).toLocaleDateString('vi-VN')) : '-'}`, 14, y);
    doc.text(`Trạng thái: ${audit.status === 'completed' ? 'Không chênh lệch' : 'Có chênh lệch'}`, 80, y);
    y += 6;
    doc.text(`Ghi chú: ${audit.notes || '-'}`, 14, y);
    y += 8;

    autoTable(doc, {
        head: [[
            'STT', 'Khu vực', 'Mã hàng', 'Tên hàng', 'Đơn vị', 'Tồn kho', 'Thực tế', 'SL chênh', 'Ghi chú chênh'
        ]],
        body: filteredProducts.value.map((product, idx) => [
            idx + 1,
            product.zone,
            product.code,
            product.name_product,
            product.unit,
            product.quantity_on_hand,
            auditData.value.items[idx]?.actual_quantity || '',
            (auditData.value.items[idx]?.actual_quantity || 0) - (product.quantity_on_hand || 0),
            auditData.value.items[idx]?.discrepancy_reason || ''
        ]),
        startY: y,
        styles: { fontSize: 10 },
        headStyles: { fillColor: [79, 70, 229] },
    });

    doc.save(`phieu-kiem-kho-${audit.id}.pdf`);
};

const auditData = ref({
    items: audit.items.map(item => ({
        actual_quantity: item.actual_quantity,
        discrepancy_reason: item.discrepancy_reason,
    }))
});
console.log(audit);

const fetchAudit = async () => {
    try {
        const response = await axios.get(`/api/inventory-audit/${audit.id}`);
        const newAudit = response.data.audit;

        audit.items = newAudit.items;
        audit.status = newAudit.status;
        audit.is_adjusted = newAudit.is_adjusted;
        audit.notes = newAudit.notes;
        audit.audited_zones = newAudit.audited_zones;
        audit.approved_by = newAudit.approved_by
        audit.adjusted_at = newAudit.adjusted_at

        auditData.value.items = newAudit.items.map(item => ({
            actual_quantity: item.actual_quantity,
            discrepancy_reason: item.discrepancy_reason,
        }));

        console.log('Cập nhật lại phiếu kiểm kho thành công!');
    } catch (e) {
        console.error('Lỗi khi fetch audit:', e);
        toastError('Không thể tải lại thông tin phiếu kiểm kho.');
    }
};

// Tạo danh sách sản phẩm từ audit
const filteredProducts = computed(() =>
    audit.items.map((item, idx) => ({
        id: item.id,
        zone: item.zone,
        custom_location_name: item.custom_location_name,
        code: item.product_variant?.product?.code || '',
        name_product: item.product_variant?.product?.name || '',
        unit: item.unit || item.product_variant?.product?.unit || '',
        quantity_on_hand: item.expected_quantity,
        variant_attributes: item.product_variant?.attributes || {},
    }))
);

// Gọi đồng bộ kiểm kho
const handleSync = async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.patch('/api/inventory/update', {
            audit_id: audit.id
        }, {
            withCredentials: true
        });

        await fetchAudit();
        toastSuccess('Đồng bộ thành công!');
    } catch (e) {
        console.error(e);
        toastError('Đồng bộ thất bại!');
        await fetchAudit();
        toastSuccess('Đã tải lại thông tin phiếu kiểm kho.');
    }
};

// Từ chối phiếu kiểm kho
const handleReject = async () => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.patch('/api/inventory-audit/update', {
            audit_id: audit.id,
            reject: true
        }, {
            withCredentials: true
        });

        await fetchAudit();
        toastSuccess('Đã từ chối phiếu kiểm kho thành công!');
    } catch (e) {
        console.error(e);
        toastError('Từ chối thất bại!');
        await fetchAudit();
        toastSuccess('Đã tải lại thông tin phiếu kiểm kho.');
    }
};

// In phiếu
const handlePrint = () => {
    window.print();
};

const showSyncConfirm = ref(false);
const showRejectConfirm = ref(false);

const confirmSync = async () => {
  showSyncConfirm.value = false;
  await handleSync();
};
const confirmReject = async () => {
  showRejectConfirm.value = false;
  await handleReject();
};
</script>

<style>
@media print {
    .print {
        display: block !important;
    }

    .no-print {
        display: none !important;
    }
}
</style>