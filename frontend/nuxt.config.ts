import Noir from './app/themes/noir';

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  ssr: false,

  modules: ['@primevue/nuxt-module', '@nuxtjs/tailwindcss', '@nuxtjs/i18n'],

  i18n: {
    locales: [
      { code: 'en', name: 'English' },
      { code: 'es', name: 'Español' },
    ],
    defaultLocale: 'en',
    strategy: 'no_prefix',
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_redirected',
      redirectOn: 'root',
    },
  },

  primevue: {
    options: {
      ripple: true,
      theme: {
        preset: Noir,
        options: {
          darkModeSelector: '.dark',
        },
      },
    },
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    },
  },
})
