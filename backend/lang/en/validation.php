<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute field must be a string.',
    'max' => [
        'string' => 'The :attribute field must not be greater than :max characters.',
    ],
    'email' => 'The :attribute field must be a valid email address.',
    'date' => 'The :attribute field must be a valid date.',
    'in' => 'The selected :attribute is invalid.',
    'after' => 'The :attribute must be a date after :date.',
    'weekday_only' => 'Appointments are only available on weekdays.',
    'time_slot.in' => 'The selected time slot is not valid.',
    'date.after' => 'The appointment date must be in the future.',
    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'date' => 'date',
        'time_slot' => 'time slot',
    ],
];
