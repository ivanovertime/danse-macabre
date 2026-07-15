<?php

namespace App\Http\Controllers;

use App\Enums\TimeSlot;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\BookAppointmentService;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function slots(string $date)
    {
        $allSlots = TimeSlot::all();

        $bookedSlots = Appointment::active()
            ->forDate($date)
            ->pluck('time_slot')
            ->toArray();

        $slots = array_map(fn (string $slot) => [
            'time' => $slot,
            'available' => ! in_array($slot, $bookedSlots),
        ], $allSlots);

        return response()->json(['data' => $slots]);
    }

    public function slotsMonth(string $year, string $month)
    {
        $totalSlots = count(TimeSlot::all());

        $bookedPerDate = Appointment::active()
            ->forMonth((int) $year, (int) $month)
            ->selectRaw('date, count(*) as booked')
            ->groupBy('date')
            ->pluck('booked', 'date');

        $result = [];
        $daysInMonth = Carbon::createFromDate((int) $year, (int) $month, 1)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate((int) $year, (int) $month, $day);

            if (($date->isFuture() || $date->isToday()) && ! $date->isWeekend()) {
                $dateKey = $date->format('Y-m-d');
                $booked = $bookedPerDate->get($dateKey, 0);
                $result[$dateKey] = [
                    'available' => $totalSlots - (int) $booked,
                    'total' => $totalSlots,
                ];
            }
        }

        return response()->json(['data' => $result]);
    }

    public function store(StoreAppointmentRequest $request, BookAppointmentService $service)
    {
        $appointment = $service->handle($request);

        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(201);
    }
}
