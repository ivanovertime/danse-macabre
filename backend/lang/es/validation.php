<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'max' => [
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'in' => 'El :attribute seleccionado no es válido.',
    'after' => 'El :attribute debe ser una fecha posterior a :date.',
    'weekday_only' => 'Las citas solo están disponibles en días laborales.',
    'time_slot.in' => 'El horario seleccionado no es válido.',
    'date.after' => 'La fecha de la cita debe ser en el futuro.',
    'attributes' => [
        'name' => 'nombre',
        'email' => 'correo electrónico',
        'date' => 'fecha',
        'time_slot' => 'horario',
    ],
];
