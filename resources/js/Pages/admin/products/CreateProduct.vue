<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div class="p-4 shadow rounded bg-white mb-4 flex justify-between items-center">
                <h5 class="text-lg text-indigo-700 font-semibold">
                    Thêm Sản Phẩm Mới
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
                                <label class="block text-sm font-medium text-indigo-700">
                                    Hình ảnh sản phẩm
                                </label>
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
                                            <div v-for="i in maxImages" :key="i"
                                                class="relative w-13 h-12 border border-dashed border-gray-300 rounded">
                                                <template v-if="form.images[i - 1]">
                                                    <img :src="form.images[i - 1].preview"
                                                        class="w-full h-full object-cover rounded" />
                                                    <button type="button" @click="removeImage(i - 1)"
                                                        class="absolute top-0 right-0 p-1 text-red-600 bg-white rounded-full shadow hover:bg-red-50 transform translate-x-1/2 -translate-y-1/2">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="form.errors.images" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.images }}
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
                            <div class="grid grid-cols-4 grid-rows-1 gap-4">
                                <div class="col-span-2">
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

                                <div class="col-span-2">
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-indigo-700   ">
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
                                    </div>
                                    <!-- Hệ số quy đổi -->
                                    <div>
                                        <label class="block text-sm font-medium text-indigo-700">
                                            Hệ số quy đổi
                                        </label>
                                        <input v-model="conversion.conversion_factor" type="number" step="0.0001"
                                            min="0.0001" placeholder="Ví dụ: 10"
                                            class="mt-1 w-full px-4 py-2 text-gray-700 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                                    </div>
                                    <!-- Nút xóa -->
                                    <div class="flex item-center mt-5">
                                        <button type="button" @click="removeConversion(index)"
                                            class="text-red-600 hover:underline text-sm ">
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
                    <div class="col-span-2 row-span-2">
                        tạo tổ hợp giá trị
                    </div>
                    <!-- Phần biến thể sản phẩm -->
                    <div class="border-t border-gray-200 pt-6 col-span-3">
                        <h6 class="text-md font-semibold text-indigo-700 mb-4">Biến thể sản phẩm</h6>

                        <!-- Thêm biến thể -->
                        <div v-for="(variant, index) in form.variants" :key="index"
                            class="mb-6 p-4 border border-gray-200 rounded">
                            <!-- Thuộc tính biến thể -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-indigo-700 mb-2">
                                    Thuộc tính biến thể
                                </label>
                                <div v-for="(attribute, attrIndex) in variant.attributes" :key="attrIndex"
                                    class="mb-4 space-y-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <Multiselect v-model="attribute.attribute_id"
                                        :options="getAvailableAttributes(variant, attribute.attribute_id)" label="name"
                                        value-prop="id" placeholder="Chọn thuộc tính" :searchable="true"
                                        :can-clear="true" :create-option="true"
                                        @create-option="handleCreateAttribute" />


                                    <div class="space-y-2 flex gap-3">
                                        <Multiselect v-model="attribute.attribute_value_ids"
                                            :options="attributeValues[attribute.attribute_id] || []" label="name"
                                            value-prop="id" placeholder="Chọn nhiều giá trị" mode="tags"
                                            :searchable="true" :can-clear="true" :create-option="true"
                                            @create-option="(val) => handleCreateAttributeValue(attribute.attribute_id, val)" />


                                        <button type="button" @click="removeVariantAttribute(index, attrIndex)"
                                            class="text-red-600 hover:underline text-sm mt-1">
                                            <i class="fas fa-times mr-1"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" @click="addVariantAttribute(index)"
                                    v-if="variant.attributes.length < 5"
                                    class="mt-2 px-3 py-1 text-sm text-indigo-600 bg-indigo-50 rounded hover:bg-indigo-100">
                                    <i class="fas fa-plus mr-1"></i> Thêm thuộc tính
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3 border border-gray-100 rounded p-4 bg-gray-50">
                        <h6 class="text-sm font-semibold text-indigo-700 mb-2">Tổ hợp giá trị đã chọn</h6>
                        <ul class="list-disc list-inside text-sm text-gray-700">
                            <li v-for="item in variantCombinations" :key="item.key">
                                {{ item.label }}
                            </li>
                            <li v-if="variantCombinations.length === 0" class="text-gray-400">Chưa có tổ hợp nào</li>
                        </ul>
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
                            <span v-if="!form.processing" class="font-medium">Thêm sản phẩm</span>
                            <span v-else class="font-medium">Đang xử lý...</span>
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
import { ref, onMounted, computed } from 'vue';
import Multiselect from "@vueform/multiselect";

const props = defineProps({
    categories: Array,
    units: Array,
    attributes: Array,
    attributeValues: Object,
});

