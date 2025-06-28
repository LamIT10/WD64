<template>
  <AppLayout>
    <div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-6 md:p-8">
      <!-- Stats Cards -->
      <InventoryStatsCards/>
      <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 gap-6">
          <div class="flex justify-center">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Chọn khu vực xem sản
                phẩm</label>
              <div class="flex flex-wrap gap-4 justify-center">
                <button v-for="zone in zones" :key="zone" type="button"
                  :class="['px-4 py-2 rounded-lg border', selectedZones.includes(zone) ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-indigo-50 text-indigo-700 border-indigo-200']"
                  @click="toggleZone(zone)">
                  {{ zone }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Danh sách Sản phẩm cần kiểm kê -->
      <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between mb-4">
          <div class="flex gap-x-10 items-start space-y-1">
            <h5 class="text-xl  font-bold text-indigo-800 tracking-tight">Danh sách sản phẩm trong kho</h5>

          </div>
          <div class="flex items-center space-x-4">
            <div class="relative w-76">
              <input v-model="searchQuery" type="text" placeholder="Nhập mã hoặc tên sản phẩm..."
                class="border border-indigo-200 rounded-lg py-2 px-4 bg-indigo-50 text-sm w-full focus:outline-none focus:ring-2 focus:ring-indigo-300 transition" />
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-indigo-400">
                <i class="fa fa-search"></i>
              </span>
            </div>
            <div @click="exportSampleExcel"
              class="px-4 py-2 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 flex items-center gap-2"
              type="button">
              <i class="fa fa-download icon-btn"></i> Tải file
            </div>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-50">
              <tr>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">#
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Khu
                  vực
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Mã
                  hàng</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tên
                  hàng</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Đơn
                  vị</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Tồn
                  kho</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Đã
                  đặt trước</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-indigo-700 uppercase tracking-wider">Đang
                  vận chuyển</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
              <tr v-for="(product, index) in filteredProducts" :key="product.id"
                class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ index + 1 }}</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.zone }}</td>
                <td class="px-6 py-4 text-center text-sm font-medium text-gray-600">{{ product.code }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ product.name_product }}
                  <template v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                    <div class="text-xs text-gray-500 mt-1">
                      <span v-for="(value, key) in product.variant_attributes" :key="key" class="mr-2">
                        <span class="font-medium">{{ value.attribute }}:</span> {{ value.value }}
                      </span>
                    </div>
                  </template>
                </td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.unit }}</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.quantity_on_hand }}</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.quantity_reserved }}</td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">{{ product.quantity_in_transit }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { reactive, ref, watch, computed } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import * as XLSX from 'xlsx';
import InventoryStatsCards from '../../components/InventoryStatsCards.vue';

const filterZone = ref("");
const searchQuery = ref("");

const page = usePage();
const zones = page.props.zones || [];
const cardData = page.props.cardData || [];
const products = reactive([]);
const selectedZones = ref([]);
const today = new Date().toISOString().split('T')[0];
const auditData = ref({
  audit_date: today,
  notes: '',
  items: []
});
const canSubmit = computed(() =>
  auditData.value.items.length > 0 &&
  auditData.value.items.every(item =>
    item.actual_quantity !== '' &&
    item.actual_quantity !== null &&
    item.actual_quantity !== undefined &&
    !isNaN(Number(item.actual_quantity)) &&
    Number(item.actual_quantity) >= 0
  )
);
const toggleZone = async (zone) => {
  if (selectedZones.value.includes(zone)) {
    selectedZones.value = selectedZones.value.filter(z => z !== zone);
  } else {
    selectedZones.value.push(zone);
  }
  await fetchProductsByZones();
};
const fetchProductsByZones = async () => {
  if (!selectedZones.value.length) {
    products.length = 0;
    auditData.value.items = [];
    return;
  }
  try {
    const params = new URLSearchParams();
    selectedZones.value.forEach(z => params.append('zones[]', z));
    const response = await fetch(`/api/inventory-audit/information-create?${params.toString()}`);
    const data = await response.json();
    products.length = 0;
    products.push(...(data.data || []));
    auditData.value.items = products.map(product => ({
      product_variant_id: product.id_product_variant,
      expected_quantity: product.quantity_on_hand,
      actual_quantity: '',
      discrepancy_reason: '',
      zone: product.zone,
    }));
  } catch (err) {
    products.length = 0;
    auditData.value.items = [];
  }
};
const importInput = ref(null);

const exportSampleExcel = () => {
  const sampleData = [
    ['Khu vực', 'Mã hàng', 'Tên hàng', 'ĐVT', 'Tồn kho', "Đã đặt trước", "Đang vận chuyển"],
    ...filteredProducts.value.map((product, idx) => [
      product.zone,
      product.code,
      product.name_product,
      product.unit,
      product.quantity_on_hand,
      product.quantity_reserved,
      product.quantity_in_transit,
    ])
  ];
  const ws = XLSX.utils.aoa_to_sheet(sampleData);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'MauKiemKho');
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth() + 1).padStart(2, '0');
  const dd = String(today.getDate()).padStart(2, '0');
  const dateStr = `${yyyy}-${mm}-${dd}`;
  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
  import('file-saver').then(({ saveAs }) => {
    saveAs(new Blob([wbout], { type: 'application/octet-stream' }), `mau-kiem-kho-${dateStr}.xlsx`);
  });
};

const filteredProducts = computed(() => {
  let result = products;
  if (filterZone.value) {
    result = result.filter(p => p.zone === filterZone.value);
  }
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    result = result.filter(p =>
      (p.code && p.code.toLowerCase().includes(q)) ||
      (p.name_product && p.name_product.toLowerCase().includes(q))
    );
  }
  return result;
});
</script>