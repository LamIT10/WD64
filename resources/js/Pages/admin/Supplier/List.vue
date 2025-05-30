<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100 p-6">
            <div class="flex flex-col">
                <Link :href="route('admin.suppliers.create')" class="">
                    <button class="mb-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Supplier</button>
                </Link>
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full text-center">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="supplier in suppliers" :key="supplier.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ supplier.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ supplier.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ supplier.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ supplier.phone }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
    suppliers: {
        type: Array,
        default: () => []
    },
});

</script>

<style lang="scss" scoped></style>
