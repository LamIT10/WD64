import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/login.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                compilerOptions: {
                    // Cho phép hiển thị HTML trong custom label
                    isCustomElement: (tag) => tag.includes('multiselect')
                }
            }
        }),
    ],
});
