import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            /* ─── Font ─── */
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                cormorant: ['Cormorant', 'serif'],
                montserrat: ['Montserrat', 'sans-serif'],
            },

            /* ─── Font Size (Mobile-first type scale) ─── */
            fontSize: {
                'display': ['1.875rem', { lineHeight: '1.2', fontWeight: '700' }],   // 30px
                'h1':      ['1.5rem',   { lineHeight: '1.3', fontWeight: '700' }],   // 24px
                'h2':      ['1.25rem',  { lineHeight: '1.35', fontWeight: '600' }],  // 20px
                'h3':      ['1rem',     { lineHeight: '1.4', fontWeight: '600' }],   // 16px
                'body-lg': ['1rem',     { lineHeight: '1.6', fontWeight: '400' }],   // 16px
                'body':    ['0.875rem', { lineHeight: '1.5', fontWeight: '400' }],   // 14px
                'caption': ['0.75rem',  { lineHeight: '1.4', fontWeight: '500' }],   // 12px
                'overline': ['0.6875rem', { lineHeight: '1.3', fontWeight: '600' }], // 11px
            },

            /* ─── Colors (Light Mode) ─── */
            colors: {
                primary: {
                    DEFAULT: '#1E3A8A',
                    foreground: '#FFFFFF',
                    50:  '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },
                accent: {
                    DEFAULT: '#A16207',
                    foreground: '#FFFFFF',
                    light: '#FEF3C7',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    foreground: '#0F172A',
                },
                muted: {
                    DEFAULT: '#F1F5F9',
                    foreground: '#64748B',
                },
                success: {
                    DEFAULT: '#16A34A',
                    light: '#DCFCE7',
                    foreground: '#FFFFFF',
                },
                warning: {
                    DEFAULT: '#D97706',
                    light: '#FEF3C7',
                    foreground: '#FFFFFF',
                },
                destructive: {
                    DEFAULT: '#DC2626',
                    light: '#FEE2E2',
                    foreground: '#FFFFFF',
                },
                info: {
                    DEFAULT: '#0284C7',
                    light: '#E0F2FE',
                    foreground: '#FFFFFF',
                },
            },

            /* ─── Border Radius ─── */
            borderRadius: {
                'sm':  '6px',
                'md':  '8px',
                'lg':  '12px',
                'xl':  '16px',
                '2xl': '20px',
            },

            /* ─── Box Shadow ─── */
            boxShadow: {
                'xs':  '0 1px 2px rgba(0, 0, 0, 0.05)',
                'sm':  '0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06)',
                'md':  '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1)',
                'lg':  '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
                'xl':  '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
                'card': '0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 4px 12px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06)',
            },

            /* ─── Spacing (8px grid extras) ─── */
            spacing: {
                '4.5': '1.125rem', // 18px
                '13':  '3.25rem',  // 52px
                '15':  '3.75rem',  // 60px
                '18':  '4.5rem',   // 72px
                '22':  '5.5rem',   // 88px — bottom nav offset
            },

            /* ─── Animation ─── */
            transitionDuration: {
                '150': '150ms',
                '200': '200ms',
                '250': '250ms',
                '300': '300ms',
            },
            keyframes: {
                'slide-up': {
                    '0%':   { transform: 'translateY(100%)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                'slide-down': {
                    '0%':   { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                'fade-in': {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'scale-in': {
                    '0%':   { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                'shimmer': {
                    '0%':   { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            animation: {
                'slide-up':   'slide-up 250ms ease-out',
                'slide-down': 'slide-down 200ms ease-out',
                'fade-in':    'fade-in 200ms ease-out',
                'scale-in':   'scale-in 200ms ease-out',
                'shimmer':    'shimmer 1.5s ease-in-out infinite',
            },

            /* ─── Z-Index Scale ─── */
            zIndex: {
                'dropdown':    '10',
                'sticky':      '20',
                'fixed':       '30',
                'modal-backdrop': '40',
                'modal':       '50',
                'toast':       '60',
            },
        },
    },

    plugins: [forms],
};
