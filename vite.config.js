import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        hmr: {
            host: '192.168.2.106',
            // host: 'localhost',
            // host: 'store.management',
            // host: '0.0.0.0'
        },
        watch: {
            usePolling: true
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: { additionalData: `@import "resources/scss/app.scss";` },
        },
    },
});
