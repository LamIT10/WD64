<template>
  <AppLayout>
    <div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-6 md:p-8">
      <!-- Header Card -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
        <h5 class="text-xl font-bold text-indigo-800 tracking-tight">Tạo Phiếu Kiểm Kê</h5>
      </div>

      <!-- Form Phiếu Kiểm Kê -->
      <form @submit.prevent="submitForm">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="grid grid-cols-1 gap-6">
            <div class="flex justify-center">
              <div class="my-4">
                <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Khu vực kiểm kê</label>
                <div class="space-y-2 max-w-[22rem] mx-auto">
                  <div
                    v-for="(row, rowIndex) in grid"
                    :key="rowIndex"
                    class="flex flex-row items-center justify-center space-x-2"
                  >
                    <div
                      v-for="(cell, colIndex) in row"
                      :key="`${rowIndex}-${colIndex}`"
                      class="p-2 text-center text-sm rounded-lg cursor-pointer transition-all duration-200 min-w-[2.5rem] min-h-[2.5rem] flex items-center justify-center"
                      :class="{
                        'bg-indigo-600 text-white font-medium': cell.id && auditData.warehouse_zone_id === cell.id,
                        'bg-gray-200 text-gray-500 cursor-not-allowed': !cell.id,
                        'bg-gray-50 text-gray-800 hover:bg-indigo-100': cell.id && auditData.warehouse_zone_id !== cell.id
                      }"
                      @click="cell.id && selectZone(cell.id)"
                    >
                      {{ cell.label }}
                    </div>
                  </div>
                </div>
                <p v-if="!auditData.warehouse_zone_id" class="text-red-500 text-xs mt-2 text-center">Vui lòng chọn khu vực</p>
              </div>
            </div>
                <p class="text-sm text-gray-600 mt-2 text-left ">Thời gian tạo phiếu: {{ creationTime }}</p>
          </div>

          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
            <textarea
              v-model="auditData.notes"
              rows="4"
              class="block w-full border border-gray-200 rounded-lg py-2 px-3 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-sm resize-y transition-all duration-200"
              placeholder="Nhập ghi chú nếu có"
            ></textarea>
          </div>
        </div>

        <!-- Danh sách Sản phẩm cần kiểm kê -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <h6 class="text-lg font-semibold text-indigo-800 mb-4">Danh sách Sản phẩm</h6>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-indigo-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tên sản phẩm</th>
                  <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Mã sản phẩm</th>
                  <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Số lượng hệ thống</th>
                  <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Số lượng thực tế</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Ghi chú</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="(product, index) in products" :key="product.id" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="px-6 py-4 text-sm text-gray-900">{{ product.name }}</td>
                  <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">{{ product.item_code }}</td>
                  <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.expected_quantity }}</td>
                  <td class="px-6 py-4 text-center">
                    <input
                      type="number"
                      v-model="auditData.items[index].actual_quantity"
                      class="w-24 border border-gray-200 rounded-lg py-1.5 px-2 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-sm"
                      min="0"
                      required
                    />
                  </td>
                  <td class="px-6 py-4">
                    <input
                      type="text"
                      v-model="auditData.items[index].discrepancy_reason"
                      class="w-full border border-gray-200 rounded-lg py-1.5 px-2 bg-gray-50 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-sm"
                      placeholder="Lý do chênh lệch"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 hover:-translate-y-[1px] focus:ring-4 focus:ring-indigo-200 transition-all duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed"
            :disabled="!auditData.warehouse_zone_id"
          >
            Lưu Phiếu Kiểm Kê
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

// Fake Data
const warehouseZones = [
  { id: 1, name: 'Kho A - Điện tử', label: 'A1' },
  { id: 2, name: 'Kho B - Thời trang', label: 'B2' },
  { id: 3, name: 'Kho C - Cao cấp', label: 'C3' }
];

const products = [
  { id: 1, name: 'Giấy A4 Double A', item_code: 'A4-1234', expected_quantity: 150 },
  { id: 2, name: 'Giấy A3 Idea', item_code: 'A3-5678', expected_quantity: 100 },
  { id: 3, name: 'Giấy in màu A4', item_code: 'A4-9876', expected_quantity: 50 },
  { id: 4, name: 'Bàn phím cơ Keychron K2', item_code: 'BMT-1234', expected_quantity: 75 },
  { id: 5, name: 'Tai nghe Sony WH-1000XM5', item_code: 'THS-4567', expected_quantity: 20 },
  { id: 6, name: 'Máy in HP LaserJet Pro', item_code: 'PRD-6543', expected_quantity: 30 }
];

// Create 4x6 grid for warehouse zones (rows: A, B, C, D; columns: 1, 2, 3, 4, 5, 6)
const grid = ref([
  [
    { id: 1, label: 'A1' },
    { id: null, label: 'A2' },
    { id: null, label: 'A3' },
    { id: null, label: 'A4' },
    { id: null, label: 'A5' },
    { id: null, label: 'A6' }
  ],
  [
    { id: null, label: 'B1' },
    { id: 2, label: 'B2' },
    { id: null, label: 'B3' },
    { id: null, label: 'B4' },
    { id: null, label: 'B5' },
    { id: null, label: 'B6' }
  ],
  [
    { id: null, label: 'C1' },
    { id: null, label: 'C2' },
    { id: 3, label: 'C3' },
    { id: null, label: 'C4' },
    { id: null, label: 'C5' },
    { id: null, label: 'C6' }
  ],
  [
    { id: null, label: 'D1' },
    { id: null, label: 'D2' },
    { id: null, label: 'D3' },
    { id: null, label: 'D4' },
    { id: null, label: 'D5' },
    { id: null, label: 'D6' }
  ]
]);

// Get creation timestamp in Vietnam time
const creationTime = (() => {
  const date = new Date();
  const vietnamTime = new Date(date.toLocaleString('en-US', { timeZone: 'Asia/Ho_Chi_Minh' }));
  const day = String(vietnamTime.getDate()).padStart(2, '0');
  const month = String(vietnamTime.getMonth() + 1).padStart(2, '0');
  const year = vietnamTime.getFullYear();
  const hours = String(vietnamTime.getHours()).padStart(2, '0');
  const minutes = String(vietnamTime.getMinutes()).padStart(2, '0');
  return `${day}/${month}/${year}, ${hours}:${minutes}`;
})();

// Dữ liệu phiếu kiểm kê
const auditData = ref({
  warehouse_zone_id: 2, // Pre-select "Kho B - Thời trang" (B2)
  notes: '',
  items: products.map(product => ({
    product_variant_id: product.id,
    actual_quantity: 0,  // Mặc định số lượng thực tế là 0
    discrepancy_reason: '' // Lý do chênh lệch
  }))
});

// Function to select a zone
const selectZone = (zoneId) => {
  auditData.value.warehouse_zone_id = zoneId;
};

// Hàm submit form
const submitForm = () => {
  if (!auditData.warehouse_zone_id) {
    alert('Vui lòng chọn khu vực kiểm kê!');
    return;
  }
  router.post('/api/inventory-audits', {
    ...auditData.value,
  }).then(response => {
    alert('Phiếu kiểm kê đã được lưu thành công!');
  }).catch(error => {
    console.error(error);
    alert('Đã xảy ra lỗi, vui lòng thử lại.');
  });
};
</script>