```vue
<script setup>
import { ref, computed } from "vue";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import AppLayout from "../Layouts/AppLayout.vue";
import { route } from "ziggy-js";
import axios from "axios";
import Waiting from "../../components/Waiting.vue";
import { useForm } from "@inertiajs/vue3";

// Props
const { products } = defineProps(["products"]);

// Computed
const selectOptions = computed(() =>
  products.map((p) => ({
    label: p.name,
    value: p.id,
  }))
);

// Refs
const variantOfProducts = ref([]);
const selectedProduct = ref(null);
const showModal = ref(false);
const variantOptions = ref([]);
const checkedVariants = ref([]);
const selectedVariants = ref([]);
const unitDefault = ref(null);
const unitOptionsMap = ref({});
const unitConversions = ref([]);
const showConfirmModal = ref(false);
const preparedOrders = ref([]);
const finalItems = ref([]);

// Tìm kiếm và lấy biến thể của sản phẩm
async function onProductSelect(productId) {
  try {
    const url = route("admin.purchases.getVariants", { id: productId });
    const { data } = await axios.get(url);

    variantOfProducts.value = data;
    unitDefault.value = data.unit_default;
    unitConversions.value = data.unit_conversions;

    const toUnits = data.unit_conversions.map((conv) => ({
      id: conv.to_unit.id,
      name: conv.to_unit.name,
    }));

    const allUnitOptions = [
      unitDefault.value,
      ...toUnits.filter(
        (u, index, self) =>
          self.findIndex((x) => x.id === u.id) === index &&
          u.id !== unitDefault.value.id
      ),
    ];

    variantOptions.value = data.product_variants.map((v) => {
      unitOptionsMap.value[v.id] = allUnitOptions;
      return {
        id: v.id,
        label: `${data.name} - ${v.attributes.map((attr) => attr.name).join(" - ")}`,
        value: v.id,
        suppliers: v.suppliers,
      };
    });

    checkedVariants.value = [...variantOptions.value];
    showModal.value = true;
  } catch (error) {
    console.error("Lỗi lấy biến thể:", error);
  }
}

// Chọn hoặc bỏ chọn tất cả biến thể
function toggleAllVariants(state) {
  checkedVariants.value = state ? [...variantOptions.value] : [];
}

// Xác nhận các biến thể đã chọn
function confirmVariants() {
  if (!checkedVariants.value.length) return;

  selectedVariants.value = checkedVariants.value.map((v) => {
    const defaultUnit = unitDefault.value;
    const unitOpts = unitOptionsMap.value[v.id] || [defaultUnit];
    const processedVendors = v.suppliers.map((s) => ({
      id: s.pivot.supplier_id,
      name: s.name,
    }));

    return {
      name: v.label,
      vendors: processedVendors,
      vendorSelected: null,
      quantity: 1,
      variantId: v.id,
      unit: defaultUnit.name,
      unitSelected: defaultUnit.id,
      unitOptions: unitOpts,
      price: 0,
      total: 0,
      conversionFactor: 1,
    };
  });

  showModal.value = false;
}

// Tính toán hệ số quy đổi
function getConversionFactor(fromUnitId, toUnitId) {
  if (fromUnitId === toUnitId) return 1;
  const conversion = unitConversions.value.find(
    (conv) => conv.from_unit_id === fromUnitId && conv.to_unit_id === toUnitId
  );
  return conversion ? parseFloat(conversion.conversion_factor) : 1;
}

// Tính tổng giá trị cho biến thể
function calculateTotal(variant) {
  const conversionFactor = getConversionFactor(
    unitDefault.value?.id,
    variant.unitSelected
  );
  variant.conversionFactor = conversionFactor;
  variant.convertedPrice = variant.price * conversionFactor;
  variant.total = variant.quantity * variant.convertedPrice;
}

// Lấy tên nhà cung cấp
function getSupplierName(supplierId) {
  const item = finalItems.value.find((i) => i.vendorSelected === supplierId);
  if (!item || !item.vendors) return "Không rõ";
  const supplier = item.vendors.find((v) => v.id === supplierId);
  return supplier?.name || "Không rõ";
}

// Thêm biến thể vào danh sách cuối
function addToList() {
  const convertedItems = selectedVariants.value
    .filter(
      (item) => item.vendorSelected && item.quantity > 0 && item.price >= 0
    )
    .map((item) => ({
      name: item.name,
      vendors: item.vendors,
      vendorSelected: item.vendorSelected,
      quantity: item.quantity,
      variants: item.variantId,
      unitId: item.unitSelected,
      unit: item.unitOptions.find((u) => u.id === item.unitSelected)?.name || "",
      unitSelected: item.unitSelected,
      conversionFactor: item.conversionFactor,
      price: item.price * item.conversionFactor,
      total: item.total,
    }));

  finalItems.value.push(...convertedItems);
  selectedVariants.value = [];
}

// Xóa mục khỏi danh sách
function removeItem(index) {
  finalItems.value.splice(index, 1);
}

// Tính tổng giá trị đơn hàng
const grandTotal = computed(() =>
  finalItems.value.reduce((sum, item) => sum + item.total, 0)
);

// Chuẩn bị đơn hàng theo nhà cung cấp
function preparePurchaseOrders() {
  const ordersMap = {};

  for (const item of finalItems.value) {
    const supplierId = item.vendorSelected;
    if (!ordersMap[supplierId]) {
      ordersMap[supplierId] = {
        supplier_id: supplierId,
        supplier_name: getSupplierName(supplierId),
        items: [],
        subtotal: 0,
        expected_date: null,
      };
    }

    ordersMap[supplierId].items.push({
      variant_id: item.variants,
      name: item.name,
      quantity: item.quantity,
      unit_id: item.unitId,
      unit: item.unit,
      price: item.price,
      total: item.total,
    });

    ordersMap[supplierId].subtotal += item.total;
  }

  return Object.values(ordersMap);
}

// Mở modal xác nhận
function openConfirmModal() {
  if (!finalItems.value.length) return;
  preparedOrders.value = preparePurchaseOrders();
  showConfirmModal.value = true;
}

// Gửi đơn hàng đã xác nhận
function submitConfirmedOrders() {
  const form = useForm({
    orders: preparedOrders.value,
  });

  form.post(route("admin.purchases.store"), {
    onError: () => {
      console.error("Có lỗi !!!");
    },
  });
}
</script>

<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 p-6">
      <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
        <h5 class="text-lg text-indigo-700 font-semibold">
          Tạo yêu cầu nhập hàng
        </h5>
        <Waiting
          route-name="admin.purchases.index"
          :route-params="{}"
          :color="'bg-indigo-50 text-indigo-700'"
        >
          <i class="fas fa-arrow-left mr-1"></i> Quay lại
        </Waiting>
      </div>

      <div class="mx-auto bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="mb-5">
          <label class="block text-sm font-medium text-gray-700 mb-2 ml-2">
            Chọn sản phẩm
          </label>
          <Multiselect
            v-model="selectedProduct"
            :options="selectOptions"
            placeholder="Tìm kiếm hoặc chọn sản phẩm"
            label="label"
            track-by="value"
            :searchable="true"
            :close-on-select="true"
            :multiple="false"
            @select="onProductSelect"
            class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Bảng biến thể đã chọn -->
        <div class="mb-12">
          <div class="overflow-x-auto rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-indigo-100 text-gray-700">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Sản phẩm
                  </th>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Nhà cung cấp
                  </th>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Số lượng
                  </th>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Đơn vị
                  </th>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Giá nhập (VND)
                  </th>
                  <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider">
                    Thành tiền (VND)
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="!selectedVariants.length" class="hover:bg-gray-50">
                  <td
                    colspan="6"
                    class="px-4 py-2 text-sm text-gray-500 text-center"
                  >
                    Tìm kiếm sản phẩm để tiến hành nhập hàng
                  </td>
                </tr>
                <tr
                  v-for="(variant, i) in selectedVariants"
                  :key="i"
                  class="hover:bg-gray-50"
                >
                  <td class="px-4 py-2 text-sm text-gray-900 w-60">
                    {{ variant.name }}
                  </td>
                  <td class="px-4 py-2 w-60">
                    <select
                      v-if="variant.vendors.length"
                      v-model="variant.vendorSelected"
                      class="w-full bg-white border border-gray-300 text-sm rounded-md p-2 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <option
                        v-for="vendor in variant.vendors"
                        :key="vendor.id"
                        :value="vendor.id"
                      >
                        {{ vendor.name }}
                      </option>
                    </select>
                    <span v-else class="text-sm text-red-600">
                      <i>Hiện chưa có nhà cung cấp</i>
                    </span>
                  </td>
                  <td class="px-3 py-2 w-24">
                    <input
                      type="number"
                      v-model.number="variant.quantity"
                      @input="calculateTotal(variant)"
                      min="1"
                      class="w-full text-sm border border-gray-300 rounded-md px-2 py-1 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                    />
                  </td>
                  <td class="px-4 py-2 w-48">
                    <select
                      v-model="variant.unitSelected"
                      @change="calculateTotal(variant)"
                      class="w-full bg-white border border-gray-300 text-sm rounded-md p-2 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <option
                        v-for="u in variant.unitOptions"
                        :key="u.id"
                        :value="u.id"
                      >
                        {{ u.name }}
                      </option>
                    </select>
                  </td>
                  <td class="px-3 py-2 w-32 align-top">
                    <div class="flex flex-col gap-1">
                      <input
                        type="number"
                        v-model.number="variant.price"
                        @input="calculateTotal(variant)"
                        min="0"
                        step="1000"
                        class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                      />
                      <span
                        class="text-xs text-gray-800 bg-indigo-50 rounded px-2 py-1 border border-dashed border-indigo-300 mt-1"
                      >
                        Giá quy đổi:
                        <span
                          class="converted-price-box"
                          :class="{
                            'text-green-600 font-semibold': variant.conversionFactor > 1,
                            'text-gray-500': variant.conversionFactor <= 1,
                          }"
                        >
                          {{ (variant.price * variant.conversionFactor).toLocaleString() }}
                        </span>
                      </span>
                    </div>
                  </td>
                  <td
                    class="px-3 py-2 text-sm text-gray-900 font-medium w-32 text-right"
                  >
                    {{ variant.total.toLocaleString() }}đ
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <button
            v-if="selectedVariants.length"
            @click="addToList"
            :disabled="
              selectedVariants.some(
                (v) => !v.vendorSelected || v.quantity <= 0 || v.price < 0
              )
            "
            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Thêm vào danh sách
          </button>
        </div>

        <!-- Modal chọn biến thể -->
        <div
          v-if="showModal"
          class="fixed inset-0 z-50 flex items-center justify-center hihi"
          @click.self="showModal = false"
        >
          <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="p-6">
              <h3 class="text-lg font-medium text-gray-900 mb-4">
                Chọn biến thể cho sản phẩm
              </h3>
              <div class="flex justify-between mb-4">
                <button
                  @click="toggleAllVariants(true)"
                  class="text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none"
                >
                  Chọn tất cả
                </button>
                <button
                  @click="toggleAllVariants(false)"
                  class="text-sm text-red-600 hover:text-red-800 focus:outline-none"
                >
                  Bỏ chọn tất cả
                </button>
              </div>
              <div
                class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-2"
              >
                <div
                  v-for="v in variantOptions"
                  :key="v.id"
                  class="flex items-center mb-2 last:mb-0"
                >
                  <input
                    type="checkbox"
                    :id="`variant-${v.id}`"
                    :value="v"
                    v-model="checkedVariants"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 rounded"
                  />
                  <label
                    :for="`variant-${v.id}`"
                    class="ml-2 block text-sm text-gray-700"
                  >
                    {{ v.label }}
                  </label>
                </div>
              </div>
            </div>
            <div
              class="bg-gray-50 px-4 py-2 flex justify-end gap-3 rounded-lg"
            >
              <button
                @click="showModal = false"
                class="px-4 py-2 bg-white text-gray-700 rounded-md border hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                Hủy bỏ
              </button>
              <button
                @click="confirmVariants"
                :disabled="checkedVariants.length === 0"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Xác nhận
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Danh sách sản phẩm đã chọn -->
      <div class="mx-auto bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">
          Danh sách sản phẩm đã chọn
        </h3>
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Sản phẩm
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Nhà cung cấp
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Số lượng
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Đơn vị
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Giá tiền
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Thành tiền
                </th>
                <th
                  class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                ></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="!finalItems.length" class="hover:bg-gray-50">
                <td
                  colspan="7"
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center"
                >
                  Chưa có sản phẩm nào được thêm
                </td>
              </tr>
              <tr
                v-for="(item, i) in finalItems"
                :key="i"
                class="hover:bg-gray-50"
              >
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                >
                  {{ item.name }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                >
                  {{ item.vendors.find((v) => v.id === item.vendorSelected)?.name || "N/A" }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                >
                  {{ item.quantity }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                >
                  {{ item.unit }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                >
                  {{ item.price.toLocaleString() }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium"
                >
                  {{ item.total.toLocaleString() }}
                </td>
                <td
                  class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium"
                >
                  <button
                    @click="removeItem(i)"
                    class="text-red-600 hover:text-red-900 focus:outline-none"
                  >
                    Xóa
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 flex justify-between items-center">
          <div class="text-lg font-medium text-gray-900">
            Tổng giá trị sản phẩm đề xuất:
            <span class="font-semibold text-indigo-700">
              {{ grandTotal.toLocaleString() }} VND
            </span>
          </div>
          <button
            @click="openConfirmModal"
            :disabled="finalItems.length === 0"
            class="px-4 py-3 bg-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-xl text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Xem trước đơn nhập
          </button>
        </div>

        <!-- Modal xác nhận đơn hàng -->
        <div
          v-if="showConfirmModal"
          class="fixed inset-0 z-50 flex items-center justify-center hihi"
          @click.self="showConfirmModal = false"
        >
          <div
            class="bg-white rounded-lg shadow-lg w-[80%] max-h-[90vh] p-6 overflow-y-auto"
          >
            <h2 class="text-lg bipho font-semibold w-max text-gray-800 mb-4">
              Xác nhận đơn nhập hàng ({{ preparedOrders.length }} đơn tạo)
            </h2>

            <div
              v-for="order in preparedOrders"
              :key="order.supplier_id"
              class="mb-10 p-4 rounded-lg"
              style="box-shadow: 0 0 3px rgba(0, 0, 0, 0.4)"
            >
              <div class="mb-4 flex items-center justify-between px-24">
                  <div class="text-sm font-semibold text-indigo-700">
                    Nhà cung cấp: {{ order.supplier_name }}
                  </div>
                  <div class="flex items-center gap-4">
                      <label class="block text-sm text-orange-600">
                        Ngày dự kiến giao hàng:
                      </label>
                      <input
                        type="date"
                        v-model="order.expected_date"
                        class="border p-2 rounded-md text-sm w-48 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      />
                  </div>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Sản phẩm
                      </th>
                      <th
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Số lượng
                      </th>
                      <th
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Đơn vị
                      </th>
                      <th
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Giá tiền (VNĐ)
                      </th>
                      <th
                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Thành tiền (VNĐ)
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="item in order.items"
                      :key="item.variant_id"
                      class="hover:bg-gray-50"
                    >
                      <td
                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                      >
                        {{ item.name }}
                      </td>
                      <td
                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                      >
                        {{ item.quantity }}
                      </td>
                      <td
                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-500"
                      >
                        {{ item.unit }}
                      </td>
                      <td
                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"
                      >
                        {{ item.price.toLocaleString() }}
                      </td>
                      <td
                        class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium"
                      >
                        {{ item.total.toLocaleString() }}
                      </td>
                    </tr>
                    <tr class="bg-indigo-50">
                      <td
                        colspan="4"
                        class="px-4 py-3 text-sm text-green-500 font-semibold text-right"
                      >
                        Tổng cộng:
                      </td>
                      <td
                        class="px-4 py-3 text-sm text-gray-900 font-semibold"
                      >
                        {{ order.subtotal.toLocaleString() }}đ
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="flex justify-end mt-4 gap-3">
              <button
                @click="showConfirmModal = false"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
              >
                Hủy
              </button>
              <button
                @click="submitConfirmedOrders"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Xác nhận & Gửi
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </AppLayout>
  </template>

<style scoped>
.multiselect {
  border-radius: 0.375rem;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  border: 1px solid #d1d5db;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  outline: none;
}

.multiselect:focus,
.multiselect:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.bipho::after{
    width: 60%;
    height: 2px;
    content: "";
    display: block;
    background: linear-gradient(to right, #4369b7, #6800cf);
    margin-top: 3px;
}

.multiselect-search {
  font-size: 0.875rem;
}

.multiselect-placeholder {
  color: #9ca3af;
}

.multiselect-single-label {
  color: #374151;
}

.multiselect-option {
  font-size: 0.875rem;
  padding: 0.25rem 0.5rem;
  cursor: pointer;
}

.multiselect-option.is-selected {
  background-color: #eff6ff;
  color: #1e3a8a;
}

.multiselect-option.is-pointed {
  background-color: #f3f4f6;
}

.hihi {
  background-color: rgba(0, 0, 0, 0.5);
}

.converted-price-box {
  font-size: 0.75rem;
  background-color: #ffffff;
  border: 1px dashed #d1d5db;
  padding: 0.125rem 0.5rem;
  border-radius: 0.375rem;
  display: inline-block;
}
</style>