<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Enums\TimeSlot;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $slots = TimeSlot::all();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'date' => function () {
                $date = Carbon::parse(fake()->dateTimeBetween('+1 week', '+2 months'));

                while ($date->isWeekend()) {
                    $date->addDay();
                }

                return $date;
            },
            'time_slot' => $slots[array_rand($slots)],
            'status' => AppointmentStatus::Active,
        ];
    }
}
