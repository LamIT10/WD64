import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { createPinia } from "pinia";
import { route } from "ziggy-js";
import { Ziggy } from "./ziggy";
import { useAuthStore } from "./stores/auth"; // Thêm import authStore
import flatpickr from 'flatpickr'; // Thêm import flatpickr
import { Vietnamese } from 'flatpickr/dist/l10n/vn.js'; // Thêm import locale tiếng Việt
import './bootstrap';

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
        
        // Thêm directive v-has
        app.directive('has', (el, binding) => {
            const authStore = useAuthStore();
            const role = binding.value;
            if (!authStore.hasRole(role)) {
                el.style.display = 'none'; // Ẩn phần tử nếu không có quyền
            }
        });

        // Thêm directive v-date-picker
        app.directive('date-picker', {
            mounted(el) {
                if (el.classList.contains('date-picker')) {
                    const instance = flatpickr(el, {
                        locale: Vietnamese,
                        dateFormat: 'd/m/Y', // Hiển thị định dạng d/m/Y
                        onChange: (selectedDates, dateStr) => {
                            el.value = dateStr;
                            el.dispatchEvent(new Event('input')); // Kích hoạt sự kiện input cho v-model
                        },
                    });
                    el._flatpickr = instance; // Lưu instance để hủy
                }
            },
            unmounted(el) {
                if (el._flatpickr) {
                    el._flatpickr.destroy(); // Hủy Flatpickr khi element bị xóa
                }
            },
        });

        app.use(plugin).mount(el);
    },
});