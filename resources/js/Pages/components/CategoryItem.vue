<template>
    <li>
        {{ category.name }}
        <ul v-if="category.all_children && category.all_children.length">
            <CategoryItem v-for="(children, index) in category.all_children" :key="index" :category="children">
            </CategoryItem>
        </ul>
    </li>
</template>

<script setup>
import CategoryItem from './CategoryItem.vue'
const props = defineProps({
    category: Object
})
</script>


<style scoped>

</style>

