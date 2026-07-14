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
    time_slot CHAR(5) NOT NULL,          -- format: HH:MM (e.g. "09:00")
    status VARCHAR(20) DEFAULT 'active', -- enum: active | cancelled
    created_at TIMESTAMPTZ,
    updated_at TIMESTAMPTZ
);

-- Constraints
ALTER TABLE appointments ADD CONSTRAINT chk_time_slot_format
CHECK (time_slot ~ '^\d{2}:\d{2}$');

-- Performance indexes
CREATE INDEX idx_appointments_status ON appointments (status);
CREATE INDEX idx_appointments_date ON appointments (date);

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

- [x] Create `appointments` migration with partial unique indexes
- [x] Create `Appointment` model
- [x] Add status enum (`active` | `cancelled`)
- [x] Add CHECK constraint on `time_slot` format
- [x] Add indexes on `status` and `date` columns

### Phase 2: Backend API

- [x] `StoreAppointmentRequest` (validation)
- [x] `AppointmentResource` (response transformation)
- [x] `AppointmentController` (CRUD + slots)
- [x] `BookAppointmentService` (business logic)
- [x] Routes configuration

### Phase 3: Frontend - PrimeVue Setup

- [x] Change theme from Aura → Lara with Noir preset
- [x] Add Surface Stone palette
- [x] Enable Ripple effect
- [x] Configure dark mode as default

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
| 2 | `refactor(backend): improve appointments migration and use status enum` | `app/Enums/AppointmentStatus.php`, `app/Models/Appointment.php`, `database/migrations/*` |
| 3 | `feat(backend): implement appointment booking API` | `app/Http/*`, `routes/api.php`, `app/Services/*` |
| 4 | `feat(frontend): configure PrimeVue Noir theme with Lara preset` | `nuxt.config.ts`, `app.vue` |
| 5 | `feat(frontend): build appointment booking form` | `app/components/*`, `app/pages/*` |
| 6 | `feat(frontend): add confirmation screen with macabre copy` | `app/components/*`, `app/pages/*` |
| 7 | `test(backend): add appointment API tests` | `tests/Feature/*`, `tests/Unit/*` |

## Success Criteria

- [ ] User can select weekday, future date
- [ ] User can see available/occupied slots
- [ ] User can book with name + email
- [ ] One active appointment per email
- [ ] One booking per slot
- [ ] Cancel flow works (for technical test)
- [ ] Dark humor theme throughout
- [ ] All tests pass
