# Feature Plan — Danse Macabre

## Overview

Euthanasia appointment booking system with dark humor theme.

> *The ultimate solution to all your problems.*

## Theme: "Danse Macabre — Euthanasia Clinic"

| Element | Value |
|---------|-------|
| **PrimeVue Preset** | Lara |
| **Theme Variant** | Noir (surface tones as primary) |
| **Surface** | Stone |
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
| **Success Message** | "Your final appointment has been booked." |

## Database Schema

```sql
CREATE TABLE appointments (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time_slot CHAR(5) NOT NULL,          -- format: "HH:MM"
    status VARCHAR(20) DEFAULT 'active', -- enum: active
    created_at TIMESTAMPTZ,
    updated_at TIMESTAMPTZ
);

ALTER TABLE appointments ADD CONSTRAINT chk_time_slot_format
CHECK (time_slot ~ '^\d{2}:\d{2}$');

CREATE INDEX idx_appointments_status ON appointments (status);
CREATE INDEX idx_appointments_date ON appointments (date);

CREATE UNIQUE INDEX idx_appointments_slot_active
ON appointments (date, time_slot) WHERE status = 'active';

CREATE UNIQUE INDEX idx_appointments_email_active
ON appointments (email) WHERE status = 'active';
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/slots/{date}` | Available slots for a given date |
| GET | `/api/slots/month/{year}/{month}` | Monthly availability summary |
| POST | `/api/appointments` | Book an appointment |

## Feature Phases

### Phase 1: Database & Models
- [x] Create `appointments` migration with partial unique indexes
- [x] Create `Appointment` model
- [x] Add status enum (`active`)
- [x] Add CHECK constraint on `time_slot` format
- [x] Add indexes on `status` and `date` columns

### Phase 2: Backend API
- [x] `StoreAppointmentRequest` (validation)
- [x] `AppointmentResource` (response transformation)
- [x] `AppointmentController` (slots + store)
- [x] `BookAppointmentService` (business logic)
- [x] Routes configuration

### Phase 3: Frontend - PrimeVue Setup
- [x] Theme: Lara preset with Noir variant
- [x] Surface Stone palette
- [x] Ripple effect enabled
- [x] Dark mode as default

### Phase 4: Frontend - Appointment Form
- [x] Calendar component (PrimeVue)
- [x] Time slot picker
- [x] Name/email form
- [x] API integration via `useApi` composable
- [x] URL state management with `nuqs`

### Phase 5: Frontend - Confirmation
- [x] Success screen with macabre copy
- [x] Appointment details display

### Phase 6: Testing
- [x] 14 PHPUnit tests (12 Feature + 1 Unit + 1 Example)
- [x] 92 assertions covering validation, booking, and slot availability

## Success Criteria

- [x] User can select weekday, future date
- [x] User can see available/occupied slots
- [x] User can book with name + email
- [x] One active appointment per email
- [x] One booking per slot
- [x] Dark humor theme throughout
- [x] All tests pass
