import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { Ziggy } from "./ziggy";
createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) });
        const app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.route = (
            name,
            params = {},
            absolute = true
        ) => {
            return route(name, params, absolute, Ziggy);
        };
        app.use(plugin).mount(el);
    },
});
