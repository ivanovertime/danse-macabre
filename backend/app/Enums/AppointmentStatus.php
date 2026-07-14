<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case Active = 'active';
    case Cancelled = 'cancelled';
}
