import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [],

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/admin/"),
        },
    },
});
