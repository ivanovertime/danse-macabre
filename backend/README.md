# Backend — Danse Macabre

Laravel 13 API REST. JSON puro, sin vistas.

## Stack

- **Laravel 13**
- **PostgreSQL 17**
- **PHP 8.4**

Si necesitas/quieres ejecutarlo sin Docker no me hago responsable, pero deberia funcionar. 

## Estructura clave

```
backend/app/
├── Enums/
│   ├── TimeSlot.php              # Fuente unica: 10 slots (09:00 - 18:00)
│   └── AppointmentStatus.php     # Solo 'active'
├── Http/
│   ├── Controllers/
│   │   └── AppointmentController.php  # 3 endpoints
│   ├── Requests/
│   │   └── StoreAppointmentRequest.php  # Validacion
│   └── Resources/
│       └── AppointmentResource.php  # Transformacion de respuesta
├── Models/
│   └── Appointment.php           # Scopes: active, forDate, forMonth
└── Services/
    └── BookAppointmentService.php  # Logica de negocio
```

## API

### `GET /api/slots/{date}`

Devuelve los 10 slots del dia con su disponibilidad.

```json
{
  "data": [
    { "time": "09:00", "available": true },
    { "time": "10:00", "available": false },
    ...
  ]
}
```

### `GET /api/slots/month/{year}/{month}`

Resumen de disponibilidad por dia del mes.

```json
{
  "data": {
    "2025-07-21": { "available": 8, "total": 10 },
    "2025-07-22": { "available": 10, "total": 10 },
    ...
  }
}
```

### `POST /api/appointments`

Reserva una cita.

```json
// Request
{
  "name": "Juan Perez",
  "email": "juan@example.com",
  "date": "2025-07-21",
  "time_slot": "10:00"
}

// Response: 201 Created
{
  "data": {
    "id": 1,
    "name": "Juan Perez",
    "email": "juan@example.com",
    "date": "2025-07-21",
    "time_slot": "10:00",
    "status": "active"
  }
}
```

### Errores de validacion (422)

```json
{
  "message": "The selected time slot is not available.",
  "errors": {
    "time_slot": ["The selected time slot is not available."]
  }
}
```

## Desarrollo

```bash
# Tests (14 tests, 92 assertions)
docker compose exec backend php artisan test

# Formateo de codigo (PSR-12)
docker compose exec backend ./vendor/bin/pint

# Migraciones
docker compose exec backend php artisan migrate:fresh

# Shell de artisan
docker compose exec backend php artisan tinker
```

## Reglas de negocio

- Slots: `TimeSlot` enum es la fuente unica de verdad (09:00 a 18:00)
- Solo dias habiles, sin fechas pasadas
- Un slot (fecha + hora) no se reserva dos veces (unique index parcial)
- Un email solo puede tener una cita activa (unique index parcial)
- La validacion doble capa: request validation + database constraints
