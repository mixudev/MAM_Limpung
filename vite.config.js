import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Inter', { weights: [400, 500, 600, 700, 800] }),
                bunny('Sora', { weights: [300, 400, 500, 600, 700, 800] }),
                bunny('Plus Jakarta Sans', { weights: [400, 500, 600, 700] }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',      // ← listen semua interface
        port: 5173,
        hmr: {
            host: '127.0.0.1', // ← default untuk dev lokal
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
}));