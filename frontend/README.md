# Frontend — Danse Macabre

Nuxt 4.4 SPA con PrimeVue 4.5. Sin SSR — todo el rendering es del lado del cliente.

## Stack

- **Nuxt 4.4** (`ssr: false`)
- **PrimeVue 4.5** — Noir (tema custom basado en Lara con paleta Stone, dark mode por defecto)
- **Tailwind CSS** — utilidades
- **i18n** — ingles y espanol

## Estructura

```
frontend/app/
├── components/
│   ├── AppointmentCalendar.vue   # Selector de fecha (solo dias habiles)
│   ├── TimeSlotPicker.vue        # Grid de 10 slots (09:00 - 18:00)
│   ├── BookingForm.vue           # Nombre + email
│   └── BookingConfirmation.vue   # Pantalla de exito
├── composables/
│   └── useApi.ts                 # Comunicacion con la API de Laravel
├── pages/
│   └── index.vue                 # Pagina principal (wizard de booking)
└── themes/
    └── noir.ts                   # Configuracion del tema PrimeVue
```

## Desarrollo

Ya esta levantado con docker compose up -d desde la raiz del proyecto? 
Hot reload en http://localhost:3000

Si necesitas/quieres ejecutarlo sin Docker no me hago responsable, pero deberia funcionar. 

## Build

```bash
# Build estatico para produccion
pnpm generate

# Preview del build
pnpm preview
```

El build estatico se sirve via FrankenPHP en produccion (mismo contenedor que la API).
