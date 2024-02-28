import { defineConfig } from "vite"
import laravel from "laravel-vite-plugin"
import vue from "@vitejs/plugin-vue"
import vuetify from "vite-plugin-vuetify"

export default defineConfig({
    plugins: [
        laravel([
            "resources/ts/app.ts",
            "resources/ts/index.ts",
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({
            styles: {
                configFile: "/resources/sass/_vuetify-settings.scss",
            },
        }),
    ],
    resolve: {
        alias: {
            "@sass": "/resources/sass",
            "@composables": "/resources/ts/composables",
            "@components": "/resources/ts/components",
            "@plugins": "/resources/ts/plugins",
            "@stores": "/resources/ts/stores",
            "@classes": "/resources/ts/classes",
            "@interfaces": "/resources/ts/interfaces",
        },
    },
})
