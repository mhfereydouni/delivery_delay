<?php

namespace App\Enums;

enum TripStatus: string
{
    case ASSIGNED = 'ASSIGNED';
    case AT_VENDOR = 'AT_VENDOR';
    case PICKED = 'PICKED';
    case DELIVERED = 'DELIVERED';

    public static function allValues(): array
    {
        return [
            TripStatus::ASSIGNED->value,
            TripStatus::AT_VENDOR->value,
            TripStatus::PICKED->value,
            TripStatus::DELIVERED->value,
        ];
    }

    public static function notDelivered(): array
    {
        return [
            TripStatus::ASSIGNED,
            TripStatus::AT_VENDOR,
            TripStatus::PICKED,
        ];
    }
}
