# Feature Plan — Danse Macabre

## Overview

Euthanasia appointment booking system with dark humor theme.

> *The ultimate solution to all your problems.*

## Theme: "Danse Macabre — Euthanasia Clinic"

### Visual Identity

| Element | Value |
|---------|-------|
| **PrimeVue Preset** | Lara |
| **Theme Variant** | Noir (surface tones as primary) |
| **Surface** | Stone |
| **Ripple** | Enabled |
| **Mode** | Dark (default) |

### Dark Humor Copy

| Element | Copy |
|---------|------|
| **App Title** | "Danse Macabre — Euthanasia Clinic" |
| **Tagline** | "The ultimate solution to all your problems" |
| **Calendar Label** | "Choose your farewell date" |
| **Time Picker** | "Select your final hour" |
| **Submit Button** | "Book my eternal rest" |
| **Success Title** | "Appointment Confirmed" |
| **Success Message** | "Your final appointment has been booked. The ultimate solution to all your problems awaits." |
| **Cancel Button** | "I choose to suffer" |

## Database Schema

```sql
CREATE TABLE appointments (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time_slot VARCHAR(5) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMPTZ,
    updated_at TIMESTAMPTZ
);

-- Partial unique indexes (PostgreSQL)
CREATE UNIQUE INDEX idx_appointments_slot_active
ON appointments (date, time_slot) WHERE status = 'active';

CREATE UNIQUE INDEX idx_appointments_email_active
ON appointments (email) WHERE status = 'active';
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/slots/{date}` | Get available final consultation slots |
| POST | `/api/appointments` | Book your final appointment |
| GET | `/api/appointments/{id}` | View your eternal reservation |
| DELETE | `/api/appointments/{id}` | Cancel (for technical test purposes) |

## Feature Phases

### Phase 1: Database & Models

- [ ] Create `appointments` migration with partial unique indexes
- [ ] Create `Appointment` model
- [ ] Add status enum (`active` | `cancelled`)

### Phase 2: Backend API

- [ ] `StoreAppointmentRequest` (validation)
- [ ] `AppointmentResource` (response transformation)
- [ ] `AppointmentController` (CRUD)
- [ ] `BookAppointmentService` (business logic)
- [ ] Routes configuration

### Phase 3: Frontend - PrimeVue Setup

- [ ] Change theme from Aura → Lara with Noir preset
- [ ] Add Surface Stone palette
- [ ] Enable Ripple effect
- [ ] Configure dark mode as default

### Phase 4: Frontend - Appointment Form

- [ ] Calendar component (PrimeVue)
- [ ] Time slot picker
- [ ] Name/email form
- [ ] API integration

### Phase 5: Frontend - Confirmation

- [ ] Success screen with macabre copy
- [ ] Appointment details display
- [ ] "Book another" option (for rebooking)

### Phase 6: Testing

- [ ] Unit tests for services
- [ ] Feature tests for API endpoints
- [ ] Vue component tests

## Commit Plan (Conventional Commits)

| # | Commit Message | Files |
|---|----------------|-------|
| 1 | `feat(backend): add appointments migration and model` | `database/migrations/*`, `app/Models/Appointment.php` |
| 2 | `feat(backend): implement appointment booking API` | `app/Http/*`, `routes/api.php`, `app/Services/*` |
| 3 | `feat(frontend): configure PrimeVue Noir theme with Lara preset` | `nuxt.config.ts`, `app.vue` |
| 4 | `feat(frontend): build appointment booking form` | `app/components/*`, `app/pages/*` |
| 5 | `feat(frontend): add confirmation screen with macabre copy` | `app/components/*`, `app/pages/*` |
| 6 | `test(backend): add appointment API tests` | `tests/Feature/*`, `tests/Unit/*` |

## Success Criteria

- [ ] User can select weekday, future date
- [ ] User can see available/occupied slots
- [ ] User can book with name + email
- [ ] One active appointment per email
- [ ] One booking per slot
- [ ] Cancel flow works (for technical test)
- [ ] Dark humor theme throughout
- [ ] All tests pass
