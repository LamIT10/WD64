<template>
  <AppLayout>
    <form @submit.prevent="submitForm">
      <div class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen p-4 md:p-8">
        <!-- Header Card -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100 flex items-center justify-between">
          <h5 class="text-lg font-bold text-indigo-800">Tạo Phiếu Kiểm Kho</h5>
          <span class="text-xs text-gray-400">{{ today }}</span>
        </div>
        <!-- Thông tin nhanh -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100 flex flex-wrap gap-4 items-center">
          <label class="text-sm font-medium text-gray-700">Ngày kiểm kho</label>
          <input type="date" v-model="auditData.audit_date" :max="today"
            class="border border-gray-200 rounded px-3 py-1 bg-gray-50 text-sm" required />
          <label class="text-sm font-medium text-gray-700 ml-4">Ghi chú</label>
          <input v-model="auditData.notes" type="text" placeholder="Nhập ghi chú nếu có"
            class="border border-gray-200 rounded px-3 py-1 bg-gray-50 text-sm flex-1 min-w-[200px]" />
        </div>
        <!-- Upload Ảnh Kiểm Kho -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100">
          <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh kiểm kho (có thể chọn nhiều)</label>
          <input type="file" multiple accept="image/*" @change="handleImageChange" class="mb-2" />
          <div v-if="imagePreviews.length" class="flex flex-wrap gap-3 mt-2">
            <div v-for="(img, idx) in imagePreviews" :key="idx" class="relative w-24 h-24 border rounded overflow-hidden group">
              <img :src="img" class="object-cover w-full h-full" />
              <button type="button" @click="removeImage(idx)" class="absolute top-1 right-1 bg-white bg-opacity-80 rounded-full p-1 text-red-500 hover:bg-opacity-100 transition">
                <i class="fa fa-times"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- Chọn khu vực -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100 flex flex-wrap gap-2 items-center">
          <span class="text-sm font-medium text-gray-700 mr-2">Khu vực:</span>
          <button v-for="zone in zones" :key="zone" type="button"
            :class="['px-3 py-1 rounded border text-sm', selectedZones.includes(zone) ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-indigo-50 text-indigo-700 border-indigo-200']"
            @click="toggleZone(zone)">
            {{ zone }}
          </button>
        </div>
        <!-- Danh sách Sản phẩm cần kiểm kê -->
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-100">
          <div class="flex justify-between items-center mb-2">
            <h6 class="text-base font-semibold text-indigo-800">Danh sách Sản phẩm</h6>
            <div class="flex gap-2">
              <button @click="exportSampleExcel"
                class="px-3 py-1 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 flex items-center gap-1">
                <i class="fa fa-download"></i> Tải mẫu
              </button>
              <input ref="importInput" type="file" accept=".xlsx,.xls" style="display: none"
                @change="handleImportExcel" />
              <button @click="$refs.importInput.click()"
                class="px-3 py-1 border border-indigo-200 bg-indigo-50 text-indigo-700 rounded text-xs font-medium hover:bg-indigo-100 hover:border-indigo-300 flex items-center gap-1">
                <i class="fa fa-sign-in"></i> Nhập file
              </button>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-indigo-50">
                <tr>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">#</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Khu vực</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Chi tiết khu vực</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Mã hàng</th>
                  <th class="px-3 py-2 text-left font-semibold text-indigo-700">Tên hàng</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Đơn vị</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Tồn kho</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">Thực tế</th>
                  <th class="px-3 py-2 text-center font-semibold text-indigo-700">SL chênh</th>
                  <th class="px-3 py-2 text-left font-semibold text-indigo-700">Ghi chú chênh</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-100">
                <tr v-for="(product, index) in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                  <td class="px-3 py-2 text-center">{{ index + 1 }}</td>
                  <td class="px-3 py-2 text-center">{{ product.zone }}</td>
                  <td class="px-3 py-2 text-center">{{ product.custom_location_name }}</td>
                  <td class="px-3 py-2 text-center font-medium">{{ product.code }}</td>
                  <td class="px-3 py-2">
                    {{ product.name_product }}
                    <template v-if="product.variant_attributes && Object.keys(product.variant_attributes).length">
                      <div class="text-xs text-gray-500 mt-1">
                        <span v-for="(value, key) in product.variant_attributes" :key="key" class="mr-2">
                          <span class="font-medium">{{ value.attribute }}:</span> {{ value.value }}
                        </span>
                      </div>
                    </template>
                  </td>
                  <td class="px-3 py-2 text-center">{{ product.unit }}</td>
                  <td class="px-3 py-2 text-center">{{ product.quantity_on_hand }}</td>
                  <td class="px-3 py-2 text-center">
                    <input type="number" v-model="auditData.items[index].actual_quantity"
                      class="w-20 border border-gray-200 rounded px-2 py-1 bg-gray-50 text-sm text-center" min="0"
                      required />
                  </td>
                  <td class="px-3 py-2 text-center">
                    {{ auditData.items[index].actual_quantity - product.quantity_on_hand || 0 }}
                  </td>
                  <td class="px-3 py-2">
                    <input type="text" v-model="auditData.items[index].discrepancy_reason"
                      class="w-full border border-gray-200 rounded px-2 py-1 bg-gray-50 text-sm"
                      placeholder="Lý do chênh lệch" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-4">
          <button type="submit"
            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-200 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
            :disabled="!canSubmit">
            Lưu Phiếu Kiểm Kho
          </button>
        </div>
      </div>
    </form>

  </AppLayout>