const form = useForm({
    name: '',
    code: '',
    min_stock: 0,
    description: '',
    category_id: '',
    expiration_date: '',
    production_date: '',
    base_unit_id: '',
    variants: [
        {
            barcode: '',
            sale_price: 0,
            unit_id: '',
            attributes: [
                {
                    attribute_id: '',
                    attribute_value_ids: []
                }
            ]
        }
    ],
    images: []
});

//Image
const maxImages = 4;

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);

    if (!files || files.length === 0) return;

    files.forEach((file) => {

        if (file.size > 2 * 1024 * 1024) {
            form.errors.images = 'Mỗi ảnh không được vượt quá 2MB';
            return;
        }

        if (!file.type.match('image.*')) {
            form.errors.images = 'Chỉ chấp nhận file ảnh';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            if (form.images.length >= maxImages) {
                form.errors.images = `Chỉ cho phép tối đa ${maxImages} ảnh.`;
                return;
            }
            form.images.push({
                file: file,
                preview: e.target.result
            });
            form.errors.images = null;
        };
        reader.readAsDataURL(file);
    });
};

const removeImage = (index) => {
    form.images.splice(index, 1);
};

const attributeValues = ref({});
// Biến thể sản phẩm
const handleCreateAttribute = async (name) => {
    try {
        const response = await axios.post(route('admin.attributes.store'), { name });
        props.attributes.push(response.data); // Cập nhật danh sách
    } catch (error) {
        console.error("Không thể tạo thuộc tính mới:", error);
    }
};
const getAvailableAttributes = (variant, currentAttrId = null) => {
    const selectedIds = variant.attributes
        .map(attr => attr.attribute_id)
        .filter(id => id && id !== currentAttrId);

    return props.attributes.filter(attr => !selectedIds.includes(attr.id));
};
const addVariantAttribute = (variantIndex) => {
    form.variants[variantIndex].attributes.push({
        attribute_id: '',
        attribute_value_id: ''
    });
};

const removeVariantAttribute = (variantIndex, attrIndex) => {
    form.variants[variantIndex].attributes.splice(attrIndex, 1);
};

const handleSubmitForm = () => {
    form.post(route('admin.products.store'), {
        onError: () => {
            console.error("Something went wrong. Please try again.");
        }
    });
};

onMounted(() => {
    attributeValues.value = props.attributeValues || {};
});

//Danh Mục Sản Phẩm
const searchQuery = ref('')

const handleSearch = (query) => {
    searchQuery.value = query.toLowerCase()
}
const getLevelColor = (level) => {
    const colors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444']
    return colors[level] || colors[colors.length - 1]
}
const flattenedCategories = computed(() => {
    const result = []

    result.push({
        id: null,
        name: 'Danh mục gốc',
        formattedName: 'Danh mục gốc',
        level: 0
    })

    function flatten(categories, level = 1) {
        categories.forEach(category => {
            result.push({
                id: category.id,
                name: category.name,
                formattedName: `${'— '.repeat(level)}${category.name}`,
                level,
                original: category
            })

            if (category.children && category.children.length > 0) {
                flatten(category.children, level + 1)
            }
        })
    }

    flatten(props.categories)
    return result
})

const filteredCategories = computed(() => {
    if (!searchQuery.value) return flattenedCategories.value

    return flattenedCategories.value.filter(category => {
        return category.name.toLowerCase().includes(searchQuery.value) ||
            (category.level > 0 && category.original?.description?.toLowerCase().includes(searchQuery.value))
    })
})
// Kết thúc phần danh mục

// Đơn vị sản phẩm 
form.unit_conversions = ref([
    // Mặc định chưa có dòng nào
]);

const addConversion = () => {
    form.unit_conversions.push({
        to_unit_id: '',
        conversion_factor: ''
    });
};

const removeConversion = (index) => {
    form.unit_conversions.splice(index, 1);
};

const resetForm = () => {
    form.reset()
    form.parent_id = null
}

//Ghép biến thể
const generateCombinations = (attributes) => {
    const values = attributes
        .map(attr => attr.attribute_value_ids)
        .filter(arr => Array.isArray(arr) && arr.length > 0);

    if (values.length === 0) return [];

    return values.reduce((acc, curr) => {
        const result = [];
        acc.forEach(a => {
            curr.forEach(b => {
                result.push([...a, b]);
            });
        });
        return result;
    }, [[]]);
};
const variantCombinations = computed(() => {
    if (!form.variants.length) return [];

    return form.variants.map((variant, index) => {
        const attributeCombinations = generateCombinations(variant.attributes);

        return attributeCombinations.map((combo) => {
            const labels = combo.map(id => {
                const attrId = variant.attributes.find(attr => attr.attribute_value_ids.includes(id))?.attribute_id;
                const value = attributeValues.value[attrId]?.find(v => v.id === id);
                return value?.name || '';
            });

            return {
                key: combo.join('-'),
                label: labels.join(' - ')
            };
        });
    }).flat(); // Flatten toàn bộ tổ hợp từ các biến thể
});


</script>

<style></style>