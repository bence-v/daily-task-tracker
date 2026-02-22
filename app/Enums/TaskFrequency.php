<?php

namespace App\Enums;

enum TaskFrequency: string
{
    case Daily = 'daily';
    case WeekDays = 'weekdays';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
