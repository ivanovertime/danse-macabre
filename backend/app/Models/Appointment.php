<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'date', 'time_slot', 'status'])]
#[Hidden([])]
class Appointment extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => AppointmentStatus::class,
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', AppointmentStatus::Active);
    }

    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->where('date', $date);
    }

    public function isActive(): bool
    {
        return $this->status === AppointmentStatus::Active;
    }

    public function cancel(): bool
    {
        return $this->update(['status' => AppointmentStatus::Cancelled]);
    }
}
