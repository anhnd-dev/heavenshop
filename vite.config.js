import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/admin/app.css",
                "resources/js/admin/app.js",

                "resources/css/frontend/app.css",
                "resources/js/frontend/app.js",
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js/admin/"),
        },
    },
});
