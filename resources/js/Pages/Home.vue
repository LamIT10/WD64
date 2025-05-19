<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Product Management</h1>
        <p class="text-gray-600 mb-6">Manage your product inventory efficiently</p>

        <div class="overflow-x-auto shadow-md rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Image</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, index) in products" :key="product.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{  index + 1 }}
                        
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ product.name }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ product.price.toLocaleString() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ product.description }}
                        </td>
                        <td class="px-6 py-4">
                            <img :src="`/storage/${product.image}`" :alt="product.name" width="70">
                        </td>
                        <td class="px-6 py-4">
                            {{ product.category_name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="onOpenEdit(product)"
                                class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                Edit
                            </button>
                            <button @click="onDestroyProduct(product.id)"
                                class="px-3 ms-2 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                Destroy
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button @click="onOpenCreate()"
            class="mt-5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            Create New Product
        </button>
        <UpdateProduct v-if="product.name != null" :product="product" @close-modal="closeModal"
            :categories="categories" />
        <CreateProduct v-if="OpenCreateProduct" @close-modal="closeModal" :categories="categories" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import UpdateProduct from './Products/UpdateProduct.vue';
import CreateProduct from './Products/CreateProduct.vue';
import { useForm } from '@inertiajs/vue3';

const product = ref({});
const OpenCreateProduct = ref(false);

const onOpenEdit = (productEdit) => {
    product.value = productEdit;
}
const onOpenCreate = () => {
    OpenCreateProduct.value = true;
}

const { products, categories } = defineProps({
    products: {
        type: Array,
        required: true
    },
    categories: {
        type: Object,
        required: true
    }
});
const formDelete = useForm();
const onDestroyProduct = (id) => {
    if (confirm('Bạn có xác nhận xoá sản phẩm này ko')) {
        formDelete.delete(route('products.destroy', {
            id: id,
        }), {
            onSuccess: (res) => {
                console.log(res);

            },
            onError: (xhr) => {
                console.log(xhr);

            }
        });
    };
}
const closeModal = () => {
    product.value = {};
    OpenCreateProduct.value = false;
}

</script>