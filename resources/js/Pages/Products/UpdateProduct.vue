<template>
    <!-- Main modal -->
    <div class="fixed inset-0 bg-opacity-50 backdrop-blur-sm flex items-center justify-center p-4 z-40">
        <div id="crud-modal" tabindex="-1" class="relative z-50 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create New Product
                    </h3>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crud-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>

                    </button>
                </div>
                <!-- Modal body -->
                <form @submit.prevent="handleFormUpdate" class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name" v-model="form.name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name">
                            <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                            <input type="number" name="price" id="price" v-model="form.price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="$2999">
                            <span v-if="errors.price" class="text-red-500">{{ errors.price }}</span>

                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                            <select v-model="form.category_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option v-for="category in categories.data" :value="category.id" :key="category.id">
                                    {{ category.name }}
                                </option>
                            </select>

                            <span v-if="errors.category_id" class="text-red-500">{{ errors.category_id }}</span>

                        </div>

                        <div class="col-span-2">
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                            <input type="file" name="" id="price" @change="handleImage($event)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="$2999">
                            <span v-if="errors.file" class="text-red-500">{{ errors.file }}</span>

                        </div>
                        <div class="col-span-2">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                                Description</label>
                            <textarea id="description" rows="4" v-model="form.description"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Write product description here"></textarea>
                            <span v-if="errors.description" class="text-red-500">{{ errors.description }}</span>

                        </div>
                    </div>
                    <button type="submit" :disable="form.processing"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Update product
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from "ziggy-js";


const errors = ref({});

const form = useForm({
  _method: 'PATCH',
    category_id: Number(product.category_id),
    name: product.name,
    description: product.description,
    price: Number(product.price),
    file: null,
});
const { categories, product } = defineProps({
    categories: {
        type: Object
    },
    product: {
        type: Object,
    }
})

const handleImage = (event) => {
    form.file = event.target.files[0];
}
const handleFormUpdate = () => {
    form.post(route('products.update', {
        id: product.id
    }), {
        onSuccess: (res) => {
          
            emit('close-modal');
        },
        onError: (xhr) => {
            errors.value = xhr;
       
        }

    });
}

const emit = defineEmits(['close-modal']);

const closeModal = () => {
    emit("close-modal");
}
</script>

<style lang="scss" scoped></style>