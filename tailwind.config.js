import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',
    ],
    // TODO: integrare design tokens da docs/design-system/tailwind.config.js
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Cormorant Garamond', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    gold: '#B08D57',
                    'gold-light': '#C9A96E',
                    'gold-dark': '#8A6D3B',
                    dark: '#0F172A',
                    cream: '#FAF8F5',
                },
            },
        },
    },
    plugins: [forms, typography, aspectRatio],
};
