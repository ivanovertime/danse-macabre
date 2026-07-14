<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validTimeSlots = [
            '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00',
            '17:00', '18:00',
        ];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'date' => ['required', 'date', 'after:today', function ($attribute, $value, $fail) {
                $day = \Carbon\Carbon::parse($value)->dayOfWeek;
                if ($day === \Carbon\Carbon::SATURDAY || $day === \Carbon\Carbon::SUNDAY) {
                    $fail('Appointments are only available on weekdays.');
                }
            }],
            'time_slot' => ['required', Rule::in($validTimeSlots)],
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot.in' => 'The selected time slot is not valid.',
            'date.after' => 'The appointment date must be in the future.',
        ];
    }
}
