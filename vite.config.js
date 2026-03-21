import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
    'resources/css/app.css',
    'resources/css/hero.css',
    'resources/css/featured-product.css',
    'resources/css/product-grid.css',
    'resources/css/about.css',
    'resources/css/contact.css',
    'resources/js/app.js',
],            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
