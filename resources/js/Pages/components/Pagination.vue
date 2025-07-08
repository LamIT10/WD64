<template>
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Hiển thị <span class="font-medium">{{ data.from }}</span> đến
            <span class="font-medium">{{ data.to }}</span> của
            <span class="font-medium">{{ data.total }}</span> kết quả
        </div>
        <div class="flex space-x-1">
            <Link v-if="data.prev_page_url" :href="data.prev_page_url"
                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
            <i class="fas fa-chevron-left"></i>
            </Link>
            <span v-else class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>

            <!-- Page Numbers -->
            <template v-for="page in data.links">
                <Link v-if="page.url && !page.active && page.label !== '...'"
                    :href="page.url + '&perPage=' + newPerPage + '&'+ newUrl" 
                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                {{ page.label }}
                </Link>
                <span v-else-if="page.active" class="px-3 py-1 bg-indigo-600 text-white rounded-md">
                    {{ page.label }}
                </span>
                <span v-else-if="page.label === '...'"
                    class="px-3 py-1 border border-gray-300 rounded-md text-gray-600">
                    {{ page.label }}
                </span> 
            </template>

            <Link v-if="data.next_page_url" :href="data.next_page_url + '&perPage=' + newPerPage + '&' + newUrl" 
                class="px-3 py-1 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
            <i class="fas fa-chevron-right"></i>
            </Link>
            <span v-else class="px-3 py-1 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </span>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
const { data, perPage, searchForm } = defineProps({
    data: Object,
    perPage: String,
    searchForm: Object
})
const array = Object.entries(searchForm)
console.log(array);
const newUrl = ref("");
for (let index = 0; index < array.length; index++) {
    if(array[index][0] == 'perPage' || array[index][0] ==    'page') {
        continue; 
    }
    else if (array.length - 1 == index) {
        newUrl.value +=     array[index][0] +"=" + array[index][1];
    } else {
        newUrl.value += array[index][0] + "=" + array[index][1] + "&";
    }
}
console.log(newUrl.value);
const newPerPage = perPage == null ? "20" : perPage.trim == "" ? "20" : perPage;
const editLabel = () => {
    data.links.forEach(element => {
        if (element.label == "&laquo; Previous") element.label = "Previous";
        if (element.label == "Next &raquo;") element.label = "Next"
    });
}
editLabel();

</script>

<style lang="scss" scoped></style>