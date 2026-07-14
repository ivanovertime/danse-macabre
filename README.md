# Danse Macabre

> *Tu próxima cita ya está agendada. Solo falta confirmar la fecha.*

**AgendaSalud.online** es un portal de salud que te ayuda a agendar tu próxima consulta médica. Nada fuera de lo normal... salvo por el hecho de que tu doctora se llama La Muerte.

Esta prueba técnica para **JuegaEnLinea** juega con la ironía de un sistema de citas médicas que en realidad sirve para reservar tu último baile. Detrás de la interfaz limpia y profesional se esconde una arquitectura sólida: concurrencia real, validaciones en capa de base de datos y una experiencia de usuario que no te da tiempo de arrepentirte.

---

## Stack

| Capa | Tecnología |
|------|-----------|
| Frontend | Nuxt 4.4 SPA (`ssr: false`) + PrimeVue 4.5 |
| Backend | Laravel 13 (API REST JSON) |
| Base de datos | PostgreSQL 17 |
| Infra local | Docker Compose |
| Infra prod | Cloudflare Pages + DigitalOcean Droplet |
| Dev Environment | Nix Flake + direnv |

## Arquitectura del Monorepo

```
danse-macabre/
├── backend/          # Laravel 13 — API REST pura (solo JSON)
├── frontend/         # Nuxt 4 SPA — interfaz de agendamiento
├── docker-compose.yml
├── flake.nix         # Entorno de desarrollo reproducible
├── docs/
│   └── PRUEBA_TECNICA.md
└── README.md
```

## Requisitos previos

- [Nix](https://nixos.org/download/) con [direnv](https://direnv.net/)
- Docker y Docker Compose

## Instalación y configuración

```bash
# 1. Clonar y activar el entorno
git clone <repo-url> danse-macabre
cd danse-macabre
direnv allow    # activa el flake: PHP 8.4, Node 22, pnpm, Composer

# 2. Backend
composer create-project laravel/laravel backend
cd backend
cp .env.example .env
# Configurar DB_CONNECTION=pgsql, DB_HOST=pgsql, DB_DATABASE=danse_macabre
php artisan key:generate
cd ..

# 3. Frontend
cd frontend
pnpm nuxi init .
pnpm install
pnpm add primevue @primeuix/themes
pnpm add -D @primevue/nuxt-module
cd ..

# 4. Levantar infraestructura
docker-compose up -d    # PostgreSQL
cd backend && php artisan migrate && cd ..
cd frontend && pnpm dev    # http://localhost:3000
```

## Reglas de negocio

- Solo días hábiles (lunes a viernes)
- Sin fechas pasadas
- Horario: 09:00 a 19:00 en bloques de 1 hora
- Un mismo bloque (fecha + hora) no se reserva dos veces
- Un correo electrónico solo puede tener una cita activa
- La interfaz muestra visualmente qué bloques están disponibles y cuáles ocupados

## Especificación original

El requisito completo está en [`docs/PRUEBA_TECNICA.md`](docs/PRUEBA_TECNICA.md).

---

*Desarrollado como prueba técnica para JuegaEnLinea — evaluado por Arniel Serrano.*
