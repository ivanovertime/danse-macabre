<template>
  <div>
    <NuxtRouteAnnouncer />
    <NuxtPage />
  </div>
</template>

<script setup>
const { t } = useI18n()

useHead({
  htmlAttrs: { class: 'dark' },
  title: () => t('title'),
  link: [
    { rel: 'icon', type: 'image/png', href: '/favicon-96x96.png?v=20260715', sizes: '96x96' },
    { rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg?v=20260715' },
    { rel: 'shortcut icon', href: '/favicon.ico?v=20260715' },
    { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png?v=20260715' },
    { rel: 'manifest', href: '/site.webmanifest?v=20260715' },
    { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
    { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
    { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&display=swap' },
  ],
  meta: [
    { name: 'apple-mobile-web-app-title', content: 'Danse Macabre' },
  ],
})
</script>
