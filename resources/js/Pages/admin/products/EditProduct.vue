<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Cập Nhật Sản Phẩm
                </h5>
                <Waiting route-name="admin.products.index" :route-params="{}" :color="'bg-indigo-50 text-indigo-700'">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại
                </Waiting>
            </div>

            <div class="p-6 bg-white rounded shadow-md">
                <form @submit.prevent="handleSubmitForm" class="space-y-6">
                    <!-- Tên sản phẩm -->
                    <div class="grid grid-cols-3 grid-rows-3 gap-4">
                        <div class="col-span-2 row-span-1">
                            <div class="space-y-2">
                                <label for="product-name" class="block text-sm font-medium text-indigo-700">
                                    Tên sản phẩm <span class="text-red-500">*</span>
                                </label>
                                <input v-model="form.name" type="text" id="product-name" name="name"
                                    placeholder="Nhập tên sản phẩm"
                                    class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200" />
                                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-1 row-span-3">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-indigo-700">Hình ảnh sản phẩm</label>
                                <div class="flex gap-2">
                                    <div class="flex items-center justify-center w-70">
                                        <label for="dropzone-file"
                                            class="flex flex-col items-center justify-center w-70 h-55 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Click để tải lên</span> hoặc kéo thả
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG (Tối đa 2MB/ảnh, tối đa 4 ảnh)
                                                </p>
                                            </div>
                                            <input id="dropzone-file" type="file" class="hidden" accept="image/*"
                                                multiple @change="handleImageUpload" />
                                        </label>
                                    </div>

                                    <div class="mt-2 w-22">
                                        <div class="grid grid-cols-1 gap-2">
                                            <div v-for="(src, i) in imagePreviews" :key="i"
                                                class="relative w-13 h-12 border border-dashed border-gray-300 rounded">
                                                <img :src="src" class="w-full h-full object-cover rounded" />
                                                <button type="button" @click="removeImage(i, i < existingImages.length)"
                                                    class="absolute top-0 right-0 p-1 text-red-600 bg-white rounded-full shadow hover:bg-red-50 transform translate-x-1/2 -translate-y-1/2">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="form.errors.images" class="text-red-500 text-sm mt-1">{{ form.errors.images }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <!-- Mã sản phẩm -->
                            <div class="space-y-2">
                                <label for="product-code" class="block text-sm font-medium text-indigo-700">
                                    Mã sản phẩm (SKU)
                                </label>
                                <input v-model="form.code" type="text" id="product-code" name="code"
                                    placeholder="Nhập mã sản phẩm"
                                    class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200" />
                                <p v-if="form.errors.code" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.code }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <!-- Danh mục -->
                            <div class="space-y-2">
                                <label for="category" class="block text-sm font-medium text-indigo-700">
                                    Danh mục <span class="text-red-500">*</span>
                                </label>
                                <Multiselect v-model="form.category_id" :options="filteredCategories" :searchable="true"
                                    :filterResults="false" placeholder="Tìm kiếm hoặc chọn danh mục"
                                    :close-on-select="true" :can-clear="true" value-prop="id" label="formattedName"
                                    track-by="id" :searchFields="['name']" @search-change="handleSearch">
                                    <template v-slot:option="{ option }">
                                        <span :style="{ color: getLevelColor(option.level) }">{{ option.formattedName
                                            }}</span>
                                    </template>
                                </Multiselect>
                                <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.category_id }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="space-y-2">
                                <label for="min-stock" class="block text-sm font-medium text-indigo-700">
                                    Tồn kho tối thiểu <span class="text-red-500">*</span>
                                </label>
                                <input v-model="form.min_stock" type="number" id="min-stock" name="min_stock"
                                    placeholder="Nhập số lượng tối thiểu" min="0"
                                    class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200" />
                                <p v-if="form.errors.min_stock" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.min_stock }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <div class="grid grid-cols-1 grid-rows-1 gap-4">
                                <div class="col-span-1">
                                    <div class="space-y-2">
                                        <label for="production-date" class="block text-sm font-medium text-indigo-700">
                                            Ngày sản xuất
                                        </label>
                                        <input v-model="form.production_date" type="date" id="production-date"
                                            name="production_date"
                                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200" />
                                        <p v-if="form.errors.production_date" class="text-red-500 text-sm mt-1">
                                            {{ form.errors.production_date }}
                                        </p>
                                    </div>
                                </div>

                                <!-- <div class="col-span-2">
                                    <div class="space-y-2">
                                        <label for="expiration-date" class="block text-sm font-medium text-indigo-700">
                                            Ngày hết hạn
                                        </label>
                                        <input v-model="form.expiration_date" type="date" id="expiration-date"
                                            name="expiration_date"
                                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200" />
                                        <p v-if="form.errors.expiration_date" class="text-red-500 text-sm mt-1">
                                            {{ form.errors.expiration_date }}
                                        </p>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-indigo-700">
                            Mô tả sản phẩm
                        </label>
                        <textarea v-model="form.description" id="description" name="description" rows="4"
                            placeholder="Nhập mô tả chi tiết sản phẩm"
                            class="w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200"></textarea>
                        <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Đơn vị sản phẩm -->
                    <div class="col-span-3">
                        <div class="mb-6">
                            <label for="base-unit" class="block text-sm font-medium text-indigo-700">
                                Đơn vị cơ bản <span class="text-red-500">*</span>
                            </label>
                            <select v-model="form.base_unit_id" id="base-unit"
                                class="mt-1 w-full px-4 py-3 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent transition-all duration-200">
                                <option value="">Chọn đơn vị cơ bản</option>
                                <option v-for="unit in units" :key="unit.id" :value="unit.id">
                                    {{ unit.name }} ({{ unit.symbol }})
                                </option>
                            </select>
                            <p v-if="form.errors.base_unit_id" class="text-red-500 text-sm mt-1">
                                {{ form.errors.base_unit_id }}
                            </p>
                        </div>
                        <!-- Danh sách đơn vị quy đổi -->
                        <div class="space-y-4">
                            <div v-for="(conversion, index) in form.unit_conversions" :key="index"
                                class="p-4 border rounded border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Đơn vị cần quy đổi -->
                                    <div>
                                        <label class="block text-sm font-medium text-indigo-700">
                                            Đơn vị quy đổi
                                        </label>
                                        <select v-model="conversion.to_unit_id"
                                            class="mt-1 w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                            <option value="">Chọn đơn vị</option>
                                            <option v-for="unit in units" :key="unit.id" :value="unit.id">
                                                {{ unit.name }} ({{ unit.symbol }})
                                            </option>
                                        </select>
                                        <p v-if="form.errors[`unit_conversions.${index}.to_unit_id`]"
                                            class="text-red-500 text-sm mt-1">
                                            {{ form.errors[`unit_conversions.${index}.to_unit_id`] }}
                                        </p>
                                    </div>
                                    <!-- Hệ số quy đổi -->
                                    <div>
                                        <label class="block text-sm font-medium text-indigo-700">
                                            Hệ số quy đổi
                                        </label>
                                        <input v-model="conversion.conversion_factor" type="number" step="0.0001"
                                            min="0.0001" placeholder="Ví dụ: 10"
                                            class="mt-1 w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                                        <p v-if="form.errors[`unit_conversions.${index}.conversion_factor`]"
                                            class="text-red-500 text-sm mt-1">
                                            {{ form.errors[`unit_conversions.${index}.conversion_factor`] }}
                                        </p>
                                    </div>
                                    <!-- Nút xóa -->
                                    <div class="flex items-center justify-end mt-5">
                                        <button type="button" @click="removeConversion(index)"
                                            class="text-red-600 hover:underline text-sm">
                                            <i class="fas fa-times mr-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút thêm dòng quy đổi -->
                            <button type="button" @click="addConversion" v-if="form.unit_conversions.length < 3"
                                class="px-4 py-2 text-indigo-600 bg-indigo-50 rounded hover:bg-indigo-100">
                                <i class="fas fa-plus mr-1"></i> Thêm đơn vị quy đổi
                            </button>
                        </div>
                    </div>

                    <!-- Kiểu sản phẩm -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-indigo-700 mb-2">Kiểu sản phẩm</label>
                        <p class="text-gray-700 font-semibold">
                            {{ hasVariant ? 'Sản phẩm có biến thể' : 'Sản phẩm đơn giản' }}
                        </p>
                    </div>

                    <!-- Sản phẩm đơn giản -->
                    <div v-if="!hasVariant" class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Giá bán</label>
                            <input v-model="form.simple_sale_price" type="number" min="0"
                                class="w-full px-3 py-2 border rounded border-gray-300" />
                            <p v-if="form.errors.simple_sale_price" class="text-red-500 text-sm mt-1">
                                {{ form.errors.simple_sale_price }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Số lượng tồn kho ban đầu</label>
                            <input v-model="form.simple_quantity" type="number" min="0"
                                class="w-full px-3 py-2 border rounded border-gray-300" />
                            <p v-if="form.errors.simple_quantity" class="text-red-500 text-sm mt-1">
                                {{ form.errors.simple_quantity }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Mã vạch</label>
                            <input v-model="form.simple_barcode" type="text"
                                class="w-full px-3 py-2 border rounded border-gray-300" />
                            <p v-if="form.errors.simple_barcode" class="text-red-500 text-sm mt-1">
                                {{ form.errors.simple_barcode }}
                            </p>
                        </div>
                    </div>
                    <div v-if="!hasVariant" class="grid grid-cols-1 gap-4 mb-6">
                        <!-- Chọn nhà cung cấp -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Nhà cung cấp</label>
                            <Multiselect v-model="form.supplier_ids" :options="suppliers" label="name" value-prop="id"
                                placeholder="Chọn nhiều nhà cung cấp" mode="tags" :can-clear="true" />
                            <p v-if="form.errors.supplier_ids" class="text-red-500 text-sm mt-1">
                                {{ form.errors.supplier_ids }}
                            </p>
                        </div>
                    </div>
                    <div v-if="!hasVariant" class="grid grid-cols-2 gap-4 mb-6">
                        <!-- Chọn khu vực kho -->
                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Khu vực kho</label>
                            <select v-model="form.warehouse_zone_id"
                                class="w-full px-3 py-2 border rounded border-gray-300">
                                <option class="text-gray-600" value="">Chọn khu vực kho</option>
                                <option v-for="zone in props.warehouseZones" :key="zone.id" :value="zone.id">
                                    {{ zone.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.warehouse_zone_id" class="text-red-500 text-sm mt-1">
                                {{ form.errors.warehouse_zone_id }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-indigo-700">Tên vị trí lưu kho (nếu có)</label>
                            <input v-model="form.custom_location_name" type="text"
                                class="w-full px-3 py-2 border rounded border-gray-300"
                                placeholder="Ví dụ: Kệ A2, Tầng 3..." />
                            <p v-if="form.errors.custom_location_name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.custom_location_name }}
                            </p>
                        </div>
                    </div>

                    <!-- Sản phẩm có biến thể -->
                    <div v-if="hasVariant" class="pt-6 col-span-3">
                        <h6 class="text-md font-semibold text-indigo-700 mb-4">Biến thể sản phẩm</h6>

                        <!-- Thêm biến thể -->
                        <div class="mb-6 p-4 border border-gray-200 rounded">
                            <!-- Thuộc tính biến thể -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-indigo-700 mb-2">
                                    Thuộc tính biến thể
                                </label>
                                <div v-for="(attribute, attrIndex) in form.variants[0].attributes" :key="attrIndex"
                                    class="mb-4 space-y-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <Multiselect v-model="attribute.attribute_id"
                                            :options="getAvailableAttributes(form.variants[0], attribute.attribute_id)"
                                            label="name" value-prop="id" placeholder="Chọn thuộc tính"
                                            :searchable="true" :can-clear="true" :create-option="true"
                                            @create-option="handleCreateAttribute" />
                                        <p v-if="form.errors[`variants.0.attributes.${attrIndex}.attribute_id`]"
                                            class="text-red-500 text-sm mt-1">
                                            {{ form.errors[`variants.0.attributes.${attrIndex}.attribute_id`] }}
                                        </p>
                                    </div>

                                    <div class="space-y-1">
                                        <div class="flex gap-3 items-start">
                                            <div class="w-full">
                                                <Multiselect v-model="attribute.attribute_value_ids"
                                                    :options="attributeValues[attribute.attribute_id] || []"
                                                    label="name" value-prop="id" placeholder="Chọn nhiều giá trị"
                                                    mode="tags" :searchable="true" :can-clear="true"
                                                    :create-option="true"
                                                    @create-option="(val) => handleCreateAttributeValue(attribute.attribute_id, val)" />
                                                <p v-if="form.errors[`variants.0.attributes.${attrIndex}.attribute_value_ids`]"
                                                    class="text-red-500 text-sm mt-1">
                                                    {{
                                                        form.errors[`variants.0.attributes.${attrIndex}.attribute_value_ids`]
                                                    }}
                                                </p>
                                            </div>
                                            <button type="button" @click="removeVariantAttribute(attrIndex)"
                                                class="text-red-600 hover:underline text-sm mt-1">
                                                <i class="fas fa-times mr-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" @click="addVariantAttribute"
                                    v-if="form.variants[0].attributes.length < 5"
                                    class="mt-2 px-3 py-1 text-sm text-indigo-600 bg-indigo-50 rounded hover:bg-indigo-100">
                                    <i class="fas fa-plus mr-1"></i> Thêm thuộc tính
                                </button>
                            </div>
                        </div>
                    </div>
                    <h6 v-if="hasVariant" class="text-sm font-semibold text-indigo-700 mb-2">Tổ hợp giá trị đã chọn</h6>
                    <div v-if="hasVariant" class="col-span-3 p-4 bg-gray-50 grid grid-cols-2 gap-3">
                        <div v-for="(item, index) in variantCombinations" :key="item.key"
                            class="relative mb-4 p-4 rounded bg-white shadow-sm space-y-3 border border-gray-200">
                            <button type="button" @click="removeCombinationItem(item.key)"
                                class="absolute top-2 right-2 text-red-500">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                            <div class="text-sm font-medium text-indigo-700">{{ item.label }}</div>
                            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                <div>
                                    <input v-model="item.data.code" type="text" placeholder="Mã biến thể (SKU)"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm focus:ring-indigo-300 focus:outline-none" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.code`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.code`] }}
                                    </p>
                                </div>
                                <div>
                                    <input v-model="item.data.barcode" type="text" placeholder="Mã vạch"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm focus:ring-indigo-300 focus:outline-none" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.barcode`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.barcode`] }}
                                    </p>
                                </div>
                                <div>
                                    <input v-model.number="item.data.sale_price" type="number" placeholder="Giá bán"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm focus:ring-indigo-300 focus:outline-none" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.sale_price`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.sale_price`] }}
                                    </p>
                                </div>
                                <div>
                                    <input v-model.number="item.data.quantity_on_hand" type="number"
                                        placeholder="Tồn kho ban đầu"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm focus:ring-indigo-300 focus:outline-none" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.quantity_on_hand`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.quantity_on_hand`] }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                                <div>
                                    <Multiselect v-model="item.data.supplier_ids" :options="suppliers" label="name"
                                        value-prop="id" placeholder="Chọn nhà cung cấp" mode="tags" :can-clear="true" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.supplier_ids`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.supplier_ids`] }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                <div>
                                    <select v-model="item.data.warehouse_zone_id"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm">
                                        <option value="">Chọn vị trí</option>
                                        <option v-for="z in warehouseZones" :key="z.id" :value="z.id">{{ z.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors[`variants.0.combinations.${index}.warehouse_zone_id`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.warehouse_zone_id`] }}
                                    </p>
                                </div>
                                <div>
                                    <input v-model="item.data.custom_location_name" type="text"
                                        placeholder="Tên vị trí lưu kho (nếu có)"
                                        class="w-full px-2 py-1 border rounded border-gray-300 text-sm focus:ring-indigo-300 focus:outline-none" />
                                    <p v-if="form.errors[`variants.0.combinations.${index}.custom_location_name`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`variants.0.combinations.${index}.custom_location_name`] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" @click="resetForm"
                            class="px-4 py-2 text-indigo-700 bg-white border border-indigo-300 rounded hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            Nhập lại
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all duration-200 shadow-md hover:shadow-lg"
                            :disabled="form.processing">
                            <span v-if="!form.processing" class="font-medium">Cập nhật sản phẩm</span>
                            <span v-else class="font-medium">Đang xử lý...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { route } from 'ziggy-js';
import AppLayout from '../Layouts/AppLayout.vue';
import Waiting from '../../components/Waiting.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';

const props = defineProps({
    product: Object,
    categories: Array,
    units: Array,
    attributes: Array,
    attributeValues: Object,
    warehouseZones: Array,
    suppliers: Array,
});

// Hàm gộp biến thể
const mergeVariants = (variants) => {
    console.log('Input variants:', variants); // Debug dữ liệu đầu vào

    if (!variants || !variants.length) {
        console.log('No variants provided, returning default');
        return [{
            code: '',
            barcode: '',
            sale_price: 0,
            unit_id: '',
            attributes: [{ attribute_id: '', attribute_value_ids: [] }],
            combinationData: {},
        }];
    }

    const allAttributes = [];
    const combinationData = {};

    variants.forEach((variant, variantIndex) => {
        console.log(`Processing variant ${variantIndex}:`, variant); // Debug từng variant

        // Thu thập thuộc tính
        if (variant.attributes) {
            variant.attributes.forEach((attr, attrIndex) => {
                if (!attr.attribute_id) return;
                const existingAttr = allAttributes.find(a => a.attribute_id === attr.attribute_id);
                if (!existingAttr) {
                    allAttributes.push({
                        ...attr,
                        uniqueKey: `attr_${attrIndex}_${Date.now()}`, // Thêm uniqueKey
                    });
                } else {
                    existingAttr.attribute_value_ids = [
                        ...new Set([...existingAttr.attribute_value_ids, ...(Array.isArray(attr.attribute_value_ids) ? attr.attribute_value_ids : [])]),
                    ];
                }
            });
        }

        // Thu thập tổ hợp từ combinationData nếu có
        if (variant.combinationData && Object.keys(variant.combinationData).length) {
            Object.entries(variant.combinationData).forEach(([key, combo]) => {
                combinationData[key] = {
                    code: combo.code || '',
                    barcode: combo.barcode || '',
                    sale_price: Number(combo.sale_price) || 0,
                    quantity_on_hand: Number(combo.quantity_on_hand) || 0,
                    supplier_ids: Array.isArray(combo.supplier_ids) ? [...combo.supplier_ids] : [],
                    warehouse_zone_id: combo.warehouse_zone_id || '',
                    custom_location_name: combo.custom_location_name || '',
                };
                console.log(`Combination ${key} from combinationData:`, combinationData[key]);
            });
        }
        // Thu thập tổ hợp từ combinations nếu có
        else if (variant.combinations && variant.combinations.length) {
            variant.combinations.forEach((combo, comboIndex) => {
                if (!combo.attribute_value_ids || !combo.attribute_value_ids.length) {
                    console.warn(`Skipping invalid combination at variant ${variantIndex}, combo ${comboIndex}:`, combo);
                    return;
                }
                const key = combo.attribute_value_ids.join('-');
                combinationData[key] = {
                    code: combo.code || '',
                    barcode: combo.barcode || '',
                    sale_price: Number(combo.sale_price) || 0,
                    quantity_on_hand: Number(combo.quantity_on_hand) || 0,
                    supplier_ids: Array.isArray(combo.supplier_ids) ? [...combo.supplier_ids] : [],
                    warehouse_zone_id: combo.warehouse_zone_id || '',
                    custom_location_name: combo.custom_location_name || '',
                };
                console.log(`Combination ${key} from combinations:`, combinationData[key]);
            });
        }
        // Tạo tổ hợp từ attributes nếu không có combinationData hoặc combinations
        else {
            const valueIds = variant.attributes
                ? variant.attributes
                    .filter(attr => Array.isArray(attr.attribute_value_ids) && attr.attribute_value_ids.length)
                    .flatMap(attr => attr.attribute_value_ids)
                : [];
            if (valueIds.length) {
                const key = valueIds.join('-');
                // Chỉ tạo mới nếu chưa có trong combinationData
                if (!combinationData[key]) {
                    combinationData[key] = {
                        code: variant.code || '',
                        barcode: variant.barcode || '',
                        sale_price: Number(variant.sale_price) || 0,
                        quantity_on_hand: Number(variant.quantity_on_hand) || 0,
                        supplier_ids: Array.isArray(variant.supplier_ids) ? [...variant.supplier_ids] : [],
                        warehouse_zone_id: variant.warehouse_zone_id || '',
                        custom_location_name: variant.custom_location_name || '',
                    };
                    console.log(`Combination ${key} from attributes:`, combinationData[key]);
                }
            } else {
                console.log(`No valid attribute_value_ids for variant ${variantIndex}`);
            }
        }
    });

    const result = [{
        code: '',
        barcode: '',
        sale_price: 0,
        unit_id: '',
        attributes: allAttributes.length > 0 ? allAttributes : [{ attribute_id: '', attribute_value_ids: [] }],
        combinationData,
    }];

    console.log('Merged variants result:', result); // Debug kết quả cuối cùng
    return result;
};

const form = useForm({
    name: props.product.name || '',
    code: props.product.code || '',
    min_stock: props.product.min_stock || 30,
    description: props.product.description || '',
    category_id: props.product.category_id || '',
    expiration_date: props.product.expiration_date || '',
    production_date: props.product.production_date || '',
    base_unit_id: props.product.base_unit_id || '',
    variants: mergeVariants(props.product.variants),
    unit_conversions: props.product.unit_conversions || [],
    images: [],
    deleted_image_ids: [],
    simple_sale_price: props.product.simple_sale_price || '',
    simple_quantity: props.product.simple_quantity || '',
    simple_barcode: props.product.simple_barcode || '',
    supplier_ids: props.product.supplier_ids || [],
    warehouse_zone_id: props.product.warehouse_zone_id || '',
    custom_location_name: props.product.custom_location_name || '',
});

// Ảnh
const maxImages = 4;
const imagePreviews = ref([]);
const existingImages = ref([]);
const deletedImageIds = ref([]);

onMounted(() => {
    console.log('props.product.variants:', props.product.variants);
    console.log('props.suppliers:', props.suppliers); // Debug suppliers
    attributeValues.value = props.attributeValues || {};
    if (props.product.images) {
        existingImages.value = props.product.images.map((image, index) => ({
            id: image.id || index,
            url: image.url || `/storage/${image.path}`,
        }));
        imagePreviews.value = existingImages.value.map((img) => img.url);
    }
    if (props.product.production_date) {
        const date = new Date(props.product.production_date);
        form.production_date = date.toISOString().split('T')[0];
    }
});

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);

    if (!files || files.length === 0) return;

    for (const file of files) {
        if (form.images.length + existingImages.value.length >= maxImages) {
            form.errors.images = `Chỉ cho phép tối đa ${maxImages} ảnh.`;
            break;
        }

        if (!file.type.match('image.*')) {
            form.errors.images = 'Chỉ chấp nhận file ảnh.';
            continue;
        }

        if (file.size > 2 * 1024 * 1024) {
            form.errors.images = 'Mỗi ảnh không được vượt quá 2MB.';
            continue;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            form.images.push({
                name: file.name,
                data: e.target.result,
            });
            imagePreviews.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    form.errors.images = null;
};

const removeImage = (index, isExisting = false) => {
    if (isExisting) {
        const removedImage = existingImages.value[index];
        if (removedImage.id) {
            deletedImageIds.value.push(removedImage.id);
        }
        existingImages.value.splice(index, 1);
    } else {
        form.images.splice(index - existingImages.value.length);
    }
    imagePreviews.value.splice(index, 1);
};

// Biến thể sản phẩm
const hasVariant = ref(!!props.product.variants?.length);
watch(hasVariant, (newVal) => {
    if (newVal) {
        form.simple_sale_price = '';
        form.simple_quantity = '';
        form.simple_barcode = '';
        form.supplier_ids = [];
        form.warehouse_zone_id = '';
        form.custom_location_name = '';
    } else {
        form.variants = [{
            code: '',
            barcode: '',
            sale_price: 0,
            unit_id: '',
            attributes: [{ attribute_id: '', attribute_value_ids: [] }],
            combinationData: {},
        }];
    }
});
const variantBackup = ref(null);
// Debug form.errors
watch(() => form.errors, (newErrors) => {
    console.log('form.errors updated:', newErrors);
}, { deep: true });

// Submit form
const handleSubmitForm = () => {

    if (hasVariant.value && form.variants[0]?.combinationData) {
        const backup = JSON.parse(JSON.stringify(form.variants[0]));
        variantBackup.value = backup;
    }
    console.log('Dữ liệu form trước khi gửi:', form);
    transformFormBeforeSubmit();
    console.log('Dữ liệu form sau transform:', form);

    form.images = [
        ...existingImages.value.map((img) => ({ id: img.id, url: img.url })),
        ...form.images,
    ];
    form.deleted_image_ids = deletedImageIds.value;

    form.put(route('admin.products.update', props.product.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Cập nhật sản phẩm thành công 🎉');
            resetForm();
            variantBackup.value = null;
        },
        onError: (errors) => {
            if (hasVariant.value && variantBackup.value) {
                form.variants = mergeVariants([variantBackup.value]);
            }
            console.error('Lỗi validate:', errors);
            console.log('Nội dung form.errors:', form.errors);
        },
    });
};

// Reset form về dữ liệu ban đầu
const resetForm = () => {
    form.reset();
    form.name = props.product.name || '';
    form.code = props.product.code || '';
    form.min_stock = props.product.min_stock || 30;
    form.description = props.product.description || '';
    form.category_id = props.product.category_id || '';
    form.expiration_date = props.product.expiration_date || '';
    form.production_date = props.product.production_date || '';
    form.base_unit_id = props.product.base_unit_id || '';
    form.variants = mergeVariants(props.product.variants);
    form.unit_conversions = props.product.unit_conversions || [];
    form.images = [];
    form.deleted_image_ids = [];
    form.simple_sale_price = props.product.simple_sale_price || '';
    form.simple_quantity = props.product.simple_quantity || '';
    form.simple_barcode = props.product.simple_barcode || '';
    form.supplier_ids = props.product.supplier_ids || [];
    form.warehouse_zone_id = props.product.warehouse_zone_id || '';
    form.custom_location_name = props.product.custom_location_name || '';

    existingImages.value = props.product.images
        ? props.product.images.map((image, index) => ({
            id: image.id || index,
            url: image.url || `/storage/${image.path}`,
        }))
        : [];
    imagePreviews.value = existingImages.value.map((img) => img.url);
    deletedImageIds.value = [];
    hasVariant.value = !!props.product.variants?.length;
};

// Các hàm liên quan đến thuộc tính
const attributeValues = ref({});
const handleCreateAttribute = async (name) => {
    try {
        const response = await axios.post(route('admin.attributes.store'), { name });
        props.attributes.push(response.data);
    } catch (error) {
        console.error('Không thể tạo thuộc tính mới:', error);
    }
};

const handleCreateAttributeValue = async (attributeId, name) => {
    try {
        const response = await axios.post(route('admin.attribute-values.store'), {
            attribute_id: attributeId,
            name,
        });
        if (!attributeValues.value[attributeId]) {
            attributeValues.value[attributeId] = [];
        }
        attributeValues.value[attributeId].push(response.data);
    } catch (error) {
        console.error('Không thể tạo giá trị thuộc tính mới:', error);
    }
};

const getAvailableAttributes = (variant, currentAttrId = null) => {
    const selectedIds = variant.attributes
        .map((attr) => attr.attribute_id)
        .filter((id) => id && id !== currentAttrId);
    return props.attributes.filter((attr) => !selectedIds.includes(attr.id));
};

const addVariantAttribute = () => {
    form.variants[0].attributes.push({
        attribute_id: '',
        attribute_value_ids: [],
    });
};

const removeVariantAttribute = (attrIndex) => {
    form.variants[0].attributes.splice(attrIndex, 1);
};

// Danh mục sản phẩm
const searchQuery = ref('');
const handleSearch = (query) => {
    searchQuery.value = query.toLowerCase();
};
const getLevelColor = (level) => {
    const colors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'];
    return colors[level] || colors[colors.length - 1];
};
const flattenedCategories = computed(() => {
    const result = [];
    result.push({
        id: null,
        name: 'Danh mục gốc',
        formattedName: 'Danh mục gốc',
        level: 0,
    });
    function flatten(categories, level = 1) {
        categories.forEach((category) => {
            result.push({
                id: category.id,
                name: category.name,
                formattedName: `${'— '.repeat(level)}${category.name}`,
                level,
                original: category,
            });
            if (category.children && category.children.length > 0) {
                flatten(category.children, level + 1);
            }
        });
    }
    flatten(props.categories);
    return result;
});
const filteredCategories = computed(() => {
    if (!searchQuery.value) return flattenedCategories.value;
    return flattenedCategories.value.filter(
        (category) =>
            category.name.toLowerCase().includes(searchQuery.value) ||
            (category.level > 0 &&
                category.original?.description?.toLowerCase().includes(searchQuery.value)),
    );
});

// Đơn vị sản phẩm
const addConversion = () => {
    form.unit_conversions.push({
        to_unit_id: '',
        conversion_factor: '',
    });
};
const removeConversion = (index) => {
    form.unit_conversions.splice(index, 1);
};

// Ghép biến thể
const generateCombinations = (attributes) => {
    const values = attributes
        .map((attr) => attr.attribute_value_ids)
        .filter((arr) => Array.isArray(arr) && arr.length > 0);
    if (values.length === 0) return [];
    return values.reduce(
        (acc, curr) => {
            const result = [];
            acc.forEach((a) => {
                curr.forEach((b) => {
                    result.push([...a, b]);
                });
            });
            return result;
        },
        [[]],
    );
};

const variantCombinations = computed(() => {
    if (!form.variants.length || !hasVariant.value) return [];

    const variant = form.variants[0];
    console.log('biến thể:', JSON.parse(JSON.stringify(variant)));

    if (!variant.combinationData) variant.combinationData = {};

    const newCombinations = generateCombinations(variant.attributes);
    const combinationSet = new Set();

    const combinations = [];

    // 1. Xử lý tổ hợp mới
    newCombinations.forEach((combo) => {
        const key = combo.join('-');
        combinationSet.add(key);

        if (deletedCombinationKeys.value.includes(key)) return;

        const labels = combo.map((id) => {
            const attrId = variant.attributes.find((attr) =>
                attr.attribute_value_ids.includes(id)
            )?.attribute_id;
            const value = attributeValues.value[attrId]?.find((v) => v.id === id);
            return value?.name || '';
        });

        if (!variant.combinationData[key]) {
            variant.combinationData[key] = {
                code: '',
                barcode: '',
                sale_price: 0,
                quantity_on_hand: 0,
                supplier_ids: [],
                warehouse_zone_id: '',
                custom_location_name: '',
            };
        }

        combinations.push({
            key,
            label: labels.join(' - '),
            data: variant.combinationData[key],
        });
    });

    // 2. Xử lý tổ hợp cũ từ combinationData
    Object.keys(variant.combinationData).forEach((key) => {
        if (combinationSet.has(key) || deletedCombinationKeys.value.includes(key)) return;

        const valueIds = key.split('-').map(id => parseInt(id));
        const labels = valueIds.map((id) => {
            let attrId = null;
            for (const [attributeId, values] of Object.entries(attributeValues.value)) {
                if (values.some(v => v.id === id)) {
                    attrId = attributeId;
                    break;
                }
            }
            const value = attributeValues.value[attrId]?.find((v) => v.id === id);
            return value?.name || 'Không xác định';
        });

        combinations.push({
            key,
            label: labels.join(' - '),
            data: variant.combinationData[key],
        });
    });
    return combinations;
    // return combinations.sort((a, b) => a.label.localeCompare(b.label));
});
const deletedCombinationKeys = ref([]);
const removeCombinationItem = (key) => {
    if (form.variants[0].combinationData && form.variants[0].combinationData[key]) {
        delete form.variants[0].combinationData[key];
    }
    if (!deletedCombinationKeys.value.includes(key)) {
        deletedCombinationKeys.value.push(key);
    }
};

const transformFormBeforeSubmit = () => {
    if (hasVariant.value) {
        const variant = form.variants[0];
        if (!variant.combinationData) return;

        const combinations = [];
        const keyToIndexMap = {}; // Ánh xạ key -> index

        for (const key in variant.combinationData) {
            if (deletedCombinationKeys.value.includes(key)) continue;
            const valueIds = key.split('-').map((id) => parseInt(id));
            const comboData = variant.combinationData[key];

            const index = combinations.length;
            keyToIndexMap[key] = index; // Lưu ánh xạ key -> index

            combinations.push({
                attribute_value_ids: valueIds,
                code: comboData.code ?? null,
                barcode: comboData.barcode ?? null,
                sale_price: Number(comboData.sale_price ?? 0),
                quantity_on_hand: Number(comboData.quantity_on_hand ?? 0),
                supplier_ids: comboData.supplier_ids ?? [],
                warehouse_zone_id: comboData.warehouse_zone_id ?? null,
                custom_location_name: comboData.custom_location_name ?? null,
            });
        }

        variant.combinations = combinations;
        delete variant.combinationData;

        // Lưu ánh xạ keyToIndexMap vào form để sử dụng khi hiển thị lỗi
        form.keyToIndexMap = keyToIndexMap;

        form.variants = [variant];
        form.simple_sale_price = null;
        form.simple_quantity = null;
        form.simple_barcode = null;
        form.supplier_ids = [];
        form.warehouse_zone_id = null;
        form.custom_location_name = null;
    } else {
        form.variants = [];
        form.unit_conversions = form.unit_conversions.filter(
            (uc) => uc.to_unit_id && uc.conversion_factor,
        );
        form.simple_sale_price = Number(form.simple_sale_price ?? 0);
        form.simple_quantity = Number(form.simple_quantity ?? 0);
        form.supplier_ids = form.supplier_ids ?? [];
        form.simple_barcode = form.simple_barcode ?? null;
        form.custom_location_name = form.custom_location_name ?? null;
    }
};
</script>
<style></style>