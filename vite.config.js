import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const cssFiles = [
    'resources/css/common.css',
];

const jsFiles = [
    'resources/js/common.js',
    'resources/js/lib/jquery-validation/additional-setting.js',
    'resources/js/partials/menu.js',
    'resources/js/screens/auth/login.js',
    'resources/js/screens/user/search.js',
    'resources/js/screens/user/add.js',
    'resources/js/screens/user/edit.js',
    'resources/js/lib/jquery-validation/my-validation.js',
];

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...cssFiles,
                ...jsFiles,
            ],
            refresh: true,
        }),
    ],
});