</template>
<script setup>
import { reactive, ref, watch, computed } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import * as XLSX from 'xlsx';

const importInput = ref(null);
const toastRef = ref(null);
const page = usePage();
const zones = page.props.zones || [];
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

// Ảnh upload
const images = ref([]); // File[]
const imagePreviews = ref([]); // string[]

const handleImageChange = (e) => {
  const files = Array.from(e.target.files);
  images.value.push(...files);
  // Tạo preview cho ảnh mới
  files.forEach(file => {
    const reader = new FileReader();
    reader.onload = (ev) => {
      imagePreviews.value.push(ev.target.result);
    };
    reader.readAsDataURL(file);
  });
  // Reset input để chọn lại được cùng 1 file nếu cần
  e.target.value = '';
};

const removeImage = (idx) => {
  images.value.splice(idx, 1);
  imagePreviews.value.splice(idx, 1);
};
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
    console.log(data);
    
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


const handleImportExcel = async (event) => {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = async (e) => {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: 'array' });
    const sheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[sheetName];
    const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

    // Xác định header
    const headers = jsonData[0].map(h => h ? h.toString().trim() : '');
    const rows = jsonData.slice(1).filter(row => row.length && row.some(cell => cell !== undefined && cell !== null && cell !== ''));

    // Lấy danh sách khu vực từ file
    const importedZones = Array.from(new Set(rows.map(row => row[headers.indexOf('Khu vực')] || '').filter(Boolean)));
    // Cập nhật selectedZones và chờ API load xong products rồi mới xử lý tiếp
    selectedZones.value = importedZones;
    await fetchProductsByZones();

    // Map dữ liệu từ file Excel vào auditData.value.items
    rows.forEach(row => {
      const code = row[headers.indexOf('Mã hàng')] || '';
      const zone = row[headers.indexOf('Khu vực')] || '';
      const actual_quantity = row[headers.indexOf('Thực tế')] ?? '';
      const discrepancy_reason = row[headers.indexOf('Ghi chú chênh')] ?? '';
      // Tìm đúng item trong auditData.value.items để cập nhật
      const idx = products.findIndex(
        p => p.code === code && p.zone === zone
      );
      if (idx !== -1 && auditData.value.items[idx]) {
        auditData.value.items[idx].actual_quantity = actual_quantity;
        auditData.value.items[idx].discrepancy_reason = discrepancy_reason;
      }
    });
  };
  reader.readAsArrayBuffer(file);
};

const submitForm = () => {
  // Kiểm tra trạng thái hoàn thành hay có chênh lệch
  const allMatched = auditData.value.items.every(item =>
    Number(item.expected_quantity) === Number(item.actual_quantity)
  );
  const status = allMatched ? 'completed' : 'issues';

  // Debug: Log dữ liệu trước khi gửi
  console.log('Submitting audit data:', {
    notes: auditData.value.notes,
    audit_date: auditData.value.audit_date,
    status: status,
    items: auditData.value.items,
    images: images.value
  });

  // Chuẩn bị FormData để gửi kèm ảnh
  const formData = new FormData();
  formData.append('notes', auditData.value.notes);
  formData.append('audit_date', auditData.value.audit_date);
  formData.append('status', status);
  auditData.value.items.forEach((item, idx) => {
    formData.append(`items[${idx}][product_variant_id]`, item.product_variant_id);
    formData.append(`items[${idx}][expected_quantity]`, item.expected_quantity);
    formData.append(`items[${idx}][actual_quantity]`, item.actual_quantity);
    formData.append(`items[${idx}][discrepancy_reason]`, item.discrepancy_reason);
  });
  images.value.forEach((file, idx) => {
    formData.append('images[]', file);
  });

  router.post(route('admin.inventory-audit.store'), formData, {
    forceFormData: true,
    preserveState: true,
    onSuccess: (page) => {
      console.log('Success response:', page);
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Lưu phiếu kiểm kho thành công!', 'success');
      }
      // resetForm();
      images.value = [];
      imagePreviews.value = [];
    },
    onError: (errors) => {
      console.error('Lỗi từ backend:', errors);
      console.error('Full error object:', JSON.stringify(errors, null, 2));
      const toast = document.querySelector('toast');
      if (toast && toast.showLocalToast) {
        toast.showLocalToast('Lỗi khi lưu phiếu kiểm kho: ' + (errors.message || 'Vui lòng kiểm tra lại dữ liệu!'), 'error');
      }
    },
    onProgress: (progress) => {
      console.log('Upload progress:', progress);
    }
  });
};

const filteredProducts = computed(() => products);

const exportSampleExcel = () => {
  const sampleData = [
    ['Khu vực', 'Chi tiết khu vực','Mã hàng', 'Tên hàng', 'ĐVT', 'Tồn kho', 'Thực tế', 'Ghi chú chênh'],
    ...filteredProducts.value.map((product, idx) => [
      product.zone,
      product.custom_location_name,
      product.code,
      product.name_product,
      product.unit,
      product.quantity_on_hand,
      auditData.value.items[idx]?.actual_quantity || '',
      auditData.value.items[idx]?.discrepancy_reason || ''
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
</script>