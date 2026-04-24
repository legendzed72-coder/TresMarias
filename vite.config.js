import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/geolocation.js',
                'resources/js/admin-dashboard-chart.js',
                'resources/js/admin-chartsale.js',
                'resources/js/customer-analytics.js',
                'resources/js/customer-profile-chart.js',
                'resources/js/user-orders.js',
                'resources/js/staff-dashboard.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
