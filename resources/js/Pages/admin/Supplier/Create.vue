<template>
    <AppLayout>
        <div class="min-h-screen bg-gray-100 p-6">
            <h3>FORM</h3>
            <Link :href="route('admin.suppliers.store')" class="">
                    <button class="mb-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Supplier</button>
                </Link>
        </div>
    </AppLayout>
</template>

<script setup>
import { route } from 'ziggy-js';
import AppLayout from '../Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
    suppliers: {
        type: Array,
        default: () => []
    },
});

</script>

<style lang="scss" scoped></style>
