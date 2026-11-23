import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/site/index.scss',
                'resources/js/site/app.js',
            ],
            refresh: true,
        }),
    ],
});
