import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // ADD THIS "SERVER" BLOCK
    server: {
        port: 3000,
        host: '127.0.0.1', // Be explicit
    },
    // YOUR PLUGINS BLOCK (STAYS THE SAME)
    plugins: [
        laravel({
            input: ['resources/css/app.css'],
            refresh: [
                'public/**/*.html',
            ],
        }),
    ],
});