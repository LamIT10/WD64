import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { createPinia } from "pinia";
import { route } from "ziggy-js";
import { Ziggy } from "./ziggy";
import { useAuthStore } from "./stores/auth"; // Thêm import authStore

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.config.globalProperties.route = (
            name,
            params = {},
            absolute = true
        ) => {
            return route(name, params, absolute, Ziggy);
        };

        const pinia = createPinia();
        app.use(pinia);

        // Thêm directive v-can
        app.directive('can', (el, binding) => {
            const authStore = useAuthStore();
            const permission = binding.value;
            if (!authStore.hasPermission(permission)) {
                el.style.display = 'none'; // Ẩn phần tử nếu không có quyền
            }
        });
        // Thêm directive v-can
        app.directive('has', (el, binding) => {
            const authStore = useAuthStore();
            const role = binding.value;
            if (!authStore.hasRole(role)) {
                el.style.display = 'none'; // Ẩn phần tử nếu không có quyền
            }
        });
        app.use(plugin).mount(el);
    },
});