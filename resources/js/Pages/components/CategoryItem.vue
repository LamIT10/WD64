<template>
    <template v-if="category">
        <!-- Parent row -->
        <tr class="hover:bg-gray-50 transition-all duration-200 ease-out group">
            <td
                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 cursor-pointer flex items-center mx-2"
                @click="toggle"
                :style="{
                    paddingLeft: `${level * 24 + (hasChildren ? 0 : 24)}px`,
                }"
            >
                <span
                    v-if="hasChildren"
                    class="mr-2 transition-all duration-200 ease-out w-4 h-4 flex items-center justify-center rounded-full hover:bg-gray-200"
                    :class="{
                        'transform rotate-90 text-blue-500': isOpen,
                        'text-gray-400': !isOpen,
                    }"
                >
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <span class="flex items-center">
                    <span
                        class="w-2 h-2 rounded-full mr-3 transition-all duration-200"
                        :class="{
                            'bg-blue-500': level === 0,
                            'bg-green-500': level === 1,
                            'bg-purple-500': level === 2,
                            'bg-yellow-500': level >= 3,
                        }"
                    ></span>
                    <span
                        class="transition-all duration-200"
                        :class="{ 'font-semibold text-gray-900': level === 0 }"
                    >
                        {{ category.name }}
                    </span>
                </span>
            </td>

            <td
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 transition-all duration-200"
            >
                {{ category.description || "---" }}
            </td>

            <td
                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
            >
                <div class="relative inline-block text-left">
                    <button
                        class="text-gray-400 hover:text-gray-600 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                        @click.stop="toggleDropdown"
                    >
                        <i class="fas fa-ellipsis-v"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95">
                        <div v-if="dropdownOpen"
                            class="origin-top-right right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            ref="dropdown" @click.stop>
                            <div class="py-1">
                                <Link :href="route('admin.categories.edit', category.id)"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                <i class="fas fa-edit mr-3 text-blue-500 w-4 text-center"></i>
                                <span>Sửa danh mục</span>
                                </Link>
                                <Link :href="route('admin.categories.show', category.id)"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                <i class="fas fa-plus-circle mr-3 text-green-500 w-4 text-center"></i>
                                <span>Chi tiết</span>
                                </Link>
                                <form :action="route('admin.categories.destroy', category.id)" method="POST"
                                    @submit.prevent="() => $inertia.delete(route('admin.categories.destroy', category.id))">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" :value="csrf">

                                    <button type="submit"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150">
                                        <i class="fas fa-trash-alt mr-3 text-red-500 w-4 text-center"></i>
                                        <span>Xóa</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </transition>
                </div>
            </td>
        </tr>

        <transition
            @before-enter="beforeEnter"
            @enter="enter"
            @leave="leave"
            :css="false"
        >
            <div v-if="hasChildren && isOpen" class="overflow-hidden">
                <CategoryItem
                    v-for="(child, index) in category.all_children"
                    :key="index"
                    :category="child"
                    :level="level + 1"
                />
            </div>
        </transition>
    </template>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { Link } from "@inertiajs/vue3";
import CategoryItem from "./CategoryItem.vue";

const props = defineProps({
    category: Object,
    level: {
        type: Number,
        default: 0,
    },
});

const isOpen = ref(false);
const dropdownOpen = ref(false);
const dropdown = ref(null);

const hasChildren = computed(
    () => props.category.all_children && props.category.all_children.length > 0
);

function toggle() {
    if (hasChildren.value) {
        isOpen.value = !isOpen.value;
    }
}

function toggleDropdown() {
    dropdownOpen.value = !dropdownOpen.value;
}

function handleClickOutside(event) {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        dropdownOpen.value = false;
    }
}

function beforeEnter(el) {
    el.style.height = "0";
    el.style.opacity = "0";
}

function enter(el, done) {
    const childCount = props.category.all_children.length;
    const rowHeight = 56;
    const targetHeight = childCount * rowHeight;

    el.style.transition = "height 0.3s ease, opacity 0.3s ease";
    el.style.overflow = "hidden";

    setTimeout(() => {
        el.style.height = targetHeight + "px";
        el.style.opacity = "1";
    }, 10);

    const onTransitionEnd = () => {
        el.style.height = "";
        el.style.overflow = "";
        done();
    };

    el.addEventListener("transitionend", onTransitionEnd, { once: true });
}

function leave(el, done) {
    el.style.transition = "height 0.2s ease, opacity 0.2s ease";
    el.style.overflow = "hidden";
    el.style.height = el.scrollHeight + "px";

    setTimeout(() => {
        el.style.height = "0";
        el.style.opacity = "0";
    }, 10);

    const onTransitionEnd = () => {
        done();
    };

    el.addEventListener("transitionend", onTransitionEnd, { once: true });
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
/* Custom scrollbar for dropdown */
.dropdown-content::-webkit-scrollbar {
    width: 6px;
}

.dropdown-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.dropdown-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.dropdown-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Smooth highlight transition */
.highlight-item {
    transition: all 0.2s ease;
}

.highlight-item:hover {
    background-color: #f8fafc;
    transform: translateX(2px);
}
</style>
