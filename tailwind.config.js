import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
                serif: ["Merriweather", ...defaultTheme.fontFamily.serif],
            },
            colors: {
                mandalaloka: {
                    50: '#f0f5fa',
                    100: '#e0ebf5',
                    200: '#c2d6ea',
                    300: '#94bbdb',
                    400: '#5f9bc9',
                    500: '#3e7fb2',
                    600: '#2c6594',
                    700: '#245179',
                    800: '#1a498b',
                    900: '#0f2e5c',
                    950: '#0a1d3d',
                },
                gold: {
                    400: '#ebd173',
                    500: '#d4af37',
                    600: '#b8902d',
                }
            },
            backgroundImage: {
                'batik': "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d4af37' fill-opacity='0.1'%3E%3Cpath d='M30 30c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zm-15 0c0-8.284-6.716-15-15-15C6.716 15 0 21.716 0 30c0 8.284 6.716 15 15 15 8.284 0 15-6.716 15-15zM15 15c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15zM15 45c0-8.284 6.716-15 15-15 8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
            }
        },
    },

    plugins: [forms],
};
