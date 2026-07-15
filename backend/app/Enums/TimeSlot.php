<?php

namespace App\Enums;

enum TimeSlot: string
{
    case H09 = '09:00';
    case H10 = '10:00';
    case H11 = '11:00';
    case H12 = '12:00';
    case H13 = '13:00';
    case H14 = '14:00';
    case H15 = '15:00';
    case H16 = '16:00';
    case H17 = '17:00';
    case H18 = '18:00';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
