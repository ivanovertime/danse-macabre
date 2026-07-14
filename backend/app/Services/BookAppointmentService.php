<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class BookAppointmentService
{
    public function handle(StoreAppointmentRequest $request): Appointment
    {
        return DB::transaction(function () use ($request) {
            $date = $request->validated('date');
            $timeSlot = $request->validated('time_slot');
            $email = $request->validated('email');

            $this->ensureSlotAvailable($date, $timeSlot);
            $this->ensureEmailAvailable($email);

            return Appointment::create([
                'name' => $request->validated('name'),
                'email' => $email,
                'date' => $date,
                'time_slot' => $timeSlot,
                'status' => AppointmentStatus::Active,
            ]);
        });
    }

    private function ensureSlotAvailable(string $date, string $timeSlot): void
    {
        $exists = Appointment::active()
            ->forDate($date)
            ->where('time_slot', $timeSlot)
            ->exists();

        if ($exists) {
            throw new ConflictHttpException('This time slot is already booked.');
        }
    }

    private function ensureEmailAvailable(string $email): void
    {
        $exists = Appointment::active()
            ->where('email', $email)
            ->exists();

        if ($exists) {
            throw new ConflictHttpException('You already have an active appointment.');
        }
    }
}
