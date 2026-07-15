<?php

namespace App\Http\Requests;

use App\Enums\TimeSlot;
use Carbon\Carbon;
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'date' => ['required', 'date', 'after_or_equal:today', function ($attribute, $value, $fail) {
                $day = Carbon::parse($value)->dayOfWeek;
                if ($day === Carbon::SATURDAY || $day === Carbon::SUNDAY) {
                    $fail(trans('validation.weekday_only'));
                }
            }],
            'time_slot' => ['required', Rule::in(TimeSlot::all())],
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot.in' => trans('validation.time_slot.in'),
            'date.after_or_equal' => trans('validation.date.after'),
        ];
    }
}
